<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/user/register', function (Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        $email = isset($data['email']) ? $data['email'] : " ";

    if (empty($data['email'])) {
        return $response->withJson(
            [
                'status'=>'kontol',
                'message'=>'goblok',
            ],200
        );
    }
    });
   
    
};
