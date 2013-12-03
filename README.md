# OAuth 2.0 Client Library

[![Build Status](https://travis-ci.org/php-loep/oauth2-client.png?branch=master)](https://travis-ci.org/php-loep/oauth2-client)
[![Total Downloads](https://poser.pugx.org/league/oauth2-client/downloads.png)](https://packagist.org/packages/league/oauth2-client)
[![Latest Stable Version](https://poser.pugx.org/league/oauth2-client/v/stable.png)](https://packagist.org/packages/league/oauth2-client)

This library makes it stupidly simple to integrate your application with OAuth 2.0 identity providers. It has built in support for:

* Facebook
* Github
* Google
* Instagram
* LinkedIn
* Microsoft
* Vkontakte
* Kompas

Adding support for other providers is trivial.

The library requires PHP 5.3+ and is PSR-0 compatible.

First, you must install [composer](http://getcomposer.org/doc/00-intro.md) in your project.

Create a `composer.json` file in your project root:
```bash
{
    "require": {
        "kompas/oauth2-client": "dev-develop"
    }
}
```
Add this line to your application’s `index.php` file:
```bash
require 'vendor/autoload.php';
```

## Usage for Kompas

```php
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
```

## Usage for Global

```php
$provider = new League\OAuth2\Client\Provider\<provider name>(array(
    'clientId'  =>  'XXXXXXXX',
    'clientSecret'  =>  'XXXXXXXX',
    'redirectUri'   =>  'http://your-registered-redirect-uri/'
));

if ( ! isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $provider->authorize();

} else {

    try {

        // Try to get an access token (using the authorization code grant)
        $t = $provider->getAccessToken('authorization_code', array('code' => $_GET['code']));

        try {

            // We got an access token, let's now get the user's details
            $userDetails = $provider->getUserDetails($t);

            foreach ($userDetails as $attribute => $value) {
                var_dump($attribute, $value) . PHP_EOL . PHP_EOL;
            }

        } catch (Exception $e) {

            // Failed to get user details

        }

    } catch (Exception $e) {

        // Failed to get access token

    }
}
```

### List of built-in identity providers

| Provider | uid    | nickname | name   | first_name | last_name | email  | location | description | imageUrl | urls |
| :------- | :----- | :------- | :----- | :--------- | :-------- | :----- | :------- | :---------- | :------- | :--- |
| **Facebook** | string | string | string | string | string | string | string | string | string   | array (Facebook) |
| **Github**   | string | string | string | null | null | string | null | null | null | array (Github, [personal])|
| **Google** | string | string | string | string | string | string | null | null | string | null |
| **Instagram** | string | string | string | null | null | null | null | string | string | null |
| **LinkedIn** | string | null | string | null | null | string | string | string | string | string |
| **Microsoft** | string | null | string | string | string | string | null | null | string | string |
| **Kompas** | null | null | null | null | null | null | null | null | null | null |

**Notes**: Providers which return URLs sometimes include additional URLs if the user has provided them. These have been wrapped in []

## License

The MIT License (MIT). Please see [License File](https://github.com/php-loep/:package_name/blob/master/LICENSE) for more information.
