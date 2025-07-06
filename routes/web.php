<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
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

Route::redirect('/', '/dashboard');

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Product
    Route::resource('products', ProductController::class)->except('show');
    
    // Sales 
    Route::resource('sales', SaleController::class);
    Route::prefix('sales/{sale}')->group(function () {
        Route::get('invoice', [SaleController::class, 'invoice'])->name('sales.invoice');
        Route::get('invoice/download', [SaleController::class, 'downloadInvoice'])->name('sales.invoice.download');
        Route::post('payment', [SaleController::class, 'recordPayment'])->name('sales.payment');
    });
    
    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::get('sales', [ReportController::class, 'salesReport'])->name('reports.sales');
        Route::get('inventory', [ReportController::class, 'inventoryReport'])->name('reports.inventory');
        Route::get('{report}', [ReportController::class, 'show'])->name('reports.show');
    });
});