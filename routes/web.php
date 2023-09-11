<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/telegram/updates/{bot?}', function ($bot = 'default') {
    // This method will fetch updates,
    // fire relevant events and,
    // confirm we've received the updates with Telegram.
    $updates = Telegram::bot($bot)->listen();

    // You can do something with the fetched array of update objects.

    // NOTE: You won't be able to fetch updates if a webhook is set.
    // Remove webhook before using this method.
});
