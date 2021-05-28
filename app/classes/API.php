<?php

namespace API;

class API
{
    protected object $db;
    protected string $endpoint;
    protected string $request;
    protected array $body;
    protected array $params;
    protected int $department;
    protected int $itemID;
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

    public function department(string $department) : self
    {
        $this->department = $department;
        return $this;
    }
    public function itemID(string $itemID): self
    {
        $this->itemID = $itemID;
        return $this;
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

    public function execute()
    {
     $endpoint = new $this->endpoint($this->requesterID,$this->manager);
     //$endpoint = new Employee(1,true)
     if ( $this->itemID > 0 ) {
        $this->body['itemid'] = $this->itemID;
     }
     if ( $this->department > 0 ) {
            $this->body['departmentid'] = $this->department;
     }
     return $endpoint->{$this->request}($this->body);
    }

    public function validateGet(array $get)
    {
        //validate the passed in query-parameters
        foreach ($get as $param => $value)
        {
            switch ($param){
                case "apipath":
                    break;
                case "departmentid":
                    //max length of the parameter is 15
                    if ( strlen((string)$value)>15 )
                    {
                        throw new BadRequestException("DepartmentID cannot exceed 15 characters");
                    }

                    //parameter must be an integer
                    if ( ! preg_match('/^[0-9]{0,15}$/', $value ) ) {
                        throw new BadRequestException("DepartmentID must be an integer");
                    }

                    //parameter must be existing department
                    $departments = $this->db->table("departmenttypes")->get();
                    $numbers = [];
                    foreach ($departments as $obj)
                    {
                        array_push($numbers,$obj->DepartmentID);
                    }
                    if ( ! in_array ( $value,$numbers ) )
                    {
                        throw new NotFoundException("DepartmentID '$value' does not exist");
                    }
                    break;
                //throw bad request if the parameter does not exist
                default:
                    throw new BadRequestException("Parameter '$param' is not valid");
            }
        }
    }
}