<?php

use Illuminate\Support\Facades\Route;

Route::prefix("author")->name("author.")->group(function() {
    Route::get("", [\App\Http\Controllers\App\AuthorController::class,"index"])->name("index");
    Route::get("/create",[\App\Http\Controllers\App\AuthorController::class,"create"])->name("create");
    Route::post("/store",[\App\Http\Controllers\App\AuthorController::class,"store"])->name("store");
    Route::get("/{id}/book",[\App\Http\Controllers\App\AuthorController::class,"book"])->name("book.index");
    Route::get("/{id}/book/assign", [\App\Http\Controllers\App\AuthorController::class,"assignBook"])->name("book.assign");
    Route::post("/{id}/book/store",[\App\Http\Controllers\App\AuthorController::class,"storeBook"])->name("book.store");
    Route::delete("/{id}/delete",[\App\Http\Controllers\App\AuthorController::class,"destroy"])->name("delete");
    Route::get("/{id}/update",[\App\Http\Controllers\App\AuthorController::class,"update"])->name("update");
    Route::patch("/{id}/update",[\App\Http\Controllers\App\AuthorController::class,"edit"])->name("edit");
});


