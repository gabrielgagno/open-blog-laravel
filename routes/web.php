<?php

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
use App\Classes\Logger;
Route::get('/', function () {
    $logger = new Logger();
    $logger->logInfo('/', "tried logger");
    return view('welcome');
});
