<?php

$json = json_decode($input);

$data['user_id'] = isset($json->user_id) ? $json->user_id : "";
$data['shorten_id'] = isset($json->shorten_id) ? $json->shorten_id : "";
//$data['referrer'] = isset($json->referrer) ? $json->referrer : "";
$data['url_type'] = isset($json->url_type) ? $json->url_type : "1"; // 4 type shorten url

if (trim($data['user_id']) == "") {
    return array(
        "error" => 1,
        "message" => "Error: user_id is empty"
    );
}
if (trim($data['shorten_id']) == "") {
    return array(
        "error" => 1,
        "message" => "Error: shorten_id is empty"
    );
}
//if (trim($data['referrer']) == "") {
//    return array(
//        "error" => 1,
//        "message" => "Error: referrer is empty"
//    );
//}

include("config.php");
$connection = new PDO(
    "mysql:dbname=$mydatabase;host=$myhost;port=$myport",
    $myuser, $mypass
);
    
//$sql2 = "UPDATE referral "
//        . "SET referrer = :referrer "
//        . "WHERE user_id = :user_id "
//        . "and (referrer is null or referrer <> :referrer1) ";
$sql2 = "UPDATE referral t1
        INNER JOIN referral t2 ON t1.user_id = :user_id and t2.shorten_id = :shorten_id
        SET t1.referrer = t2.user_id
        WHERE t1.referrer is null OR t1.referrer <> t2.user_id
";
$statement2 = $connection->prepare($sql2);
//$statement2->bindParam(":referrer", $data['referrer']);
$statement2->bindParam(":user_id", $data['user_id']);
$statement2->bindParam(":shorten_id", $data['shorten_id']);
//$statement2->bindParam(":referrer1", $data['referrer']);
$statement2->execute();
$affected_row = $statement2->rowCount();

if ($affected_row > 0 && isset($referral_reward[ $data['url_type'] ])) {
    // TODO - integrate to inbox
    $sql = "SELECT * FROM referral WHERE shorten_id = :shorten_id ";
    $statement1 = $connection->prepare($sql);
    $statement1->execute(array(':shorten_id' => $data['shorten_id']));
    $row = $statement1->fetch(PDO::FETCH_ASSOC);

    $world = isset($row["world"]) ? $row["world"] : "1";

    if ($data['url_type'] == "1" || $data['url_type'] == "3") {
        $title = STR_VERIFIED_INSTALL_CASH1;
        $caption = STR_VERIFIED_INSTALL_CASH2;
        $reward = $referral_reward[ $data['url_type'] ] . "," . $world;
    } else {
        $title = STR_VERIFIED_INSTALL_CRYSTALS1;
        $caption = STR_VERIFIED_INSTALL_CRYSTALS2;
        $reward = $referral_reward[ $data['url_type'] ];
    }
    
    $device_id = $row['user_id'];
    $facebook_id = "";

    $sql = "INSERT INTO master_inbox (type, header, message, data, target_device, target_fb, os, status, valid_from, valid_to)
            VALUES ('reward', :title, :caption, :data, :target_device, :target_fb, 'All', 1, null, null)";
    $statement1 = $connection->prepare($sql);
    $statement1->bindParam(":title", $title);
    $statement1->bindParam(":caption", $caption);
    $statement1->bindParam(":data", $reward);
    $statement1->bindParam(":target_device", $device_id);
    $statement1->bindParam(":target_fb", $facebook_id);
    $statement1->execute();
}

$data['affected_row'] = $affected_row;
$data['error'] = 0;
$data['message'] = 'Success';

return $data;