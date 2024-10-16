<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMagRequests;
use App\Http\Resources\MagResource;
use App\Services\Mag\MagServices;
use App\Services\Mag\MagStoreDTO;
use App\Services\Upload\UploadServices;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class MagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param MagServices $magServices
     * @return \Illuminate\Http\Response
     */
    public function index(MagServices $magServices): \Illuminate\Http\Response
    {
        $mags = $magServices->getAllMag();
        return response(MagResource::collection($mags), Response::HTTP_OK);
    }


    /**
     * @param StoreMagRequests $request
     * @param MagServices $magServices
     * @param UploadServices $uploadServices
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function store(StoreMagRequests $request, MagServices $magServices, UploadServices $uploadServices)
    {
        try {
            $mag = $magServices->storeMag(new MagStoreDTO(
                $request->id,
                $request->title,
                $request->subTitle,
                $request->contenu,
                $uploadServices->storeFile($request->picture),
                (bool)$request->enable,
            ));

            $response = response(new MagResource($mag), Response::HTTP_OK);
        } catch (Exception $e){
            $error['message'] = $e->getMessage();
            $response = response($error, Response::HTTP_NOT_FOUND);
        }
        return $response;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param Request $request
     * @param MagServices $magServices
     * @param UploadServices $uploadServices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id, MagServices $magServices, UploadServices $uploadServices)
    {
       $picture = $request->file()['picture'];
        try {
            $magUpdated = $magServices->storeMag(new MagStoreDTO(
                $id,
                $request->title,
                $request->subTitle,
                $request->contenu,
                $uploadServices->storeFile($picture),
                (bool)$request->enable,
            ));

            $response = response($magUpdated, Response::HTTP_OK);
        } catch (Exception $e) {
            $error['message'] = $e->getMessage();
            $response = response($error, Response::HTTP_NOT_FOUND);
        }
        return $response;
    }

    /**
     * @param int $id
     * @param MagServices $magServices
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function updatePublishState(int $id, MagServices $magServices) {
        $magUpdated = $magServices->updatePublishMag($id);
        return response($magUpdated, Response::HTTP_OK);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param MagServices $magServices
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id, MagServices $magServices)
    {
        try {
            $magServices->deleteMag($id);
            $success['message'] = "Le donnée a été supprimé";
            $response = response($success, Response::HTTP_OK);
        } catch (Exception $e) {
            $error['message'] = $e->getMessage();
            $response = response($error, Response::HTTP_NOT_FOUND);
        }
        return $response;
    }
}
