<?php

use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\AnmeldungenController;

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

Route::get('/test', function(){
    // return view('emails.reset-password');
    // Mail::to('mithunhalderrptc@gmail.com')->send(new TestMail);
    // Mail::to('mithun@infodigita.com')->send(new TestMail);
    // Mail::to('mithunrptc@outlook.com')->send(new TestMail);
    return (new TestMail)->render();
});


// Images
Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');
//SPA
Route::any('{path}', function () {
    return view('welcome');
})->where(['path' => '(.*)']);
