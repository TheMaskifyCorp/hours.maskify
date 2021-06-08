<?php

namespace API;
use Database;
use Exception;

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once "ApiEndpointInterface.php";


class Holidays extends Endpoint implements ApiEndpointInterface
{
    /**
     * @param array $body
     * @param array $params
     * @return array
     * @throws NotAuthorizedException
     */

    public function get(array $body, array $params): array
    {
        //an employee can only see his own holidays unless he is manager
        if(( !$this->manager))  throw new NotAuthorizedException('Holidays can only be viewed by a manager or the object employee');
        //throw error for filtering on department AND employee
        if((isset($params['employeeid'])) AND (isset($params['departmentid']))) throw new BadRequestException("Cannot filter on both single Employee and Department");
        //is a uuid is provided, return that uuid
        //add every parameter to an array
        $selection =[
            "departmentmemberlist.DepartmentID",
            "EmployeeID",
            "StartDateRange",
            "EndDateRange",
            "Status"];
        $where = [];
        if(isset($params['departmentid'])) array_push($where,["departmentmemberlist.DepartmentID",'=',$params['departmentid']]);
//        if(isset($params['startdaterange'])) array_push($where,["HolidayStartDate",'=',$params['startdaterange']]);
//        if(isset($params['enddaterange'])) array_push($where,["HolidayEndDate",'=',$params['enddaterange']]);
        if(isset($params['status'])) array_push($where,["HolidaysAccorded",'=',$params['status']]);

        if((isset($params['startdaterange'])) AND isset($params['enddaterange']))
        {
            $start = $params['startdaterange'];
            $end = $params['enddaterange'];
            array_push($where,["HolidayStartDate",'=>',$start]);
            array_push($where,["HolidayEndDate",'<=',$end]);
            var_dump($start);
            var_dump($end);
            var_dump($where);
            //make a function that fetches
            try {
                $result = $this->db->table('holidays')->where($where)->returnstmt();
            } catch (Exception $e) {
                throw new DatabaseConnectionException();
            }
            return (array)$result;
        }

        //if any of the above params are set get the results, an empty array will return false, if the params array has values it will validate to true, works for status
        if($params)
        {
            try {
                $result = $this->db->table('holidays')->where($where)->get();
            } catch (Exception $e) {
                throw new DatabaseConnectionException();
            }
            return (array)$result;
        }

//      ->selection($selection)
//        ->innerjoin('departmentmemberlist', 'EmployeeID')->distinct()
        //if no parameters are given return all holidays
        if($params == [])
        {
            try{
                $result = $this->db->table('holidays')->get();
            }catch (Exception $e){
                throw new DatabaseConnectionException();
            }
            return (array)$result;
        }

    }

    public function post(array $body, array $params): array
    {
        if (!$this->manager) throw new NotAuthorizedException("This request can only be performed by a manager");
        if (isset($body["Description"]) and (is_string($body["Description"]) !== true))
            throw new BadRequestException("Description must be of type string and cannot be null");
        if (isset($body["DepartmentID"])) {
            $required = ["DepartmentID", "Description"];
            $missingParams = [];
            $requestParams = [];
            foreach ($required as $value) {
                if (!array_key_exists($value, $body)) {
                    array_push($missingParams, $value . " is required");
                } else {
                    $requestParams[$value] = $body[$value];
                }
            }
            $departmentCreated = $this->db->table('departmenttypes')->insert($requestParams);

            // throw error if employee is not created
            if ($departmentCreated !== true) throw new BadRequestException("Could not create department");
            return ["New department created"];
        }
    }

    public function put(array $body, array $params): array
    {

        if (!$this->manager) throw new NotAuthorizedException("This request can only be performed by a manager");

        //check if all required parameters are set
        $requiredBodyParam = ["DepartmentID", "Description"];
        foreach ($requiredBodyParam as $param) {
            if (!isset($body[$param])) throw new BadRequestException("Body does not contain required parameter '$param'");
        }

        //move departmentid and description from body to where-clause
        $where = [];
        $where = ['DepartmentID', '=', $body['DepartmentID'], 'Description', '=', $body['Description']];

        //execute request
        try {
            $this->db->table('departmenttypes')->update($body, $where);
        } catch (\Exception $e) {
            throw new BadRequestException("Error updating record in database");
        }
        //response
        return [$where[5] . " updated"];

    }

    public function delete(array $body, array $params): array
    {
        //check if department id param is set
        if (!isset ($params['departmentid']))
            throw new BadRequestException('departmentid is not set');

        //check if user is manager
        if (!$this->manager)
            throw new NotAuthorizedException('This request can only be performed by a manager');

        $departmentid = $params['departmentid'];
        $where = [];
        array_push($where, ["departmenttypes.DepartmentID", '=', $departmentid]);
        $this->db->table('departmenttypes')->delete($where);
        return (['department deleted']);

    }

//
    public static function validateEndpoint(array $apipath): ?array
    {
        if (count($apipath) > 2) throw new BadRequestException("Endpoint could not be validated");
        if ((isset ($apipath[1])) and (preg_match('/[0-9]+/', $apipath[1])))
            return ['departmentid' => $apipath[1]];
        return null;
    }

        public static function validateGet(array $get)
    {
        $db = new Database();
        foreach ($get as $UCparam => $value) {
            $param = strtolower($UCparam);
            switch ($param) {
                case "employeeid":
                    //parameter cannot exceed length 15
                    if (strlen((string)$value) > 15)
                        throw new BadRequestException("EmployeeID cannot exceed 15 characters");

                    //parameter must be an integer
                    if (!preg_match('/^[0-9]{0,15}$/', $value))
                        throw new BadRequestException("EmployeeID must be an integer");

                    //parameter must be existing employee
                    if ($db->table('employees')->exists(["EmployeeID" => $value]))
                        throw new NotFoundException("Employee '$value' does not exist");
                    break;
                case "departmentid":
                    //parameter cannot exceed length 15
                    if (strlen((string)$value) > 15)
                        throw new BadRequestException("DepartmentID cannot exceed 15 characters");

                    //parameter must be an integer
                    if (!preg_match('/^[0-9]{0,15}$/', $value))
                        throw new BadRequestException("DepartmentID must be an integer");

                    //parameter must be existing department
                    if (! $db->table('departmenttypes')->exists(['DepartmentID'=>$value]))
                        throw new NotFoundException("DepartmentID '$value' does not exist");
                    break;
                case "startdaterange":
                    if ( ! preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $value ) )
                        throw new BadRequestException('Dates must be formatten YYYY-MM-DD');
                    break;
                case "enddaterange":
                    if ( ! preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $value ) )
                        throw new BadRequestException('Dates must be formatten YYYY-MM-DD');
                    break;
                case "status":
                    if (! in_array(strtolower($value),['null','0','1']))
                        throw new BadRequestException('Status must be 0, 1 or NULL');
                    break;
                default:
                    throw new BadRequestException("Parameter $UCparam is not valid for this endpoint");
            }
        }
    }
}
