<?php


namespace API;
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once "ApiEndpointInterface.php";


class Departments extends Endpoint implements ApiEndpointInterface
{
    /**
     * @param array $body
     * @param array $params
     * @return array
     * @throws NotAuthorizedException
     */

    public function get(array $body, array $params): array
    {
        if (!$this->manager)  throw new NotAuthorizedException("This request can only be performed by a manager");
        //return departmentid + description
        if (isset($params['departmentid'])) {
            $result = (array)$this->db->table('departmenttypes')->selection(['DepartmentID', 'Description'])->where(['DepartmentID', '=', $params['departmentid']])->get();
            return (array)$result;
        }
        if (!isset($params['departmentid'])) {
            $result = (array)$this->db->table('departmenttypes')->selection(['DepartmentID', 'Description'])->get();
            return (array)$result;
        }
    }

    public function post(array $body, array $params) :array
    {
        if(isset($body)) throw new BadRequestException("Can not create new departments");
    }

    public function put (array $body, array $params) :array
    {
        $where = [];
        if (!$this->manager) throw new NotAuthorizedException("This request can only be performed by a manager");

        if (isset($body ['DepartmentID'], $body['Description'])) {
            //check if all required parameters are set
            $requiredParamsArray = ["DepartmentID", "Description"];
            foreach ($requiredParamsArray as $param) {
                if (!isset($body[$param])) throw new BadRequestException("Body does not contain required parameter '$param'");
            }
            //move departmentid and description from body to where-clause
            $where = ['DepartmentID', '=', $body['DepartmentID'], 'Description', '=', $body['Description']];
            unset($body['departmentid'], $body['description']);
            //execute request
            try {
                $this->db->table('departmenttypes')->update($body, $where);
            } catch (\Exception $e) {
                throw new BadRequestException("Error updating record in database");
            }
            //response
            return [$where[5] . " updated"];
        }
    }


    public function delete (array $body, array $params) :array
    {
        $employeeid = $params['employeeid'];
        $contractstartdate = $params['contractstartdate'];
        $where = [];

        //check if employee id and contractstartdate are set
        if (! isset ( $params['employeeid'] ) OR (! isset($params['contractstartdate'] )))
            throw new BadRequestException('emplopyeeid or contractstartdate is not set');

        //check if user is manager
        if ( !$this->manager)
            throw new NotAuthorizedException('This request can only be performed by a manager');

        // check if both employeeid and startdate params are set
        if ( isset( $params [ 'employeeid' ]  ) AND isset($params['contractstartdate'] )) {
            array_push($where, ["contracts.EmployeeID", '=', $employeeid]);
            array_push($where, ["contracts.ContractStartDate", '=', $contractstartdate]);
        }

        //try database request
        try {
            $this->db->table('contracts')->delete($where);
        } catch (\Exception $e) {
            throw new BadRequestException('Error updating database');
        }
        //return message
        return ["Contract with {$params['employeeid']} and {$params['contractstartdate']} deleted"];

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
        $db = new \Database;
        foreach ($get as $UCparam => $value) {
            $param = strtolower($UCparam);
            switch ($param) {
                case "DepartmentID":
                    if (strlen((string)$value) > 15)
                        throw new BadRequestException("DepartmentID cannot exceed 15 characters");

                    //parameter must be an integer
                    if (!preg_match('/^[0-9]{0,15}$/', $value))
                        throw new BadRequestException("departmentid must be an integer");

                    //parameter must be existing department
                    if (!$db->table('departmenttypes')->exists(['DepartmentID' => $value]))
                        throw new NotFoundException("departmentid not found");
                    break;

                case "Description":
                    if (strlen((string)$value) > 15)
                        throw new BadRequestException("Description cannot exceed 15 characters");

                    //parameter must be an integer
                    if (is_string($value))
                        throw new BadRequestException("Description must be an string");

                    //parameter must be existing department
                    if (!$db->table('departmenttypes')->exists(['DepartmentID' => $value]))
                        throw new NotFoundException("Description not found");
                    break;

//                case "contractstartdate":
//                    if (strlen((string)$value) > 15)
//                        throw new BadRequestException("contractstartdate cannot exceed 15 characters");
//
//                    if (isset($request['ContractStartDate'])) if (!preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $request['ContractStartDate']))
//                        throw new BadRequestException("ContractStartDate must be formatted as: YYYY-MM-DD");
//
//                    break;
//                default:
//                    throw new BadRequestException("Parameter $UCparam is not valid for this endpoint");
            }
        }
    }


    /**
     * @throws BadRequestException
     */
    private function validatePostRequest(array $request)
    {
        $requiredString =["EmployeeID"];
        $requiredDate=["ContractStartDate", "ContractEndDate"];
        $requiredAlphaNum = ["WeeklyHours", "PayRate"];
        if (isset($request['EmployeeID'])) if ( ! preg_match ( '/[0-9]{1,11}$/', $request['EmployeeID'] )) throw new BadRequestException("EmployeeID must be integer");
        if (isset($request['WeeklyHours'])) if ( ! preg_match ( '/[0-9]{1,11}$/', $request['WeeklyHours'] )) throw new BadRequestException("WeeklyHours must be integer");
        if (isset($request['PayRate'])) if ( ! preg_match ( '/[0-9]{1,11}$/', $request['PayRate'] )) throw new BadRequestException("PayRate must be integer");
        if (isset($request['ContractStartDate'])) if ( ! preg_match ( '/[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $request['ContractStartDate'])) throw new BadRequestException("ContractStartDate must be formatted as: YYYY-MM-DD");
        if (isset($request['ContractEndDate'])) if ( ! preg_match ( '/[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $request['ContractEndDate'])) throw new BadRequestException("ContractEndDate must be formatted as: YYYY-MM-DD");
        foreach($requiredString as $key)
        {
            if (isset($request[$key])) if ( ! preg_match ( '/[A-z0-9]+$/', $request[$key]) ) throw new BadRequestException("$key can only contain alphanumeric symbols");
        }
        foreach($requiredAlphaNum as $key)
        {
            if (isset($request[$key])) if ( ! preg_match ( '/[A-z0-9]+$/', $request[$key]) ) throw new BadRequestException("$key can only contain alphanumeric symbols");
        }
        foreach($requiredDate as $key)
        {
            if (isset($request[$key])) if ( ! preg_match ( '/[A-z0-9]+$/', $request[$key]) ) throw new BadRequestException("$key can only contain alphanumeric symbols");
        }

    }

}
