<?php

namespace API;
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require_once "ApiEndpointInterface.php";

class Contracts implements ApiEndpointInterface
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

    /**
     * @param array $body
     * @param array $params
     * @return array
     * @throws NotAuthorizedException
     */



    public function get (array $body, array $params) :array
    {
        $employeeid = $params['employeeid'];
        $currentDateTime = date('Y-m-d ');
        $onlycurrent =  $params['onlycurrent'];

        //return only contracts from specified DepartmentID
        if(isset($params['departmentid']))
        {
            $result = (array)$this->db->table('departmentmemberlist')->innerjoin('contracts','EmployeeID')->where(['DepartmentID','=',$params['departmentid']])->get();
            return (array)$result;
        }
        //return only the current contracts
//        if(isset($params['onlycurrent']))
//        {
//            $onlycurrent = $params['onlycurrent'];
//        } else $onlycurrent = true;
        if((isset($params['employeeid'])) AND (isset($params['departmentid']))) throw new BadRequestException("Cannot filter on both single Employee and Department");
        if ( ( ! $this->manager) AND ( $employeeid !=$this->employee ) ) throw new NotAuthorizedException("Can only be viewed by a manager or the object employee");
        if(isset($params['onlycurrent'])) {
            $currentDateTime = date('Y-m-d ');
            $where = [];
            if ($onlycurrent == 'true') {
                //add parameters to an array
                array_push($where, ["contracts.ContractStartDate", '<=', $currentDateTime]);
                array_push($where, ["contracts.ContractEndDate", '>=', $currentDateTime]);

                //if no where clauses, select all employees
                if (!count($where) > 0) array_push($where, ["contracts.EmployeeID", '>', 0]);

                //fetch and return the result
                $result = $this->db->table('contracts')->selection(['ContractStartDate', 'ContractEndDate', 'PayRate', 'WeeklyHours'])->innerjoin('departmentmemberlist', 'EmployeeID')->distinct()->where($where)->get();
                var_dump($onlycurrent);
                return (array)$result;
            }
            //return expired contracts
            if ($onlycurrent == 'false') {
                //add parameters to an array
                array_push($where, ["contracts.ContractEndDate", '<=', $currentDateTime]);

                //if no where clauses, select all employees
                if (!count($where) > 0) array_push($where, ["contracts.EmployeeID", '>', 0]);

                //fetch and return the result
                $result = $this->db->table('contracts')->selection(['ContractStartDate', 'ContractEndDate', 'PayRate', 'WeeklyHours'])->innerjoin('departmentmemberlist', 'EmployeeID')->distinct()->where($where)->get();

                return (array)$result;
            }
        }

        //Show only contracts for object-employee
        if ( ( ! $this->manager) AND ( $employeeid !=$this->employee ) ) throw new NotAuthorizedException("Can only be viewed by a manager or the object employee");
        $response = (array)$this->db->table('contracts')->where(['contracts.EmployeeID','=',$employeeid])->get();
        if (isset( $response )) {
            return (array)$response;
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
        //check of het op /employees gebeurd
        if (isset($params['itemid'])) throw new BadRequestException('Contracts can only be created at top-level endpoint /contracts');
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
        if ($contractCreated !== true) throw new BadRequestException( ["Could not create contract"] );
    }
    public function put (array $body, array $params) :array {
        if (( ! $this->manager) AND (! ($this->employee == $params['itemid'] ) ) ) throw new NotAuthorizedException("Employees can only be updated by a manager, or the object employee");
        if (!isset( $params['itemid']) ) throw new TeapotException("Employees can only be updated at employee-specific endpoints");
        //validate body
        $this->validatePostRequest($body);
        $response = [];
        $optional = ['DocumentNumberID','Email',"FirstName", "LastName", "PhoneNumber", "Street", "HouseNumber","City", "PostalCode", "DateOfBirth", "FunctionTypeID","DepartmentID"];
        //check if departmentID must be altered, and if so do it
        if(isset($body['DepartmentID'])) {
            $dpResult = $this->db->table("departmentmembertypes")->update(['DepartmentID'], ['EmployeeID', "=", $params['itemid']]);
            if ($dpResult !== true) throw new BadRequestException("Could not update department");
            unset($body['DepartmentID']);
        }
        var_dump($params['itemid']);
        $empResult = $this->db->table('employees')->update($body,['EmployeeID','=',$params['itemid']]);
        if ($empResult !== true) throw new BadRequestException("Could not update Employee");
        return ["Employee Updated Succesfully"];
    }
    public function delete (array $body, array $params) :array{
        throw new TeapotException("Employees can not be deleted. Update current contract to alter end-date");
        return [];
    }

    //private functies ter ondersteuning vd public functies

    /**
     * @param int $itemID
     * @return array
     * @throws NotAuthorizedException
     */
    private function returnDepartmentEmployees(int $itemID): array
    {
        if (! $this->manager) throw new NotAuthorizedException("Can only be viewed by a manager");
        return (array)$this->db->table('employees')->innerjoin('departmentmemberlist','EmployeeID')->where(['DepartmentID','=',$itemID])->get();
    }

    /**
     * @param int $itemID
     * @return array
     * @throws NotAuthorizedException
     */
    private function returnSingleItem(int $employeeid): array
    {
        if ( ( ! $this->manager) AND ( $employeeid !=$this->employee ) ) throw new NotAuthorizedException("Can only be viewed by a manager or the object employee");
        $response = (array)$this->db->table('contracts')->where(['contracts.EmployeeID','=',$employeeid])->get();
        if (isset( $response )) {
            return (array)$response[0];
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
        if (isset($request['FunctionTypeID'])) if ( ! preg_match ( '/[0-9]{1,11}$/', $request['FunctionTypeID'] )) throw new BadRequestException("Functiontype must be integer");
        if (isset($request['DepartmentID'])) if ( ! preg_match ( '/[0-9]{1,11}$/', $request['DepartmentID'] )) throw new BadRequestException("DepartmentID must be integer");
        if (isset($request['PhoneNumber'])) if ( ! preg_match ( '/\+[0-9]{11}$/', $request['PhoneNumber']) ) throw new BadRequestException("PhoneNumber must be like +31612345678");
        if (isset($request['DateOfBirth'])) if ( ! preg_match ( '/[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $request['DateOfBirth'])) throw new BadRequestException("DateOfBirth must be like YYYY-MM-DD");
        foreach($requiredString as $key)
        {
            if (isset($request[$key])) if ( ! preg_match ( '/[A-z]+$/', $request[$key]) ) throw new BadRequestException("$key can only contain upper- and lowercase letters");
        }
        foreach($requiredAlphaNum as $key)
        {
            if (isset($request[$key])) if ( ! preg_match ( '/[A-z0-9]+$/', $request[$key]) ) throw new BadRequestException("$key can only contain alphanumeric symbols");
        }
        $emailRegex = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';
        if (isset($request["Email"])) if (!preg_match( $emailRegex, $request['Email'])) throw new BadRequestException("Email adres is not a valid e-mail");
        if (isset($request["Email"])) if ($this->db->table("employees")->where(["Email","=",$request['Email']])->count() > 0) throw new BadRequestException("Emailadres already in database");
    }

}