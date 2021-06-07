<?php

use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;

// prefix: department
// name: department.

Route::get('/', [DepartmentController::class, "getAllDepartments"])->name("view");
