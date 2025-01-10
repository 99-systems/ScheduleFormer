<?php

use Illuminate\Support\Facades\Route;

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
    return redirect()->route('login');
});

//Auth::routes();
Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/ShowAvailableAllSections', 'App\Http\Controllers\API\ShowAvailableAllSections')
    ->middleware('auth')
    ->name('home');

Route::post('/ShowElectiveCoursesByElCode', 'App\Http\Controllers\API\ShowElectiveCoursesByElCode')
    ->middleware('auth')
    ->name('home');


Route::post('/SearchCourse', 'App\Http\Controllers\API\SearchCourse')
    ->middleware('auth');

//Route::post('/logout', [App\Http\Controllers\Auth\LogoutController::class,'logout'])
//    ->middleware('guest')
//    ->name('logout');

//Route::post('/logout')
