<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeCollection;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    // I've ignored the validation proccess ...

    public function getEmployeesByDepartmentView(Request $request, $department_slug)
    {
        return view("employee_by_departement", ["department_slug" => $department_slug]);
    }

    public function getEmployeesView(Request $request)
    {
        return view("employee");
    }

    public function getEmployeesData(Request $request)
    {
        $per_page = $request->get("per_page", 10);
        $department_slug = $request->get("department_slug", "any");
        $export = $request->get("export", "no");
        if ($export === "all" || $export === "range") {
            return $this->exportEmployeesAsXml($department_slug, $per_page, $export);
        }
        $employees = $this->getEmployees($department_slug, $per_page, false);
        if ($employees != []) {
            $employees->withPath('/employee/data');
        }
        return new EmployeeCollection($employees);
    }

    public function ImportEmployees(Request $request)
    {
        if ($request->file("xml_file")) {
            $xml_file = $request->file("xml_file");
            $data = $this->xmlFileToArray($xml_file);
            $employees = $data["employee"];

            foreach ($employees as $empl) {
                $department = $empl["department"];
                unset($department["id"]);

                // here we can avoid duplicate employee entry..., here I will import all
                $employee = new Employee();
                $employee->name = $empl["name"];
                $employee->birthdate = $empl["birthdate"];
                $employee->position = $empl["position"];
                $employee->pay_type = $empl["pay_type"];
                $employee->salary = $empl["salary"];
                $employee->department_id = Department::firstOrCreate(["slug" => $department["slug"]], $department)->id;
                $employee->save();
            }
            return response()->json(["status" => "success", "employees" => $employees]);
        }
        return response()->json(["status" => "error"]);
    }

    private function xmlFileToArray($xml_file)
    {
        $xml_file_contents = file_get_contents($xml_file);
        $xml_string = simplexml_load_string($xml_file_contents);
        $xml_json = json_encode($xml_string);
        return json_decode($xml_json, true);
    }

    public function exportAllEmployees(Request $request)
    {
        $employees = $this->getEmployees("any", 10, true);
        if (count($employees) > 0) {
            return $this->exportEmployeesAsXml($employees);
        }
        return "Nothing to export!";
    }

    public function exportRangeEmployees(Request $request)
    {
        $per_page = $request->get("per_page", 10);
        $department_slug = $request->get("department_slug", "any");
        $employees = $this->getEmployees($department_slug, $per_page, false);
        if (count($employees)) {
            return $this->exportEmployeesAsXml($employees);
        }
        return "Nothing to export!";
    }

    private function exportEmployeesAsXml($employees)
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><employees></employees>');
        $xml->addAttribute('version', '1.0');
        $xml->addChild('exported_on', now() . " UTC");
        $xml->addChild('total', count($employees));
        foreach ($employees as $empl) {
            $employee = $xml->addChild('employee');
            $employee->addChild('id', $empl->id);
            $employee->addChild('name', $empl->name);
            $employee->addChild('birthdate', $empl->birthdate);
            $employee->addChild('position', $empl->position);
            $employee->addChild('pay_type', $empl->pay_type);
            $employee->addChild('salary', $empl->salary);
            $department = $employee->addChild('department');
            $dep = $empl->department;
            $department->addChild('name', $dep->name);
            $department->addChild('slug', $dep->slug);
            $department->addChild('created_at', $dep->created_at);
            $department->addChild('updated_at', $dep->updated_at);
            $employee->addChild('department_id', $empl->department_id);
            $employee->addChild('created_at', $empl->created_at);
            $employee->addChild('updated_at', $empl->updated_at);
        }
        $response = Response::make($xml->asXML(), 200);
        $response->header('Cache-Control', 'public');
        $response->header('Content-Description', 'File Transfer');
        $response->header('Content-Disposition', 'attachment; filename=employees.xml');
        $response->header('Content-Transfer-Encoding', 'binary');
        $response->header('Content-Type', 'text/xml');
        return $response;
    }

    private function getEmployees($department_slug, $per_page, $all = false)
    {
        if ($department_slug === "any") {
            if ($all === true) {
                return Employee::orderBy("id", "desc")->get();
            }
            return Employee::orderBy("id", "desc")->paginate($per_page);
        }

        $department = Department::where("slug", $department_slug)->first();
        if (!$department) {
            return [];
        }
        if ($all === true) {
            return $department->employees()->orderBy("id", "desc")->get();
        }
        return $department->employees()->orderBy("id", "desc")->paginate($per_page);
    }
}
