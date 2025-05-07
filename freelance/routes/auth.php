<?php


use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PropositionController;
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('home.home');
    });
    Route::get('/login', function () {
        return view('login.login');
    })->name('login');
    Route::post('/create', [AuthController::class, 'store'])->name('signup.store');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/signup', [AuthController::class, 'create'])->name('login.signup');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('login.logout');
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
    Route::get('chat', [ChatController::class,'index'])->name('chat.index');
    Route::post('chatstore', [ChatController::class,'store'])->name('chat.store');
    Route::get('/authors', [PostController::class, 'getAuthors'])->name('authors.index');
    
    Route::get('/courses/{id}', [CourseController::class, 'show'])->name('course.show');
    Route::get('/post', [PostController::class, 'index1'])->name('post.show');
    Route::get('/cours', [CourseController::class, 'index1'])->name('cours.index');
    // Change the parameter binding to use project ID
    Route::get('projects/{project_id}/propositions', [PropositionController::class, 'index'])->name('propositions.index');
    Route::post('projects/{project_id}/propositions', [PropositionController::class, 'store'])->name('propositions.store');
    Route::get('/chat/{id}', [MessageController::class, 'showChat'])->name('chat.show');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/chats/{chatId}/messages', [MessageController::class, 'index']); // Get messages for a specific chat
    Route::post('/chats/{chatId}/messages', [MessageController::class, 'store']); // Store a new message
    Route::get('/messages/{message}', [MessageController::class, 'show']); // Show a specific message
    Route::put('/messages/{message}', [MessageController::class, 'update']); // Update a message
    Route::delete('/messages/{message}', [MessageController::class, 'destroy']);
    Route::put('/messages/{id}', [MessageController::class, 'update']);
    Route::delete('/messages/{id}', [MessageController::class, 'destroy']);
 // Delete a message
});
