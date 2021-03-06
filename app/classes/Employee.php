<?php

/**
 * Class Employee
 */
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

    /**
     * Employee constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->db = new Database;
        $result = $this->db->table('employees')->where(["EmployeeID", "=", "$id"])->first();
        $result = (array)$result;
        foreach ($result as $key => $value) {
            $this->$key = $value;
        }
        $result = $this->db->table('departmentmemberlist')->selection(['DepartmentID'])->where(["EmployeeID", "=", "$id"])->get();
        foreach ($result as $key => $value){
            $dep = $value->DepartmentID;
            $this->DepartmentID[] = $dep;
        }
    }

    /**
     * @return mixed
     */
    public function getDepartment(){
        $id = $this->db->table('departmentmemberlist')->selection(['DepartmentID'])->where(['EmployeeID','=',$this->EmployeeID])->first();
        return $id->DepartmentID;
    }

    /**
     * @return false
     */
    public function getManager(): bool
    {
        $managers = $this->db->table('employees')->selection(['employees.EmployeeID, departmentmemberlist.DepartmentID'])->innerJoin('departmentmemberlist','EmployeeID')->where(["FunctionTypeID",">","1"])->get();
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

    /**
     * @return mixed
     */
    public function getPassword()
    {
        $credentials = $this->db->table('logincredentials')->where(['EmployeeID','=',$this->EmployeeID])->first();
        return $credentials->Password;
    }
}