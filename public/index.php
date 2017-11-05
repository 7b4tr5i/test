<?php

define('app', (__DIR__) . DIRECTORY_SEPARATOR . '../');
$config = app . 'app/config/config.php';

require app . 'vendor/autoload.php';
require app . 'vendor/myapp/Base.php';
$cfg = require($config);

Base::loadApp($cfg);
