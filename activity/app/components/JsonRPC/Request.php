<?php

namespace App\Components\JsonRPC;

use App\Exceptions\ParseError;
use App\Exceptions\InvalidRequest;
use App\Exceptions\MethodNotFound;
use App\Exceptions\InvalidParams;

class Request
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
    public $version;

    /**
     * Method
     * @var string
     */
    public $method;

    /**
     * Parameters
     * @var array
     */
    public $params = [];

    /**
     * Creates request object from string
     * @param string $string
     * @return JsonRPC\Request
     */
    public static function fromString($string)
    {
        // Check that request is not empty
        if (empty($string)) {
            throw new InvalidRequest('Given request is empty');
        }

        // Decode given string
        $data = json_decode($string, true);

        // If there is parse error, throw exception
        if (json_last_error() !== JSON_ERROR_NONE) {
            switch (json_last_error()) {
                case JSON_ERROR_DEPTH:
                    $message = 'Maximum stack depth exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $message = 'Underflow or the modes mismatch';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $message = 'Unexpected control character found';
                    break;
                case JSON_ERROR_SYNTAX:
                    $message = 'Syntax error, malformed JSON';
                    break;
                case JSON_ERROR_UTF8:
                    $message = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                    break;
                default:
                    $message = 'Unknown parsing error';
                    break;
            }
            throw new ParseError($message);
        }

        // Set up version
        if ($data['jsonrpc'] !== '2.0') {
            throw new InvalidRequest('Incorrect JSON-RPC version');
        }

        // If there is no method, throw exception
        if (empty($data['method'])) {
            throw new MethodNotFound('Method can not be empty');
        }

        // If threre is no params, throw exception
        if (!isset($data['params'])) {
            throw new InvalidParams('Params are not specified');
        }

        // Create and fill in jsonrpc request
        $request = new self();
        $request->version = $data['jsonrpc'];
        $request->method  = $data['method'];
        $request->params  = $data['params'];

        return $request;
    }
}