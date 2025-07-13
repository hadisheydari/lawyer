<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});
Route::get('/register', function () {
    return view('Authentication.register');

});
Route::get('/login', function () {
    return view('Authentication.login');

});
