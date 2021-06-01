<?php

namespace API;

class Hours implements ApiEndpointInterface
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

    public function get(array $body, array $params): array
    {
        //throw error for filtering on department AND employee
        if((isset($params['itemid'])) AND (isset($params['departmentid']))) throw new BadRequestException("Cannot filter on both single Employee and Department");

        //add every parameter to an array
        $where = [];
        if(isset($params['departmentid'])) array_push($where,["DepartmentID",'=',$params['departmentid']]);
        if(isset($params['startdate'])) array_push($where,["DeclaratedDate",'>=',$params['startdate']]);
        if(isset($params['enddate'])) array_push($where,["DeclaratedDate",'<=',$params['enddate']]);
        if(isset($params['itemid'])) array_push($where,["employeehours.EmployeeID",'=',$params['itemid']]);
        if(isset($params['employeehoursid'])) array_push($where,["EmployeeHoursID",'=',$params['employeehoursid']]);
        if(isset($params['status'])) array_push($where,["HoursAccorded",'=',$params['status']]);

        //if no where clauses, select all employees
        if (!count($where)>0) array_push($where,["employeehours.EmployeeID",'>',0]);

        //fetch and return the result
        $result = $this->db->table('employeehours')->innerjoin('departmentmemberlist','EmployeeID')->where($where)->get();
        return (array)$result;
    }

    public function put(array $body, array $params): array
    {
        // TODO: Implement put() method.
        return [];
    }

    public function post(array $body, array $params): array
    {
        // TODO: Implement post() method.
        return [];
    }

    public function delete(array $body, array $params): array
    {
        // TODO: Implement delete() method.
        return [];
    }
}