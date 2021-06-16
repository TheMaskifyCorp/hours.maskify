<?php

namespace API;
use Database;
use Exception;

require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require_once "ApiEndpointInterface.php";

/**
 * Class Contracts
 * @package API
 */
class Contracts implements ApiEndpointInterface
{
    protected int $employee;
    protected bool $manager;
    protected object $db;

    public function __construct(int $employee, bool $manager)
    {
        $this->employee = $employee;
        $this->manager = $manager;
        $this->db = new Database;
    }

    /**
     * @param array $body
     * @param array $params
     * @return array
     * @throws BadRequestException
     * @throws NotAuthorizedException
     */



    public function get (array $body, array $params) :array
    {
        $currentDateTime = date('Y-m-d ');
        if(isset($params['employeeid']))$employeeid = $params['employeeid'];
        if(isset($params['departmentid']))$employeeid = $params['departmentid'];
        if(!isset($params['onlycurrent']))$params['onlycurrent']=true;
        $onlycurrent = $params['onlycurrent'];

        //check if employeeid and department id are both set
        if((isset($params['employeeid'])) AND (isset($params['departmentid']))) throw new BadRequestException("Cannot filter on both single Employee and Department");
        if ( ( ! $this->manager) AND ( $employeeid !=$this->employee ) ) throw new NotAuthorizedException("Can only be viewed by a manager or the object employee");
        //return only contracts from specified DepartmentID
        if(isset($params['departmentid']))
        {
            $result = (array)$this->db->table('departmentmemberlist')->innerjoin('contracts','EmployeeID')->where(['DepartmentID','=',$params['departmentid']])->get();
            return $result;
        }
        
        if(isset($params['employeeid']))
        {
            $result = (array)$this->db->table('contracts')->where(['EmployeeID','=',$params['employeeid']])->get();
            return $result;
        }

        if(!isset($params['employeeid']))
        {
            $result = (array)$this->db->table('contracts')->get();
            return $result;
        }


        if(isset($params['onlycurrent'])) {
            $currentDateTime = date('Y-m-d ');
            $where = [];
            if ($onlycurrent == true) {
                //add parameters to an array
                array_push($where, ["contracts.ContractStartDate", '<=', $currentDateTime]);
                array_push($where, ["contracts.ContractEndDate", '>=', $currentDateTime]);

                //if no where clauses, select all employees
                if (!count($where) > 0) array_push($where, ["contracts.EmployeeID", '>', 0]);

                //fetch and return the result
                $result = $this->db->table('contracts')->selection(['EmployeeID', 'ContractStartDate', 'ContractEndDate', 'PayRate', 'WeeklyHours'])->innerjoin('departmentmemberlist', 'EmployeeID')->distinct()->where($where)->get();
                return (array)$result;
            }
            //return expired contracts
            if ($onlycurrent == false) {
                //add parameters to an array
                array_push($where, ["contracts.ContractEndDate", '<=', $currentDateTime]);

                //if no where clauses, select all employees
                if (!count($where) > 0) array_push($where, ["contracts.EmployeeID", '>', 0]);

                //fetch and return the result
                $result = $this->db->table('contracts')->selection(['EmployeeID', 'ContractStartDate', 'ContractEndDate', 'PayRate', 'WeeklyHours'])->innerjoin('departmentmemberlist', 'EmployeeID')->distinct()->where($where)->get();

                return (array)$result;
            }
        }
    }


    /**
     * @param array $body
     * @param array $params
     * @return string[]
     * @throws BadRequestException|NotAuthorizedException
     */
    public function post(array $body, array $params) :array
    {
        if( ! $this->manager) throw new NotAuthorizedException("Contracts can only be created by a manager");
        if((isset($body['ContractEndDate'])) AND strtotime($body['ContractEndDate']) < strtotime($body['ContractStartDate'])) throw new BadRequestException("End date cannot be earlier than start date");
        //verwachte variabelen
        $required = ["EmployeeID", "ContractStartDate", "WeeklyHours"];
        $optional = ['ContractEndDate','PayRate'];
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
        // throw an error if parameters are missing
        if ( count ( $missingParams ) > 0 ) throw new BadRequestException((  json_encode ( $missingParams ) ) );

        $this->validatePostRequest($requestParams);
        $contractCreated = $this->db->table('contracts')->insert($requestParams);

        // throw error if contract is not created
        if ($contractCreated !== true) throw new BadRequestException( "Could not create contract" );
        return ["New contract created"];
    }

    //Geen put in contracts
    public function put (array $body, array $params) :array
    {
        if(isset($body)) throw new BadRequestException("Can not update existing contracts, create a new contract instead");
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
                if ( isset ($params [ 'employeeid' ] , $params['contractstartdate'])){
                array_push($where, ["contracts.EmployeeID", '=', $employeeid]);
                array_push($where, ["contracts.ContractStartDate", '=', $contractstartdate]);
            }

                //try database request
                try {
                    $this->db->table('contracts')->delete($where);
                } catch (Exception $e) {
                    throw new BadRequestException('Error updating database');
                }
                //return message
                return ["Contract with {$params['employeeid']} and {$params['contractstartdate']} deleted"];

    }

    public static function validateEndpoint(array $apipath) : ?array
    {
        if (count ($apipath) > 2) throw new BadRequestException("Endpoint could not be validated");
        if ((isset ( $apipath[1]) ) AND (preg_match('/[0-9]+/',$apipath[1])))
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
        $db = new Database;
        foreach ($get as $UCparam => $value) {
            $param = strtolower($UCparam);
            switch ($param) {
                case "employeeid":
                    if (strlen((string)$value) > 15)
                        throw new BadRequestException("emplooyeeid cannot exceed 15 characters");

                    //parameter must be an integer
                    if (!preg_match('/^[0-9]{0,15}$/', $value))
                        throw new BadRequestException("emplooyeeid must be an integer");

                    //parameter must be existing department
                    if (! $db->table('employees')->exists(['EmployeeID' => $value]))
                        throw new NotFoundException( "employeeid not found");
                    break;

                case "onlycurrent":
                    if (is_bool($value))
                        throw new BadRequestException("emplooyeeid cannot exceed 15 characters");

                    break;

                case "departmentid":
                    if (strlen((string)$value) > 15)
                        throw new BadRequestException("departmentid cannot exceed 15 characters");

                    //parameter must be an integer
                    if (!preg_match('/^[0-9]{0,15}$/', $value))
                        throw new BadRequestException("departmentid must be an integer");

                    //parameter must be existing department
                    if (! $db->table('departmenttypes')->exists(['DepartmentID' => $value]))
                        throw new NotFoundException( "departmentid not found");
                    break;

                case "contractstartdate":
                    if (strlen((string)$value) > 15)
                        throw new BadRequestException("contractstartdate cannot exceed 15 characters");

                    if (isset($request['ContractStartDate'])) if ( ! preg_match ( '/[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $request['ContractStartDate']))
                        throw new BadRequestException("ContractStartDate must be formatted as: YYYY-MM-DD");

                    break;
                default:
                    throw new BadRequestException("Parameter $UCparam is not valid for this endpoint");
            }
        }
    }


    /**
     * @throws BadRequestException
     */
    private function validatePostRequest(array $request)
    {
        $requiredDate=["ContractStartDate", "ContractEndDate"];
        $requiredAlphaNum = ["EmployeeID", "WeeklyHours", "PayRate"];
        if (isset($request['EmployeeID'])) if ( ! preg_match ( '/[0-9]{1,11}$/', $request['EmployeeID'] )) throw new BadRequestException("EmployeeID must be integer");
        if (isset($request['WeeklyHours'])) if ( ! preg_match ( '/[0-9]{1,11}$/', $request['WeeklyHours'] )) throw new BadRequestException("WeeklyHours must be integer");
        if (isset($request['PayRate'])) if ( ! preg_match ( '/[0-9]{1,11}$/', $request['PayRate'] )) throw new BadRequestException("PayRate must be integer");
        if (isset($request['ContractStartDate'])) if ( ! preg_match ( '/[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $request['ContractStartDate'])) throw new BadRequestException("ContractStartDate must be formatted as: YYYY-MM-DD");
        if (isset($request['ContractEndDate'])) if ( ! preg_match ( '/[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $request['ContractEndDate'])) throw new BadRequestException("ContractEndDate must be formatted as: YYYY-MM-DD");
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

