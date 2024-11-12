<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Applica il middleware SetLocale globalmente
Route::middleware([\App\Http\Middleware\SetLocale::class])->group(function () {
    // Redirect della root URL
    Route::get('/', function () {
        return redirect(app()->getLocale());
    });

    // Raggruppa tutte le rotte localizzate
    Route::prefix('{locale}')
        ->where(['locale' => '[a-zA-Z]{2}'])
        ->group(function () {
            // Welcome page con Inertia
            Route::get('/', function () {
                return Inertia::render('Welcome', [
                    'canLogin' => Route::has('login'),
                    'canRegister' => Route::has('register'),
                    'laravelVersion' => Application::VERSION,
                    'phpVersion' => PHP_VERSION,
                ]);
            })->name('welcome');

            // Dashboard route con Inertia
            Route::get('/dashboard', function () {
                return Inertia::render('Dashboard');
            })->middleware(['auth', 'verified'])->name('dashboard');

            // Profile routes raggruppate
            Route::middleware('auth')->group(function () {
                Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
                Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
                Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
            });

            // Includi le rotte di autenticazione
            require __DIR__.'/auth.php';
        });

    // Cattura tutte le rotte non localizzate e redireziona
    Route::fallback(function (Request $request) {
        $locale = $request->cookie('preferred_locale', config('app.fallback_locale'));
        return redirect("/{$locale}/{$request->path()}");
    });
});