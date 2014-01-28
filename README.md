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
    },
    "minimum-stability": "dev"
}
```
Add this line to your application’s `index.php` file:
```php
require 'vendor/autoload.php';
```

## Usage for Kompas

```php
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
        $response['latestFiltered'] = json_decode($latest, true); // result filtered
        $mostcommented = $provider->getRssMostCommented($t);
        $response['mostCommentedFiltered'] = json_decode($mostcommented, true); // result filtered
        $provider->setFilterBySite(); // reset filtered
        $mostpopular = $provider->getRssMostPopular($t);
        $response['mostPopularNonFiltered'] = json_decode($mostpopular, true); // result not filtered

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
```

Available Feature:

`getRssLatest(token, service, siteno, sectionid)`
`getRssMostCommented(token, service, siteno, sectionid)`
`getRssMostPopular(token, service, siteno, sectionid)`
`setFilterBySite(sites)` *only in `json` format

Example:
```php
$all_latest = $provider->getRssLatest(AccessToken);
$provider->setFilterBySite('nasional,megapolitan'); // (,) delimiter
$filter_latest = $provider->getRssLatest(AccessToken);
$provider->setFilterBySite(); // reset filter
$news_latest = $provider->getRssLatest(AccessToken, 'kompascom', 1, 1);
```

## Kompas API Reference
Authorization:

Request body
- HTTP request
    `POST http://apis.kompas.com/oauth2/token`

- Parameters
    Require body parameters
    - client_id [Your registered client id]
    - client_secret [Your registered client secret]
    - grant_type [MUST value "client_credentials"]

- Example
    ```bash
    POST /oauth2/token HTTP/1.1
    Host: apis.kompas.com
    Cache-Control: no-cache
    Content-Type: application/x-www-form-urlencoded

    client_id=xxx&client_secret=xxx&grant_type=client_credentials
    ```

- Response
```bash
{
    "access_token": "xxx",
    "token_type": "bearer",
    "expires": 1387445831,
    "expires_in": 3600
}
```

1.  Kompascom: latest
    Requires authorization

Request body
- HTTP request
    `GET http://apis.kompas.com/rss/kompascom/latest`

- Parameters
    Require query parameters
    - access_token [MUST value access token from authorization response]

    Optional path parameters
    - siteId [integer]
    - sectionId [integer]

    Optional query parameters
    - filterBySite [string, delimeter with comma. ex: nasional,megapolitan]

- Example all latests
    ```bash
    GET /rss/kompascom/latest?access_token=xxx HTTP/1.1
    Host: apis.kompas.com
    Cache-Control: no-cache
    Content-Type: application/x-www-form-urlencoded
    ```

- Example latest with filter sites
    ```bash
    GET /rss/kompascom/latest?access_token=xxx&filterBySite=nasional,megapolitan HTTP/1.1
    Host: apis.kompas.com
    Cache-Control: no-cache
    Content-Type: application/x-www-form-urlencoded
    ```

- Example latest for specific site
    ```bash
    GET /rss/kompascom/latest/1?access_token=xxx HTTP/1.1
    Host: apis.kompas.com
    Cache-Control: no-cache
    Content-Type: application/x-www-form-urlencoded
    ```

- Example latest for specific section site
    ```bash
    GET /rss/kompascom/latest/1/1?access_token=xxx HTTP/1.1
    Host: apis.kompas.com
    Cache-Control: no-cache
    Content-Type: application/x-www-form-urlencoded
    ```

- Response
```bash
[
    {
        uid: "2013.12.13.0711189",
        channel: {
            site: "bola",
            section: ""
        },
        title: "Awal Januari, Trofi Piala Dunia Tiba di Indonesia",
        description: "Coca-Cola sebagai official sponsor of the FIFA World Cup™ ...",
        media: {
            image: {
                thumb: "http://assets.kompas.com/data/photo/2013/12/13/1458220455320155t.jpg",
                content: "http://assets.kompas.com/data/photo/2013/12/13/1458220455320155780x390.jpg"
            }
        },
        url: {
            permalink: "http://bola.kompas.com/read/2013/12/13/0711189/Awal.Januari.Trofi.Piala.Dunia.Tiba.di.Indonesia"
        },
        service: "kompascom",
        published_date: "2013-12-13 07:11:18"
    },
    ...
]
```

2.  Kompascom: Most Commented
    Requires authorization

Request body
- HTTP request
    `GET http://apis.kompas.com/rss/kompascom/mostcommented`

- Parameters
    Require query parameters
    - access_token [MUST value access token from authorization response]

    Optional path parameters
    - siteId [integer]
    - sectionId [integer]

    Optional query parameters
    - filterBySite [string, delimeter with comma. ex: nasional,megapolitan]

- Example all of most commented
    ```bash
    GET /rss/kompascom/mostcommented?access_token=xxx HTTP/1.1
    Host: apis.kompas.com
    Cache-Control: no-cache
    Content-Type: application/x-www-form-urlencoded
    ```

- Example most commented with filter sites
    ```bash
    GET /rss/kompascom/mostcommented?access_token=xxx&filterBySite=nasional,megapolitan HTTP/1.1
    Host: apis.kompas.com
    Cache-Control: no-cache
    Content-Type: application/x-www-form-urlencoded
    ```

- Example most commented for specific site
    ```bash
    GET /rss/kompascom/mostcommented/1?access_token=xxx HTTP/1.1
    Host: apis.kompas.com
    Cache-Control: no-cache
    Content-Type: application/x-www-form-urlencoded
    ```

- Example most commented for specific section site
    ```bash
    GET /rss/kompascom/mostcommented/1/1?access_token=xxx HTTP/1.1
    Host: apis.kompas.com
    Cache-Control: no-cache
    Content-Type: application/x-www-form-urlencoded
    ```

- Response
```bash
[
    {
        uid: "2013.12.13.0711189",
        channel: {
            site: "bola",
            section: ""
        },
        title: "Awal Januari, Trofi Piala Dunia Tiba di Indonesia",
        description: "Coca-Cola sebagai official sponsor of the FIFA World Cup™ ...",
        media: {
            image: {
                thumb: "http://assets.kompas.com/data/photo/2013/12/13/1458220455320155t.jpg",
                content: "http://assets.kompas.com/data/photo/2013/12/13/1458220455320155780x390.jpg"
            }
        },
        url: {
            permalink: "http://bola.kompas.com/read/2013/12/13/0711189/Awal.Januari.Trofi.Piala.Dunia.Tiba.di.Indonesia"
        },
        service: "kompascom",
        published_date: "2013-12-13 07:11:18",
        statistics: {
            comment_count: 279
        }
    },
    ...
]
```

3.  Kompascom: Most Popular
    Requires authorization

Request body
- HTTP request
    `GET http://apis.kompas.com/rss/kompascom/mostpopular`

- Parameters
    Require query parameters
    - access_token [MUST value access token from authorization response]

    Optional path parameters
    - siteId [integer]
    - sectionId [integer]

    Optional query parameters
    - filterBySite [string, delimeter with comma. ex: nasional,megapolitan]

- Example all of most popular
    ```bash
    GET /rss/kompascom/mostpopular?access_token=xxx HTTP/1.1
    Host: apis.kompas.com
    Cache-Control: no-cache
    Content-Type: application/x-www-form-urlencoded
    ```

- Example most popular with filter sites
    ```bash
    GET /rss/kompascom/mostpopular?access_token=xxx&filterBySite=nasional,megapolitan HTTP/1.1
    Host: apis.kompas.com
    Cache-Control: no-cache
    Content-Type: application/x-www-form-urlencoded
    ```

- Example most popular for specific site
    ```bash
    GET /rss/kompascom/mostpopular/1?access_token=xxx HTTP/1.1
    Host: apis.kompas.com
    Cache-Control: no-cache
    Content-Type: application/x-www-form-urlencoded
    ```

- Example most popular for specific section site
    ```bash
    GET /rss/kompascom/mostpopular/1/1?access_token=xxx HTTP/1.1
    Host: apis.kompas.com
    Cache-Control: no-cache
    Content-Type: application/x-www-form-urlencoded
    ```

- Response
```bash
[
    {
        uid: "2013.12.13.0711189",
        channel: {
            site: "bola",
            section: ""
        },
        title: "Awal Januari, Trofi Piala Dunia Tiba di Indonesia",
        description: "Coca-Cola sebagai official sponsor of the FIFA World Cup™ ...",
        media: {
            image: {
                thumb: "http://assets.kompas.com/data/photo/2013/12/13/1458220455320155t.jpg",
                content: "http://assets.kompas.com/data/photo/2013/12/13/1458220455320155780x390.jpg"
            }
        },
        url: {
            permalink: "http://bola.kompas.com/read/2013/12/13/0711189/Awal.Januari.Trofi.Piala.Dunia.Tiba.di.Indonesia"
        },
        service: "kompascom",
        published_date: "2013-12-13 07:11:18",
        statistics: {
            read_count: 279
        }
    },
    ...
]
```

## License

The MIT License (MIT). Please see [License File](https://github.com/php-loep/:package_name/blob/master/LICENSE) for more information.
