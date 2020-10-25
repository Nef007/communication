<?php

require_once '../vendor/connect.php';

$dev_id = $_POST['dev_id'];


if (
    mysqli_query($connect, "DELETE FROM `device` WHERE `device`.`dev_id` = '$dev_id'")

) {

    $response = [
        "status" => true,
        "message" => "Устройство удалено!",
    ];
    echo json_encode($response);
} else {
    $response = [
        "status" => false,
        "message" => "Устройство не удалено!",
    ];
}
