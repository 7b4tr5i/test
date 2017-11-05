<?php
$file = (__DIR__) . DIRECTORY_SEPARATOR .'db.back';
$app  = (__DIR__) . DIRECTORY_SEPARATOR .'../../';

if(!file_exists($file))
    die('file db.sql not found');

$post    = null;
$success = false;
$error   = false;

if($_POST != NULL && array_key_exists('Bd', $_POST) && ($post = $_POST['Bd']) != null){

    try {
        $host = trim($post['host']);
        $user = trim($post['username']);
        $pass = trim($post['password']);
        $char = 'utf8';
        $db = trim($post['database']);

        $srv = "mysql:host=$host;charset=$char";

        $pdo = new \PDO($srv, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        if(file_exists($fw = $app . 'app/config/db.php'))
            unlink($fw);

        $created = fopen($fw, "w");
        if(!$created)
            throw new Exception('Ошибка при создании файла конфигурации');

        $cfgString = <<<EOF
<?php 
return[
    'driver'    => 'mysql',
    'host'      => '$host',
    'database'  => '$db',
    'username'  => '$user',
    'password'  => '$pass',
    'charset'   => '$char',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
];
EOF;

        fwrite($created, $cfgString);
        fclose($created);

        ob_start();
        require($file);
        $sql = ob_get_contents();
        ob_clean();

        $sql = explode(";", $sql);

        foreach ($sql as $v) {
            if (($query = trim($v)) != NULL)
                $pdo->exec($query);
        }

        $success = true;
        $f = new SplFileObject('.htaccess', 'w');
    }catch(Exception $ex){
        $error = $ex->getMessage();
        unlink($app . 'app/config/db.php');
    }
}
require('install.incl');