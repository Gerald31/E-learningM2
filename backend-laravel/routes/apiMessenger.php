<?php

use App\Http\Controllers\Api\ChatController;
use Illuminate\Support\Facades\Route;

/**
 * Authentication for pusher private channels
 */
Route::post('/chat/auth', [ChatController::class, 'pusherAuth'])->name('api.pusher.auth');

/**
 *  Fetch info for specific id [user/group]
 */
Route::post('/idInfo', [ChatController::class, 'idFetchData'])->name('api.idInfo');

/**
 * Send message route
 */
Route::post('/sendMessage/{tutoratId}', [ChatController::class, 'send'])->name('api.send.message');

/**
 * Fetch messages
 */
Route::post('/fetchMessages/{tutoratId}', [ChatController::class, 'fetch'])->name('api.fetch.messages');

/**
 * Download attachments route to create a downloadable links
 */
Route::get('/download/{message}', [ChatController::class, 'download'])->name('api.'.config('chat.attachments.download_route_name'));

/**
 * Make messages as seen
 */
Route::post('/makeSeen', [ChatController::class, 'seen'])->name('api.messages.seen');

/**
 * Get contacts
 */
Route::get('/fetchContacts/{tutoratId}', [ChatController::class, 'getContacts'])->name('api.contacts.get');

/**
 * Search in messenger
 */
Route::get('/search', [ChatController::class, 'search'])->name('api.search');

/**
 * Get shared photos
 */
Route::post('/shared', [ChatController::class, 'sharedPhotos'])->name('api.shared');

/**
 * Delete Conversation
 */
Route::post('/deleteConversation', [ChatController::class, 'deleteConversation'])->name('api.conversation.delete');

/**
 * Delete Conversation
 */
Route::post('/updateSettings', [ChatController::class, 'updateSettings'])->name('api.avatar.update');

/**
 * Set active status
 */
Route::post('/setActiveStatus', [ChatController::class, 'setActiveStatus'])->name('api.activeStatus.set');



