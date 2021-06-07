<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
   /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
   public static $wrap = 'employee';

   public function toArray($request)
   {
      return [
         'id' => $this->id,
         'name' => $this->name,
         'birthdate' => $this->birthdate,
         'position' => $this->position,
         'pay_type' => $this->pay_type,
         'salary' => $this->salary,
         'department' => $this->department
      ];
   }
}
