<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HelloWorldController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('hello',[HelloWorldController::class,'index']);

Route::get('categories', [CategoryController::class, 'index']);
Route::post('login', [AuthController::class,'login']);
Route::post('logout',[AuthController::class,'logout']);
Route::get('getUsers',[AuthController::class,'getUser']);
Route::post('signup',[AuthController::class, 'signup']);
Route::get('getProducts',[CategoryController::class,'getProducts']);
