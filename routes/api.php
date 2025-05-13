<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HelloWorldController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('hello',[HelloWorldController::class,'index']);

Route::get('categories', [CategoryController::class, 'index']);
