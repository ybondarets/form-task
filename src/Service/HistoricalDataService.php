<?php

namespace App\Service;

use App\Entity\QuotesRequest;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HistoricalDataService
{
    private const API_KEY_HEADER = 'x-rapidapi-key';
    private HttpClientInterface $client;
    private string $apiKey;
    private string $apiUrl;

    private array $defaults = [
        'frequency' => '1d',
        'filter' => 'history',
    ];

    public function __construct(
        HttpClientInterface $client,
        string $historicalDataApiKey,
        string $historicalDataApiBase
    )
    {
        $this->client = $client;
        $this->apiKey = $historicalDataApiKey;
        $this->apiUrl = $historicalDataApiBase;
    }

    public function getData(QuotesRequest $request)
    {
        $response = $this->client->request(
            'GET',
            $this->apiUrl,
            [
                'query' => array_merge($this->defaults, [
                    'symbol' => $request->getCompanyCode(),
                    'period1' => $request->getStartDate()->getTimestamp(),
                    'period2' => $request->getEndDate()->getTimestamp(),
                ]),
                'headers' => [
                    static::API_KEY_HEADER => $this->apiKey,
                ]
            ]);

        return json_decode($response->getContent(), true);
    }
}
