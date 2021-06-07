<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
   public function run()
   {
      $departments = [
         "Agriculture",
         "Commerce",
         "Defense",
         "Education",
         "Energy",
         "Health and Human Services",
         "Homeland Security",
         "Housing and Urban Development",
         "Development",
         "Health"
      ];

      foreach ($departments as $dep) {
         $department = new Department();
         $department->name = $dep;
         $department->slug = Str::slug($dep);
         $department->save();
      }
   }
}
