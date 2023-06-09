<?php
/**************************************************
 * 프로젝트명   : laravel_board
 * 디렉토리     : routes
 * 파일명       : web.php
 * 이력         :   v001 0530 DH.Lee new
**************************************************/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardsController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Boards
Route::resource('/boards', BoardsController::class);

// Users
Route::get('/users/login', [UserController::class, 'login'])->name('users.login');
Route::post('/users/loginpost', [UserController::class, 'loginpost'])->name('users.login.post');
Route::get('/users/registration', [UserController::class, 'registration'])->name('users.registration');
Route::post('/users/registrationpost', [UserController::class, 'registrationpost'])->name('users.registration.post');
Route::get('/users/logout', [UserController::class, 'logout'])->name('users.logout');
Route::get('/users/withdraw', [UserController::class, 'withdraw'])->name('users.withdraw');
Route::get('/users/edit', [UserController::class, 'edit'])->name('users.edit');
Route::post('/users/editpost', [UserController::class, 'editpost'])->name('users.edit.post');

// 메일전송 TEST
Route::get('/mails/mail', [MailController::class, 'mail'])->name('mails.mail');
Route::post('/mails/mailpost', [MailController::class, 'mailpost'])->name('mails.mail.post');

// 메일인증 TEST
Route::get('/users/verify/{code}/{email}', [UserController::class, 'verify'])->name('users.verify');
Route::get('/resend-email', [UserController::class, 'resend_email'])->name('resend.email');

// 카카오 로그인 TEST
Route::get('/logintest', [UserController::class, 'logintest']);