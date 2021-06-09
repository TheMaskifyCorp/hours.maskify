<?php

namespace API;

use Database;
use Exception;
use UUID;

class Hours extends Endpoint implements ApiEndpointInterface
{

    /**
     * @param array $body
     * @param array $params
     * @return array
     * @throws BadRequestException | DatabaseConnectionException | NotAuthorizedException
     */
    public function get(array $body, array $params): array

    {

        //check manager and employee for authorisation
        if ( ( (! isset($params['employeeid']) ) OR ( $this->employee != $params [ 'employeeid' ] ) ) AND ( !$this->manager) ) throw new NotAuthorizedException('Hours can only be viewed by a manager or the object employee');
        //throw error for filtering on department AND employee
        if((isset($params['employeeid'])) AND (isset($params['departmentid']))) throw new BadRequestException("Cannot filter on both single Employee and Department");
        //is a uuid is provided, return that uuid
        if (isset($params['uuid']))
        try{
            $response = $this->db->table('employeehours')->where(['EmployeeHoursID','=',$params['uuid']])->first();
            if (count((array)$response) > 0) return [$response];
            throw new BadRequestException("Record {$params['uuid']} not found");
        }catch(Exception $e){
            throw new DatabaseConnectionException();
        }

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
        if(isset($params['employeeid'])) {

            $selection = array_filter($selection, function($v){
                return $v != 'employeehours.EmployeeID';
            });
            array_push($where,["employeehours.EmployeeID",'=',$params['employeeid']]);
        }
        if(isset($params['uuid'])) array_push($where,["EmployeeHoursID",'=',$params['uuid']]);
        if(isset($params['status'])) array_push($where,["HoursAccorded",'=',$params['status']]);
        //if no where clauses, select all employees
        if (!count($where)>0) array_push($where,["employeehours.EmployeeID",'>',0]);
            //fetch and return the result

        try{
            $result = $this->db
                ->table('employeehours')
                ->selection($selection)
                ->innerjoin('departmentmemberlist','EmployeeID')
                ->distinct()
                ->order("DeclaratedDate","DESC")
                ->where($where)
                ->get();
//                ->returnstmt();
//                ->returnError();
        }catch (Exception $e){
            throw new DatabaseConnectionException();
        }
        return (array)$result;
    }

    /**
     * @param array $body
     * @param array $params
     * @return string[]
     * @throws BadRequestException | NotAuthorizedException | TeapotException
     */
    public function put(array $body, array $params): array
    {
        // check for employeeid
        if (! isset($params['employeeid'])) throw new TeapotException('Hours can only be updated at individual endpoints');
        //check manager and employee for authorisation
        if ( ( (! isset($params['employeeid']) ) OR ( $this->employee != $params [ 'employeeid' ] ) ) AND ( !$this->manager) ) throw new NotAuthorizedException('Hours can only be viewed by a manager or the object employee');

        //check if all required parameters are set
        $update = [];
        $requiredParamsArray = ["EmployeeHoursID", "HoursAccorded", "AccordedByManager"];
        foreach ($requiredParamsArray as $param)
        {
            if (! isset($body[$param])) throw new BadRequestException("Body does not contain required parameter '$param'");
            $update['$param'] = $body['$param'];
        }
        //move employeeid from body to where-clause
        $where = ['EmployeeHoursID','=', $body['EmployeeHoursID'] ];
        unset($update['EmployeeHoursID']);
        //execute request
        try{
            $this->db->table('employeehours')->update($body,$where);
        } catch (Exception $e){
            throw new BadRequestException("Error updating record in database");
        }
        //response
        return [$where[2] . " updated"];
    }

    /**
     * @param array $body
     * @param array $params
     * @return string[]
     * @throws BadRequestException | NotAuthorizedException | TeapotException
     */
    public function post(array $body, array $params): array
    {
        // check for employeeid
        if (isset($params['employeeid'])) throw new TeapotException('Hours can only be created at the general endpoint');

        if ( ( (! isset($params['employeeid']) ) OR ( $this->employee != $params [ 'employeeid' ] ) ) AND ( !$this->manager) )
            throw new NotAuthorizedException('Hours can only be deleted by a manager or the object employee');
        if (isset ($params['uuid']))
            throw new BadRequestException("UUID will be generated on insertion");
        //check if the request is okay
        $requiredParamsArray = ["EmployeeID", "DeclaratedDate", "EmployeeHoursQuantityInMinutes"];

        $insert = [];
        foreach ($requiredParamsArray as $param)
        {
            if (! isset($body[$param])) throw new BadRequestException("Body does not contain required parameter '$param'");
            $insert['param'] = $body['param'];
        }
        //check if the record is set with Accorded status
        if (isset($body['HoursAccorded'],$body['AccordedByManager']))
        {
            $insert['HoursAccorded'] = $body['HoursAccorded'];
            $insert['AccordedByManager'] = $body['AccordedByManager'];
        }

        //start insertion
        $uuid = UUID::createRandomUUID();
        $insert['EmployeeHoursID'] = $uuid;
        try {
            $this->db->table('employeehours')->insert($insert);
        }catch(Exception $e){
            throw new BadRequestException("Error updating record in database");
        }
        return ["New record with ID $uuid created"];
    }

    /**
     * @param array $body
     * @param array $params
     * @return string[]
     * @throws BadRequestException | NotAuthorizedException | TeapotException
     */
    public function delete(array $body, array $params): array
    {
        // check for employeeid
        if ( isset( $params [ 'employeeid' ] ) )
            throw new TeapotException('Hours can only be deleted at general endpoint' );
        //check for object id
        if (! isset ( $params['uuid'] ))
            throw new BadRequestException('Object UUID is not set');
        //check authorisation
        $object = $this->db->table("employeehours")->where(['EmployeeHoursID','=',$params['uuid']])->first();
        $employee = $object->EmployeeID;

        if ( ( ( $this->employee != $employee ) ) AND ( !$this->manager) )
            throw new NotAuthorizedException('Hours can only be deleted by a manager or the object employee');

        //check if hours have status null
        if ( ( $object->HoursAccorded == "0") OR ( $object->HoursAccorded == "1") )
            throw new BadRequestException("Only Hours with accorded-status of NULL can be deleted");

        //try database request
        try {
            $this->db->table('employeehours')->delete(["EmployeeHoursID", '=', $params['uuid']]);
        }catch(Exception $e){
            throw new BadRequestException('Error updating database');
        }
        //return message
        return ["Hours with ID {$params['uuid']} deleted"];
    }

    /**
     *
     * @param array $apipath
     * @return array|null
     * @throws BadRequestException
     * @throws NotFoundException
     */
    public static function validateEndpoint(array $apipath): ?array
    {
        $db = new Database;
        if (count ($apipath) > 2) throw new BadRequestException("Endpoint could not be validated");
        //check if second item is in the list of integers
        if ( isset( $apipath [ 1 ] ) and (preg_match('/^[0-9]+$/',$apipath[ 1 ])  ) ){
            if ( ( isset($apipath[1]) ) AND (! $db->table('employees')->exists(['EmployeeID' => $apipath[1]]) ) ) throw new BadRequestException("Employee does not exist");
            return ['employeeid' => $apipath[1]];
        }
        if ( isset($apipath[1]) && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/',$apipath[1]) ) {

            if (! $db->table('employeehours')->exists(['EmployeeHoursID' => $apipath[1]])) throw new NotFoundException("UUID not found in database");
            return ['uuid' => $apipath[1]];
            }
        if (isset ($apipath[1])) throw new BadRequestException('Could not validate endpoint');
        return null;
    }

    /**
     * @param array $get
     * @throws BadRequestException
     * @throws NotFoundException
     */

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
