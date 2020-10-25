<?php
session_start();
require_once '../vendor/connect.php';
$dev_id = $_POST['dev_id'];
$type = $_POST['type'];
$name = $_POST['name'];
$marka = $_POST['marka'];
$img = $_POST['img'];
$zav_number = $_POST['zav_number'];
$location3 = $_POST['location3'];







$error_fields = [];


if ($name === '') {
    $error_fields[] = 'type3';
}

if ($marka === '') {
    $error_fields[] = 'name3';
}

if ($zav_number === '') {
    $error_fields[] = 'marka3';
}

if ($dev_data_release === '') {
    $error_fields[] = 'zav_number3';
}

if ($dev_data_pred_poverki === '') {
    $error_fields[] = 'location3';
}




if (!empty($error_fields)) {
    $response = [
        "status" => false,
        "type" => 1,
        "message" => "Проверьте правильность полей",
        "fields" => $error_fields
    ];

    echo json_encode($response);

    die();
}



$ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);

if ($_FILES['img']) {

    if ($ext === "pdf" || $ext === "jpg" || $ext === "png") {
        $path = 'uploads/' . time() . $_FILES['img']['name'];
        if (!move_uploaded_file($_FILES['img']['tmp_name'], '../' . $path)) {
            $response = [
                "status" => false,
                "type" => 2,
                "message" => "Ошибка при загрузке файла",
            ];
            echo json_encode($response);
        }

        if (mysqli_query($connect, "UPDATE `device` SET  `dev_type`='$type',`dev_name`='$name',`dev_marka`='$marka',`zav_num`='$zav_number', `location`='$location3',  `img`='$path' WHERE `dev_id`=$dev_id")) {




            $response = [
                "status" => true,
                "message" => "Изменение успешно!",
            ];
            echo json_encode($response);

            die();
        } else {
            $response = [
                "status" => false,
                "type" => 2,
                "message" => "Данные некорректны",
            ];
            echo json_encode($response);
        }
    } else {
        $error_fields[] = 'pasport';
        $response = [
            "status" => false,
            "type" => 1,
            "message" => "Неверный формат(jpg,png,pdf)",
            "fields" => $error_fields,

        ];
        echo json_encode($response);
    }
} else {

    if (mysqli_query($connect, "UPDATE `device` SET `dev_type`='$type',`dev_name`='$name',`dev_marka`='$marka',`zav_num`='$zav_number', `location`='$location3' WHERE `dev_id`=$dev_id")) {





        $response = [
            "status" => true,
            "message" => "Изменение успешно!",
        ];
        echo json_encode($response);
    } else {
        $response = [
            "status" => false,
            "type" => 2,
            "message" => $location3,
        ];
        echo json_encode($response);
    }
}
