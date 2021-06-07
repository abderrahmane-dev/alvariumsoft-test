<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
   public function getAllDepartments(Request $request)
   {
      $departments = Department::orderBy("id", "desc")->paginate(5);
      return view("department", ["departments" => $departments]);
   }
}
