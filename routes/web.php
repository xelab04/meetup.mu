<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MeetupController;
use Illuminate\Support\Facades\Route;

Route::get("/", [MeetupController::class, "home"])->name("home");

Route::get("/past", [MeetupController::class, "past"])->name("past");

Route::get("/past/{community}", [
    MeetupController::class,
    "past_community",
])->name("past-community");

Route::get("/c/{community}", [MeetupController::class, "community"])->name(
    "community"
);

Route::get("/healthz", function () {
    return response()->json(["status" => "ok"]);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/debug-proto', function (\Illuminate\Http\Request $request) {
    return [
        'secure' => $request->secure(),
        'scheme' => $request->getScheme(),
        'url' => $request->fullUrl(),
        'headers' => $request->headers->all()
    ];
});

require __DIR__.'/auth.php';
