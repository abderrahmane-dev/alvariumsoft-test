<?php

use Illuminate\Support\Facades\Route;

// public routes

Route::get('/', function () {
    return redirect()->route("employee.view");
});
