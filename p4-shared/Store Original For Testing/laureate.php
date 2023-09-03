<?php
// connect to mongodb
$mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// get the id parameter from the request
$id = intval($_GET['id']);

// generate filters and projections
$id_str = strval($id);
$filter = ['id' => $id_str];
$options = ["projection" => ['_id' => 0]];

// query db
$query = new MongoDB\Driver\Query($filter, $options);
$results = $mng->executeQuery("nobel.laureates", $query);

// set the Content-Type header to JSON, so that the client knows that we are returning a JSON data
header('Content-Type: application/json');

// print result
foreach($results as $result) {
        echo json_encode($result);
}
?>