<?php

namespace App\Controllers;

use App\Components\JsonRPC\Response;
use App\Models\FollowLinks;
use Phalcon\Paginator\Adapter\QueryBuilder;

class AdminController extends ControllerBase
{
    public function activityAction($page = null)
    {
        $builder = $this
            ->modelsManager
            ->createBuilder()
            ->columns('count(id) as counter, url, max(date) as date')
            ->from(FollowLinks::class)
            ->orderBy('counter DESC')
            ->groupBy('url');

        $paginator = new QueryBuilder(
            [
                'builder' => $builder,
                'limit'   => 5,
                'page'  => (int)$page ?? 1,
            ]
        );

        $response = new Response();
        $response->result = $paginator->paginate();

        return $this->response->setJsonContent($response);
    }
}