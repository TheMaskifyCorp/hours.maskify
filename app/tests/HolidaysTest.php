<?php

namespace API;
require_once "/opt/lampp/htdocs/contracts/hours.maskify/app/tests/init.php";
use PHPUnit\Framework\TestCase;

class HolidaysTest extends TestCase
{

    public function test__construct()
    {
        //should get all entries in contracts table, the installer creates 50 contracts by default, therefore an array with 50 or more objects should be returned.
//        /** @var TYPE_NAME $holiday */
        $holiday = new Holidays(1, true);
        //Result from get should be of type array
        $this->assertIsArray($holiday->get([],[]));
        //The returned array should contain 1 or more objects. Note: this will fail if the database is generated without dummy data.
        $this->assertGreaterThanOrEqual(1, $holiday->get([],[]));
        //The array should contain all columnnames from the database as array keys.
        $resultArray = $holiday->get([],[]);
        $EmployeeID = $resultArray[0];
        $this->assertObjectHasAttribute('EmployeeID',$EmployeeID);
        $HolidayStartDate = $resultArray[1];
        $this->assertObjectHasAttribute('HolidayStartDate',$HolidayStartDate);
        $HolidayEndDate = $resultArray[2];
        $this->assertObjectHasAttribute('HolidayEndDate',$HolidayEndDate);
        $TotalHoursInMinutes = $resultArray[3];
        $this->assertObjectHasAttribute('TotalHoursInMinutes',$TotalHoursInMinutes);
        $HolidaysAccorded = $resultArray[4];
        $this->assertObjectHasAttribute('HolidaysAccorded',$HolidaysAccorded);
        $AccordedByManager = $resultArray[5];
        $this->assertObjectHasAttribute('AccordedByManager',$AccordedByManager);

        //Tests if the method get is implemented in the class Contracts
        $checkmethods = method_exists($holiday, 'get');
        $this->assertTrue($checkmethods);

        //Tests if the method post is implemented in the class Contracts
        $checkmethods = method_exists($holiday, 'post');
        $this->assertTrue($checkmethods);

        //Tests if the method put is implemented in the class Contracts
        $checkmethods = method_exists($holiday, 'put');
        $this->assertTrue($checkmethods);

        //Tests if the method delete is implemented in the class Contracts
        $checkmethods = method_exists($holiday, 'delete');
        $this->assertTrue($checkmethods);

        //Tests if the method delete is implemented in the class Contracts
        $checkmethods = method_exists($holiday, 'delete');
        $this->assertTrue($checkmethods);

        //Tests if the method validateEndpoint is implemented in the class Contracts
        $checkmethods = method_exists($holiday, 'validateEndpoint');
        $this->assertTrue($checkmethods);

        //Tests if the method validateEndpoint is implemented in the class Contracts
        $checkmethods = method_exists($holiday, 'validateEndpoint');
        $this->assertTrue($checkmethods);
    }

    public function testGet()
    {
        $holiday = new Holidays(1, true);
        //Result from get should be of type array
        $this->assertIsArray($holiday->get([],[]));
        //The returned array should contain 1 or more contracts for employee 1
        $this->assertGreaterThanOrEqual(1, $holiday->get([],[]));
        //Check if the result contains all of the columns present in the Contracts table
        $resultArray = $holiday->get([],[]);
        $arraystring = json_encode($resultArray);
        $EmployeeID = 'EmployeeID';
        $HolidayStartDate = 'HolidayStartDate';
        $HolidayEndDate = 'HolidayEndDate';
        $TotalHoursInMinutes = 'TotalHoursInMinutes';
        $HolidaysAccorded = 'HolidaysAccorded';
        $AccordedByManager = 'AccordedByManager';
        $this->assertStringContainsStringIgnoringCase($EmployeeID,$arraystring);
        $this->assertStringContainsStringIgnoringCase($HolidayStartDate,$arraystring);
        $this->assertStringContainsStringIgnoringCase($HolidayEndDate,$arraystring);
        $this->assertStringContainsStringIgnoringCase($TotalHoursInMinutes,$arraystring);
        $this->assertStringContainsStringIgnoringCase($HolidaysAccorded,$arraystring);
        $this->assertStringContainsStringIgnoringCase($AccordedByManager,$arraystring);

    }
}
