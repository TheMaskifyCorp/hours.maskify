<?php

namespace API;

use Dotenv\Dotenv;

class Hours extends Endpoint implements ApiEndpointInterface
{

    public function get(array $body, array $params): array
    {
        extract($params);
        //check manager and employee for authorisation
        if ( ( (! isset($employeeid) ) OR ( $this->employee != $employeeid ) ) AND ( !$this->manager) ) throw new NotAuthorizedException('Hours can only be viewed by a manager or the object employee');
        //throw error for filtering on department AND employee
        if((isset($employeeid)) AND (isset($departmentid))) throw new BadRequestException("Cannot filter on both single Employee and Department");

        //add every parameter to an array
        $selection =[
            "EmployeeHoursID",
            "employeehours.EmployeeID",
            "HoursAccorded",
            "AccordedByManager",
            "DeclaratedDate",
        "EmployeeHoursQuantityInMinutes"];
        $where = [];
        if(isset($departmentid)) array_push($where,["DepartmentID",'=',$departmentid]);
        if(isset($startdaterange)) array_push($where,["DeclaratedDate",'>=',$startdaterange]);
        if(isset($enddaterange)) array_push($where,["DeclaratedDate",'<=',$enddaterange]);
        if(isset($params['employeeid'])) {

            $selection = array_filter($selection, function($v){
                return $v != 'employeehours.EmployeeID';
            });
            array_push($where,["employeehours.EmployeeID",'=',$params['employeeid']]);
        }
        if(isset($employeehoursid)) array_push($where,["EmployeeHoursID",'=',$employeehoursid]);
        if(isset($status)) array_push($where,["HoursAccorded",'=',$status]);

        //if no where clauses, select all employees
        if (!count($where)>0) array_push($where,["employeehours.EmployeeID",'>',0]);
            //fetch and return the result
        try{
            $result = $this->db->table('employeehours')->selection($selection)->innerjoin('departmentmemberlist','EmployeeID')->distinct()->where($where)->get();
        }catch (\Exception $e){
            throw new DatabaseConnectionException();
        }
        return (array)$result;
    }

    public function put(array $body, array $params): array
    {
        // check for employeeid
        if (! isset($employeeid)) throw new TeapotException('Hours can only be updated at individual endpoints');
        //check manager and employee for authorisation
        if ( ( (! isset($employeeid) ) OR ( $this->employee != $employeeid ) ) AND ( !$this->manager) ) throw new NotAuthorizedException('Hours can only be viewed by a manager or the object employee');

        //check if all required parameters are set
        $requiredParamsArray = ["EmployeeHoursID", "HoursAccorded", "AccordedByManager"];
        foreach ($requiredParamsArray as $param)
        {
            if (! isset($body[$param])) throw new BadRequestException("Body does not contain required parameter '$param'");
        }
        //move employeeid from body to where-clause
        $where = ['EmployeeHoursID','=', $body['EmployeeHoursID'] ];
        unset($body['EmployeeHoursID']);
        //execute request
        try{
            $this->db->table('employeehours')->update($body,$where);
        } catch (\Exception $e){
            throw new BadRequestException("Error updating record in database");
        }
        //response
        return [$where[2] . " updated"];
    }

    public function post(array $body, array $params): array
    {
        extract($params);
        // check for employeeid
        if (isset($employeeid)) throw new TeapotException('Hours can only be created at the general endpoint');
        // TODO: Implement delete() method.
        if ( ( (! isset($employeeid) ) OR ( $this->employee != $employeeid ) ) AND ( !$this->manager) )
            throw new NotAuthorizedException('Hours can only be deleted by a manager or the object employee');
        if (isset ($employeehoursid))
            throw new BadRequestException("UUID will be generated on insertion");
        //check if the request is okay
        $requiredParamsArray = ["EmployeeID", "DeclaratedDate", "EmployeeHoursQuantityInMinutes"];
        $insert = [];
        foreach ($requiredParamsArray as $param)
        {
            if (! isset($body[$param])) throw new BadRequestException("Body does not contain required parameter '$param'");
        }

        //start insertion
        $uuid = \UUID::createRandomUUID();
        $body['EmployeeHoursID'] = $uuid;
        try {
            $result = $this->db->table('employeehours')->insert($body);
        }catch(\Exception $e){
            throw new BadRequestException("Error updating record in database");
        }
        return ["New record with ID $uuid created"];
    }

    public function delete(array $body, array $params): array
    {
        extract($params);
        // check for employeeid
        if ( isset( $employeeid ) )
            throw new TeapotException('Hours can only be deleted at general endpoint' );
        //check for object id
        if (! isset ( $employeehoursid ))
            throw new BadRequestException('Object EmployeeHoursID is not set');
        //check authorisation
        $object = $this->db->table("employeehours")->where(['EmployeeHoursID','=',$employeehoursid])->first();
        $employee = $object->EmployeeID;

        if ( ( ( $this->employee != $employee ) ) AND ( !$this->manager) )
            throw new NotAuthorizedException('Hours can only be deleted by a manager or the object employee');

        //check if hours have status null
        if ( ( $object->HoursAccorded == "0") OR ( $object->HoursAccorded == "1") )
            throw new BadRequestException("Only Hours with accorded-status of NULL can be deleted");

        //try database request
        try {
            $this->db->table('employeehours')->delete(["EmployeeHoursID", '=', $employeehoursid]);
        }catch(\Exception $e){
            throw new BadRequestException('Error updating database');
        }
        //return message
        return ["Hours with ID $employeehoursid deleted"];
    }

    /**
     * @throws BadRequestException
     */
    public static function validateEndpoint($apipath): ?array
    {
        $db = new \Database;
        if (count ($apipath) > 2) throw new BadRequestException("Endpoint $path could not be validated");

        if ( isset( $apipath [ 1 ] ) ) intval( $apipath [ 1 ] );
        if ( ( isset($apipath[1]) ) AND (! $db->table('employees')->exists($apipath[1],'EmployeeID') ) ) throw new BadRequestException("Employee does not exist");
        if ( isset($apipath[1]) )
            return ['employeeid' => $apipath[1]];
        return null;
    }

    /**
     * @throws BadRequestException
     * @throws NotFoundException
     */

    public static function validateGet($get)
    {
        $db = new \Database();
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
                    if ($db->table('employees')->exists($value, "EmployeeID"))
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


    /*
     * PRIVATE FUNCTIONS
     */

    private function checkHourStatus(string $uuid) : bool
    {
        $result = $this->db->table("employeehours")->where(['EmployeeHoursID','=',$uuid])->first();
        if ($result->HoursAccorded = null) return true;
        return false;
    }
}