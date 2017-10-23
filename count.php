<?php

$json = json_decode($input);

$data['user_id'] = isset($json->user_id) ? $json->user_id : "";
$data['count_install'] = isset($json->count_install) ? $json->count_install : "";

if (trim($data['user_id']) == "") {
    return array(
        "error" => 1,
        "message" => "Error: user_id is empty"
    );
}

if (is_numeric($data['count_install']) && $data['count_install'] > -1) {
    return array(
        'user_id' => $data['user_id'],
        'count_install' => (int)$data['count_install'],
        'error' => 0,
        'message' => 'Success'
    );
}

include("config.php");
$connection = new PDO(
    "mysql:dbname=$mydatabase;host=$myhost;port=$myport",
    $myuser, $mypass
);
    
$sql2 = "SELECT count(*) as count_install FROM referral WHERE referrer = :user_id";
$statement2 = $connection->prepare($sql2);
$statement2->execute(array(':user_id' => $data['user_id']));
$row = $statement2->fetch(PDO::FETCH_ASSOC);

return array(
    'user_id' => $data['user_id'],
    'count_install' => intval($row['count_install']),
    'error' => 0,
    'message' => 'Success'
);
