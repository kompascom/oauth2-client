<?php

namespace League\OAuth2\Client\Provider;
use Guzzle\Service\Client as GuzzleClient;
use League\OAuth2\Client\Token\AccessToken;

class Kompas extends IdentityProvider
{
    protected $rssResponse;
    protected $format = 'xml';
    protected $filterBySite = null;

    public function urlAuthorize()
    {
        return NULL;
    }

    public function urlUserDetails(AccessToken $token)
    {
        return NULL;
    }

    public function userDetails($response, AccessToken $token)
    {
       return NULL;
    }

    public function urlAccessToken()
    {
        return 'http://apis.kompas.com/oauth2/token';
    }

    public function urlRss()
    {
        return 'http://apis.kompas.com/rss';
    }

    public function setFilterBySite($filter = null)
    {
        $this->filterBySite = $filter;
    }

    protected function fetchRss(AccessToken $token, $query)
    {
        $query_param = array(
            'access_token' => $token->accessToken,
            'format' => $this->format
        );

        if (! is_null($this->filterBySite)) {
            $query_param['filterBySite'] = $this->filterBySite;
        }

        $url = $this->urlRss().$query.'?'.http_build_query($query_param);

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

    public function getRssLatest(AccessToken $token, $service = 'kompascom', $site_no = NULL, $section_id = NULL)
    {
        $query = "/{$service}/latest/{$site_no}/{$section_id}";
        $response = $this->fetchRss($token, $query);

        return $response;
    }

    public function getRssMostCommented(AccessToken $token, $service = 'kompascom', $site_no = NULL, $section_id = NULL)
    {
        $query = "/{$service}/mostcommented/{$site_no}/{$section_id}";
        $response = $this->fetchRss($token, $query);

        return $response;
    }

    public function getRssMostPopular(AccessToken $token, $service = 'kompascom', $site_no = NULL, $section_id = NULL)
    {
        $query = "/{$service}/mostpopular/{$site_no}/{$section_id}";
        $response = $this->fetchRss($token, $query);

        return $response;
    }
}