<?php

namespace API;


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
        if ( ! isset($searchterm))
            return $this->db->table("faq")->get();
        //check if the searchterm is in database
        if(! $this->db->table('searchresults')->exists(['SearchTerm' => $searchterm]))
        {
            // if not in database, fake it: searchterm is added
            $insert = ['SearchTerm' => $searchterm, 'SearchTermCounter' =>1];
            try
            {
                $this->db->table('searchresults')->insert($insert);
            } catch(\Exception $e){
                Throw new DatabaseConnectionException();
            }
        } else {
            try{
                $this->db->table('searchresults')->increment('SearchTermCounter',["SearchTerm","=",$searchterm]);
            }catch(\Exception $e){
                Throw new DatabaseConnectionException();
            }
        }
        $searchtermResult = $this->db->table('searchresults')->where(['SearchTerm','=',$searchterm])->first();
        //if searchterm is connected to a solution, also return the title and content of the solution
        if ($searchtermResult->SolutionID <> null){
            $result = $this->db->table("searchresults")->innerJoin("faq","SolutionID")->where(['searchresults.SolutionID','=',$searchtermResult->SolutionID])->first();
        } else {
            $result = $searchtermResult;
        }
        return [$result];
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
    public static function validateEndpoint(array $apipath) : ?array
    {
        if (count ($apipath) > 2)
            throw new BadRequestException('Searchterm cannot contain a slash');
        if (! isset($apipath[1]))
            return null;
        if (! preg_match('/^[A-z0-9]+$/',$apipath[1]))
            throw new BadRequestException('Searchterm cannot contain special characters');
        return ['searchterm' => $apipath[1]];
    }

    public static function validateGet(array $get)
    {

    }
}