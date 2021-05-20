<?php

namespace App\Tests\Service;

use App\Service\CompaniesDataService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class CompaniesDataServiceTest extends TestCase
{
    public function testCaching()
    {
        $response = new MockResponse('someData');
        $client = new MockHttpClient([$response]);
        $dataService = new CompaniesDataService($client, new FilesystemAdapter(), 'https://base.url');
        $dataService->getData();
        $dataService->getData();

        $this->assertSame($client->getRequestsCount(), 1);
    }
}
