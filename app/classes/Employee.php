<?php

class Employee
{
    protected $db;
    protected $EmployeeID;
    protected $FirstName;
    protected $LastName;
    protected $Email;
    protected $PhoneNumber;
    protected $Street;
    protected $City;
    protected $DateOfBirth;
    protected $PostalCode;
    protected $FunctionTypeID;
    protected $PayRate;
    protected $DocumentNumberID;
    protected $IDfile = NULL;
    protected $StartOfContract;
    protected $EndOfContract;
    protected $OutOfContract;
    protected $DepartmentID = array();

    public function __construct($id,$db)
    {
        $this->db = $db;
        $result = $this->db->table('Employees')->where("EmployeeID", "=", "$id")->first();
        $result = (array)$result;
        foreach ($result as $key => $value) {
            $this->$key = $result[$key];
        }
        $result = $this->db->table('DepartmentMemberList')->selection(['DepartmentID'])->where("EmployeeID", "=", "$id")->get();
        foreach ($result as $key => $value){
            $dep = $result[$key]->DepartmentID;
            $this->DepartmentID[] = $dep;
        }
    }
    public function getManager()
    {
        $managers = $this->db->table('Employees')->selection(['EmployeeID'])->where("FunctionTypeID","=","3")->get();
        foreach($managers as $man){
            $id = $man->EmployeeID;
            $manager = new Employee($id,$this->db);
            if (in_array($this->DepartmentID[0],$manager->DepartmentID)){
                return $id;
            }
        }
        return false;
    }
}