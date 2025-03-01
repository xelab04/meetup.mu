<?php

// use Illuminate\Support\Facades\Route;

// Route::get("/", function () {
//     $events =
//     return view("main");
// });

use App\Http\Controllers\MeetupController;

// Route::withoutSession()->group(function () {
// });

Route::get("/", [MeetupController::class, "home"])->name("home");

Route::get("/past", [MeetupController::class, "past"])->name("past");

Route::get("/past/{community}", [
    MeetupController::class,
    "past_community",
])->name("past-community");

Route::get("/c/{community}", [MeetupController::class, "community"])->name(
    "community"
);
