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
        $where = [];
        //Begining of holidays (plural) section, manager function to grab multiple holidays.
        //an employee can only see his own holidays unless he is manager
        if ((!$this->manager)) throw new NotAuthorizedException('Holidays of multiple employees can only be viewed by a manager');
        //throw error for filtering on department AND employee
        if ((isset($params['employeeid'])) and (isset($params['departmentid']))) throw new BadRequestException("Cannot filter on both single Employee and Department");
        //selection is for select all statement
        $selection = ["*"];
        if (isset($params['employeeid'])) array_push($where, ["EmployeeID", '=', $params['employeeid']]);
        if (isset($params['departmentid'])) array_push($where, ["departmentmemberlist.DepartmentID", '=', $params['departmentid']]);
        if (isset($params['departmentid'])) {
            try {
                $result = $this->db->table('departmentmemberlist')->selection($selection)->innerjoin('holidays', 'EmployeeID')->distinct()->where($where)->get();
            } catch (Exception $e) {
                throw new DatabaseConnectionException();
            }
            return (array)$result;
        }
        if (isset($params['status'])) array_push($where, ["HolidaysAccorded", '=', $params['status']]);

        //attempt to create the get dates without stored proc
        if ((isset($params['startdaterange'])) and isset($params['enddaterange']))
        {
            $where = [];

            //it's not parsing the param as a date value, it's arrives in the request as :
            array_push($where, ["HolidayStartDate", '<=', $params['enddaterange']]);
            array_push($where, ["HolidayEndDate", '>=', $params['startdaterange']]);

            try {
                $result = $this->db->table('holidays')->where($where)->get();
            } catch (Exception $e) {
                throw new DatabaseConnectionException();
            }
            return (array)$result;

        }
//        //stored proc
//        if ((isset($params['startdaterange'])) and isset($params['enddaterange']))
//        {
//            $start = $params['startdaterange'];
//            $end = $params['enddaterange'];
//
//            //call a stored procedure called HolidaysBetween that will fetch everything from holidays within the startdaterange and enddaterange params as $start and $end.
//            $db = new \Database;
//
//            $statement = $db->pdo->prepare("CALL HolidaysBetween('$start', '$end')");
//
//            //It is problematic because params can't be combined
//            try {
//                $statement->execute();
//                $result = $statement->fetchAll();
//                $result1 = $this->db->table('holidays')->where($where)->get();
//
//                //check if param is present in result and filter for value, for example: status=null.
//                var_dump($result1[4]);
//                $status = $result1['HolidaysAccorded'];
//                if($status !== null)
//                array_filter($result1, function($status)
//                {
//                    return $status == null;
//                });
//
////                arraymerge does not append my arrays as it should but instead prints both arrays seperately
//                $totalresult = array_merge($result, $result1);
//                //compact allows both arrays to be returned in 1 result.
//                //The arrays have to be merged or innerjoined with the additional variables set besides start and end dates
//                //The problem with this is that it has to work in combination with other params.
//                $totalresult = compact('result', 'result1');
//                return array($totalresult);
//            } catch (Exception $e) {
//                throw new DatabaseConnectionException();
//            }
//
//        }


        //if any of the above params are set, get the results, an empty array will return false, if the params array has values it will validate to true, works for status
        if ($params) {
             if(isset($params [ 'employeeid' ]) AND $this->employee != $params [ 'employeeid' ] )
                throw new NotAuthorizedException('Holidays can only be viewed by a manager or the object employee');
                try {
                $result = $this->db->table('holidays')->where($where)->get();
            } catch (Exception $e) {
                throw new DatabaseConnectionException();
            }
            return (array)$result;
        }

        //if no parameters are given return all holidays
        if ($params == []) {
            try {
                $result = $this->db->table('holidays')->get();
            } catch (Exception $e) {
                throw new DatabaseConnectionException();
            }
            return (array)$result;
        }
    }

    public function post(array $body, array $params): array
    {
//        for creating holiday's for individual users
        if(isset($params [ 'employeeid' ]) AND $this->employee != $params [ 'employeeid' ] OR ( !$this->manager) ) throw new NotAuthorizedException('Holidays can only be viewed by a manager or the object employee');
        if (!isset($body["HolidayStartDate"])) throw new BadRequestException("HolidayStartDate is required");
        if (isset($body["EmployeeID"]) AND isset($body["HolidayStartDate"]))
        {
            $required = ["EmployeeID", "HolidayStartDate", "HolidayEndDate", "TotalHoursInMinutes", "AccordedByManager"];
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
    //individual employeesholidays (1 holiday can be updated at once)
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
            if($result == true)
            {
                //Problem here is
                //add body params to array and check if they are set
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




        //check if all required parameters are set
//        $update = [];
//        $requiredParamsArray = ["EmployeeHoursID", "HoursAccorded", "AccordedByManager"];
//        foreach ($requiredParamsArray as $param)
//        {
//            if (! isset($body[$param])) throw new BadRequestException("Body does not contain required parameter '$param'");
//            $update['$param'] = $body['$param'];
//        }
//        //move employeeid from body to where-clause
//        $where = ['EmployeeHoursID','=', $body['EmployeeHoursID'] ];
//        unset($update['EmployeeHoursID']);
//        //execute request
//        try{
//            $this->db->table('employeehours')->update($body,$where);
//        } catch (Exception $e){
//            throw new BadRequestException("Error updating record in database");
//        }
//        //response
//        return [$where[2] . " updated"]

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
        if (count($apipath) > 3) throw new BadRequestException("Endpoint could not be validated");
        if ((isset ($apipath[1])) and (isset ($apipath[2]) and (preg_match('/[0-9]+/', $apipath[1]))))
            return ['employeeid' => $apipath[1], 'holidaystartdate' => $apipath[2]];
        if ((isset ($apipath[1]))  and (preg_match('/[0-9]+/', $apipath[1])))
            return ['employeeid' => $apipath[1]];
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
