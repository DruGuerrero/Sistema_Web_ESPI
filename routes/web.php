<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\indexController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AcademicManagementController;
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
        Route::resource('users', UserController::class)->names('admin.users');
        Route::resource('students', StudentController::class)->names('admin.students');
        Route::get('students/{mediaFile}/download', [StudentController::class, 'download'])->name('admin.students.download');
        Route::delete('students/{mediaFile}/delete', [StudentController::class, 'deleteFile'])->name('admin.students.deleteFile');
        Route::post('students/{student}/matriculate', [StudentController::class, 'matriculate'])
            ->name('admin.students.matriculate')
            ->middleware('moodle.permission');
        Route::resource('academic', AcademicManagementController::class)->names('admin.academic');
        Route::get('academic/create', [AcademicManagementController::class, 'create'])->name('admin.academic.create');
        Route::post('academic', [AcademicManagementController::class, 'store'])->name('admin.academic.store');
    });
});