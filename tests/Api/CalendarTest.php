<?php

class calendarTest extends \PHPUnit\Framework\TestCase
{
    private $http;

    public function setUp()
    {
        $this->http = new GuzzleHttp\Client(['base_uri' => 'http://api.aladhan.com/v1/']);
    }

    public function tearDown() {
        $this->http = null;
    }

    public function testStandardMidnightMode()
    {
        $response = $this->http->request('GET', 'calendar', [
            'query' => [
                'latitude' => '25.2048493',
                'longitude' => '55.2707828',
                'month' => '12',
                'year' => '1973'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json;charset=utf-8", $contentType);

        $responseBody = json_decode($response->getBody());
        $this->assertEquals("05:41 (+04)", $responseBody->data[1]->timings->Fajr);
        $this->assertEquals("18:36 (+04)", $responseBody->data[1]->timings->Isha);
        $this->assertEquals("00:08 (+04)", $responseBody->data[1]->timings->Midnight);
        $this->assertEquals("STANDARD", $responseBody->data[1]->meta->midnightMode);
    }

    public function testJafariMidnightMode()
    {
        $response = $this->http->request('GET', 'calendar', [

            'query' => [
                'latitude' => '25.2048493',
                'longitude' => '55.2707828',
                'month' => '12',
                'year' => '1973',
                'midnightMode' => 1,
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json;charset=utf-8", $contentType);

        $responseBody = json_decode($response->getBody());
        $this->assertEquals("05:41 (+04)", $responseBody->data[1]->timings->Fajr);
        $this->assertEquals("18:36 (+04)", $responseBody->data[1]->timings->Isha);
        $this->assertEquals("23:35 (+04)", $responseBody->data[1]->timings->Midnight);
        $this->assertEquals("JAFARI", $responseBody->data[1]->meta->midnightMode);
    }

}
