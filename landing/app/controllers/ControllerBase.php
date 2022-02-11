<?php
declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Phalcon\Mvc\Controller;
use Phalcon\Url;

class ControllerBase extends Controller
{
    // Implement common logic

    /**
     * @var Client
     */
    protected $client;

    public function initialize()
    {
        $this->client = new Client([
            'headers' => ['Content-Type' => 'application/json'],
            'base_uri' => 'http://activity-nginx'
        ]);
    }

    public function afterExecuteRoute()
    {
        $this->sendActivity();
    }

    protected function sendActivity()
    {
        $this->client
            ->postAsync('', [
                RequestOptions::JSON => [
                    'jsonrpc' => '2.0',
                    'id' => time(),
                    'method' => 'url.followLinks',
                    'params' => [
                        'url' => UrlHelper::getCurrentUrl(),
                        'date' => time(),
                    ]
                ]
            ])->wait()->getBody()->getContents();
    }
}
