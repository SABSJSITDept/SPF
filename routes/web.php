<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpfRegistrationController;
use App\Http\Controllers\AdminWebAuthController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\members\BulkRegistrationController;

Route::get('/', function () {
    return view('spf_frontend.index');
});

Route::get('/spf-registration', function () {
    return view('spf_frontend.index');
});

// ── Password Reset (Frontend Users) ───────────────────────────────────────
Route::get('/change-password', [PasswordResetController::class, 'showForm'])->name('password.reset.form');
Route::post('/change-password', [PasswordResetController::class, 'resetPassword'])->name('password.reset.submit');

// ── Admin Auth (guest) ─────────────────────────────────────────────────────
Route::get('/admin/login', [AdminWebAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminWebAuthController::class, 'login'])->name('admin.login.post');

// ── Admin Panel (protected) ────────────────────────────────────────────────
Route::middleware(['admin.auth'])->prefix('spf-backend')->name('admin.')->group(function () {

    // Dashboard — accessible by all 3 roles
    Route::get('/', [SpfRegistrationController::class, 'dashboard'])->name('dashboard');

    // Registrations list
    Route::get('/registrations', [SpfRegistrationController::class, 'index'])->name('registrations');

    // Password change — accessible by all authenticated admins
    Route::get('/change-password', [AdminWebAuthController::class, 'showChangePassword'])->name('password.form');
    Route::post('/change-password', [AdminWebAuthController::class, 'updatePassword'])->name('password.update');

    // Logout
    Route::post('/logout', [AdminWebAuthController::class, 'logout'])->name('logout');

    // Super Admin only routes
    Route::middleware(['admin.role:super_admin'])->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::delete('/users/{admin}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Categories
        Route::get('/categories/add',            [CategoryController::class, 'showAddCategory'])->name('categories.add');
        Route::post('/categories',               [CategoryController::class, 'storeCategory'])->name('categories.store');
        Route::patch('/categories/{category}/toggle', [CategoryController::class, 'toggleCategory'])->name('categories.toggle');

        // Sub-Categories
        Route::get('/sub-categories/add',                        [CategoryController::class, 'showAddSubCategory'])->name('sub-categories.add');
        Route::post('/sub-categories',                           [CategoryController::class, 'storeSubCategory'])->name('sub-categories.store');
        Route::patch('/sub-categories/{subCategory}/toggle',     [CategoryController::class, 'toggleSubCategory'])->name('sub-categories.toggle');
    });

    // Super Admin + Operator + Anchal Operator routes
    Route::middleware(['admin.role:super_admin,operator,anchal_operator'])->group(function () {
        // Bulk Upload
        Route::get('/members/bulk-upload', [BulkRegistrationController::class, 'showForm'])->name('members.bulk_upload');
        Route::post('/members/bulk-upload', [BulkRegistrationController::class, 'processUpload'])->name('members.bulk_upload.post');

        // Members by status (view & status actions)
        Route::get('/members/approved',  [SpfRegistrationController::class, 'approvedMembers'])->name('members.approved');
        Route::get('/members/rejected',  [SpfRegistrationController::class, 'rejectedMembers'])->name('members.rejected');
        Route::get('/members/pending',   [SpfRegistrationController::class, 'pendingMembers'])->name('members.pending');
        Route::post('/members/approve-all', [SpfRegistrationController::class, 'approveAll'])->name('members.approveAll');
        Route::post('/members/bulk-status', [SpfRegistrationController::class, 'bulkUpdateStatus'])->name('members.bulkUpdateStatus');
        Route::post('/members/{id}/status', [SpfRegistrationController::class, 'updateStatus'])->name('members.updateStatus');

        // Edit member (not anchal_operator)
        Route::get('/members/{id}/edit',  [SpfRegistrationController::class, 'edit'])->name('members.edit');
        Route::post('/members/{id}/update', [SpfRegistrationController::class, 'updateMember'])->name('members.update');
    });
});
