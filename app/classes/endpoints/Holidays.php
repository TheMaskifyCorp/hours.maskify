<?php

namespace API;
use Database;
use Exception;

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once "ApiEndpointInterface.php";


/**
 * Class Holidays
 * @package API
 */
class Holidays extends Endpoint implements ApiEndpointInterface
{
    /**
     * @param array $body
     * @param array $params
     * @return array
     * @throws BadRequestException
     * @throws DatabaseConnectionException
     * @throws NotAuthorizedException
     */

    public function get(array $body, array $params): array
    {
        if ( ( (! isset($params['employeeid']) ) OR ( $this->employee != $params [ 'employeeid' ] ) ) AND ( !$this->manager) ) throw new NotAuthorizedException('Hours can only be viewed by a manager or the object employee');
        if((isset($params['employeeid'])) AND (isset($params['departmentid']))) throw new BadRequestException("Cannot filter on both single Employee and Department");
        if(isset($params["holidaystartdate"])) {
            try {
                $exists = $this->db->table('holidays')->exists(["HolidayStartDate" => $params['holidaystartdate'], 'EmployeeID' => $params['employeeid']]);
                }
                catch(Exception $e){
                    throw new DatabaseConnectionException();
                }
            if ($exists) {
                return $this->db->table('holidays')->where(["HolidayStartDate", "=", $params['holidaystartdate']], ['EmployeeID', "=", $params['employeeid']] );
            }else{
                throw new BadRequestException("Holiday does not exists");
            }
        }
        $where=[];
        // employeeid departmentid status startdaterange enddaterange
        if (isset($params['employeeid'])) array_push($where, ["holidays.EmployeeID", '=', $params['employeeid']]);
        if (isset($params['departmentid'])) array_push($where, ["departmentmemberlist.DepartmentID", '=', $params['departmentid']]);
        if(isset($params['startdaterange'])) array_push($where, ["HolidayEndDate", '>=', $params['startdaterange']]);
        if(isset($params['enddaterange'])) array_push($where,["HolidayStartDate",'<=',$params['enddaterange']]);
        if (isset($params['status'])) array_push($where, ["HolidaysAccorded", '=', $params['status']]);
        if (!count($where)>0) array_push($where,["holidays.EmployeeID",'>',0]);

        $selection = ["holidays.EmployeeID", "HolidayStartDate" , "HolidayEndDate", "TotalHoursInMinutes", "AccordedByManager","HolidaysAccorded"];

        return $this->db->table('holidays')->selection($selection)->innerjoin('departmentmemberlist', 'EmployeeID')->distinct()->where($where)->get();
    }

    public function post(array $body, array $params): array
    {
//        for creating holiday's for individual users
        if(isset($params [ 'employeeid' ]) AND $this->employee != $params [ 'employeeid' ] OR ( !$this->manager) ) throw new NotAuthorizedException('Holidays can only be viewed by a manager or the object employee');
        if (!isset($body["HolidayStartDate"])) throw new BadRequestException("HolidayStartDate is required");
        if (isset($body["EmployeeID"]) AND isset($body["HolidayStartDate"]))
        {
            $required = ["EmployeeID", "HolidayStartDate" , "HolidayEndDate", "TotalHoursInMinutes", "AccordedByManager"];
            $missingParams = [];
            $requestParams = [];
            foreach ($required as $value) {
                if (!array_key_exists($value, $body)) {
                    array_push($missingParams, $value . " is required");
                } else {
                    $requestParams[$value] = $body[$value];
                }
            }
            $holidayCreated = $this->db->table('holidays')->insert($requestParams);

            // throw error if employee is not created
            if ($holidayCreated !== true) throw new BadRequestException("Could not create holiday");
            return ["New holiday created"];
        }
    }

    public function put(array $body, array $params): array
    {
    //individual employeesholidays (1 holiday can be updated with 1 put request)
        if(isset($params [ 'employeeid' ]) AND $this->employee != $params [ 'employeeid' ] OR ( !$this->manager) ) throw new NotAuthorizedException('Holidays can only be viewed by a manager or the object employee');
        if  (! isset($params['employeeid']) AND (!isset($params['holidaystartdate'])))  throw new BadRequestException('No holiday found, employeeid and startdate of vacation must be set');
        if  (isset($params['employeeid']) AND (isset($params['holidaystartdate'])))
        {
            try {
                $where = [];
                array_push($where, ["EmployeeID", '=', $params['employeeid']]);
                array_push($where, ["HolidayStartDate", '=', $params['holidaystartdate']]);
                $result = $this->db->table('holidays')->exists(['EmployeeID' => $params['employeeid'],"HolidayStartDate" => $params['holidaystartdate']]);
            } catch (Exception $e) {
                throw new DatabaseConnectionException();
            }

            if($result == true)
            {
                //add body params to array and check if they are set, put each of them into the insert array
                $insert = [];
                $allowedBodyParam = ["HolidaysAccorded", "AccordedByManager"];
                foreach ($allowedBodyParam as $param) {
                    if (!isset($body[$param])) throw new BadRequestException("Body is missing parameter '$param'");
                    $insert[$param] = $body[$param];
                }

                //execute request
                try {

                   $result = $this->db->table('holidays')->update($insert, $where);
                } catch (\Exception $e) {
                    throw new BadRequestException("Error updating record in database");
                }
                //response
                return [$result];
            }
        }


    }

    public function delete(array $body, array $params): array
    {
        if (!$this->manager) throw new NotAuthorizedException("This request can only be performed by a manager / only HolidaysAccorded and AccordedByManager can be altered");
        if  (! isset($params['employeeid']) AND (!isset($params['holidaystartdate'])))  throw new BadRequestException('No holiday found, employeeid and startdate of vacation must be set');
        if  (isset($params['employeeid']) AND (isset($params['holidaystartdate'])))
        {
            try {
                $where = [];
                array_push($where, ["EmployeeID", '=', $params['employeeid']]);
                array_push($where, ["HolidayStartDate", '=', $params['holidaystartdate']]);
                $result = $this->db->table('holidays')->exists(['EmployeeID' => $params['employeeid'],"HolidayStartDate" => $params['holidaystartdate']]);
            } catch (Exception $e) {
                throw new DatabaseConnectionException();
            }
            if($result == false) throw new BadRequestException('Holiday doesnt exist');

            if($result == true)
            {
                //execute request
                try {

                    $result = $this->db->table('holidays')->delete($where);
                } catch (\Exception $e) {
                    throw new BadRequestException("Error updating record in database");
                }
            }
        }
        //response
        return ["holiday deleted"];
    }

//

    /**
     * @param array $apipath
     * @return array|null
     * @throws BadRequestException
     */
    public static function validateEndpoint(array $apipath): ?array
    {
        if (count($apipath) > 3) throw new BadRequestException("Endpoint could not be validated");
        if ((isset ($apipath[1])) and (isset ($apipath[2]) and (preg_match('/[0-9]+/', $apipath[1]))))
            return ['employeeid' => $apipath[1], 'holidaystartdate' => $apipath[2]];
        if ((isset ($apipath[1]))  and (preg_match('/[0-9]+/', $apipath[1])))
            return ['employeeid' => $apipath[1]];
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
                    if (! $db->table('holidays')->exists(['EmployeeID'=>$value]))
                        throw new NotFoundException("EmployeeID '$value' does not exist");
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
                case 'holidaystartdate':
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
