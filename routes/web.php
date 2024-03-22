<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthManager;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\GroupReservationController;
use App\Http\Controllers\ClientController;





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
})->name('welcome');

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', [AuthManager::class, 'login'])->name('login');
Route::post('/login', [AuthManager::class, 'loginPost'])->name('login.post');


Route::get('/registration', [AuthManager::class, 'registration'])->name('registration');
Route::post('/registration', [AuthManager::class, 'registrationPost'])->name('registration.post');

Route::get('/logout', [AuthManager::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function (){
    Route::get('/profile', function (){ 
        return "hi";
    });
});

Route::resource('articles',ArticlesController::class);
/*
ak to bude potrbovat auth ci je to admin... 
Route::group(['middleware' => 'auth', 'is_admin'], function (){
    Route::get('/profile', function (){ 
        return "hi";
    });
});
*/
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{id}', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');


Route::resource('trainers', TrainerController::class);

Route::group(['middleware' => ['auth', 'trainer']], function () {
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::resource('reservations', ReservationController::class)->except(['create', 'show']);

    Route::get('/group_reservations/create', [GroupReservationController::class, 'create'])->name('group_reservations.create');
    Route::post('/group_reservations', [GroupReservationController::class, 'store'])->name('group_reservations.store');
    Route::get('/group_reservations/{group_reservation}/edit', [GroupReservationController::class, 'edit'])->name('group_reservations.edit');
    Route::put('/group_reservations/{group_reservation}', [GroupReservationController::class, 'update'])->name('group_reservations.update');
    Route::delete('/group_reservations/{group_reservation}', [GroupReservationController::class, 'destroy'])->name('group_reservations.destroy');

});

Route::get('/trainers/{trainer}', [TrainerController::class, 'show'])->name('trainer.profile');
Route::post('/group_reservations/{group_reservations}/add-participant', [GroupReservationController::class, 'addParticipant'])->name('add.participant');


Route::group(['middleware' => ['auth', 'client']], function () {

    Route::post('/reservations/{reservation_id}/submit', [ReservationController::class, 'submit']);
    Route::post('/group_reservation/{id}/submit', [GroupReservationController::class, 'submit']);
    Route::get('/group_reservation/{id}/participants', [GroupReservationController::class, 'getParticipants']);


});





Route::get('/events', [EventController::class, 'index'])->name('events.index');
