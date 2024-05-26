<?php


use App\Models\Article;
use App\Models\Trainer;
use App\Models\Reservation;
use App\Models\GroupReservation; // Adjust the namespace based on your project structure
use App\Models\GroupParticipant; // Import the GroupParticipant model
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthManager;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\GroupReservationController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentsController;



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
    $articles = Article::all();
    $trainers = Trainer::all();

    return view('welcome', compact('articles', 'trainers'));
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

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{id}', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::post('/users/{user}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');


Route::resource('trainers', TrainerController::class);

Route::group(['middleware' => ['auth', 'trainer']], function () {
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::resource('reservations', ReservationController::class)->except(['create', 'show']);
    Route::post('/group_reservations/{group_reservation}/add-participant', [GroupReservationController::class, 'addParticipant'])->name('group_reservations.add_participant');
    Route::get('/group_reservations/{group_reservation}/', [AdminController::class, 'showEmailForm'])->name('group_reservations.download_pdf');
    Route::get('/group_reservations/create', [GroupReservationController::class, 'create'])->name('group_reservations.create');
    Route::post('/group_reservations', [GroupReservationController::class, 'store'])->name('group_reservations.store');
    Route::get('/group_reservations/{group_reservation}/edit', [GroupReservationController::class, 'edit'])->name('group_reservations.edit');
    Route::put('/group_reservations/{group_reservation}', [GroupReservationController::class, 'update'])->name('group_reservations.update');
    Route::delete('/group_reservations/{group_reservation}', [GroupReservationController::class, 'destroy'])->name('group_reservations.destroy');
    Route::get('/group_reservations/{groupReservation}/download_pdf', [GroupReservationController::class, 'downloadPdf'])->name('group_reservations.download_pdf');
});

Route::get('/trainers/{trainer}', [TrainerController::class, 'show'])->name('trainer.profile');
Route::post('/group_reservations/{group_reservations}/add-participant', [GroupReservationController::class, 'addParticipant'])->name('add.participant');

Route::group(['middleware' => ['auth', 'client']], function () {
    Route::post('/reservations/{reservation_id}/submit', [ReservationController::class, 'submit']);
    Route::post('/group_reservation/{id}/submit', [GroupReservationController::class, 'submit']);
    Route::get('/group_reservation/{id}/participants', [GroupReservationController::class, 'getParticipants']);
    Route::get('/home/{trainerId}', [HomeController::class, 'getReservations']);


});

Route::get('/admin/send-email', [AdminController::class, 'showEmailForm'])->name('admin.showEmailForm');
Route::post('/admin/send-email', [AdminController::class, 'sendEmail'])->name('admin.sendEmail');

Route::get('/credit', [PaymentsController::class, 'index'])->name('payments.index');
Route::get('/credit/charge_creditAdmin', [PaymentsController::class, 'chargeCredit'])->name('payments.chargeCredit');

Route::post('/credit/charge_admin', [PaymentsController::class, 'chargeAdmin'])->name('payments.chargeAdmin');


Route::post('/credit/charge_credit', [PaymentsController::class, 'charge'])->name('payments.charge');
Route::get('/credit/charge_credit', [PaymentsController::class, 'callback'])->name('payments.callback');




//Route::get('/events', [EventController::class, 'index'])->name('events.index');
