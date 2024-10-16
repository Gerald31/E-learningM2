<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubjectRequests;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;
use App\Services\Subject\SubjectServices;
use App\Services\Subject\SubjectStoreDTO;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SubjectServices $SubjectServices)
    {
        $allSubject = $SubjectServices->getAllSubject();

        return response(SubjectResource::collection($allSubject), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSubjectRequests $request
     * @param SubjectServices $subjectServices
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubjectRequests $request, SubjectServices $subjectServices)
    {
        try {
            $subject = $subjectServices->storeSubject(new SubjectStoreDTO(
                $request->id,
                $request->subjectName,
                $request->classLevelId,
            ));
            $sub = new SubjectResource($subject);

            $response = response($sub, Response::HTTP_OK);
        } catch (Exception $e){
            $error['message'] = $e->getMessage();
            $response = response($error, Response::HTTP_NOT_FOUND);
        }
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param Request $request
     * @param SubjectServices $subjectServices
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request, SubjectServices $subjectServices)
    {
        try {
            $subjectServices->storeSubject(new SubjectStoreDTO(
                $id,
                $request->subjectName,
                $request->classLevelId,
            ));

            $success['message'] = "Les données ont été mises à jour";
            $response = response($success, Response::HTTP_OK);
        } catch (Exception $e) {
            $error['message'] = $e->getMessage();
            $response = response($error, Response::HTTP_NOT_FOUND);
        }
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        try {
            $subject->delete();
            $success['message'] = "Les suppression est éffectuées";
            $response = response($success, Response::HTTP_OK);
        } catch (Exception $e) {
            $error['message'] = $e->getMessage();
            $response = response($error, Response::HTTP_NOT_FOUND);
        }
        return $response;
    }
}
