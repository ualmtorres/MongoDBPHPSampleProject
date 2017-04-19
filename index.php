<?php
require 'vendor/autoload.php';

$connection = new MongoDB\Client;
// Connecting specifying host and port
// $connection = new MongoDB\Client('mongodb://localhost:27017');

$database = $connection->ggvdTest;
$collection = $database->actor;
// $collection = $connection->ggvdTest->actor;


$document = array( 'first_name' => 'Elisabeth', 'last_name' => 'Taylor', 'country' => 'UK', 'born' => 1932, 'sex' => 'female' );
$collection->insertOne($document);

$document = array( 'first_name' => 'James', 'last_name' => 'Dean', 'country' => 'USA', 'born' => 1931, 'sex' => 'male' );
$collection->insertOne($document);

// A mistake is introduced in the first_name of Rock Hudson to update it later
$document = array( 'first_name' => 'Rod', 'last_name' => 'Hudson', 'country' => 'USA', 'born' => 1925, 'sex' => 'male' );
$collection->insertOne($document);

$documents = array(
array('first_name' => 'Caroll', 'last_name' => 'Baker', 'country' => 'USA', 'born' => 1931, 'sex' => 'female' ),
array( 'first_name' => 'Princess', 'last_name' => 'Leia', 'country' => 'USA', 'sex' => 'female' )
);

$collection->insertMany($documents);

$collection->updateMany(
array('last_name' => 'Hudson'), 
array('$set' => array('first_name' => 'Rock'))
);

$collection->deleteOne(array('last_name' => 'Leia'));

$result = $collection->find(array('sex' => 'female'));

echo '<h2>Actresses after updating and deleting bad data</h2>';

foreach ($result as $document) {
	echo $document['first_name'] . " " . $document['last_name'] . '</br>';
}


$result = $collection->find(array('born' => array('$lt' => 1930)));

echo '<h2>Actors born before 1930</h2>';

foreach ($result as $document) {
	echo $document['first_name'] . " " . $document['last_name'] . " " . $document['born'] , '</br>';
}

/*
db.actor.aggregate([
	{$match: {'country': 'USA'}},
	{$group: {_id: '$sex', 'total': {$sum: 1}}}
]);
*/

$pipeline = array(
	array('$match' => array('country' => 'USA')),
	array('$group' => array('_id' => '$sex', 'total' => array('$sum' => 1)))
);

$result = $collection->aggregate($pipeline)->toArray();

echo '<h2>USA actors grouped by sex</h2>';

foreach($result as $doc) {
	echo $doc['_id'] . ': ' . $doc['total'] . '<br/>';
}

echo '<h2>Aggregation var_dump</h2>';
var_dump($result);


?>

