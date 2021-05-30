<?php

class Employee
{
    protected $db;
    public    $EmployeeID;
    protected $FirstName;
    protected $LastName;
    protected $Email;
    protected $PhoneNumber;
    protected $Street;
    protected $HouseNumber;
    protected $City;
    protected $DateOfBirth;
    protected $PostalCode;
    protected $FunctionTypeID;
    protected $DocumentNumberID;
    protected $DepartmentID = array();

    public function __construct($id)
    {
        $this->db = new Database;
        $result = $this->db->table('employees')->where("EmployeeID", "=", "$id")->first();
        $result = (array)$result;
        foreach ($result as $key => $value) {
            $this->$key = $result[$key];
        }
        $result = $this->db->table('departmentmemberlist')->selection(['DepartmentID'])->where("EmployeeID", "=", "$id")->get();
        foreach ($result as $key => $value){
            $dep = $result[$key]->DepartmentID;
            $this->DepartmentID[] = $dep;
        }
    }
    /*TODO function is not working*/
    public static function createNewEmployee(array $values){
        $db = new Database;
        try {
            $stmt = $db->table('employees')->insert($values);
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getDepartment(){
        $id = $this->db->table('departmentmemberlist')->selection(['DepartmentID'])->where('EmployeeID','=',$this->EmployeeID)->first();
        return $id->DepartmentID;
    }

    public function getManager()
    {
        $managers = $this->db->table('employees')->selection(['employees.EmployeeID, departmentmemberlist.DepartmentID'])->innerJoin('departmentmemberlist','EmployeeID')->where("FunctionTypeID","=","3")->get();
        $department = $this->getDepartment();
        foreach($managers as $man){
            $id = $man->EmployeeID;
            $manager = new Employee($id);
            if (in_array($department,$manager->DepartmentID)){
                return $id;
            }
        }
        return false;
    }
    public function getPassword()
    {
        $credentials = $this->db->table('logincredentials')->where('EmployeeID','=',$this->EmployeeID)->first();
        return $credentials->Password;
    }
}