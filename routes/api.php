<?php

use Illuminate\Support\Facades\Route;


Route::get('match/{property}', \App\Http\Controllers\MatchingController::class);
