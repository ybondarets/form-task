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

    public function testInvalidCompanyCodeFormSubmit()
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

    public function testInvalidEmailFormSubmit()
    {
        $client = static::createClient();
        $client->request('POST', '/api/form_company_request', [
            'quotes_request' => [
                'email' => 'somser.com',
                'startDate' => '2019-4-5',
                'endDate' => '2020-5-4',
                'companyCode' => 'GOOGL',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('This email is not validemail', $responseData['errors'][0]);
    }

    public function testInvalidDateFormatsFormSubmit()
    {
        $client = static::createClient();
        $client->request('POST', '/api/form_company_request', [
            'quotes_request' => [
                'email' => 'soms@er.com',
                'startDate' => '2019-4-5555',
                'endDate' => '20212-5-4',
                'companyCode' => 'GOOGL',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('This value is not valid.startDate', $responseData['errors'][0]);
        $this->assertEquals('This value is not valid.endDate', $responseData['errors'][1]);
    }

    public function testInvalidDatesFormSubmit()
    {
        $client = static::createClient();
        $client->request('POST', '/api/form_company_request', [
            'quotes_request' => [
                'email' => 'soms@er.com',
                'startDate' => '2022-4-5',
                'endDate' => '2023-5-4',
                'companyCode' => 'GOOGL',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue(strpos('This value should be less than or equal to', $responseData['errors'][0]) >= 0);
    }

    public function testMixedDatesFormSubmit()
    {
        $client = static::createClient();
        $client->request('POST', '/api/form_company_request', [
            'quotes_request' => [
                'email' => 'soms@er.com',
                'startDate' => '2021-4-5',
                'endDate' => '2020-5-4',
                'companyCode' => 'GOOGL',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $error = $responseData['errors'][0];
        $this->assertTrue(
            strpos('This value should be less than or equal to', $error) >= 0
                &&
            strpos('.startDate', $error) >= 0
        );
    }
}
