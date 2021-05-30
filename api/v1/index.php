<?php

require_once $_SERVER['DOCUMENT_ROOT']."/app/init.php";
//TODO remove token generation in life versie
$token = array (
    'eid' => 1,
    'manager' => true,
    'iat' => time()
);
$jwt = Firebase\JWT\JWT::encode($token, $_ENV['JWTSECRET']);
// EINDE token generation

//gather all request-data
$httpMethod = strtolower($_SERVER['REQUEST_METHOD']);

//controle of er een endpoint gegeven is
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
    //controleer of het JWT token valide is
    try{
        $decoded =\Firebase\JWT\JWT::decode($jwt,$_ENV['JWTSECRET'], ['HS256']);
        //als het ouder is dan 8 uur is het niet valid
        if ($decoded->iat < (time()-28800)) throw new Exception;
    } catch (Exception $e)
    {
        throw new API\NotAuthorizedException('Token not valid');
    }
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

    //convert all get vars to lowercase
    $params = [];
    foreach($_GET as $key => $value)
    {
        $key = strtolower($key);
        $params[$key] = ($value);
    }
    //maximale lengte endpoint is 2
    if (count($apiVars) > 2)
    {
        throw new API\BadRequestException("Endpoint does not exist");
    }
    //als het eindpoint faq is, is de tweede parameter de searchterm
    if ( ( count( $apiVars ) == 2 ) AND (strtolower($apiVars[0]) == "faq") )
    {
        $params['searchterm'] = $apiVars[1];
    }
    //anders :als de tweede parameter van het endpoint een getal is, opslaan als itemid
    elseif  (( count( $apiVars ) == 2 ) AND ( preg_match('/[0-9]+/',$apiVars[1] ) ))
    {
        $params['itemid'] = $apiVars[1];
    }
    //anders kan er geen tweede parameter zijn, dus fout
    else if ( count( $apiVars ) == 2 ) {
        throw new \API\BadRequestException("Endpoint $apiVars[0]/$apiVars[1] does not exist");
    }
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
