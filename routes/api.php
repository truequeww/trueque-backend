<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ThingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ConditionController;
use App\Http\Controllers\OfferStatusController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\DislikeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OfferThingController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FilterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FcmTokenController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
Route::put('checkAndVerifyBio/{VerCode}', [AuthController::class, 'checkAndVerifyBio']);

Route::get('resetPasswordSendVerCode/{email}', [AuthController::class, 'resetPasswordSendVerCode']);
Route::put('verifyAndChanegPassword/', [AuthController::class, 'verifyAndChanegPassword']);


// Require Auth for CRUD operations
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/fcm-token/register', [FcmTokenController::class, 'register']);
    Route::post('/fcm-token/unregister', [FcmTokenController::class, 'unregister']);

    //profile
    Route::get('/user/details', [UserController::class, 'getUserDetails']); //Get the user data, things, reviews and swaps succesful count. To show in profile page
    Route::post('/UpdateThing', [ThingController::class, 'update']); //change thing.availability true to false, false to true
    Route::put('/DeleteThing/{id}/', [ThingController::class, 'deleteThing']); //change thing.availability true to false, false to true
    Route::post('/updateUser', [UserController::class, 'updateUser']); //change thing.availability true to false, false to true

    //swaps
    Route::get('/filtered-things', [ThingController::class, 'getFilteredThings']); //get things according to parameters for swipe
    Route::post('/load/swipes', [LikeController::class, 'loadSwipes']); //swipe right in a thing,
    Route::post('/swipeLeft', [LikeController::class, 'swipeLeft']); //swipe right in a thing,
    Route::post('/swipeRight', [LikeController::class, 'swipeRight']); //swipe right in a thing,

    //messages
    Route::get('/user-chats', [ChatController::class, 'getUserChats']); //Get user chats just to show in the main page
    Route::get('/getMessagesByChatId', [MessageController::class, 'getMessagesByChatId']); //Get messages by chat id

    Route::get('/user-things', [ThingController::class, 'getUserThings']); //Get things for make an offer of 2 users
    Route::post('/make-offer', [OfferController::class, 'createOffer']); //Make an offer from user 1 to user 2

    Route::get('/offer-all', [OfferController::class, 'getOfferWithDetails']); //Get offer info from 1 offer by Offerid
    Route::get('/offers/between', [OfferController::class, 'getOffersBetweenUsers']); //Get all offers between 2 users
    Route::put('/offers/changeStatus', [OfferController::class, 'changeOfferStatus']); // Change the Status of an offer

    //Models all api methods
    Route::apiResource('things', ThingController::class);
    Route::apiResource('likes', LikeController::class);
    Route::apiResource('dislikes', DislikeController::class);
    Route::apiResource('chats', ChatController::class);
    Route::apiResource('messages', MessageController::class);
    Route::apiResource('offers', OfferController::class);
    Route::apiResource('offer-things', OfferThingController::class);
    Route::apiResource('ratings', RatingController::class);
    Route::apiResource('notifications', NotificationController::class);


    Route::apiResource('offer-status', OfferStatusController::class);

});

Route::get('filters', [FilterController::class, 'index']);

// Route::get('categories', [CategoryController::class, 'index']);
// Route::get('materials', [MaterialController::class, 'index']);
// Route::get('colors', [ColorController::class, 'index']);
// Route::get('conditions', [ConditionController::class, 'index']);

// Route::apiResource('categories', CategoryController::class);
// Route::apiResource('materials', MaterialController::class);
// Route::apiResource('colors', ColorController::class);
// Route::apiResource('conditions', ConditionController::class);
//t
