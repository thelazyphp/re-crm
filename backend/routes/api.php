<?php

use App\Http\Controllers\InvitationApiController;
use App\Http\Controllers\OrgApiController;
use App\Http\Controllers\OrgInvitationApiController;
use App\Http\Controllers\OrgMemberApiController;
use App\Http\Controllers\UserApiController;
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

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [UserApiController::class, 'showCurrent']);
    Route::patch('/user', [UserApiController::class, 'updateCurrent']);
    Route::get('/orgs', [OrgApiController::class, 'index']);
    Route::post('/orgs', [OrgApiController::class, 'store']);
    Route::get('/orgs/{org}', [OrgApiController::class, 'show']);
    Route::patch('/orgs/{org}', [OrgApiController::class, 'update']);
    Route::delete('/orgs/{org}', [OrgApiController::class, 'destroy']);
    Route::get('/orgs/{org}/invitations', [OrgInvitationApiController::class, 'index']);
    Route::post('/orgs/{org}/invitations', [OrgInvitationApiController::class, 'store']);
    Route::get('/orgs/{org}/members', [OrgMemberApiController::class, 'index']);
    Route::get('/orgs/{org}/members/{member}', [OrgMemberApiController::class, 'show']);
    Route::delete('/orgs/{org}/members/{member}', [OrgMemberApiController::class, 'destroy']);
    Route::get('/orgs/{org}/memberships/{member}', [OrgMemberApiController::class, 'showMembership']);
    Route::put('/orgs/{org}/memberships/{member}', [OrgMemberApiController::class, 'updateMembership']);
    Route::get('/invitations/{invitation}', [InvitationApiController::class, 'show']);
    Route::delete('/invitations/{invitation}', [InvitationApiController::class, 'destroy']);

});
