<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SeafoodItemController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderPlacementController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::post('/orders', [OrderPlacementController::class, 'store'])->name('orders.store');

Route::middleware('guest')->group(function () {
    Route::redirect('/login', '/owner-login');
    Route::get('/owner-login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/owner-login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/admin', DashboardController::class)->name('admin.dashboard');

    Route::middleware('role:super_admin,admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('seafood-items', SeafoodItemController::class)->except('show');
        Route::resource('promotions', PromotionController::class)->except('show');
        Route::resource('testimonials', TestimonialController::class)->except('show');
        Route::get('company-profile', [CompanyProfileController::class, 'edit'])->name('company-profile.edit');
        Route::put('company-profile', [CompanyProfileController::class, 'update'])->name('company-profile.update');
    });

    Route::middleware('role:super_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->except('show');
    });

    Route::middleware('role:super_admin,admin,kasir')->prefix('admin')->name('admin.')->group(function () {
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    });

    Route::middleware('role:super_admin,admin,manager')->prefix('admin')->name('admin.')->group(function () {
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
        Route::get('reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    });
});
