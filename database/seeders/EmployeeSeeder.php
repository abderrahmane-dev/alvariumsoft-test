<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
   public function run()
   {
      $faker_ru = \Faker\Factory::create("ru_RU");

      $pay_types = ["hourly", "salary"];

      for ($i = 1; $i <= 1000; $i++) {
         $pay_type = $pay_types[rand(0, 1)];
         $employee = new Employee();
         $employee->name = $faker_ru->name();
         $employee->birthdate = $faker_ru->date('Y-m-d', $max = '2000-01-01', $min = "1980-01-01");
         $employee->position = $faker_ru->jobTitle;
         $employee->pay_type = $pay_type;
         $employee->salary = $pay_type == "salary" ? 1500 : rand(1500, 2500);
         $employee->department_id = Department::inRandomOrder()->first()->id;
         $employee->save();
      }
   }
}
