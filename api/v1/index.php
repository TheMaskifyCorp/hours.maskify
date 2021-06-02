<?php

require_once $_SERVER['DOCUMENT_ROOT']."/app/init.php";

//gather all request-data
$httpMethod = strtolower($_SERVER['REQUEST_METHOD']);

//check if endpoint is given
if (isset($_GET['apipath']))
{
    $apipath = $_GET['apipath'];
}
else
{
    header("Location: /");
};


//capture the body
$body = file_get_contents('php://input');
// URL naar variabelen omzetten door te splitsen op '/'
$apiVars=explode('/',$apipath);

try {
    /*
     * BEGIN VALIDATION OF JWT TOKEN
     */



    /*
     * START OF LIVE VERSION FOR JWT
     */
    //check of een token is meegestuurd
/*    if (! isset($_SERVER['HTTP_AUTHORIZATION']))
        throw new API\NotAuthorizedException('Token not found');

    if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches))
        throw new API\NotAuthorizedException('Token not found');

    $jwt = $matches[1];*/

    /*
     * END OF LIVE VERSION FOR JWT
     * START OF TEST VERSION FOR JWT
     */
    if (! isset($_SERVER['HTTP_AUTHORIZATION'])) {
        //throw new API\NotAuthorizedException('Token not found');
        $token = array (
            'eid' => 1,
            'manager' => true,
            'iat' => time()
        );
        $matches[1] = Firebase\JWT\JWT::encode($token, $_ENV['JWTSECRET']);
    }
    elseif (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
        throw new API\NotAuthorizedException('Token not found');
    }
    /*
     * END OF TEST VERSION FOR JWT
     */
    $jwt = $matches[1];

    if (! $jwt)
        // No token was able to be extracted from the authorization header
        throw new API\NotAuthorizedException('Token not found');

    //controleer of het JWT token valide is
    try{
        $decoded =\Firebase\JWT\JWT::decode($jwt,$_ENV['JWTSECRET'], ['HS256']);
        //als het ouder is dan 1 uur is het niet valid
        if ($decoded->iat < (time()-3600)) throw new Exception;
    } catch (Exception $e)
    {
        throw new API\NotAuthorizedException('Token not valid');
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

    $api = new API\API($jwt);

    //validate all parameters
    unset( $_GET [ 'apipath' ] ) ;
    $api->validateGet($_GET);

    //IMPORTANT
    //TODO GET ITEMID BACK
    

    //convert all get vars to lowercase
    $params = [];
    foreach($_GET as $key => $value)
    {
        $key = strtolower($key);
        $params[$key] = ($value);
    }

    $extraGetParams = $api->validateEndpoint($apiVars);
    if(isset($extraGetParams) > 0) {
        foreach ($extraGetParams as $key => $value) {
            $params[$key] = $value;
        }
    }
    /*
     * Execute the request
     */

    $result = $api->endpoint($endpoint)->request($httpMethod)->body($body)->params($params)->execute();
    $response =
        [
            "response" =>
                [
                    $result,
                ],
            "success" => true,
            "status" => 200
        ];

} catch (Exception $e){
    $response =
        [
        "response" =>
            [
            "message" =>  $e->getMessage() ,
            "error" =>  $e->getError()
            ],
        "success" => false,
        "status" => $e->getCode()
        ];
}
?>
<pre>
<?php

echo json_encode($response, JSON_PRETTY_PRINT );
?>

</pre>
