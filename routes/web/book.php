<?php

use Illuminate\Support\Facades\Route;

Route::prefix("book")->name("book.")->group(function() {
    Route::get("", [\App\Http\Controllers\App\BookController::class,"index"])->name("index");
    Route::get("/create",[\App\Http\Controllers\App\BookController::class,"create"])->name("create");
    Route::post("/store",[\App\Http\Controllers\App\BookController::class,"store"])->name("store");
    Route::delete("/{id}/delete",[\App\Http\Controllers\App\BookController::class,"destroy"])->name("delete");
    Route::get("/{id}/update",[\App\Http\Controllers\App\BookController::class,"update"])->name("update");
    Route::patch("/{id}/update",[\App\Http\Controllers\App\BookController::class,"edit"])->name("edit");
});


