<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController; // <--- 1. ADDED IMPORT
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\SubscriptionController;




// --- DEFAULT PUBLIC ROUTES ---

// 1. Set the Root URL (/) to point to the Home Page
// This makes the home page load automatically when you visit the site
Route::get('/', function () {
    return view('frontend.index');
})->name('home');

// 2. Keep the /home route pointing to the same place (optional, but good for consistency)
Route::get('/home', function () {
    return view('frontend.index');
})->name('home.link');

// <--- 2. ADDED: Public route to submit the contact form from the landing page --->
Route::post('/contact-submit', [ContactController::class, 'store'])->name('contact.submit');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Protected Routes (Require Login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard is now accessed via /dashboard explicitly
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Drugs Management
    |--------------------------------------------------------------------------
    */

    // START: Custom Routes (Must be BEFORE Route::resource)
    Route::post('drugs/import', [DrugController::class, 'import'])->name('drugs.import');
    Route::get('drugs/template', [DrugController::class, 'downloadTemplate'])->name('drugs.template');
    // END: Custom Routes

    Route::resource('drugs', DrugController::class);

    Route::post('drugs/{drug}/add-stock', [DrugController::class, 'addStock'])
        ->name('drugs.add-stock');

    /*
    |--------------------------------------------------------------------------
    | Sales
    |--------------------------------------------------------------------------
    */
    Route::resource('sales', SaleController::class);
    Route::get('sales/{sale}/receipt', [SaleController::class, 'receipt'])
        ->name('sales.receipt');

    Route::get('sales/search-drug', [SaleController::class, 'searchDrug'])
        ->name('sales.search-drug');

    // Purchases Routes
    Route::get('purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
    Route::get('purchases/{purchase}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::put('purchases/{purchase}', [PurchaseController::class, 'update'])->name('purchases.update');
    Route::delete('purchases/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');

    // Customers Routes
    Route::resource('customers', CustomerController::class);

    /*
    |--------------------------------------------------------------------------
    | POS
    |--------------------------------------------------------------------------
    */

// Branch Switcher Route
Route::post('/admin/branch/switch', [POSController::class, 'switchBranch'])->name('admin.branch.switch');



    Route::get('pos', [POSController::class, 'create'])->name('pos.create');
    Route::post('pos', [POSController::class, 'store'])->name('pos.store');
    Route::get('pos/search', [POSController::class, 'search'])->name('pos.search');

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */
    Route::prefix('reports')->name('reports.')->group(function () {

        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('sales', [ReportController::class, 'salesReport'])->name('sales');
        Route::get('inventory', [ReportController::class, 'inventoryReport'])->name('inventory');
        Route::get('expiry', [ReportController::class, 'expiryReport'])->name('expiry');
        Route::get('top-products', [ReportController::class, 'topProductsReport'])->name('top-products');
        Route::get('profit', [ReportController::class, 'profitReport'])->name('profit');
        Route::get('/export', [ReportController::class, 'export'])->name('export');
    });

    /*
    |--------------------------------------------------------------------------
    | Master Data
    |--------------------------------------------------------------------------
    */
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);

    Route::resource('users', UserController::class);
    Route::post('/roles/save-permissions', [RoleController::class, 'savePermissions'])->name('roles.savePermissions');
    Route::resource('roles', RoleController::class);

    // Pharmacy Management (Super Admin Only)
    Route::resource('pharmacies', PharmacyController::class)->middleware('is.superadmin');

    // NEW: Pharmacy Activation/Deactivation Routes
    Route::post('pharmacies/{pharmacy}/activate', [PharmacyController::class, 'activate'])->name('pharmacies.activate');
    Route::post('pharmacies/{pharmacy}/deactivate', [PharmacyController::class, 'deactivate'])->name('pharmacies.deactivate');

    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    /*
    |--------------------------------------------------------------------------
    | Subscription Management
    |--------------------------------------------------------------------------
    */

    // 1. Super Admin: View all pharmacies & renew forms
    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('subscriptions/{pharmacy}/edit', [SubscriptionController::class, 'edit'])->name('subscriptions.edit');
    Route::put('subscriptions/{pharmacy}', [SubscriptionController::class, 'update'])->name('subscriptions.update');

    // 2. Pharmacy User: View their own subscription status
    Route::get('my-subscription/{pharmacy}', [SubscriptionController::class, 'show'])->name('subscriptions.show');

    // <--- 3. ADDED: Admin route to view contact messages --->
    Route::get('/admin/contacts', [ContactController::class, 'index'])->name('admin.contacts.index');


});