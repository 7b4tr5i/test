<?php

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Events\Dispatcher;
use Illuminate\Routing\Router;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Database\Capsule\Manager as Dbmanager;

class Core{

    static $app;

    protected $container;
    
    protected $request;

    protected $classMap;

    protected $redirector;

    protected $router;

    public function __get($name){
        if(!array_key_exists($name, $this->classMap))
            throw new Exception('Class not found');

        return new $this->classMap[$name];
    }

    public static function loadApp(array $config){

        if($config === NULL)
            throw new Exception('Error load config file');
        self::$app = new self();
        self::$app->run($config);
    }

    protected function run($config){
        try {
            if (!array_key_exists('db', $config) && $config['db'] !== null)
                throw new Exception('Data to set connection db incorrect');

            $this->dbStart($config['db']);

            if (!array_key_exists('routes', $config) && $config['routes'] !== null)
                throw new Exception('Error on load routes');
            if (!array_key_exists('components', $config))
                throw new Exception('Error on load components');

            $this->classMap = $config['components'];
            $this->start($config['routes']);
        }catch(Exception $ex){
            if(!array_key_exists('exceptions', $config))
                new \base\exceptions\RestException($ex);

            (new $config['exceptions'][0]($ex));
        }
    }

    protected function start($routes){
        
        $this->container = new Container();
        $this->request = Request::capture();
        $this->container->instance('Illuminate\Http\Request', $this->request);

        $events = new Dispatcher($this->container);
        $router = new Router($events, $this->container);

        require($routes.'.php');

        $this->redirector = new Redirector(new UrlGenerator($router->getRoutes(), $this->request));
        $response = $router->dispatch($this->request);
        $response->send();
    }
    
    protected function dbStart($connection){
        
        $db = new Dbmanager;
        $db->addConnection($connection);
        $db->bootEloquent();
    }

    public function getRequest(){
        return $this->request;
    }

    public function getRedirector(){
        return $this->redirector;
    }

}