<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/sistema');
Route::view('/sistema', 'system');
Route::view('/sistema/{path}', 'system')->where('path', '.*');
