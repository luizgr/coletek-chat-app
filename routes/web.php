<?php

use App\Http\Controllers\Web\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
|
*/

Route::get('/', [PageController::class, 'index'])->name('welcome');
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
