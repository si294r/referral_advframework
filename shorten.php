<?php

$json = json_decode($input);

$data['device_id'] = isset($json->device_id) ? $json->device_id : "";
$data['world'] = isset($json->world) ? $json->world : "1";

if (trim($data['device_id']) == "") {
    return array(
        "error" => 1,
        "message" => "Error: device_id is empty"
    );
}
    
include("config.php");
include_once("function.php");

$connection = new PDO(
    "mysql:dbname=$mydatabase;host=$myhost;port=$myport",
    $myuser, $mypass
);

$user_id = get_user_id($data['device_id']);

$row = get_referral($user_id, $data['world']);
    
return array(
    'shorten_id' => intval($row['shorten_id']),
    'user_id' => $user_id,
    'world' => $row['world'],
    'device_id' => $data['device_id'],
    'shorten_url_1' => "http://$SHORT_DOMAIN/".base_convert((int)"{$row['shorten_id']}1" + 100000, 10, 32),
    'shorten_url_2' => "http://$SHORT_DOMAIN/".base_convert((int)"{$row['shorten_id']}2" + 100000, 10, 32),
    'shorten_url_3' => "http://$SHORT_DOMAIN/".base_convert((int)"{$row['shorten_id']}3" + 100000, 10, 32),
    'shorten_url_4' => "http://$SHORT_DOMAIN/".base_convert((int)"{$row['shorten_id']}4" + 100000, 10, 32),
    'error' => 0,
    'message' => 'Success'
);