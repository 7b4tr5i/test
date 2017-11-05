<?php

namespace base\exceptions;

use Illuminate\Http\Response;

class RestException
{

    public function __construct($exception)
    {
        $response = new Response;
        $response->header('Content-Type', 'application/json');

        if(method_exists($exception, 'getStatusCode')){
            $response->setStatusCode($exception->getStatusCode(), $exception->getMessage())->setContent(['code' => $exception->getStatusCode(), 'error' => $exception->getMessage()]);
        }else{
            $response->setContent(['code' => 0, 'error' => $exception->getMessage()]);
        }
        $response->send();
    }
}