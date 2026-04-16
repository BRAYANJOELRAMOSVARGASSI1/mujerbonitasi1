<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::view('about', 'about')->name('about');

    // Rutas de usuarios
    Route::resource('users', UserController::class);
    
    // Rutas específicas para usuarios
    Route::get('users/{user}/roles-permissions', [UserController::class, 'editRoles'])
        ->name('users.roles.edit');
    Route::put('users/{user}/roles-permissions', [UserController::class, 'updateRoles'])
        ->name('users.roles.update');
    Route::get('users/{user}/roles/data', [UserController::class, 'getRolesData'])
        ->name('users.roles.data');
    Route::post('users/{user}/unblock', [UserController::class, 'unblock'])
        ->name('users.unblock');
    Route::get('bitacora', [UserController::class, 'bitacora'])->name('bitacora.index');
    
    // Rutas de roles
    Route::resource('roles', RoleController::class);
    
    // Rutas de permisos
    Route::resource('permissions', PermissionController::class);


    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');


    // Route::resource('books', BookController::class)->only('index');
});
