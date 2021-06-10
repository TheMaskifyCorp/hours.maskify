<?php

namespace API;


use Database;

class Faq extends Endpoint implements ApiEndpointInterface
{
    /*
     * protected int $employee;
     * protected bool $manager;
     * protected object $db;
     */

    /**
     * @param array $body
     * @param array $params
     * @return array
     * @throws DatabaseConnectionException
     */
    public function get(array $body, array $params): array
    {
        extract($params);
        // return all FAQ-articles on general endpoint
        if (isset ($solutionid))
            return $this->db->table("faq")->where(['SolutionID', '=', $solutionid])->get();
        if (!isset($searchterm))
            return $this->db->table("faq")->get();
        //check if the searchterm is in database
        $searchterm = preg_replace('/%20/'," ", $searchterm);
        if (!$this->db->table('searchresults')->exists(['SearchTerm' => $searchterm])) {
            // if not in database, fake it: searchterm is added
            $insert = ['SearchTerm' => $searchterm, 'SearchTermCounter' => 1];
            try {
                $this->db->table('searchresults')->insert($insert);
            } catch (\Exception $e) {
                throw new DatabaseConnectionException();
            }
        } else {
            try {
                $this->db->table('searchresults')->increment('SearchTermCounter', ["SearchTerm", "=", $searchterm]);
            } catch (\Exception $e) {
                throw new DatabaseConnectionException();
            }
        }
        $searchtermResult = $this->db->table('searchresults')->where(['SearchTerm', '=', $searchterm])->first();
        //if searchterm is connected to a solution, also return the title and content of the solution
        if ($searchtermResult->SolutionID <> null) {
            $result = $this->db->table("searchresults")->innerJoin("faq", "SolutionID")->where(['searchresults.SolutionID', '=', $searchtermResult->SolutionID])->first();
        } else {
            $result = $searchtermResult;
        }
        return (array)$result;
    }

    public function put(array $body, array $params): array
    {
        // TODO: Implement put() method.
    }

    public function post(array $body, array $params): array
    {
        // TODO: Implement post() method.
    }

    public function delete(array $body, array $params): array
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param array $apipath
     * @return array|null
     * @throws BadRequestException
     */
    public static function validateEndpoint(array $apipath): ?array
    {
        $db = new Database;
        if (count($apipath) > 2)
            throw new BadRequestException('Searchterm cannot contain a slash');
        if (!isset($apipath[1]))
            return null;
        $searchTerm = preg_replace('/%20/',' ',$apipath[1]);
        if (!preg_match('/^[A-z0-9\s]+$/', $searchTerm))
            throw new BadRequestException('Searchterm cannot contain special characters');
        return ['searchterm' => $searchTerm];
    }

    /**
     * @param array $get
     * @throws BadRequestException
     * @throws NotFoundException
     */
    public static function validateGet(array $get)
    {
        $db = new Database;
        foreach ($get as $UCparam => $value) {
            $param = strtolower($UCparam);
            switch ($param) {
                case "searchterm":
                    break;
                case "solutionid":
                    if (!$db->table("faq")->exists(['SolutionID' => $value]))
                        throw new NotFoundException("Solution with ID $value not found");
                    break;
                default:
                    throw new BadRequestException("Parameter $UCparam is not valid for this endpoint");
            }
        }
    }
}