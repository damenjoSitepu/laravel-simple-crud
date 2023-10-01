<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get("/", [\App\Http\Controllers\Public\HomeController::class,"index"])->name("home");

include __DIR__ . "/web/author.php";
include __DIR__ . "/web/book.php";