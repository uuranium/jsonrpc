<?php
declare(strict_types=1);

use GuzzleHttp\RequestOptions;
use Phalcon\Http\Response;

class AdminController extends ControllerBase
{
    public function activityAction($page = null)
    {
        $contents = $this->client->post('', [
                RequestOptions::JSON => [
                    'jsonrpc' => '2.0',
                    'id' => uniqid(),
                    'method' => 'admin.activity',
                    'params' => [
                        'page' => $page
                    ]
                ]
            ])->getBody()->getContents();

        $data =  json_decode($contents, true);

        $response = new Response();
        if (empty($data['result'])) {
            $response->redirect('/')->send();
        }

        $this->view->setVars([
            'data' => $data['result']
        ]);
    }
}