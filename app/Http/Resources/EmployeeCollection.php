<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EmployeeCollection extends ResourceCollection
{
   /**
    * Transform the resource collection into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
   public $collects = EmployeeResource::class;
   public static $wrap = 'employees';

   public function toArray($request)
   {
      // return ['data' => $this->collection];
      return parent::toArray($this->collection);
   }
}
