<?php

// use Illuminate\Support\Facades\Route;

// Route::get("/", function () {
//     $events =
//     return view("main");
// });

use App\Http\Controllers\MeetupController;

// Route::withoutSession()->group(function () {
// });

Route::get("/", MeetupController::class)->name("home");
