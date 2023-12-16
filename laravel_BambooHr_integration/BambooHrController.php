<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use BambooHR\API\BambooAPI as BHR;
use Illuminate\Http\Response;

class BambooHrController extends AccountController
{
    public $fields = array('lastName', 'firstName', 'middleName', 'employeeNumber', 'gender', 'department', 'division', 'employmentStatus', 'homeEmail', 'homePhone', 'bestEmail', 'workPhone', 'workEmail', 'jobTitle', 'hireDate', 'addressLine1', 'addressLine2', 'city', 'country', 'zipCode');

    public function getEmployee()
    {
        $id = 4;
        $api = new BHR(config('services.bamboohr.domain'));
        $api->setSecretKey(config('services.bamboohr.key'));

        $employee = $api->getEmployee($id, $this->fields);

        if ($employee->isError()) {
            return self::httpBadRequest("Login Failure", Response::HTTP_BAD_REQUEST);
        }

        if ($employee) {
            return response()->json([
                'success' => true,
                'user' => $employee,
            ], Response::HTTP_OK);
        }

        return self::httpBadRequest(self::NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

    public function getEmployees()
    {
        $api = new BHR(config('services.bamboohr.domain'));
        $api->setSecretKey(config('services.bamboohr.key'));

        $response = $api->getCustomReport('json', $this->fields);

        if ($response->isError()) {
            return self::httpBadRequest("Login Failure", Response::HTTP_BAD_REQUEST);
        }

        if ($response && (isset($response->headers['content-type']) && $response->headers['content-type'] == 'application/json') || (isset($response->headers['Content-Type']) && $response->headers['Content-Type'] == 'application/json')) {
            $employees = json_decode($response->content)->employees;
            if ($employees) {
                return response()->json([
                    'success' => true,
                    'users' => $employees,
                ], Response::HTTP_OK);
            }
        }

        return self::httpBadRequest(self::NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

    public function addEmployee()
    {
        $api = new BHR(config('services.bamboohr.domain'));
        $api->setSecretKey(config('services.bamboohr.key'));
        $fieldValues = ['lastName' => 'testUserLastName', 'firstName' => 'testUserFirstName', 'middleName' => 'testUserMiddleName', 'employeeNumber' => 777, 'workPhone' => '1234567', 'workEmail' => 'test@test.com'];
        $employee = $api->addEmployee($fieldValues);

        if ($employee->isError()) {
            return self::httpBadRequest("Login Failure", Response::HTTP_BAD_REQUEST);
        }

        if ($employee) {
            return response()->json([
                'success' => true,
                'user' => $employee,
            ], Response::HTTP_OK);
        }

        return self::httpBadRequest(self::NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

    public function updateEmployee($id, $fieldValues)
    {
        $api = new BHR(config('services.bamboohr.domain'));
        $api->setSecretKey(config('services.bamboohr.key'));
        $updateEmployee = $api->updateEmployee($id, $fieldValues);

        if ($updateEmployee->isError()) {
            return self::httpBadRequest("Login Failure", Response::HTTP_BAD_REQUEST);
        }

        if ($updateEmployee) {
            return response()->json([
                'success' => true,
                'user' => $updateEmployee,
            ], Response::HTTP_OK);
        }

        return self::httpBadRequest(self::NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

}
