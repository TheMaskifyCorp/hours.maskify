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
    public function get ($department, $itemID) :array
    {
        if ($department == "D") {
            return $this->returnDepartments($itemID);
        } else
            if ($itemID > 0) {
                return $this->returnSingleItem($itemID);
            } else return $this->db->table('employees')->get();
    }
    private function returnDepartments(int $itemID)
    {
        return (array)$this->db->table('employees')->innerjoin('departmentmemberlist','EmployeeID')->where('DepartmentID','=',$itemID)->get();
    }
    private function returnSingleItem(int $itemID){
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
    public function post() :array
    {
        return [404,"work in progress"];
    }
    public function put() :array {
        return [404,"work in progress"];
    }
    public function delete() :array{
        return [404,"work in progress"];
    }
    public function validate() : boolean{
        return true;
    }
}