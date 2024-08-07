<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\indexController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AcademicManagementController;
use App\Http\Controllers\PaymentController;
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
Route::group(['prefix'=> 'admin', 'middleware' => ['auth:sanctum', 'verified']], function(){
Route::get('Panel-Administrativo', [indexController::class, 'index'])->name('dashboard');
    Route::group(['middleware' => 'superuser'], function() {
        Route::resource('users', UserController::class)->except(['show'])->names('admin.users');
        Route::get('users/change-password', [UserController::class, 'showChangePasswordForm'])->name('admin.users.changePassword');
        Route::post('users/change-password', [UserController::class, 'updatePassword'])->name('admin.users.updatePassword');
        Route::resource('students', StudentController::class)->names('admin.students');
        Route::get('students/{mediaFile}/download', [StudentController::class, 'download'])->name('admin.students.download');
        Route::delete('students/{mediaFile}/delete', [StudentController::class, 'deleteFile'])->name('admin.students.deleteFile');
        Route::post('students/{student}/matriculate', [StudentController::class, 'matriculate'])
            ->name('admin.students.matriculate')
            ->middleware('moodle.permission');
        Route::resource('academic', AcademicManagementController::class)->names('admin.academic');
        Route::get('academic/create', [AcademicManagementController::class, 'create'])->name('admin.academic.create');
        Route::post('academic', [AcademicManagementController::class, 'store'])->name('admin.academic.store');
        Route::get('academic/{id}', [AcademicManagementController::class, 'show'])->name('admin.academic.show');
        Route::get('academic/subcategory/{id}', [AcademicManagementController::class, 'showSubCategory'])->name('admin.academic.show_subcategory')->middleware('moodle.permission');
        Route::get('academic/create_course/{subcategory_id}', [AcademicManagementController::class, 'createCourse'])->name('admin.academic.create_course');
        Route::post('academic/store_course', [AcademicManagementController::class, 'storeCourse'])->name('admin.academic.store_course');
        Route::get('academic/create_year/{career_id}', [AcademicManagementController::class, 'createYear'])->name('admin.academic.create_year');
        Route::post('academic/store_year', [AcademicManagementController::class, 'storeYear'])->name('admin.academic.store_year');
        Route::delete('academic/items/{id}', [AcademicManagementController::class, 'destroy'])->name('admin.academic.items.destroy');
        Route::delete('/academic/years/{id}', [AcademicManagementController::class, 'destroyYear'])->name('admin.academic.years.destroy');
        Route::put('academic/update_subcategory/{id}', [AcademicManagementController::class, 'updateSubCategory'])->name('admin.academic.update_subcategory');
        Route::put('academic/update_category/{id}', [AcademicManagementController::class, 'updateCategory'])->name('admin.academic.update_category');
        Route::get('academic/show_course/{id}', [AcademicManagementController::class, 'showCourse'])->name('admin.academic.show_course');
        Route::put('academic/update_course/{id}', [AcademicManagementController::class, 'updateCourse'])->name('admin.academic.update_course');
        Route::post('academic/refresh_cache/{id}', [AcademicManagementController::class, 'refreshCache'])->name('admin.academic.refresh_cache');
        Route::resource('payments', PaymentController::class)->except(['show'])->names('admin.payments');
        Route::get('payments/show_payments', [PaymentController::class, 'show'])->name('admin.payments.show_payments');
        Route::get('payments/show_products', [PaymentController::class, 'showProducts'])->name('admin.payments.show_products');
        Route::get('payments/show_debts', [PaymentController::class, 'showDebts'])->name('admin.payments.show_debts');
        Route::post('products', [PaymentController::class, 'storeProduct'])->name('admin.products.store');
        Route::put('products/{product}', [PaymentController::class, 'updateProduct'])->name('admin.products.update');
        Route::delete('products/{product}', [PaymentController::class, 'destroyProduct'])->name('admin.products.destroy');
        Route::get('payments/operate_payment', [PaymentController::class, 'operatePayment'])->name('admin.payments.operate_payment');
        Route::post('debts/pay', [PaymentController::class, 'payDebt'])->name('admin.debts.pay');
    });
});