<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Authentication.login');
//    return view('welcome');

});
Route::get('/register', function () {
    return view('Authentication.register');
//    return view('welcome');

});
