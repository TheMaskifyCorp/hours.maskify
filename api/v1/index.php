<?php

use Firebase\JWT\JWT;

require_once $_SERVER['DOCUMENT_ROOT']."/app/init.php";
/**
 * @var string $docRoot
 * @var Auth $auth
 * @var Database $db
 */

//gather the request method
$httpMethod = strtolower($_SERVER['REQUEST_METHOD']);

//check if an endpoint is given
if (isset($_GET['apipath']))
{
    $apipath = $_GET['apipath'];
}
else
{
    header("Location: /");
}

//capture the body
$body = file_get_contents('php://input');
// URL naar variabelen omzetten door te splitsen op '/'
$apiVars=explode('/',$apipath);

try {
    //als endpoint NIET de faq is, moet je validaten
    if(strtolower($apiVars[0]) == "faq" && !isset($_SERVER['HTTP_AUTHORIZATION'])){
        $jwt = "noToken";
    }else{
        /*
         * BEGIN VALIDATION OF JWT TOKEN
         */
        //check of een token is meegestuurd
        if (! isset($_SERVER['HTTP_AUTHORIZATION']))
            throw new API\NotAuthorizedException('Token not found');

        if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches))
            throw new API\NotAuthorizedException('Token not found');

        $jwt = ($matches[1]);


        if (! $jwt)
        // No token was able to be extracted from the authorization header
            throw new API\NotAuthorizedException('Token not found');

        //controleer of het JWT token valide is
        try{
            $decoded = JWT::decode($jwt,$_ENV['JWTSECRET'], ['HS256']);
            //als het ouder is dan 1 uur is het niet validx
            if ($decoded->iat < (time()-3600)) throw new Exception;
        } catch (Exception $e) {
            throw new API\NotAuthorizedException('Token not valid');
        }
    }
    /*
     * BEGIN VALIDATION OF ENDPOINT AND PARAMETERS
     *
     */

    //controleer of het endpoint bestaat
    $endpoint = "API\\".ucfirst( strtolower( $apiVars[0] ) );
    if ( ! class_exists ($endpoint) ) throw new API\NotFoundException("Endpoint does not exists");

    //controleer of de body valide json is
    if ( ( json_decode($body,true) === NULL) AND (strlen($body) > 0)) {
        throw new API\BadRequestException("Body is not json-formatted correctly");
    } elseif (strlen($body == false)) {
        $body = [];
    } else $body = json_decode($body,true);

    //create objects for resolving
    $api = new API\API($jwt);
    //validate all parameters
    unset( $_GET [ 'apipath' ] ) ;

    $endpoint::validateGet($_GET);


    //IMPORTANT
    //TODO GET ITEMID BACK


    //convert all get vars to lowercase
    $params = [];
    foreach($_GET as $key => $value)
    {
        $key = strtolower($key);
        $params[$key] = ($value);
    }

    $endpointParams = $endpoint::validateEndpoint($apiVars);
    if(isset($endpointParams) > 0) {
        foreach ($endpointParams as $key => $value) {
            $params[$key] = $value;
        }
    }
    /*
     * Execute the request
     */

    $result = $api->endpoint($endpoint)->request($httpMethod)->body($body)->params($params)->execute();
    //create the response
    $response =
        [
            "response" => $result,

            "success" => true,
            "status" => 200
        ];

} catch (Exception $e){
    //create the response if error was thrown in the proces

    $error = (method_exists( $e,"getError")) ? $e->getError() : "Generic error";
    $response =
        [
            "response" =>
                [
                    "message" =>  $e->getMessage() ,
                    "error" =>  $error
                ],
            "success" => false,
            "status" => $e->getCode()
        ];
}
?>

<?php
//print the response
//http_response_code($statusCode);
header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT );
?>
