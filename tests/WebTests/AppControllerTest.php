<?php

namespace App\Tests\WebTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppControllerTest extends WebTestCase
{
    public function testGetCompaniesData()
    {
        $client = static::createClient();
        $client->request('GET', '/api/companies_data');
        $this->assertResponseIsSuccessful();

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey(0, $responseData);
    }

    public function testFormSubmit()
    {
        $client = static::createClient();
        $client->request('POST', '/api/form_company_request', [
            'quotes_request' => [
                'email' => 'some@user.com',
                'startDate' => '2019-4-5',
                'endDate' => '2020-5-4',
                'companyCode' => 'G',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('This value is not valid.companyCode', $responseData['errors'][0]);
    }
}
