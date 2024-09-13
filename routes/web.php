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

// Rutas protegidas por autenticación
Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum', 'verified']], function () {

    Route::get('Panel-Administrativo', [indexController::class, 'index'])->name('dashboard');

    // Rutas exclusivas para Superusuario
    Route::group(['middleware' => 'can:manage-users'], function () {
        Route::resource('users', UserController::class)->except(['show'])->names('admin.users');
    });

    // Rutas para cambio de contraseña accesibles por cualquier usuario autenticado
    Route::get('users/change-password', [UserController::class, 'showChangePasswordForm'])->name('admin.users.changePassword');
    Route::post('users/change-password', [UserController::class, 'updatePassword'])->name('admin.users.updatePassword');

    // Rutas accesibles por Superusuario, Jefe de carrera y Docente (Gestión Académica)
    Route::group(['middleware' => ['can:manage-academic']], function () {
        Route::resource('academic', AcademicManagementController::class)->names('admin.academic');
        Route::get('academic/create', [AcademicManagementController::class, 'create'])->name('admin.academic.create');
        Route::post('academic', [AcademicManagementController::class, 'store'])->name('admin.academic.store');
        Route::get('academic/{id}', [AcademicManagementController::class, 'show'])->name('admin.academic.show');
        Route::post('academic/{career}/upload_file', [AcademicManagementController::class, 'uploadFile'])->name('admin.academic.upload_file');
        Route::get('academic/download_file/{mediaFile}', [AcademicManagementController::class, 'downloadFile'])->name('admin.academic.download_file');
        Route::delete('academic/delete_file/{mediaFile}', [AcademicManagementController::class, 'deleteFile'])->name('admin.academic.delete_file');
        Route::get('academic/subcategory/{id}', [AcademicManagementController::class, 'showSubCategory'])->name('admin.academic.show_subcategory');
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
        Route::get('academic/{career}/generate_report', [AcademicManagementController::class, 'generateCareerReport'])->name('admin.academic.generate_report');
        Route::get('academic/generate_course_report/{id}', [AcademicManagementController::class, 'generateCourseReport'])->name('admin.academic.generate_course_report');
        Route::get('admin/academic/course/{id}/teacher_report', [AcademicManagementController::class, 'generateTeacherReportByCourse'])->name('admin.academic.generate_teacher_report_by_course');
    });

    // Rutas accesibles por Superusuario y Administrativo (Gestión de Estudiantes y Pagos)
    Route::group(['middleware' => ['can:manage-students', 'can:manage-payments']], function () {
        // Módulo de gestión de estudiantes
        Route::resource('students', StudentController::class)->names('admin.students');
        Route::get('students/{mediaFile}/download', [StudentController::class, 'download'])->name('admin.students.download');
        Route::delete('students/{mediaFile}/delete', [StudentController::class, 'deleteFile'])->name('admin.students.deleteFile');
        Route::post('students/{student}/matriculate', [StudentController::class, 'matriculate'])
            ->name('admin.students.matriculate');
        Route::get('students/{student}/generate-pdf', [StudentController::class, 'generatePDF'])->name('admin.students.generate_pdf');

        // Módulo de gestión de pagos
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
