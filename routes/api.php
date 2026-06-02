<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommunityGroupController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\FundController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\MemberContributionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('users', UserController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('groups', CommunityGroupController::class);
Route::apiResource('activities', ActivityController::class);
Route::apiResource('members', MemberController::class);
Route::apiResource('funds', FundController::class);
Route::apiResource('transactions', TransactionController::class);
Route::apiResource('contributions', MemberContributionController::class);