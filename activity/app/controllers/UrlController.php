<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Components\JsonRPC\Response;
use App\Exceptions\InvalidRequest;
use App\Models\FollowLinks;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Url;
use Phalcon\Validation\Validator\PresenceOf;

class UrlController extends ControllerBase
{

    public function followLinksAction($url, $date)
    {
        $response = new Response();

        if (!$this->validate(['url' => $url, 'date' => $date])) {
            $response->error = new InvalidRequest('Transferred data not as expected');
            die($response);
        }

        $followLinks = new FollowLinks();
        $followLinks->url = $url;
        $followLinks->date = date('Y-m-d H:i:s', $date);

        if ($result = $followLinks->save()) {
            $response->id = $followLinks->id;
            $response->result = $result;
        }

        return $this->response->setJsonContent($response);
    }

    private function validate(array $data): bool
    {
        $validation = new Validation();

        $validation->add(
            'url',
            new PresenceOf(
                [
                    'message' => 'The url is required',
                ]
            )
        );
        $validation->add(
            'url',
            new Url(
                [
                    'message' => 'Bad url',
                ]
            )
        );

        return count($validation->validate($data)) === 0;
    }

}

