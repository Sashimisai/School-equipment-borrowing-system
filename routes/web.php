<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

// Public routes (accessible by all authenticated users)
Route::middleware('auth')->group(function () {
    Route::resource('equipment', EquipmentController::class);
    Route::resource('borrowings', BorrowingController::class);
    
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/requests', [AdminController::class, 'requests'])->name('requests');
    Route::post('/requests/{borrowing}/approve', [AdminController::class, 'approveRequest'])->name('approve');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/approve', [AdminController::class, 'approveUser'])->name('users.approve');
    Route::post('/users/{user}/update-role', [AdminController::class, 'updateUserRole'])->name('users.updateRole');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
    Route::delete('/users/{user}/delete', [AdminController::class, 'deleteUser'])->name('users.delete');
});