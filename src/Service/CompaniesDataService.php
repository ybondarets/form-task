<?php

namespace App\Service;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CompaniesDataService
{
    private HttpClientInterface $client;
    private CacheInterface $cache;
    private string $apiUrl;

    public function __construct(
        HttpClientInterface $client,
        CacheInterface $cache,
        string $companiesDataApiPath
    )
    {
        $this->client = $client;
        $this->cache = $cache;
        $this->apiUrl = $companiesDataApiPath;
    }

    public function getData()
    {
        $key = $this->getKey($this->apiUrl);

        return $this->cache->get($key, function () {
            $response = $this->client->request(
                'GET',
                $this->apiUrl,
            );

            return json_decode($response->getContent(), true);
        });
    }

    private function getKey(string $payload): string
    {
        return md5($payload);
    }
}
