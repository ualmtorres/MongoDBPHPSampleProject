<?php
$connection = new MongoClient();
// Connecting specifying host and port
// $connection = new MongoClient('mongodb://localhost:27017');

$database = $connection->ggvdTest;
$collection = $database->actor;
// $collection = $connection->ggvdTest->actor;

$document = array( 'first_name' => 'Elisabeth', 'last_name' => 'Taylor', 'country' => 'UK', 'born' => 1932, 'sex' => 'female' );
$collection->insert($document);

$document = array( 'first_name' => 'James', 'last_name' => 'Dean', 'country' => 'USA', 'born' => 1931, 'sex' => 'male' );
$collection->insert($document);

// A mistake is introduced in the first_name of Rock Hudson to update it later
$document = array( 'first_name' => 'Rod', 'last_name' => 'Hudson', 'country' => 'USA', 'born' => 1925, 'sex' => 'male' );
$collection->insert($document);

$document = array( 'first_name' => 'Caroll', 'last_name' => 'Baker', 'country' => 'USA', 'born' => 1931, 'sex' => 'female' );
$collection->insert($document);

$document = array( 'first_name' => 'Princess', 'last_name' => 'Leia', 'country' => 'USA', 'sex' => 'female' );
$collection->insert($document);

$collection->update(array('last_name' => 'Hudson'), 
	array('$set' => array('first_name' => 'Rock')), 
	array('multiple' => true));

$collection->remove(array('last_name' => 'Leia'));

$cursor = $collection->find(array('sex' => 'female'));

echo '<h2>Actresses after updating and deleting bad data</h2>';

while ($row = $cursor->getNext()) {
	echo $row['first_name'] . " " . $row['last_name'] . '</br>';
}


$cursor = $collection->find(array('born' => array('$lt' => 1930)));

echo '<h2>Actors born before 1930</h2>';

while ($row = $cursor->next()) {
	echo $row['first_name'] . " " . $row['last_name'] . " " . $row['born'] , '</br>';
}

/*
db.actor.aggregate([
	{$match: {'country': 'USA'}},
	{$group: {_id: '$sex', 'total': {$sum: 1}}}
]);*/

$pipeline = array(
	array('$match' => array('country' => 'USA')),
	array('$group' => array('_id' => '$sex', 'total' => array('$sum' => 1)))
);

$aggregation = $collection->aggregate($pipeline);

echo '<h2>USA actors grouped by sex</h2>';

for ($i=0; $i < count($aggregation); $i++) {
	echo $aggregation['result'][$i]['_id'];
	echo ": ";
	echo $aggregation['result'][$i]['total'];
	echo "</br>";
}

echo '<h2>Aggregation var_dump</h2>';
var_dump($aggregation);

$connection->close();

?>

