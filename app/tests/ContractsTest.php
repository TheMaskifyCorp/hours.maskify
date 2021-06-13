<?php

namespace API;
require_once "/opt/lampp/htdocs/contracts/hours.maskify/app/tests/init.php";
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint;


class ContractsTest extends TestCase
{
    public function test__construct()
    {
    //should get all entries in contracts table, the installer creates 50 contracts by default, therefore an array with 50 or more objects should be returned.
        ///** @var TYPE_NAME $contract */
        $contract = new Contracts(1, true);
        //Result from get should of type array
        $this->assertIsArray($contract->get([],[]));
        //The returned array should contain more than 50 objects.
        $this->assertGreaterThanOrEqual(50, $contract->get([],[]));
        //The array should contain all columnnames from the database as array keys.
        $resultArray = $contract->get([],[1]);
        $EmployeeID = $resultArray[0];
        $this->assertObjectHasAttribute('EmployeeID',$EmployeeID);
        $ContractStartDate = $resultArray[1];
        $this->assertObjectHasAttribute('ContractStartDate',$ContractStartDate);
        $ContractEndDate = $resultArray[2];
        $this->assertObjectHasAttribute('ContractEndDate',$ContractEndDate);
        $WeeklyHours = $resultArray[3];
        $this->assertObjectHasAttribute('ContractEndDate',$WeeklyHours);
        $PayRate = $resultArray[4];
        $this->assertObjectHasAttribute('ContractEndDate',$PayRate);

        //Tests if the method get is implemented in the class Contracts
        $checkmethods = method_exists($contract, 'get');
        $this->assertTrue($checkmethods);

        //Tests if the method post is implemented in the class Contracts
        $checkmethods = method_exists($contract, 'post');
        $this->assertTrue($checkmethods);

        //Tests if the method put is implemented in the class Contracts
        $checkmethods = method_exists($contract, 'put');
        $this->assertTrue($checkmethods);

        //Tests if the method delete is implemented in the class Contracts
        $checkmethods = method_exists($contract, 'delete');
        $this->assertTrue($checkmethods);

        //Tests if the method delete is implemented in the class Contracts
        $checkmethods = method_exists($contract, 'delete');
        $this->assertTrue($checkmethods);

        //Tests if the method validateEndpoint is implemented in the class Contracts
        $checkmethods = method_exists($contract, 'validateEndpoint');
        $this->assertTrue($checkmethods);

        //Tests if the method validateEndpoint is implemented in the class Contracts
        $checkmethods = method_exists($contract, 'validateEndpoint');
        $this->assertTrue($checkmethods);
    }

    public function testGet()
    {
        $contract = new Contracts(1, true);
        //Result from get should of type array
        $this->assertIsArray($contract->get([],[1]));
        //The returned array should contain 1 or more contracts for employee 1
        $this->assertGreaterThanOrEqual(1, $contract->get([],[]));
        //Check if the result contains all of the columns present in the Contracts table
        $resultArray = $contract->get([],[1]);
        $arraystring = json_encode($resultArray);
        $EmployeeID = 'EmployeeID';
        $ContractStartDate = 'ContractStartDate';
        $ContractEndDate = 'ContractEndDate';
        $WeeklyHours = 'WeeklyHours';
        $PayRate = 'PayRate';
        $this->assertStringContainsStringIgnoringCase($EmployeeID,$arraystring);
        $this->assertStringContainsStringIgnoringCase($ContractStartDate,$arraystring);
        $this->assertStringContainsStringIgnoringCase($ContractEndDate,$arraystring);
        $this->assertStringContainsStringIgnoringCase($WeeklyHours,$arraystring);
        $this->assertStringContainsStringIgnoringCase($PayRate,$arraystring);

        //Check if $ContractStartDate has the correct date formatting
        $Resultobject = get_object_vars($resultArray[0]);
        $date = $Resultobject['ContractStartDate'];
        $this->assertMatchesRegularExpression('/[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date);


    }

//    public function testDelete()
//    {
//        //could be done with a pdo rowcount function or a PDOquery() which returns the database query and then comparing that to the expected query
//
//    }

//    public function testValidateEndpoint()
//    {
//
//    }
//
//
//    public function testPut()
//    {
//
//
////    Could test this with PDO::query()
//
//    }
//
//    public function testPost()
//    {
//
//    }
//
//    public function testValidateGet()
//    {
//
//    }
}
