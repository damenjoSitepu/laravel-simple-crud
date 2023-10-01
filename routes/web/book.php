<?php

use Illuminate\Support\Facades\Route;

Route::prefix("book")->name("book.")->group(function() {
    Route::get("", [\App\Http\Controllers\App\BookController::class,"index"])->name("index");
    Route::get("/create",[\App\Http\Controllers\App\BookController::class,"create"])->name("create");
    Route::post("/store",[\App\Http\Controllers\App\BookController::class,"store"])->name("store");
    Route::get("/{id}/author",[\App\Http\Controllers\App\BookController::class,"author"])->name("author.index");
    Route::get("/{id}/author/assign", [\App\Http\Controllers\App\BookController::class,"assignAuthor"])->name("author.assign");
    Route::post("/{id}/author/store",[\App\Http\Controllers\App\BookController::class,"storeAuthor"])->name("author.store");
    Route::delete("/{id}/author/{authorId}/delete",[\App\Http\Controllers\App\BookController::class,"destroyAuthor"])->name("author.delete");
    Route::delete("/{id}/delete",[\App\Http\Controllers\App\BookController::class,"destroy"])->name("delete");
    Route::get("/{id}/update",[\App\Http\Controllers\App\BookController::class,"update"])->name("update");
    Route::patch("/{id}/update",[\App\Http\Controllers\App\BookController::class,"edit"])->name("edit");
});

