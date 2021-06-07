<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

// prefix: employee
// name: employee.
// order is important!!

Route::get('/', [EmployeeController::class, "getEmployeesView"])->name("view");
Route::get('/data', [EmployeeController::class, "getEmployeesData"])->name("data");
Route::get('/export/all', [EmployeeController::class, "exportAllEmployees"])->name("export.all");
Route::get('/export/range', [EmployeeController::class, "exportRangeEmployees"])->name("export.range");
Route::post('/import/xml', [EmployeeController::class, "ImportEmployees"])->name("import.xml");
Route::get('/{department_slug}', [EmployeeController::class, "getEmployeesByDepartmentView"])->name("by_department");
