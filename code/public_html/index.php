<?php

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

echo "</xmp>";

$cursor = $collection->find();
foreach ($cursor as $doc) {
    var_dump($doc);
}


phpinfo();
