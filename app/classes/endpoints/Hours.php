<?php

namespace API;

use MongoDB\Driver\Exception\AuthenticationException;

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
        //check manager and employee for authorisation
        if ( ( (! isset($params['itemid']) ) OR ( $this->employee != $params [ 'itemid' ] ) ) AND ( !$this->manager) ) throw new NotAuthorizedException('Hours can only be viewed by a manager or the object employee');
        //throw error for filtering on department AND employee
        if((isset($params['itemid'])) AND (isset($params['departmentid']))) throw new BadRequestException("Cannot filter on both single Employee and Department");

        //add every parameter to an array
        $selection =[
            "EmployeeHoursID",
            "employeehours.EmployeeID",
            "HoursAccorded",
            "AccordedByManager",
            "DeclaratedDate",
            "EmployeeHoursQuantityInMinutes"];
        $where = [];
        if(isset($params['departmentid'])) array_push($where,["DepartmentID",'=',$params['departmentid']]);
        if(isset($params['startdaterange'])) array_push($where,["DeclaratedDate",'>=',$params['startdaterange']]);
        if(isset($params['enddaterange'])) array_push($where,["DeclaratedDate",'<=',$params['enddaterange']]);
        if(isset($params['itemid'])) {

            $selection = array_filter($selection, function($v){
                echo ($v);
                return $v != 'employeehours.EmployeeID';
            });
            array_push($where,["employeehours.EmployeeID",'=',$params['itemid']]);
        }
        if(isset($params['employeehoursid'])) array_push($where,["EmployeeHoursID",'=',$params['employeehoursid']]);
        if(isset($params['status'])) array_push($where,["HoursAccorded",'=',$params['status']]);

        //if no where clauses, select all employees
        if (!count($where)>0) array_push($where,["employeehours.EmployeeID",'>',0]);
            //fetch and return the result
        $result = $this->db->table('employeehours')->selection($selection)->innerjoin('departmentmemberlist','EmployeeID')->where($where)->get();
        return (array)$result;
    }

    public function put(array $body, array $params): array
    {
        // check for itemid
        if (! isset($params['itemid'])) throw new TeapotException('Hours can only be updated at individual endpoints');
        //check manager and employee for authorisation
        if ( ( (! isset($params['itemid']) ) OR ( $this->employee != $params [ 'itemid' ] ) ) AND ( !$this->manager) ) throw new NotAuthorizedException('Hours can only be viewed by a manager or the object employee');

        //check if all required parameters are set
        $requiredParamsArray = ["EmployeeHoursID", "HoursAccorded", "AccordedByManager"];
        foreach ($requiredParamsArray as $param)
        {
            if (! isset($params[$param])) throw new BadRequestException("Parameter '$param' is required");
        }



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