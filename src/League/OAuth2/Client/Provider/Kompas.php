<?php

namespace League\OAuth2\Client\Provider;

class Kompas extends IdentityProvider
{
    protected $rssResponse;

    public function urlAccessToken()
    {
        return 'https://apis.kompas.com/oauth2/token';
    }

    public function urlRss()
    {
        return 'https://apis.kompas.com/rss';
    }

    protected function fetchRss(\League\OAuth2\Client\Token\AccessToken $token, $query)
    {
        $url = $this->urlRss().$query.'?'.$token;

        try {

            $client = new GuzzleClient($url);
            $request = $client->get()->send();
            $response = $request->getBody();
            $this->rssResponse = $response;

        } catch (\Guzzle\Http\Exception\BadResponseException $e) {

            $raw_response = explode("\n", $e->getResponse());
            throw new IDPException(end($raw_response));

        }

        return $this->rssResponse;
    }

    public function getRssLatest(\League\OAuth2\Client\Token\AccessToken $token, $service = 'kompascom', $site_no = NULL, $section_id = NULL)
    {
        $query = "/{$service}/latest/{$site_no}/{$section_id}";
        $response = $this->fetchRss($token, $query);

        return $response;
    }

    public function getRssMostCommented(\League\OAuth2\Client\Token\AccessToken $token, $service = 'kompascom', $site_no = NULL, $section_id = NULL)
    {
        $query = "/{$service}/mostcommented/{$site_no}/{$section_id}";
        $response = $this->fetchRss($token, $query);

        return $response;
    }

    public function getRssMostPopular(\League\OAuth2\Client\Token\AccessToken $token, $service = 'kompascom', $site_no = NULL, $section_id = NULL)
    {
        $query = "/{$service}/mostpopular/{$site_no}/{$section_id}";
        $response = $this->fetchRss($token, $query);

        return $response;
    }
}