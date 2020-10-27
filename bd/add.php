<?php
session_start();
require_once '../vendor/connect.php';

//$dist_id_admin = $_POST['dist_id'];
$type = $_POST['type'];
$name = $_POST['name'];
$marka = $_POST['marka'];
$zav_number = $_POST['zav_number'];
$location1 = $_POST['location1'];

//$distr_id = $_SESSION['user']['distr_id'];
$pasport = $_FILES['img']['name'];


if ($_SESSION['user']['access'] === "0") {
    $distr_id = $_SESSION['user']['id'];
} else $distr_id = $_POST['id'];


// $_SESSION['form_select'] = [
//     "name_s" => $name,
// ];




$check_zav_number = mysqli_query($connect, "SELECT * FROM `device` WHERE `zav_num` = '$zav_number'");
if (mysqli_num_rows($check_zav_number) > 0) {
    $response = [
        "status" => false,
        "type" => 1,
        "message" => "Такой заводской номер уже существует",
        "fields" => ['zav_number']
    ];

    echo json_encode($response);
    die();
}



$error_fields = [];


if ($type === '') {
    $error_fields[] = 'type';
}

if ($name === '') {
    $error_fields[] = 'name';
}

if ($marka === '') {
    $error_fields[] = 'marka';
}

if ($zav_number === '') {
    $error_fields[] = 'zav_number';
}

if ($location1 === '') {
    $error_fields[] = 'location1';
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

        if (mysqli_query($connect, "INSERT INTO `device` (`dev_id`, `distr_id`,`dev_type`,`dev_name`,`dev_marka`,`zav_num`, `location`, `img`) 
VALUES (NULL, '$distr_id', '$type', '$name', '$marka', '$zav_number', '$location1', '$path')")) {



            $response = [
                "status" => true,
                "message" => "Добавлеение успешно!",
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

    if (mysqli_query($connect, "INSERT INTO `device` (`dev_id`, `distr_id`,`dev_type`,`dev_name`,`dev_marka`,`zav_num`, `location`, `img`) 
    VALUES (NULL, '$distr_id', '$type', '$name', '$marka', '$zav_number', '$location1', NULL)")) {



        $response = [
            "status" => true,
            "message" => "Добавлеение успешно!",
        ];
        echo json_encode($response);
    } else {
        $response = [
            "status" => false,
            "type" => 2,
            "message" => "Данные некорректны",
        ];
        echo json_encode($response);
    }
}
