<?php

namespace API;
require_once "/opt/lampp/htdocs/contracts/hours.maskify/app/tests/init.php";
use PHPUnit\Framework\TestCase;

class HoursTest extends TestCase
{

    public function test__construct()
    {
        //should get all entries in contracts table, the installer creates 50 contracts by default, therefore an array with 50 or more objects should be returned.
//        /** @var TYPE_NAME $hour */
        $hour = new Hours(1, true);
        //Result from get should be of type array
        $this->assertIsArray($hour->get([],[]));
        //The returned array should contain 1 or more objects. Note: this will fail if the database is generated without dummy data.
        $this->assertGreaterThanOrEqual(1, $hour->get([],[]));
        //The array should contain all columnnames from the database as array keys.
        $resultArray = $hour->get([],[]);
        $EmployeeHoursID = $resultArray[0];
        $this->assertObjectHasAttribute('EmployeeHoursID',$EmployeeHoursID);
        $EmployeeID = $resultArray[1];
        $this->assertObjectHasAttribute('EmployeeID',$EmployeeID);
        $HoursAccorded = $resultArray[2];
        $this->assertObjectHasAttribute('HoursAccorded',$HoursAccorded);
        $AccordedByManager = $resultArray[3];
        $this->assertObjectHasAttribute('AccordedByManager',$AccordedByManager);
        $DeclaratedDate = $resultArray[4];
        $this->assertObjectHasAttribute('DeclaratedDate',$DeclaratedDate);
        $EmployeeHoursQuantityInMinutes = $resultArray[5];
        $this->assertObjectHasAttribute('EmployeeHoursQuantityInMinutes',$EmployeeHoursQuantityInMinutes);

        //Tests if the method get is implemented in the class Contracts
        $checkmethods = method_exists($hour, 'get');
        $this->assertTrue($checkmethods);

        //Tests if the method post is implemented in the class Contracts
        $checkmethods = method_exists($hour, 'post');
        $this->assertTrue($checkmethods);

        //Tests if the method put is implemented in the class Contracts
        $checkmethods = method_exists($hour, 'put');
        $this->assertTrue($checkmethods);

        //Tests if the method delete is implemented in the class Contracts
        $checkmethods = method_exists($hour, 'delete');
        $this->assertTrue($checkmethods);

        //Tests if the method delete is implemented in the class Contracts
        $checkmethods = method_exists($hour, 'delete');
        $this->assertTrue($checkmethods);

        //Tests if the method validateEndpoint is implemented in the class Contracts
        $checkmethods = method_exists($hour, 'validateEndpoint');
        $this->assertTrue($checkmethods);

        //Tests if the method validateEndpoint is implemented in the class Contracts
        $checkmethods = method_exists($hour, 'validateEndpoint');
        $this->assertTrue($checkmethods);
    }

    public function testGet()
    {
        $hour = new Hours(1, true);
        //Result from get should be of type array
        $this->assertIsArray($hour->get([],[1]));
        //The returned array should contain 1 or more contracts for employee 1
        $this->assertGreaterThanOrEqual(1, $hour->get([],[1]));
        //Check if the result contains all of the columns present in the Contracts table
        $resultArray = $hour->get([],[1]);
        $arraystring = json_encode($resultArray);
        $EmployeeHoursID = 'EmployeeHoursID';
        $EmployeeID = 'EmployeeID';
        $HoursAccorded = 'HoursAccorded';
        $AccordedByManager = 'AccordedByManager';
        $DeclaratedDate = 'DeclaratedDate';
        $EmployeeHoursQuantityInMinutes = 'EmployeeHoursQuantityInMinutes';
        $this->assertStringContainsStringIgnoringCase($EmployeeHoursID,$arraystring);
        $this->assertStringContainsStringIgnoringCase($EmployeeID,$arraystring);
        $this->assertStringContainsStringIgnoringCase($HoursAccorded,$arraystring);
        $this->assertStringContainsStringIgnoringCase($AccordedByManager,$arraystring);
        $this->assertStringContainsStringIgnoringCase($DeclaratedDate,$arraystring);
        $this->assertStringContainsStringIgnoringCase($EmployeeHoursQuantityInMinutes,$arraystring);

    }
}
