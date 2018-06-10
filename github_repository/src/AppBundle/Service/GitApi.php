<?php

namespace AppBundle\Service;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class GitApi
{
    private $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }
    public function getProfile($username)
    {
        try
        {
            $response = $this->client->request('GET', 'https://api.github.com/users/'. $username);
        }
        catch (RequestException $e)
        {
             if ($e->hasResponse())
            {
                $error = Psr7\str($e->getRequest());
                $pieces = explode("/", $error);
                $pieces = strtok($pieces[2],  ' ');
                return $pieces;
            }
        }
        return $data = json_decode($response->getBody()->getContents(), true);
    }

    public function getRepos($username)
    {
        $response = $this->client->request('GET', 'https://api.github.com/users/' . $username . '/repos?sort=created&per_page=1000');
        $data = json_decode($response->getBody()->getContents(), true);
            return [
                'repo_count' => count($data),
                'repos' => $data ];

    }
}