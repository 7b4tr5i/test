<?php

$router->group(['middleware' => ['\app\controller\middleware\CheckToken']], function($router){
    $router->get('main/{page?}', function($page = null){
        return \app\model\Goods::take(10)->skip(($page-1) * 10)->get();
    });
    $router->match(['put', 'get'], 'update/{id}', '\app\controller\Main@update');
    $router->post('create', '\app\controller\Main@create');
});

//$router->get('token', '\app\controller\Main@generateToken');