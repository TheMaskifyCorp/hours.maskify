<?php

namespace API;

class API
{
    protected string $endpoint;
    protected string $request;
    protected string $body;
    protected int $department;
    protected int $itemID;

    public function __construct()
    {
        $this->department = 0;
        $this->itemID = 0;
    }

    public function department(string $department)
    {
        $this->department = $department;
        return $this;
    }
    public function itemID(string $itemID)
    {
        $this->itemID = $itemID;
        return $this;
    }
    public function endpoint(string $endpoint)
    {
        $this->endpoint = "API\\".ucfirst($endpoint);
        return $this;
    }
    public function request(string $request)
    {
        $this->request = $request;
        return $this;
    }
    public function body(string $body)
    {
        $this->body = $body;
        return $this;
    }

    public function execute()
    {
     $endpoint = new $this->endpoint(1,false);
     if($this->department > 0){
         $response = $endpoint->{$this->request}("D",$this->department);
     } elseif ($this->itemID > 0)
     {
         $response = $endpoint->{$this->request}("",$this->itemID);
     } else
     $response = $endpoint->{$this->request}("",0);
     return $response;
    }

}