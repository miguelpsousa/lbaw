<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\static\getStartedController;
use App\Http\Controllers\static\aboutUsController;
use App\Http\Controllers\static\contactUsController;
use App\Http\Controllers\dashboardController;

use App\Http\Controllers\projects\ProjectsController;
use App\Http\Controllers\projects\ProjectDashboardController;
use App\Http\Controllers\projects\createProjectController;
use App\Http\Controllers\search\SearchUsersController;
use App\Http\Controllers\projects\SeeTeamMembersController;
use App\Http\Controllers\profileController;
use App\Http\Controllers\projects\AddTeamMemberController;
use App\Http\Controllers\Tasks\TaskController;
use App\Http\Controllers\Tasks\TaskCommentController;
use App\Http\Controllers\notificationController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\projects\teamMemberProfileController;
use App\Http\Controllers\search\SearchController;
use App\Http\Controllers\search\SearchProjectsController;
use App\Http\Controllers\search\SearchTasksController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\projects\FavoriteController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ProjectInviteController;
use App\Http\Controllers\projects\LeaveProjectController;


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

// Home
Route::controller(getStartedController::class)->group(function (){
    Route:: get('/','show');
});;

// About us 
Route::controller(aboutUsController::class)->group(function (){
    Route:: get('/about-us','show');
});;
// Contact us
Route::controller(contactUsController::class)->group(function (){
    Route:: get('/contact-us','show');
});;


// Dashboard
Route::controller(dashboardController::class)->group(function (){
    Route:: get('/dashboard','show')->name('dashboard');
});;

// Projects
Route::controller(ProjectsController::class)->group(function () {
    Route::get('/projects', 'index')->name('projects');
});

Route::controller(ProjectDashboardController::class)->group(function () {
    Route::get('/projects/{project}', 'show')->name('projects.show');
});

Route::controller(ProjectDashboardController::class)->group(function () {
    Route::get('/projects/{project}/edit', 'edit')->name('projects.edit');
});

Route::controller(LeaveProjectController::class)->group(function () {
    Route::post('/projects/{project}/leave', 'leave')->name('projects.leave');
});

Route::controller(createProjectController::class)->group(function () {
    Route::get('/create-project', 'show')->name('projects.create');
    Route::post('/create-project', 'store')->name('projects.store');
});

Route::controller(SeeTeamMembersController::class)->group(function () {
    Route::get('/projects/{project}/team-members', 'show')->name('projects.team');
    Route::get('/projects/{project}/team-members/{user}/promote', [SeeTeamMembersController::class, 'promoteToCoordinator'])->name('projects.team.promote');
    Route::get('/projects/{project}/team-members/{user}/remove', [SeeTeamMembersController::class, 'removeMember'])->name('projects.team.remove');
});

Route::controller(AddTeamMemberController::class)->group(function () {
    Route::post('/projects/{project}/add-member', 'addTeamMember')->name('projects.team.add');
});
Route::controller(teamMemberProfileController::class)->group(function () {
    Route::get('/projects/{project}/team-members/{user}', 'show')->name('projects.team.member');
});

Route::put('/projects/{project}', [ProjectDashboardController::class, 'update'])->name('projects.update');

Route::post('/projects/{project}/archive', [ProjectDashboardController::class, 'archive'])->name('projects.archive');


// Search
Route::controller(SearchController::class)-> group(function () {
    Route::get('/search', 'show')->name('search');
});

Route::controller(SearchUsersController::class)->group(function () {
    Route::get('/search/search-usr', 'search')->name('search.users');
    Route::get('/search/search-accepted-usr', 'searchAcceptedUsers')->name('search.accepted.users');
});
Route::controller(SearchProjectsController::class)->group(function () {
    Route::get('/search/search-project', 'search')->name('search.projects');
});

Route::controller(SearchTasksController::class)->group(function () {
    Route::get('/search/search-task', 'search')->name('search.tasks');
});
Route::controller(SearchUsersController::class)->group(function () {
    Route::get('/search/search-accepted-usr', 'searchAcceptedUsers')->name('search.accepted.users');
});


// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});
Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('/forgot-password', 'showLinkRequestForm')->name('password.request');
    Route::post('/forgot-password', 'sendResetLinkEmail')->name('password.email');
    Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset');
    Route::post('/reset-password', 'reset')->name('password.update');
});

Route::controller(GoogleController::class)->group(function () {
    Route::get('login/google', 'redirect')->name('google-auth');
    Route::get('login/google/call-back', 'callbackGoogle')->name('google-call-back');
});


Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// Profile
Route::get('/profile/{user}', [profileController::class, 'show'])->name('profile.show');
Route::get('/profile/{user}/projects', [profileController::class, 'userProjects'])->name('profile.userProjects');
Route::middleware('auth')->group(function () {
    Route::get('/profile/{user}/edit', [profileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{user}', [profileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/{user}/deletePicture', [ProfileController::class, 'deleteProfilePicture'])->name('profile.deletePicture');
    Route::delete('/profile/{user}', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/users', [ProfileController::class, 'index'])->name('users.index');
});
    

// Tasks
Route::middleware('auth')->group(function () {
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/create/{project}', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks/create/{project}', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::post('/tasks/{task}/assign', [TaskController::class, 'assign'])->name('tasks.assign');
});

// Task Comments
Route::post('/task-comments', [TaskCommentController::class, 'store'])->name('task-comments.store');
Route::get('/task-comments/{comment}/edit', [TaskCommentController::class, 'edit'])->name('task-comments.edit');
Route::put('/task-comments/{comment}', [TaskCommentController::class, 'update'])->name('task-comments.update');
Route::delete('/task-comments/{id}', [TaskCommentController::class, 'destroy'])->name('task-comments.destroy');

// Notifications
Route::get('/notifications', [notificationController::class, 'index'])->middleware('auth')->name('notifications');
Route::post('/notifications/respond', [notificationController::class, 'respond'])->name('notifications.respond');
Route::get('/notifications/mark-all-read', [notificationController::class, 'markAllRead'])->name('notifications.markAllRead');
Route::get('/notifications/email-respond/{projectId}/{notificationId}/{response}/{token}', [ProjectInviteController::class, 'respond'])->name('notifications.emailRespond')->middleware('auth');


// Project Forum
Route::get('/projects/{projectId}/forum', [MessageController::class, 'index'])->name('messages.index');
Route::post('/projects/{projectId}/forum', [MessageController::class, 'store'])->name('messages.store');
Route::put('/messages/{id}', [MessageController::class, 'update'])->name('messages.update');
Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('messages.destroy');



// Favorites
Route::get('/favorites', [ProjectsController::class,'index_favorites'])->name('favorites');
Route::post('/toggle-favorite/{project}', [FavoriteController::class,'toggleFavorite'])->name('toggle-favorite');

// Admin
Route::get('/pages/createUser', [profileController::class, 'create'])->name('users.create');
Route::post('/pages', [profileController::class, 'store'])->name('users.store');