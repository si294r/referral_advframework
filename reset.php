<?php

$json = json_decode($input);

$data['user_id'] = isset($json->user_id) ? $json->user_id : "";

if (trim($data['user_id']) == "") {
    return array(
        "error" => 1,
        "message" => "Error: user_id is empty"
    );
}

include("config.php");
$connection = new PDO(
    "mysql:dbname=$mydatabase;host=$myhost;port=$myport",
    $myuser, $mypass
);
    
// reset referrer
$sql2 = "UPDATE referral "
        . "SET referrer = null "
        . "WHERE referrer = :referrer ";
$statement2 = $connection->prepare($sql2);
$statement2->bindParam(":referrer", $data['user_id']);
$statement2->execute();

return array(
    'affected_row' => $statement2->rowCount(),
    'error' => 0,
    'message' => 'Success'
);


