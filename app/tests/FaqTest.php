<?php

namespace API;
require_once "/opt/lampp/htdocs/contracts/hours.maskify/app/tests/init.php";
use PHPUnit\Framework\TestCase;

class FaqTest extends TestCase
{

    public function test__construct()
    {
        //should get all entries in contracts table, the installer creates 50 contracts by default, therefore an array with 50 or more objects should be returned.
//        /** @var TYPE_NAME $faq */
        $faq = new Faq(1, true);
        //Result from get should be of type array
        $this->assertIsArray($faq->get([],[]));
        //The returned array should contain 1 or more objects. Note: this will fail if the database is generated without dummy data.
        $this->assertGreaterThanOrEqual(1, $faq->get([],[]));
        //The array should contain all columnnames from the database as array keys.
        $resultArray = $faq->get([],[1]);
        $SolutionID = $resultArray[0];
        $this->assertObjectHasAttribute('SolutionID',$SolutionID);
        $FAQContent = $resultArray[1];
        $this->assertObjectHasAttribute('FAQContent',$FAQContent);
        $FAQTitle = $resultArray[2];
        $this->assertObjectHasAttribute('FAQTitle',$FAQTitle);

        //Tests if the method get is implemented in the class Contracts
        $checkmethods = method_exists($faq, 'get');
        $this->assertTrue($checkmethods);

        //Tests if the method post is implemented in the class Contracts
        $checkmethods = method_exists($faq, 'post');
        $this->assertTrue($checkmethods);

        //Tests if the method put is implemented in the class Contracts
        $checkmethods = method_exists($faq, 'put');
        $this->assertTrue($checkmethods);

        //Tests if the method delete is implemented in the class Contracts
        $checkmethods = method_exists($faq, 'delete');
        $this->assertTrue($checkmethods);

        //Tests if the method delete is implemented in the class Contracts
        $checkmethods = method_exists($faq, 'delete');
        $this->assertTrue($checkmethods);

        //Tests if the method validateEndpoint is implemented in the class Contracts
        $checkmethods = method_exists($faq, 'validateEndpoint');
        $this->assertTrue($checkmethods);

        //Tests if the method validateEndpoint is implemented in the class Contracts
        $checkmethods = method_exists($faq, 'validateEndpoint');
        $this->assertTrue($checkmethods);
    }

    public function testGet()
    {
        $faq = new Faq(1, true);
        //Result from get should be of type array
        $this->assertIsArray($faq->get([],[1]));
        //The returned array should contain 1 or more contracts for employee 1
        $this->assertGreaterThanOrEqual(1, $faq->get([],[]));
        //Check if the result contains all of the columns present in the Contracts table
        $resultArray = $faq->get([],[1]);
        $arraystring = json_encode($resultArray);
        $SolutionID = 'SolutionID';
        $FAQContent = 'FAQContent';
        $FAQTitle = 'FAQTitle';
        $this->assertStringContainsStringIgnoringCase($SolutionID,$arraystring);
        $this->assertStringContainsStringIgnoringCase($FAQContent,$arraystring);
        $this->assertStringContainsStringIgnoringCase($FAQTitle,$arraystring);


    }
}
