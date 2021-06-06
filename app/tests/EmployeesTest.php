<?php

namespace PHPUnit\Test;

use API\API;
use PHPUnit\Framework\TestCase;
use Firebase\JWT\JWT;

class EmployeesTest extends TestCase
{
    public function testGet()
    {
        $token = array (
            'eid' => 1,
            'manager' => true,
            'iat' => time()
        );
        $jwt = JWT::encode($token, $_ENV['JWTSECRET']);
        $params = ['employeeid'=>'1'];
        $body = [];
        $api = new API($jwt);
        $result = $api->endpoint("Employees")->body($body)->params($params)->request("get")->execute();
        $this->assertArrayHasKey("EmployeeID",$result);
    }
}
