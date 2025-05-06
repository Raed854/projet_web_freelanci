<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PropositionController;

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
Route::get('/proposition', function () {
    return view('proposition.proposition');
})->name('proposition');



// CRUD routes for User model
Route::resource('users', UserController::class);

// CRUD routes for Post model
Route::resource('posts', PostController::class);

Route::resource('courses', CourseController::class);

Route::resource('projects', ProjectController::class);
Route::resource('message', ChatController::class);
// CRUD routes for Proposition model
Route::resource('propositions', PropositionController::class);
Route::resource('chat', ChatController::class);
Route::post('/signup', [AuthController::class, 'store'])->name('signup.store');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/authors', [PostController::class, 'getAuthors'])->name('authors.index');

Route::get('/courses/{id}', [CourseController::class, 'show'])->name('course.show');
Route::get('/post', [PostController::class, 'index1'])->name('post.show');
Route::get('/cours', [CourseController::class, 'index1'])->name('cours.index');
// Change the parameter binding to use project ID
Route::get('projects/{project_id}/propositions', [PropositionController::class, 'index'])->name('propositions.index');
Route::post('projects/{project_id}/propositions', [PropositionController::class, 'store'])->name('propositions.store');
