<?php

namespace App\Components\JsonRPC;

class Response
{
    /**
     * Request id
     * @var string|int|null
     */
    public $id;

    /**
     * Request version
     * @var string
     */
    public $version = '2.0';

    /**
     * Method execution result
     * @var string
     */
    public $result;

    /**
     * Error occured while executing
     * JsonRPC request
     * @var JsonRPC\Exception
     */
    public $error;

    /**
     * Returns string representation
     * @return string
     */
    public function __toString()
    {
        $response = [
            'id' => $this->id,
            'jsonrpc' => $this->version
        ];

        if (!empty($this->error)) {
            $response['error'] = [
                'code' => $this->error->getCode(),
                'message' => $this->error->getMessage(),
            ];
        } else {
            $response['result'] = $this->result;
        }

        return json_encode($response);
    }
}