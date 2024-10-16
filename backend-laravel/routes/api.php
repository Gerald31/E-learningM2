<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClassLevelController;
use App\Http\Controllers\Api\DashBoardController;
use App\Http\Controllers\Api\MagController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\TutoratController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
require 'apiMessenger.php';

Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [RegistrationController::class, 'register']);
    Route::post('user/upload', [RegistrationController::class, 'upload']);
    Route::get('home/statistics', [DashBoardController::class, 'home']);


    Route::get('etudiant/{id}/class-level', [UserController::class, 'getEtudiantByClassLevel']);
    Route::get('professeur/{classId}/{subjectId}', [UserController::class, 'getProfesseurByClassLevel']);
    Route::get('my-skill/{userId}/{role}', [UserController::class, 'getMySkill']);


    Route::group(['prefix' => 'tutorat'], function () {
        Route::post('', [TutoratController::class, 'store']);
        Route::get('', [TutoratController::class, 'index']);
        Route::put('{id}', [TutoratController::class, 'update']);
        Route::put('update/state', [TutoratController::class, 'updateTutoratState']);
        Route::get('/{tutoratId}', [TutoratController::class, 'show']);
        Route::get('professeur/{id}', [TutoratController::class, 'getTutoratToProfesseur']);
        Route::get('etudiant/{id}', [TutoratController::class, 'getTutoratToEtudiant']);
        Route::get('my-tutorat/{userId}/{role}', [TutoratController::class, 'getMyTutorat']);
        Route::put('{id}/affected-student', [TutoratController::class, 'affectedStudentTutorat']);
        Route::put('{id}/affected-tutor/{tutor}', [TutoratController::class, 'affectedTutorTutorat']);
        Route::delete('/{id}', [TutoratController::class, 'destroy']);
    });

    Route::group(['prefix' => 'subject'], function () {
        Route::post('', [SubjectController::class, 'store']);
        Route::get('', [SubjectController::class, 'index']);
        Route::put('{id}', [SubjectController::class, 'update']);
        Route::get('/{subject}', [SubjectController::class, 'show']);
        Route::delete('/{subject}', [SubjectController::class, 'destroy']);
    });

    Route::group(['prefix' => 'mag'], function () {
        Route::post('', [MagController::class, 'store']);
        Route::get('', [MagController::class, 'index']);
        Route::put('{id}', [MagController::class, 'update']);
        Route::put('{id}/update-state', [MagController::class, 'updatePublishState']);
        Route::delete('{id}', [MagController::class, 'destroy']);
    });

    Route::group(['prefix' => 'class-level'], function () {
        Route::post('', [ClassLevelController::class, 'store']);
        Route::get('', [ClassLevelController::class, 'index']);
        Route::put('{id}', [ClassLevelController::class, 'update']);
        Route::get('{classLevel}', [ClassLevelController::class, 'show']);
        Route::delete('{classLevel}', [ClassLevelController::class, 'destroy']);
    });

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('user', function( Request $request ) {
            return $request->user();
        });

        Route::group(['prefix' => 'dashboard'], function () {
            Route::get('statistics', [DashBoardController::class, 'statisticUserAdmin']);
        });

        Route::group(['prefix' => 'users'], function () {
            Route::get('by-role/{role}', [UserController::class, 'usersByRole']);
        });

        Route::group(['middleware' => 'isadmin'], function() {
            Route::get('get-all-users', [AuthController::class, 'getAllUsers']);

        });

        Route::get('logout', [AuthController::class, 'logout']);
    });

    Route::get('/verify/{token}/user', [AuthController::class, 'verifyUser']);

    Route::group(['prefix' => 'stripe'], function () {
        Route::post('create-customer', [StripeController::class, 'createCustomer']);
        Route::post('create-payment/{tutorat}', [StripeController::class, 'createPayment']);
        Route::post('pay-intent', [StripeController::class, 'payIntent']);
        Route::get('', [ClassLevelController::class, 'index']);
    });
});

