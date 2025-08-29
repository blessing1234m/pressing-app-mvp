<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;

use App\Http\Controllers\DashboardController;
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

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/orders/create/{pressing}', [OrderController::class, 'create'])->name('orders.create')->middleware(['auth', 'checktype:client']);

Route::post('/orders', [OrderController::class, 'store'])->name('orders.store')->middleware(['auth', 'checktype:client']);

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/admin/users', [DashboardController::class, 'users'])->name('admin.users')->middleware(['auth', 'checktype:admin']);

// Routes pour la gestion des utilisateurs (admin)
Route::get('/admin/users/{user}/edit', [DashboardController::class, 'editUser'])->name('admin.users.edit')->middleware(['auth', 'checktype:admin']);
Route::put('/admin/users/{user}', [DashboardController::class, 'updateUser'])->name('admin.users.update')->middleware(['auth', 'checktype:admin']);
Route::delete('/admin/users/{user}', [DashboardController::class, 'deleteUser'])->name('admin.users.destroy')->middleware(['auth', 'checktype:admin']);

require __DIR__ . '/auth.php';

// Routes pour la gestion des prix
Route::get('/dashboard/pricing', [DashboardController::class, 'editPricing'])->name('dashboard.pricing')->middleware(['auth', 'checktype:owner']);
Route::post('/dashboard/pricing', [DashboardController::class, 'updatePricing'])->name('dashboard.pricing.update')->middleware(['auth', 'checktype:owner']);
