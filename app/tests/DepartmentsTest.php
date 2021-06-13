<?php

namespace API;
require_once "/opt/lampp/htdocs/contracts/hours.maskify/app/tests/init.php";
use PHPUnit\Framework\TestCase;

class DepartmentsTest extends TestCase
{
    public function test__construct()
    {
        //should get all entries in contracts table, the installer creates 50 contracts by default, therefore an array with 50 or more objects should be returned.
//        /** @var TYPE_NAME $department */
        $department = new Departments(1, true);
        //Result from get should be of type array
        $this->assertIsArray($department->get([],[]));
        //The returned array should contain 6 or more objects. Note: this will fail if the database is generated without dummy data.
        $this->assertGreaterThanOrEqual(6, $department->get([],[]));
        //The array should contain all columnnames from the database as array keys.
        $resultArray = $department->get([],[1]);
        $DepartmentID = $resultArray[0];
        $this->assertObjectHasAttribute('DepartmentID',$DepartmentID);
        $Description = $resultArray[1];
        $this->assertObjectHasAttribute('Description',$Description);

        //Tests if the method get is implemented in the class Contracts
        $checkmethods = method_exists($department, 'get');
        $this->assertTrue($checkmethods);

        //Tests if the method post is implemented in the class Contracts
        $checkmethods = method_exists($department, 'post');
        $this->assertTrue($checkmethods);

        //Tests if the method put is implemented in the class Contracts
        $checkmethods = method_exists($department, 'put');
        $this->assertTrue($checkmethods);

        //Tests if the method delete is implemented in the class Contracts
        $checkmethods = method_exists($department, 'delete');
        $this->assertTrue($checkmethods);

        //Tests if the method delete is implemented in the class Contracts
        $checkmethods = method_exists($department, 'delete');
        $this->assertTrue($checkmethods);

        //Tests if the method validateEndpoint is implemented in the class Contracts
        $checkmethods = method_exists($department, 'validateEndpoint');
        $this->assertTrue($checkmethods);

        //Tests if the method validateEndpoint is implemented in the class Contracts
        $checkmethods = method_exists($department, 'validateEndpoint');
        $this->assertTrue($checkmethods);
    }

    public function testGet()
    {
        $department = new Departments(1, true);
        //Result from get should be of type array
        $this->assertIsArray($department->get([],[1]));
        //The returned array should contain 1 or more contracts for employee 1
        $this->assertGreaterThanOrEqual(1, $department->get([],[]));
        //Check if the result contains all of the columns present in the Contracts table
        $resultArray = $department->get([],[1]);
        $arraystring = json_encode($resultArray);
        $DepartmentID = 'DepartmentID';
        $Description = 'Description';
        $this->assertStringContainsStringIgnoringCase($DepartmentID,$arraystring);
        $this->assertStringContainsStringIgnoringCase($Description,$arraystring);


    }
}
