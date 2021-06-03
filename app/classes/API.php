<?php

namespace API;

class API
{
    protected object $db;
    protected string $endpoint;
    protected string $request;
    protected array $body;
    protected array $params;
    protected int $requesterID;
    protected bool $manager;

    public function __construct($JWT)
    {
        $decoded = \Firebase\JWT\JWT::decode($JWT,$_ENV['JWTSECRET'], ['HS256']);

        $this->db = new \Database;

        $this->manager = $decoded->manager;
        $this->requesterID = $decoded->eid;
        $this->department = 0;
        $this->itemID = 0;
    }


    public function endpoint(string $endpoint) : self
    {
        $this->endpoint = $endpoint;
        return $this;
    }
    public function request(string $request) : self
    {
        $this->request = $request;
        return $this;
    }
    public function body(array $body)
    {
        $this->body = $body;
        return $this;
    }
    public function params (array $params)
    {
        $this->params = $params;
        return $this;
    }

    public function execute()
    {
        $endpoint = new $this->endpoint($this->requesterID,$this->manager);
        return $endpoint->{$this->request}($this->body, $this->params);
    }
//TODO Delete commented section
/*    public function validateGet(array $get)
    {
        //validate the passed in query-parameters
        foreach ($get as $UCparam => $value)
        {
            $param = strtolower($UCparam);
            switch ($param){
                case "employeeid":
                    //parameter cannot exceed length 15
                    if ( strlen((string)$value)>15 )
                        throw new BadRequestException("EmployeeID cannot exceed 15 characters");

                    //parameter must be an integer
                    if ( ! preg_match('/^[0-9]{0,15}$/', $value ) )
                        throw new BadRequestException("EmployeeID must be an integer");

                    //parameter must be existing employee
                    if (! $this->exists($value, "EmployeeID","employees")  )
                        throw new NotFoundException("Employee '$value' does not exist");

                    break;
                case "manager":
                    //parameter cannot exceed length 15
                    if ( strlen((string)$value)>15 )
                        throw new BadRequestException("Manager cannot exceed 15 characters");

                    //parameter must be an integer
                    if ( ! preg_match('/^[0-9]{0,15}$/', $value ) )
                        throw new BadRequestException("Managers EmployeeID must be an integer");

                    //parameter must be existing employee
                    if (! $this->exists($value, "EmployeeID","employees")  )
                        throw new NotFoundException("EmployeeID '$value' does not exist");

                    //TODO NON BREAKING: CHECK IF ID BELONGS TO MANAGER
                    break;
                case "departmentid":
                    //parameter cannot exceed length 15
                    if ( strlen((string)$value)>15 )
                        throw new BadRequestException("DepartmentID cannot exceed 15 characters");

                    //parameter must be an integer
                    if ( ! preg_match('/^[0-9]{0,15}$/', $value ) )
                        throw new BadRequestException("DepartmentID must be an integer");

                    //parameter must be existing department
                    if ( ! $this->exists($value,'DepartmentID','departmenttypes') )
                        throw new NotFoundException("DepartmentID '$value' does not exist");

                    break;
                //throw bad request if the parameter does not exist
                case "onlycurrent" :
                    if (($value !== "true") AND ($value !== "false"))
                        throw new BadRequestException("$UCparam must be true or false");
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
                case "employeehoursid":
                    if (! \UUID::is_valid($value) )
                        throw new BadRequestException("EmployeeHoursID must be a valid UUID");
                    if ( ! $this->exists($value,'EmployeeHoursID','employeehours'))
                        throw new NotFoundException("EmployeeHoursID not found");
                    break;
                default:
                    throw new BadRequestException("Parameter '$UCparam' is not valid");
            }
        }
    }*/
    /**
     * @throws BadRequestException
     */
    public function validateEndpoint(array $apipath)
    {
        $ep = strtolower($apipath[0]);
        $path = implode('/',$apipath);
        switch($ep)
        {
            case "employees":
                if (count ($apipath) > 2) throw new BadRequestException("Endpoint $path could not be validated");
                if ((isset ( $apipath[1]) ) AND (preg_match('/[0-9]+/',$apipath[1])))
                    return ['employeeid' => $apipath[1]];
                break;
            case "contracts":
                if (count ($apipath) > 1) throw new BadRequestException("Endpoint $path could not be validated");
                break;
            case "hours":
                if (count ($apipath) > 2) throw new BadRequestException("Endpoint $path could not be validated");

                if ( isset( $apipath [ 1 ] ) ) intval( $apipath [ 1 ] );
                if ( ( isset($apipath[1]) ) AND (! $this->exists($apipath[1],'EmployeeID','employees') ) ) throw new BadRequestException("Employee does not exist");
                if ( isset($apipath[1]) )
                    return ['employeeid' => $apipath[1]];
                break;
            case "departments":
            case "holidays":
            case "sickleave":
                break;
            case "faq":
                if (count ($apipath) > 2)
                    throw new BadRequestException('Searchterm cannot contain a slash');
                if (! preg_match('/^[A-z0-9]+$/'))
                    throw newBadRequestException('Searchterm cannot contain special characters');
                return ['searchterm' => $apipath[1]];
                break;
            default:
                throw new BadRequestException("wat je niet ziet bestaat niet");
        }

    }
    private function exists($id, string $idName, string $table)
    {
        return($this->db->table($table)->where([$idName,"=",$id])->count() > 0 );
    }
}