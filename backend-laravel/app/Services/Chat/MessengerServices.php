<?php

namespace App\Services\Chat;

use App\Models\Messages;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Pusher\ApiErrorException;
use Pusher\Pusher;
use Pusher\PusherException;

class MessengerServices
{
    /**
     * @var Pusher
     */
    public Pusher $pusher;

    /**
     * MessengerServices constructor.
     * @throws PusherException
     */
    public function __construct()
    {
        $this->pusher = new Pusher(
            config('chat.pusher.key'),
            config('chat.pusher.secret'),
            config('chat.pusher.app_id'),
            config('chat.pusher.options'),
        );
    }

    /**
     * Get max file's upload size in MB.
     *
     * @return int
     */
    public function getMaxUploadSize()
    {
        return config('chat.attachments.max_upload_size') * 1048576;
    }

    /**
     * This method returns the allowed image extensions
     * to attach with the message.
     *
     * @return array
     */
    public function getAllowedImages(): array
    {
        return config('chat.attachments.allowed_images');
    }

    /**
     * This method returns the allowed file extensions
     * to attach with the message.
     *
     * @return array
     */
    public function getAllowedFiles(): array
    {
        return config('chat.attachments.allowed_files');
    }

    /**
     * @return array
     */
    public function getAllowedVideos(): array
    {
        return config('chat.attachments.allowed_videos');
    }

    /**
     * @param int $attachmentSize
     * @return string
     */
    public function convertSizeAttachment(int $attachmentSize): string
    {
        $attachmentSize = round($attachmentSize/pow(1024, 2), 2);
        return $attachmentSize . ' M';
    }

    /**
     * Trigger an event using Pusher
     *
     * @param string $channel
     * @param string $event
     * @param array $data
     * @return object
     * @throws PusherException
     * @throws GuzzleException
     * @throws ApiErrorException
     */
    public function push(string $channel, string $event, array $data): object
    {
        return $this->pusher->trigger($channel, $event, $data);
    }

    /**
     * Authentication for pusher
     *
     * @param string $channelName
     * @param string $socket_id
     * @param string|null $data
     * @return string
     */
    public function pusherAuth(string $channelName, string $socket_id, string $data = null): string
    {
        return $this->pusher->socket_auth($channelName, $socket_id, $data);
    }

    /**
     * Fetch message by id and return the message card
     * view as a response.
     *
     * @param int $id
     * @param null $index
     * @return array
     */
    public function fetchMessage(int $id, $index = null): array
    {
        $attachment = null;
        $attachment_type = null;
        $attachment_title = null;

        $msg = Messages::where('id', $id)->first();
        if(!$msg){
            return [];
        }

        if (isset($msg->attachment)) {
            $attachmentOBJ = json_decode($msg->attachment);
            $attachment = $attachmentOBJ->new_name;
            $attachment_title = htmlentities(trim($attachmentOBJ->old_name), ENT_QUOTES, 'UTF-8');

            $ext = pathinfo($attachment, PATHINFO_EXTENSION);
            $attachment_type = in_array($ext, $this->getAllowedImages()) ? 'image' : 'file';
        }

        return [
            'index' => $index,
            'id' => $msg->id,
            'from_id' => $msg->from_id,
            'to_id' => $msg->to_id,
            'message' => $msg->body,
            'attachment' => [$attachment, $attachment_title, $attachment_type],
            'time' => $msg->created_at->diffForHumans(),
            'fullTime' => $msg->created_at,
            'viewType' => ($msg->from_id == Auth::guard('api')->user()->id) ? 'sender' : 'default',
            'seen' => $msg->seen,
        ];
    }

    /**
     * Return a message card with the given data.
     *
     * @param array $data
     * @param string|null $viewType
     * @return string
     */
    public function messageCard(array $data, string $viewType = null): string
    {
        if (!$data) {
            return '';
        }
        $data['viewType'] = ($viewType) ? $viewType : $data['viewType'];
        return view('chat::layouts.messageCard', $data)->render();
    }

    /**
     * Default fetch messages query between a Sender and Receiver.
     *
     * @param int $user_id
     * @return Messages|Builder
     */
    public function fetchMessagesQuery(int $user_id)
    {
        return Messages::where('from_id', Auth::guard('api')->user()->id)->where('to_id', $user_id)
            ->orWhere('from_id', $user_id)->where('to_id', Auth::guard('api')->user()->id);
    }

    /**
     * @param int $tutoratId
     * @return Builder
     */
    public function fetchMessages(int $tutoratId)
    {
        return Messages::with(['sender', 'tutorat', 'replyTo'])
            ->where('tutorat_id', $tutoratId)
            ->orderBy('created_at', 'DESC');
    }

    /**
     * @param int $messageId
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function fetchMessageById(int $messageId)
    {
        return Messages::with(['sender', 'tutorat', 'replyTo'])
            ->find($messageId);
    }

    /**
     * create a new message to database
     *
     * @param StoreMessageDTO $storeMessageDTO
     * @return mixed
     */
    public function newMessage(StoreMessageDTO $storeMessageDTO)
    {
        return Messages::create([
            'tutorat_id' => $storeMessageDTO->tutoratId,
            'from_id' => $storeMessageDTO->fromId,
            'body' => $storeMessageDTO->body,
            'attachment' => $storeMessageDTO->attachment,
            'reply_to' => $storeMessageDTO->replyTo,
            'type' => $storeMessageDTO->type,
        ]);
    }

    /**
     * Make messages between the sender [Auth user] and
     * the receiver [User id] as seen.
     *
     * @param int $user_id
     * @return bool
     */
    public function makeSeen(int $user_id)
    {
        Messages::Where('from_id', $user_id)
            ->where('to_id', Auth::guard('api')->user()->id)
            ->where('seen', 0)
            ->update(['seen' => 1]);
        return 1;
    }

    /**
     * Get last message for a specific user
     *
     * @param int $user_id
     * @return Messages|Collection|\Illuminate\Database\Eloquent\Builder|Model|object|null
     */
    public function getLastMessageQuery(int $user_id)
    {
        return $this->fetchMessagesQuery($user_id)->latest()->first();
    }

    /**
     * Count Unseen messages
     *
     * @param int $user_id
     * @return Collection
     */
    public function countUnseenMessages($user_id): Collection
    {
        return Messages::where('from_id', $user_id)->where('to_id', Auth::guard('api')->user()->id)->where('seen', 0)->count();
    }

    /**
     * Get user with avatar (formatted).
     *
     * @param Collection $user
     * @return Collection
     */
    public function getUserWithAvatar(Collection $user): Collection
    {
        if ($user->avatar == 'avatar.png' && config('chat.gravatar.enabled')) {
            $imageSize = config('chat.gravatar.image_size');
            $imageset = config('chat.gravatar.imageset');
            $user->avatar = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?s=' . $imageSize . '&d=' . $imageset;
        } else {
            $user->avatar = self::getUserAvatarUrl($user->avatar);
        }
        return $user;
    }

    /**
     * Get shared photos of the conversation
     *
     * @param int $user_id
     * @return array
     */
    public function getSharedPhotos(int $user_id): array
    {
        $images = array(); // Default
        // Get messages
        $msgs = $this->fetchMessagesQuery($user_id)->orderBy('created_at', 'DESC');
        if ($msgs->count() > 0) {
            foreach ($msgs->get() as $msg) {
                // If message has attachment
                if ($msg->attachment) {
                    $attachment = json_decode($msg->attachment);
                    // determine the type of the attachment
                    in_array(pathinfo($attachment->new_name, PATHINFO_EXTENSION), $this->getAllowedImages())
                        ? array_push($images, $attachment->new_name) : '';
                }
            }
        }
        return $images;
    }

    /**
     * Delete Conversation
     *
     * @param int $user_id
     * @return int
     */
    public function deleteConversation(int $user_id): int
    {
        try {
            foreach ($this->fetchMessagesQuery($user_id)->get() as $msg) {
                // delete file attached if exist
                if (isset($msg->attachment)) {
                    $path = config('chat.attachments.folder').'/'.json_decode($msg->attachment)->new_name;
                    if (self::storage()->exists($path)) {
                        self::storage()->delete($path);
                    }
                }
                // delete from database
                $msg->delete();
            }
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Delete message by ID
     *
     * @param int $id
     * @return int
     */
    public function deleteMessage(int $id): int
    {
        try {
            $msg = Messages::findOrFail($id);
            if ($msg->from_id == auth()->id()) {
                // delete file attached if exist
                if (isset($msg->attachment)) {
                    $path = config('chat.attachments.folder') . '/' . json_decode($msg->attachment)->new_name;
                    if (self::storage()->exists($path)) {
                        self::storage()->delete($path);
                    }
                }
                // delete from database
                $msg->delete();
            } else {
                return 0;
            }
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Return a storage instance with disk name specified in the config.
     *
     */
    public function storage(): Filesystem
    {
        return Storage::disk(config('chat.storage_disk_name'));
    }

    /**
     * Get user avatar url.
     *
     * @param string $user_avatar_name
     * @return string
     */
    public function getUserAvatarUrl(string $user_avatar_name): string
    {
        return self::storage()->url(config('chat.user_avatar.folder') . '/' . $user_avatar_name);
    }

    /**
     * Get attachment's url.
     *
     * @param string $attachment_name
     * @return string
     */
    public function getAttachmentUrl(string $attachment_name): string
    {
        return self::storage()->url(config('chat.attachments.folder') . '/' . $attachment_name);
    }
}
