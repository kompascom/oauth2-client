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

        // If you want to view RSS
        /**/
        header("Content-Type: text/xml");
        // We got an access token, let's now get the latest RSS
        $latest = $provider->getRssLatest($t);
        echo $latest;
        /**/

        //If you want to parse RSS and custom your view
        /*
        $latest = $provider->getRssLatest($t);
        $latest = simplexml_load_string($latest, 'SimpleXMLElement', LIBXML_NOCDATA + LIBXML_NOERROR + LIBXML_ERR_FATAL + LIBXML_ERR_NONE);

        foreach($latest->channel->item as $item) {
            echo "<div>{$item->title}</div>";
            echo "<div style='padding-bottom: 10px; font-style: italic;'>{$item->description}</div>";
        }
        */

    } catch (Exception $e) {

        // Failed to get Rss
        echo $e->getMessage();
    }

} catch (Exception $e) {

    // Failed to get access token
    echo $e->getMessage();
}