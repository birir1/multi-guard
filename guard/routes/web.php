<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;

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
});

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

route::prefix('user')->name('user.')->group(function () 
{
    route::middleware(['guest','PreventBackHistory'])->group(function ()
    {
        route::view('/login', 'dashboard.user.login')->name('login');
        route::view('/register', 'dashboard.user.register')->name('register');
        route::post('/create',[UserController::class,'create'])->name('create');
        route::post('/check',[UserController::class,'check'])->name('check');
    });

    route::middleware(['auth','PreventBackHistory'])->group(function ()
    {
        route::view('/home', 'dashboard.user.home')->name('home');
        route::post('/logout',[UserController::class,'logout'])->name('logout');
    });
});

route::prefix('admin')->name('admin.')->group(function () 
{
    route::middleware(['guest:admin'])->group(function ()
    {
        route::view('/login', 'dashboard.admin.login')->name('login');
    });

    route::middleware(['auth:admin'])->group(function ()
    {
        route::view('/home', 'dashboard.admin.home')->name('home');
    });
});