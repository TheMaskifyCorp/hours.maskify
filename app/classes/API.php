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

    public function validateGet(array $get)
    {
        //validate the passed in query-parameters
        foreach ($get as $UCparam => $value)
        {
            $param = strtolower($UCparam);
            switch ($param){
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
                case "onlycurrent" :
                    if (($value !== "true") OR ($value !== "false"))
                    {
                        throw new BadRequestException("$UCparam must be true or false");
                    }
                    break;
                default:
                    throw new BadRequestException("Parameter '$UCparam' is not valid");
            }
        }
    }
}