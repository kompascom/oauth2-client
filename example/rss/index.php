<?php

// composer autoload
require_once "vendor/autoload.php";

$provider = new League\OAuth2\Client\Provider\Kompas(array(
    'clientId'  =>  'XXXXXXXX',
    'clientSecret'  =>  'XXXXXXXX',
    'redirectUri'   =>  ''
));

try {

    // Try to get an access token (using the client credentials grant)
    $t = $provider->getAccessToken('client_credentials');

    try {

        $provider->setFilterBySite('nasional,megapolitan');
        $latest = $provider->getRssLatest($t);
        $response['latestFiltered'] = $latest; // result filtered
        $mostcommented = $provider->getRssMostCommented($t);
        $response['mostCommentedFiltered'] = $mostcommented; // result filtered
        $provider->setFilterBySite(); // reset filtered
        $mostpopular = $provider->getRssMostPopular($t);
        $response['mostCommentedNonFiltered'] = $mostpopular; // result not filtered

    } catch (Exception $e) {

        // Failed to get Rss
        $response = array(
            'status' => false,
            'error' => $e->getMessage()
        );
    }

} catch (Exception $e) {

    // Failed to get access token
    $response = array(
        'status' => false,
        'error' => $e->getMessage()
    );
}

header("Content-Type: application/json");
echo json_encode($response);