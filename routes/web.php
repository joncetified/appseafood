<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyProfileController;
use App\Http\Controllers\Admin\AccessControlController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeletedRecordController;
use App\Http\Controllers\Admin\ImportExportController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SeafoodItemController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AccountActivationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderPlacementController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::post('/orders', [OrderPlacementController::class, 'store'])->name('orders.store');

Route::middleware('guest')->group(function () {
    Route::redirect('/login', '/owner-login');
    Route::get('/owner-login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/owner-login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/activation-sent', [AccountActivationController::class, 'notice'])->name('activation.notice');
    Route::get('/activate-account/{token}', [AccountActivationController::class, 'activate'])->name('activation.verify');
    Route::post('/activate-account/resend', [AccountActivationController::class, 'resend'])->name('activation.resend');
    Route::get('/forgot-password', [PasswordResetController::class, 'showRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.reset.update');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/admin', DashboardController::class)->middleware('access:dashboard')->name('admin.dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::middleware('access:categories')->group(function () {
            Route::resource('categories', CategoryController::class)->except('show');
        });

        Route::middleware('access:seafood_items')->group(function () {
            Route::resource('seafood-items', SeafoodItemController::class)->except('show');
        });

        Route::middleware('access:promotions')->group(function () {
            Route::resource('promotions', PromotionController::class)->except('show');
        });

        Route::middleware('access:testimonials')->group(function () {
            Route::resource('testimonials', TestimonialController::class)->except('show');
        });

        Route::middleware('access:website_settings')->group(function () {
            Route::get('company-profile', [CompanyProfileController::class, 'edit'])->name('company-profile.edit');
            Route::put('company-profile', [CompanyProfileController::class, 'update'])->name('company-profile.update');
        });

        Route::middleware(['role:super_admin', 'access:users'])->group(function () {
            Route::resource('users', UserController::class)->except('show');
        });

        Route::middleware(['role:super_admin', 'access:access_control'])->group(function () {
            Route::get('access-control', [AccessControlController::class, 'index'])->name('access-control.index');
            Route::put('access-control/{role}', [AccessControlController::class, 'update'])->name('access-control.update');
        });

        Route::middleware(['role:super_admin', 'access:deleted_records'])->group(function () {
            Route::get('deleted-records', [DeletedRecordController::class, 'index'])->name('deleted-records.index');
            Route::post('deleted-records/{type}/{recordId}/restore', [DeletedRecordController::class, 'restore'])->name('deleted-records.restore');
        });

        Route::middleware(['role:super_admin', 'access:maintenance'])->group(function () {
            Route::get('maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
            Route::post('maintenance/backup-database', [MaintenanceController::class, 'backupDatabase'])->name('maintenance.backup-database');
            Route::post('maintenance/restart-database', [MaintenanceController::class, 'restartDatabase'])->name('maintenance.restart-database');
            Route::get('maintenance/backups/{backup}', [MaintenanceController::class, 'download'])->name('maintenance.backups.download');
        });

        Route::middleware('access:imports_exports')->group(function () {
            Route::get('import-export', [ImportExportController::class, 'index'])->name('import-export.index');
            Route::get('import-export/users/export', [ImportExportController::class, 'exportUsers'])->name('import-export.users.export');
            Route::get('import-export/items/export', [ImportExportController::class, 'exportItems'])->name('import-export.items.export');
            Route::post('import-export/users/import', [ImportExportController::class, 'importUsers'])->name('import-export.users.import');
            Route::post('import-export/items/import', [ImportExportController::class, 'importItems'])->name('import-export.items.import');
            Route::post('import-export/users/backup', [ImportExportController::class, 'backupUsers'])->name('import-export.users.backup');
            Route::post('import-export/items/backup', [ImportExportController::class, 'backupItems'])->name('import-export.items.backup');
        });

        Route::middleware('access:orders')->group(function () {
            Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
            Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
            Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
        });

        Route::middleware('access:reports')->group(function () {
            Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
            Route::get('reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
            Route::get('reports/print', [ReportController::class, 'print'])->name('reports.print');
        });
    });
});
