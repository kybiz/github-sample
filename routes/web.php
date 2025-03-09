<?php

use App\Http\Controllers\API\GithubController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome'); // Use Blade just to serve Vue
})->where('any', '.*');

Route::get('/git', [GithubController::class, 'getIssues']);
