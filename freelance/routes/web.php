<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProjectController;

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
    return view('home.home');
});
Route::get('/login', function () {
    return view('login.login');
})->name('login');
Route::get('/gestionpost', function () {
    return view('gestiondepost.gestionpost');
})->name('gestionpost');

Route::get('/gestioncour', function () {
    return view('gestioncours.gestioncour');
})->name('gestioncour');
Route::get('/projet', function () {
    return view('projet.projet');
})->name('projet');
// CRUD routes for User model
Route::resource('users', UserController::class);

// CRUD routes for Post model
Route::resource('posts', PostController::class);

Route::resource('courses', CourseController::class);

Route::resource('projects', ProjectController::class);

Route::post('/signup', [AuthController::class, 'store'])->name('signup.store');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/authors', [PostController::class, 'getAuthors'])->name('authors.index');

Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');