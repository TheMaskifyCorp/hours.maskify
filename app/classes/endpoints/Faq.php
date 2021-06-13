<?php

namespace API;


use Database;

/**
 * Class Faq
 * @package API
 */
class Faq extends Endpoint implements ApiEndpointInterface
{
    /**
     * @var protected int $employee;
     * @var protected bool $manager;
     * @var protected object $db;
     */

    /**
     * @param array $body
     * @param array $params
     * @return array
     * @throws DatabaseConnectionException
     * @throws NotAuthorizedException
     */
    public function get(array $body, array $params): array
    {
        extract($params);
        // return all FAQ-articles on general endpoint
        // get the searches for the manager page
        if (isset($getsearchresults)){
            if (!$this->manager) throw new NotAuthorizedException("Can only be viewed by a manager");
            return $this->db->table("searchresults")->get();
        }
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
        if (!$this->manager)
            throw new NotAuthorizedException("Editing can only be perfomed by a manager");

    }

    public function post(array $body, array $params): array
    {
        if (!$this->manager && !isset($param['searchterm']))
            throw new NotAuthorizedException("Creation can only be perfomed by a manager");

    }

    /**
     * @throws DatabaseConnectionException
     * @throws BadRequestException
     * @throws NotAuthorizedException
     */
    public function delete(array $body, array $params): array
    {
        if (!$this->manager)
            throw new NotAuthorizedException("Deletion can only be perfomed by a manager.");
        if (!isset($params['searchterm']) && !isset($params['solutionid']))
            throw new BadRequestException("Nothing to delete on general endpoint.");
        if (isset($params['searchterm']) && isset($params['solutionid']))
            throw new BadRequestException("Request is ambiguous; delete Solution OR searchterm.");
        if (isset($params['searchterm'])) {
            $deleteObject = ['SearchTerm','=', $params['searchterm']];
            $table = 'searchresults';
        }
        if (isset($params['solutionid'])){
            $deleteObject = ['SolutionID','=', $params['solutionid'] ];
            $table = 'faq';
        }
        if (!$this->db->table($table)->exists([$deleteObject[0]=>$deleteObject[2]]))
            throw new BadRequestException($deleteObject[0] .": ".$deleteObject[2] . " does not exist");
        try{
            $this->db->table($table)->delete($deleteObject);
        }catch(\Exception $e){
            throw new DatabaseConnectionException();
        }
        $response = $deleteObject[0] .": ".$deleteObject[2] . " deleted";
        return [$response];
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
                case "getsearchresults":
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