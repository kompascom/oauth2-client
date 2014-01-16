<?php

// composer autoload
require_once "vendor/autoload.php";

$provider = new League\OAuth2\Client\Provider\Kompas(array(
    'clientId'  =>  'XXXXXXXX',
    'clientSecret'  =>  'XXXXXXXX',
    'redirectUri'   =>  '',
    // 'format' => 'json' // uncomment this if you want to json output, default output is xml (rss formated)
));

try {

    // Try to get an access token (using the client credentials grant)
    $t = $provider->getAccessToken('client_credentials');

    try {

        /**
        * default output xml (rss formatted)
        */

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

        /**
        * end of rss formated
        */

        /**
        * json output (require: format => "json" in provider parameter)
        */

            /*
            header("Content-Type: application/json");
            // We got an access token, let's now get the latest RSS
            $provider->setFilterBySite('nasional,megapolitan');
            $latest = $provider->getRssLatest($t);
            echo $latest; // result filtered
            $mostcommented = $provider->getRssMostCommented($t);
            echo $mostcommented; // result filtered
            $provider->setFilterBySite(); // reset filtered
            $mostpopular = $provider->getRssMostPopular($t);
            echo $mostpopular; // result not filtered
            */

        /**
        * end of json output
        */

    } catch (Exception $e) {

        // Failed to get Rss
        echo $e->getMessage();
    }

} catch (Exception $e) {

    // Failed to get access token
    echo $e->getMessage();
}