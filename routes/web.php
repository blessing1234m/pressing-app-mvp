<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PressingController;
use App\Http\Controllers\Admin\PressingApprovalController;
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

// Routes pour la gestion des prix
Route::get('/dashboard/pricing', [DashboardController::class, 'editPricing'])->name('dashboard.pricing')->middleware(['auth', 'checktype:owner']);
Route::post('/dashboard/pricing', [DashboardController::class, 'updatePricing'])->name('dashboard.pricing.update')->middleware(['auth', 'checktype:owner']);

// Routes pour la gestion des commandes
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show')->middleware('auth');
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status')->middleware(['auth', 'checktype:owner']);

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index')->middleware('auth');
Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read')->middleware('auth');
Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all')->middleware('auth');
Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count')->middleware('auth');

// Routes pour la gestion des pressings
Route::get('/pressings/create', [PressingController::class, 'create'])->name('pressings.create')->middleware(['auth', 'checktype:owner']);
Route::post('/pressings', [PressingController::class, 'store'])->name('pressings.store')->middleware(['auth', 'checktype:owner']);
Route::get('/pressings/{pressing}/edit', [PressingController::class, 'edit'])->name('pressings.edit')->middleware(['auth', 'checktype:owner']);
Route::put('/pressings/{pressing}', [PressingController::class, 'update'])->name('pressings.update')->middleware(['auth', 'checktype:owner']);

Route::prefix('admin')->name('admin.')->middleware(['auth', 'checktype:admin'])->group(function () {
    Route::get('/pressings', [PressingApprovalController::class, 'index'])->name('pressings.index');
    Route::get('/pressings/{pressing}', [PressingApprovalController::class, 'show'])->name('pressings.show');
    Route::post('/pressings/{pressing}/approve', [PressingApprovalController::class, 'approve'])->name('pressings.approve');
    Route::delete('/pressings/{pressing}/reject', [PressingApprovalController::class, 'reject'])->name('pressings.reject');
    Route::get('/pressings/{pressing}/confirm-delete', [PressingApprovalController::class, 'confirmDelete'])->name('pressings.confirm-delete');
    Route::delete('/pressings/{pressing}', [PressingApprovalController::class, 'destroy'])->name('pressings.destroy');
});

require __DIR__ . '/auth.php';

