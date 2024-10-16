<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequests;
use App\Models\Messages;
use App\Models\User;
use App\Services\Chat\MessengerServices;
use App\Services\Chat\StoreMessageDTO;
use App\Services\Tutorat\TutoratServices;
use App\Services\Upload\UploadServices;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as ResponseFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatController extends Controller
{
    /**
     * @var int
     */
    protected int $perPage = 30;

    /**
     * @var MessengerServices
     */
    protected MessengerServices $messengerServices;

    /**
     * ChatController constructor.
     * @param MessengerServices $messengerServices
     */
    public function __construct(MessengerServices $messengerServices)
    {
        $this->messengerServices = $messengerServices;
    }

    /**
     * Authinticate the connection for pusher
     *
     * @param Request $request
     * @return string
     */
    public function pusherAuth(Request $request): string
    {
        // Auth data
        $authData = json_encode([
            'user_id' => Auth::guard('api')->user()->id,
            'user_info' => [
                'name' => Auth::guard('api')->user()->firstname
            ]
        ]);
        // check if user authorized
        if (Auth::guard('api')->check()) {
            return $this->messengerServices->pusherAuth(
                $request->channel_name,
                $request->socket_id,
                $authData
            );
        }
        // if not authorized
        return response()->json(['message'=>'Unauthorized'], 401);
    }

    /**
     * Fetch data by id for (user/group)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function idFetchData(Request $request): JsonResponse
    {
        //return auth()->user();
        // Favorite
        $favorite = $this->messengerServices->inFavorite($request['id']);

        // User data
        if ($request['type'] == 'user') {
            $fetch = User::where('id', $request['id'])->first();
            if($fetch){
                $userAvatar = $this->messengerServices->getUserWithAvatar($fetch)->avatar;
            }
        }

        // send the response
        return Response::json([
            'favorite' => $favorite,
            'fetch' => $fetch ?? [],
            'user_avatar' => $userAvatar ?? null,
        ]);
    }

    /**
     * This method to make a links for the attachments
     * to be downloadable.
     *
     * @param Messages $message
     * @return StreamedResponse
     */
    public function download(Messages $message)
    {
        try {
            $attachment = json_decode($message->attachment);
            $diskLocal = Storage::disk('public');
            $isExist = $diskLocal->exists($attachment->path);
            if ($isExist) {
                $response = $diskLocal->download($attachment->path, $attachment->name);
            }
        } catch (\Exception $exception) {
            $response = response()->json([
                'message'=>"Sorry, File does not exist in our server or may have been deleted!"
            ], 404);
        }
        return $response;
    }

    /**
     * Send a message to database
     *
     * @param StoreMessageRequests $request
     * @param int $tutoratId
     * @param UploadServices $uploadServices
     * @return JsonResponse response
     * @throws GuzzleException
     */
    public function send(StoreMessageRequests $request, int $tutoratId, UploadServices $uploadServices): JsonResponse
    {
        $attachment = null;
        $messageType = $request->type;
        try {
            // if there is attachment [attachment]
            if ($request->hasFile('attachment')) {
                // allowed extensions
                $allowed_images = $this->messengerServices->getAllowedImages();
                $allowed_videos  = $this->messengerServices->getAllowedVideos();

                $file = $request->file('attachment');
                $extension = $file->getClientOriginalExtension();
                $attachmentName = $file->getClientOriginalName();
                $attachmentSize = $file->getSize();
                // upload attachment and store the new name
                $attachmentPath = $uploadServices->storeFile($file);
                $attachment = json_encode(['name' => $attachmentName, 'path' => $attachmentPath, 'size' => $this->messengerServices->convertSizeAttachment($attachmentSize)]);
                $messageType = in_array(strtolower($extension), $allowed_images) ? 'image' : (in_array(strtolower($extension), $allowed_videos) ? 'video' : 'file');
            }
            // send to database
            $message = $this->messengerServices->newMessage(new StoreMessageDTO(
                $tutoratId,
                Auth::guard('api')->user()->id,
                htmlentities(trim($request->message), ENT_QUOTES, 'UTF-8'),
                ($attachment) ?? null,
                $request->replyTo,
                $messageType
            ));
            $messageData = $this->messengerServices->fetchMessageById($message->id);
            // send to user using pusher
            $this->messengerServices->push('private-chat', 'messaging', [
                'from_id' => Auth::guard('api')->user()->id,
                'tutorat_id' => $tutoratId,
                'message' => $messageData
            ]);
            $response = Response::json([
                'success' => true,
                'message' => $messageData
            ], 200);
        } catch (\Exception $exception) {
            $response = Response::json([
                'success' => false,
                'error' => $exception->getMessage()
            ], $exception->getCode());
        }
        // send the response
        return $response;
    }

    /**
     * fetch [user/group] messages from database
     *
     * @param Request $request
     * @param int $tutoratId
     * @return JsonResponse response
     */
    public function fetch(Request $request, int $tutoratId): JsonResponse
    {
        $query = $this->messengerServices->fetchMessages($tutoratId)->latest();
        $messages = $query->paginate($request->per_page ?? $this->perPage);
        $totalMessages = $messages->total();
        $lastPage = $messages->lastPage();
        $response = [
            'total' => $totalMessages,
            'last_page' => $lastPage,
            'last_message_id' => collect($messages->items())->last()->id ?? null,
            'messages' => $messages->items(),
        ];
        return Response::json($response);
    }

    /**
     * Make messages as seen
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function seen(Request $request): JsonResponse
    {
        // make as seen
        $seen = $this->messengerServices->makeSeen($request['id']);
        // send the response
        return Response::json([
            'status' => $seen,
        ], 200);
    }

    /**
     * Get contacts list
     *
     * @param Request $request
     * @param int $tutoratId
     * @param TutoratServices $tutoratServices
     * @return JsonResponse response
     */
    public function getContacts(Request $request, int $tutoratId, TutoratServices $tutoratServices): JsonResponse
    {
        // get all users that received/sent message from/to [Auth user]
        $contacts = $tutoratServices->getContactsTutorat($tutoratId);
        $tutorat = $tutoratServices->getTutorat($tutoratId);

        return response()->json([
            'contacts' => $contacts,
            'tutorat' => $tutorat,
        ], 200);
    }

    /**
     * Search in messenger
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $input = trim(filter_var($request['input']));
        $records = User::where('id','!=',Auth::guard('api')->user()->id)
            ->where('name', 'LIKE', "%{$input}%")
            ->paginate($request->per_page ?? $this->perPage);

        foreach ($records->items() as $index => $record) {
            $records[$index] += $this->messengerServices->getUserWithAvatar($record);
        }

        return Response::json([
            'records' => $records->items(),
            'total' => $records->total(),
            'last_page' => $records->lastPage()
        ], 200);
    }

    /**
     * Get shared photos
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sharedPhotos(Request $request): JsonResponse
    {
        $images = $this->messengerServices->getSharedPhotos($request['user_id']);

        foreach ($images as $image) {
            $image = asset(config('chat.attachments.folder') . $image);
        }
        // send the response
        return Response::json([
            'shared' => $images ?? [],
        ], 200);
    }

    /**
     * Delete conversation
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteConversation(Request $request): JsonResponse
    {
        // delete
        $delete = $this->messengerServices->deleteConversation($request['id']);

        // send the response
        return Response::json([
            'deleted' => $delete ? 1 : 0,
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateSettings(Request $request): JsonResponse
    {
        $msg = null;
        $error = $success = 0;

        // if there is a [file]
        if ($request->hasFile('avatar')) {
            // allowed extensions
            $allowed_images = $this->messengerServices->getAllowedImages();

            $file = $request->file('avatar');
            // check file size
            if ($file->getSize() < $this->messengerServices->getMaxUploadSize()) {
                if (in_array(strtolower($file->getClientOriginalExtension()), $allowed_images)) {
                    // delete the older one
                    if (Auth::guard('api')->user()->avatar != config('chat.user_avatar.default')) {
                        $path = $this->messengerServices->getUserAvatarUrl(Auth::guard('api')->user()->avatar);
                        if ($this->messengerServices->storage()->exists($path)) {
                            $this->messengerServices->storage()->delete($path);
                        }
                    }
                    // upload
                    $avatar = Str::uuid() . "." . $file->getClientOriginalExtension();
                    $update = User::where('id', Auth::guard('api')->user()->id)->update(['avatar' => $avatar]);
                    $file->storeAs(config('chat.user_avatar.folder'), $avatar, config('chat.storage_disk_name'));
                    $success = $update ? 1 : 0;
                } else {
                    $msg = "File extension not allowed!";
                    $error = 1;
                }
            } else {
                $msg = "File size you are trying to upload is too large!";
                $error = 1;
            }
        }

        // send the response
        return Response::json([
            'status' => $success ? 1 : 0,
            'error' => $error ? 1 : 0,
            'message' => $error ? $msg : 0,
        ], 200);
    }

    /**
     * Set user's active status
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setActiveStatus(Request $request): JsonResponse
    {
        $status = filter_var($request->status, FILTER_VALIDATE_BOOL);
        $update = User::where('id', Auth::guard('api')->user()->id)->update(['active_status' => $status]);
        // send the response
        return Response::json([
            'status' => $update,
        ], 200);
    }
}
