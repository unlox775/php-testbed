<?php

$GLOBALS['PHP_START_microtime'] = microtime(true);
define('CODE_ROOT', realpath('../'));
define('APPLICATION_PATH', CODE_ROOT .'/application');
require_once( APPLICATION_PATH .'/config/Bootstrap.php');

use PHPUnit\Framework\TestCase;

require_once(dirname(dirname(__FILE__)). '/application/models/Email.php');

echo "<xmp>Hello World!!!!";

$connection = new MongoClient( "mongodb://mongo:27017" );

$collection = $connection->selectCollection('db-name', 'collection-name');
if (!$collection) {
        echo 'not connected to collection';
        exit;
}
        echo "\n\nconnected to collection";


$collection->insert(["foo" => 1, 'bar' => "asdf", "date" => date('Y-m-d H:i:s')]);

$cursor = $collection->find();
foreach ($cursor as $doc) {
    var_export($doc);
}


echo "</xmp>";

phpinfo();
