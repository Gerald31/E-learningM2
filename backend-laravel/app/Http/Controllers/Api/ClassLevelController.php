<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassLevelRequests;
use App\Models\ClassLevel;
use App\Services\ClassLevel\ClassLevelServices;
use App\Services\ClassLevel\ClassLevelStoreDTO;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClassLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ClassLevelServices $classLevelServices)
    {
        $allClassLevel = $classLevelServices->getAllClassLevel();

        return response($allClassLevel, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreClassLevelRequests $classLevelRequests
     * @param ClassLevelServices $classLevelServices
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClassLevelRequests $classLevelRequests, ClassLevelServices $classLevelServices)
    {
        try {
            $classe = $classLevelServices->storeClassLevel(new ClassLevelStoreDTO(
                $classLevelRequests->id,
                $classLevelRequests->label,
                $classLevelRequests->niveau,
            ));

            $response = response($classe, Response::HTTP_OK);
        } catch (Exception $e){
            $error['message'] = $e->getMessage();
            $response = response($error, Response::HTTP_NOT_FOUND);
        }
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassLevel  $classLevel
     * @return \Illuminate\Http\Response
     */
    public function show(ClassLevel $classLevel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param Request $request
     * @param ClassLevelServices $classLevelServices
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request, ClassLevelServices $classLevelServices)
    {
        try {
            $classLevelServices->storeClassLevel(new ClassLevelStoreDTO(
                $id,
                $request->label,
                $request->niveau,
            ));

            $error['message'] = "Les données ont été mises à jour";
            $response = response($error, Response::HTTP_OK);
        } catch (Exception $e) {
            $error['message'] = $e->getMessage();
            $response = response($error, Response::HTTP_NOT_FOUND);
        }
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClassLevel  $classLevel
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassLevel $classLevel)
    {
        //
    }
}
