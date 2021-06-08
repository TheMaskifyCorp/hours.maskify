<?php


namespace API;
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once "ApiEndpointInterface.php";


class Departments extends Endpoint implements ApiEndpointInterface
{
    /**
     * @param array $body
     * @param array $params
     * @return array
     * @throws NotAuthorizedException
     */

    public function get(array $body, array $params): array
    {
        if (!$this->manager)  throw new NotAuthorizedException("This request can only be performed by a manager");
        //return departmentid + description
        if (isset($params['departmentid'])) {
            $result = (array)$this->db->table('departmenttypes')->selection(['DepartmentID', 'Description'])->where(['DepartmentID', '=', $params['departmentid']])->get();
            return (array)$result;
        }
        if (!isset($params['departmentid'])) {
            $result = (array)$this->db->table('departmenttypes')->selection(['DepartmentID', 'Description'])->get();
            return (array)$result;
        }
    }

    public function post(array $body, array $params) :array
    {
        if (!$this->manager)  throw new NotAuthorizedException("This request can only be performed by a manager");
        if(isset($body["Description"]) AND (is_string($body["Description"])!== true))
            throw new BadRequestException("Description must be of type string and cannot be null");
        if(isset($body["DepartmentID"]))
        {
            $required = ["DepartmentID", "Description"];
            $missingParams = [];
            $requestParams = [];
            foreach($required as $value)
            {
                if ( ! array_key_exists ( $value, $body ) ) {
                    array_push($missingParams, $value." is required");
                } else {
                    $requestParams[$value] = $body[$value];
                }
            }
            $departmentCreated = $this->db->table('departmenttypes')->insert($requestParams);

            // throw error if employee is not created
            if ($departmentCreated !== true) throw new BadRequestException( "Could not create department" );
            return ["New department created"];
        }
    }

    public function put (array $body, array $params) :array
    {

        if (!$this->manager) throw new NotAuthorizedException("This request can only be performed by a manager");

            //check if all required parameters are set
            $requiredBodyParam = ["DepartmentID", "Description"];
            foreach ($requiredBodyParam as $param) {
                if (!isset($body[$param])) throw new BadRequestException("Body does not contain required parameter '$param'");
            }

            //move departmentid and description from body to where-clause
            $where = [];
            $where = ['DepartmentID', '=', $body['DepartmentID'], 'Description', '=', $body['Description']];

            //execute request
            try {
                $this->db->table('departmenttypes')->update($body, $where);
            } catch (\Exception $e) {
                throw new BadRequestException("Error updating record in database");
            }
            //response
            return [$where[5] . " updated"];

    }

    public function delete (array $body, array $params) :array
    {
        //check if department id param is set
        if (! isset ( $params['departmentid'] ))
            throw new BadRequestException('departmentid is not set');

        //check if user is manager
        if ( !$this->manager)
            throw new NotAuthorizedException('This request can only be performed by a manager');

        $departmentid = $params['departmentid'];
        $where = [];
        array_push($where, ["departmenttypes.DepartmentID", '=', $departmentid]);
        $this->db->table('departmenttypes')->delete($where);
        return (['department deleted']);

    }
//
    public static function validateEndpoint(array $apipath): ?array
    {
        if (count($apipath) > 2) throw new BadRequestException("Endpoint could not be validated");
        if ((isset ($apipath[1])) and (preg_match('/[0-9]+/', $apipath[1])))
            return ['departmentid' => $apipath[1]];
        return null;
    }

    public static function validateGet(array $get)
    {
        $db = new \Database;
        foreach ($get as $UCparam => $value) {
            $param = strtolower($UCparam);
            switch ($param) {
                case "DepartmentID":
                    if (strlen((string)$value) > 15)
                        throw new BadRequestException("DepartmentID cannot exceed 15 characters");

                    //parameter must be an integer
                    if (!preg_match('/^[0-9]{0,15}$/', $value))
                        throw new BadRequestException("DepartmentID must be an integer");

                    //parameter must be existing department
                    //ps: departments always have employees because employeeID is part of the PK of departmenttypes,
                    // I'll pretend I did not read the word 'active', what is the use case for this again?
                    if (!$db->table('departmenttypes')->exists(['DepartmentID' => $value]))
                        throw new NotFoundException("DepartmentID not found");
                    break;

                case "Description":
                    if (strlen((string)$value) > 15)
                        throw new BadRequestException("Description cannot exceed 15 characters");

                    //parameter must be an integer
                    if (is_string($value))
                        throw new BadRequestException("Description must be an string");

                    //parameter must be existing department
                    if (!$db->table('departmenttypes')->exists(['DepartmentID' => $value]))
                        throw new NotFoundException("Description not found");
                    break;

            }
        }
    }
}
