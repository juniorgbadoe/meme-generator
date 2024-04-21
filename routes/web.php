<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', [MemeController::class, 'index']);

// Afficher le formulaire de création de mème
Route::get('/memes/create', [MemeController::class, 'create'])->name('memes.create');

// Enregistrer un nouveau mème
Route::post('/memes', [MemeController::class, 'store'])->name('memes.store');

// Afficher un mème spécifique
Route::get('/memes/{meme}', [MemeController::class, 'show'])->name('memes.show');

// Afficher tous les mèmes
Route::get('/memes', [MemeController::class, 'index'])->name('memes.index');

// Modifier un mème
Route::get('/memes/{id}/edit', [MemeController::class, 'edit'])->name('memes.edit');
Route::put('/memes/{id}', [MemeController::class, 'update'])->name('memes.update');

// Supprimer un mème
Route::delete('/memes/{id}', [MemeController::class, 'destroy'])->name('memes.destroy');
