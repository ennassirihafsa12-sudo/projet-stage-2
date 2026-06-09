<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EtapeController;
use App\Http\Controllers\LettreController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MarcheController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RappelController;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::get('/acceuil', function () {
    return view('welcome');
})->name('home');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/photo', [ProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');

    Route::resource('marches', MarcheController::class);

    Route::get('/etapes', [EtapeController::class, 'index'])->name('etapes.index');
    Route::get('/marches/{marche}/etapes', [EtapeController::class, 'show'])->name('etapes.show');
    Route::post('/etapes/{etape}/terminer', [EtapeController::class, 'terminer'])->name('etapes.terminer');

    Route::get('/rappels', [RappelController::class, 'index'])->name('rappels.index');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/marquer-tout-lu', [NotificationController::class, 'marquerToutLu'])->name('notifications.marquer-tout-lu');

    Route::get('/lettres', [LettreController::class, 'index'])->name('lettres.index');
    Route::post('/lettres', [LettreController::class, 'store'])->name('lettres.store');
    Route::get('/lettres/apercu', [LettreController::class, 'preview'])->name('lettres.preview');
    Route::get('/lettres/{lettre}/telecharger', [LettreController::class, 'download'])->name('lettres.download');
    Route::post('/lettres/download-pdf', [LettreController::class, 'downloadPdf'])->name('lettres.download-pdf');

});

