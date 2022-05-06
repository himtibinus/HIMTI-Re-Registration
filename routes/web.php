<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    return redirect('/home');
});
Route::get('/loginredirect', function () {
    return redirect(session('loginTo', '/home'));
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::resource('/profile', App\Http\Controllers\ProfileController::class);
// Re-Regsitration
Route::get('/Registration', [App\Http\Controllers\ReRegistrationController::class, 'index']);
Route::get('/re-regist/{Year}/{Quartil}', [App\Http\Controllers\ReRegistrationController::class, 'reregistGenerate']);
Route::post('/re-regist/{Year}/{Quartil}', [App\Http\Controllers\ReRegistrationController::class, 'SubmitAnswer']);
Route::get('/getFile/{FileName}', [App\Http\Controllers\ReRegistrationController::class, 'getFile']);
Route::get('/Admin/{Year}', [App\Http\Controllers\ReRegistrationController::class, 'Admin']);
Route::get('/Admin/{Year}/{Quartil}/Edit', [App\Http\Controllers\ReRegistrationController::class, 'EditData']);
Route::get('/Admin/{Year}/{Quartil}/{Section}/Edit', [App\Http\Controllers\ReRegistrationController::class, 'EditSection']);
Route::post('/Admin/{Year}/{Quartil}/EditQuartilInformation', [App\Http\Controllers\ReRegistrationController::class, 'EditQuartilInformation']);