<?php

namespace API;
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require_once "ApiEndpointInterface.php";

class Employees implements ApiEndpointInterface
{
    protected int $employee;
    protected bool $manager;
    protected object $db;

    public function __construct(int $employee, bool $manager)
    {
        $this->employee = $employee;
        $this->manager = $manager;
        $this->db = new \Database;
    }
    public function get ($body) :array
    {
        if((isset($body['itemid'])) AND (isset($body['departmentid']))) throw new API\BadRequestException("Cannot Filter single Employee on Departments");
        if(isset($body['itemid'])) return $this->returnSingleItem($body['itemid']);
        if(isset($body['departmentid'])) return $this->returnDepartmentEmployees($body['departmentid']);
        if( ! $this->manager) throw new NotAuthorizedException("Can only be viewed by a manager");
        return $this->db->table('employees')->get();
    }

    public function post(array $body) :array
    {
        //check of het op /employees gebeurd
        if (isset($body['itemid'])) throw new BadRequestException('Employees can only be created at top-level endpoint /employees');
        //verwachte variabelen
        $required = ["FirstName", "LastName", "Email", "PhoneNumber", "Street", "HouseNumber","City", "PostalCode", "DateOfBirth", "FunctionTypeID","DepartmentID"];
        $optional = ['DocumentNumberID'];
        $missingParams = [];
        $requestParams = [];
        foreach($required as $value){
            if ( ! array_key_exists ( $value, $body ) ) {
                array_push($missingParams, $value." is required");
            } else {
                $requestParams[$value] = $body[$value];
            }
        }
        foreach($optional as $value){
            if ( array_key_exists ( $value, $body ) ) $requestParams[$value] = $body[$value];
        }
        if ( count ( $missingParams ) > 0 ) throw new BadRequestException((  json_encode ( $missingParams ) ) );
        return (array)$requestParams;
        /*$response = $this->db->table('employees')->insert($newEmployee);
        if ($response) return [$response];
        throw new BadRequestException($response[2]);*/
    }
    public function put(array $body) :array {
        return [404,"work in progress"];
    }
    public function delete(array $body) :array{
        return [404,"work in progress"];
    }

    //private functies ter ondersteuning vd public functies
    private function returnDepartmentEmployees(int $itemID)
    {
        if (! $this->manager) throw new NotAuthorizedException("Can only be viewed by a manager");
        return (array)$this->db->table('employees')->innerjoin('departmentmemberlist','EmployeeID')->where('DepartmentID','=',$itemID)->get();
    }
    private function returnSingleItem(int $itemID){
        if ( ( ! $this->manager) AND ( $itemID !=$this->employee ) ) throw new NotAuthorizedException("Can only be viewed by a manager or the object employee");
        $response = (array)$this->db->table('employees')->innerjoin('departmentmemberlist','EmployeeID')->where('employees.EmployeeID','=',$itemID)->get();
        if ( count ( $response ) >1 ) {
            $departments = (array)[];
            foreach ($response as $emp){
                array_push($departments, $emp->DepartmentID);
            }
            $response[0]->DepartmentID = $departments;
        }
        return (array)$response[0];
    }
    private function validatePostRequest(array $request)
    {
        //$required = ["FirstName", "LastName", "Email", "PhoneNumber", "Street", "HouseNumber","City", "PostalCode", "DateOfBirth", "FunctionTypeID","DepartmentID"];
        //$optional = ['DocumentNumberID'];

    }

}