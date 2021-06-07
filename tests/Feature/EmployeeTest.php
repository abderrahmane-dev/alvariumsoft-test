<?php

namespace Tests\Feature;

use Tests\TestCase;


class EmployeeTest extends TestCase
{
   /**
    * A basic feature test example.
    *
    * @return void
    */
   public function test_employee_view()
   {
      $response = $this->get('/employee');
      $response->assertViewIs("employee");
   }

   public function test_employee_data()
   {
      $response = $this->get('/employee/data?per_page=10&department_slug=any&export=no');
      $jsonStructure = [
         "employees" => [
            '*' => [
               'id',
               'name',
               'birthdate',
               'position',
               'pay_type',
               'salary',
               'department' => [
                  "id",
                  "name",
                  "slug",
                  "description",
                  "created_at",
                  "updated_at"
               ]
            ]
         ]
      ];
      $response->assertJsonStructure($jsonStructure);
      $response = $this->get('/employee/data?per_page=10&department_slug=commerce&export=no');
      $response->assertJsonStructure($jsonStructure);
   }

   public function test_export_all()
   {
      $this->seed();
      $response = $this->get('/employee/export/all');
      $response->assertDownload('employees.xml');
      $response->assertDontSee("Nothing to export!", false);
   }

   public function test_export_range()
   {
      $this->seed();
      $response = $this->get('/employee/export/range');
      $response->assertDownload('employees.xml');
      $response->assertDontSee("Nothing to export!", false);
   }

   // public function test_import_xml()
   // {
   //    Storage::fake('xml_file');
   //    $file = UploadedFile::fake()->create('xml_file.xml', 100, 'text/xml');
   //    $response = $this->post('/employee/import/xml', [
   //       'xml_file' => $file,
   //    ]);
   //    $response->assertJsonMissingExact(["status" => "error"]);
   // }

   public function test_get_employee_by_department()
   {
      $this->seed();
      $response = $this->get('/employee/commerce');
      $response->assertViewIs("employee_by_departement");
   }
}
