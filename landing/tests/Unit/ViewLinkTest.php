<?php

namespace Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class ViewLinkTest extends AbstractUnitTest
{
    /**
     * @var Client
     */
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client([
            'headers' => ['Content-Type' => 'application/json'],
            'base_uri' => 'http://activity-nginx'
        ]);
    }

    public function testLink()
    {
        $content = $this->client->postAsync('', [
            RequestOptions::JSON => [
                'jsonrpc' => '2.0',
                'id' => time(),
                'method' => 'url.followLinks',
                'params' => [
                    'url' => 'http://localhost/home',
                    'date' => time(),
                ]
            ]
        ])->wait()->getBody()->getContents();

        $result = json_decode($content, true);

        $this->assertIsString($result['id']);
        $this->assertEquals($result['version'], '2.0');
        $this->assertEquals($result['result'], true);
    }

    public function testErrorRpcVersion()
    {
        $content = $this->client->postAsync('', [
            RequestOptions::JSON => [
                'jsonrpc' => '3.0',
                'id' => time(),
                'method' => 'url.followLinks',
                'params' => [
                    'url' => '/home',
                    'date' => time(),
                ]
            ]
        ])->wait()->getBody()->getContents();

        $result = json_decode($content, true);

        $this->assertEquals($result['jsonrpc'], '2.0');
        $this->assertEquals($result['error']['message'], 'Incorrect JSON-RPC version');
    }

    public function testErrorMethod()
    {
        $content = $this->client->postAsync('', [
            RequestOptions::JSON => [
                'jsonrpc' => '2.0',
                'id' => time(),
                'method' => '',
                'params' => [
                    'url' => '/home',
                    'date' => time(),
                ]
            ]
        ])->wait()->getBody()->getContents();

        $result = json_decode($content, true);

        $this->assertEquals($result['jsonrpc'], '2.0');
        $this->assertEquals($result['error']['message'], 'Method can not be empty');
    }

    public function testErrorParams()
    {
        $content = $this->client->postAsync('', [
            RequestOptions::JSON => [
                'jsonrpc' => '2.0',
                'id' => time(),
                'method' => 'url.followLinks',
            ]
        ])->wait()->getBody()->getContents();

        $result = json_decode($content, true);

        $this->assertEquals($result['jsonrpc'], '2.0');
        $this->assertEquals($result['error']['message'], 'Params are not specified');
    }

    public function testBadMethod()
    {
        $content = $this->client->postAsync('', [
            RequestOptions::JSON => [
                'jsonrpc' => '2.0',
                'id' => time(),
                'method' => 'bad.method',
                'params' => [
                    'url' => '/home',
                    'date' => time(),
                ]
            ]
        ])->wait()->getBody()->getContents();

        $result = json_decode($content, true);

        $this->assertEquals($result['jsonrpc'], '2.0');
        $this->assertEquals($result['error']['message'], 'Controller not found');
    }

    public function testBadMethodAction()
    {
        $content = $this->client->postAsync('', [
            RequestOptions::JSON => [
                'jsonrpc' => '2.0',
                'id' => time(),
                'method' => 'url.method',
                'params' => [
                    'url' => '/home',
                    'date' => time(),
                ]
            ]
        ])->wait()->getBody()->getContents();

        $result = json_decode($content, true);

        $this->assertEquals($result['jsonrpc'], '2.0');
        $this->assertEquals($result['error']['message'], 'Action not found');
    }

    public function testBadUrl()
    {
        $content = $this->client->postAsync('', [
            RequestOptions::JSON => [
                'jsonrpc' => '2.0',
                'id' => time(),
                'method' => 'url.followLinks',
                'params' => [
                    'url' => '',
                    'date' => time(),
                ]
            ]
        ])->wait()->getBody()->getContents();

        $result = json_decode($content, true);

        $this->assertEquals($result['jsonrpc'], '2.0');
        $this->assertEquals($result['error']['message'], 'Transferred data not as expected');
    }

}