<?php

namespace API;

class Hours implements ApiEndpointInterface
{
    protected int $employee;
    protected bool $manager;
    protected object $db;

    public function __construct(int $employee, bool $manager)
    {
        $this->employee = $employee;
        $this->manager = $manager;
        $this->db = new \Database;
    }

    public function get(array $body, array $params): array
    {
        if((isset($params['itemid'])) AND (isset($params['departmentid']))) throw new BadRequestException("Cannot Filter single Employee on Departments");
        if((isset($params['[itemid]']))) return $this->db->table('employeehours')->where(['EmployeeHoursID', '=', $params['itemid']])->first();
        return [];
    }

    public function put(array $body, array $params): array
    {
        // TODO: Implement put() method.
        return [];
    }

    public function post(array $body, array $params): array
    {
        // TODO: Implement post() method.
        return [];
    }

    public function delete(array $body, array $params): array
    {
        // TODO: Implement delete() method.
        return [];
    }
}