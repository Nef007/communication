<?php
session_start();
require_once '../vendor/connect.php';
unset($_SESSION["sql"]["sql"]);
$type = $_POST['type'];
$name = $_POST['name'];
$marka = $_POST['marka'];
$zav_number = $_POST['zav_number'];
$location2 = $_POST['location2'];


$_SESSION['form_select'] = [
    "type" => $type,
    "name" => $name,
    "marka" => $marka,
    "zav_number" => $zav_number,
    "location2" => $location2,


];




$error_fields = [];






function addWhere($where, $add, $and = true, $ferst = false)
{
    if ($where && $ferst) {
        $where = $add;
    } elseif ($where) {
        if ($and) $where .= " AND $add";
        else $where .= " OR $add";
    } else $where = $add;
    return $where;
}
// Принимаем тип
$search = explode(' ', $_POST['type']);


//если оно есть
if (!empty($_POST['type'])) {
    $where = "(";
    if (count($search) === 1) {
        //  если оно одно 
        $where .= addWhere($where, "`dev_type` LIKE '%" . htmlspecialchars($search[0]), false, true) . "%'";
        $where .= ")";
    } else {
        // если их несколько
        $where .= addWhere($where, "`dev_type` LIKE '%" . htmlspecialchars($search[0]), false, true) . "%'";
        unset($search[0]);
        foreach ($search as $name) {

            $where = addWhere($where, "`dev_type` LIKE '%" . htmlspecialchars($name), false) . "%'";
        }

        $where .= ")";
    }
}

// Принимаем имя


$search = explode(' ', $_POST['name']);


//если оно есть
if (!empty($_POST['name'])) {
    // и если имя есть
    if ($where) {
        $where .= "AND (";
    } else $where = "(";

    if (count($search) === 1) {
        //  если оно одно 
        $where .= addWhere($where, "`dev_name` LIKE '%" . htmlspecialchars($search[0]), false, true) . "%'";
        $where .= ")";
    } else {
        // если их несколько
        $where .= addWhere($where, "`dev_name` LIKE '%" . htmlspecialchars($search[0]), false, true) . "%'";
        unset($search[0]);
        foreach ($search as $name) {

            $where = addWhere($where, "`dev_name` LIKE '%" . htmlspecialchars($name), false) . "%'";
        }

        $where .= ")";
    }
}
// Принимаем модель


$search = explode(' ', $_POST['marka']);


//если оно есть
if (!empty($_POST['marka'])) {
    // и если имя есть
    if ($where) {
        $where .= "AND (";
    } else $where = "(";

    if (count($search) === 1) {
        //  если оно одно 
        $where .= addWhere($where, "`dev_marka` LIKE '%" . htmlspecialchars($search[0]), false, true) . "%'";
        $where .= ")";
    } else {
        // если их несколько
        $where .= addWhere($where, "`dev_marka` LIKE '%" . htmlspecialchars($search[0]), false, true) . "%'";
        unset($search[0]);
        foreach ($search as $name) {

            $where = addWhere($where, "`dev_marka` LIKE '%" . htmlspecialchars($name), false) . "%'";
        }

        $where .= ")";
    }
}

// Принимаем заводской номер
if (!empty($_POST['zav_number'])) {

    if ($where) {
        $where .= "AND (";
    } else $where = "(";

    $where .= addWhere($where, "`zav_num` LIKE '%" . htmlspecialchars($zav_number), false, true) . "%'";

    $where .= ")";
}
// Принимаем место установки
if (!empty($_POST['location2'])) {

    if ($where) {
        $where .= "AND (";
    } else $where = "(";

    $where .= addWhere($where, "`location` LIKE '%" . htmlspecialchars($location2), false, true) . "%'";

    $where .= ")";
}





$sql = "SELECT DISTINCT  `dev_id`,  `dev_type`,`dev_name`,`dev_marka`,`zav_num`, `location`, `img`,`akt`,`status` FROM `device`, `users` WHERE (users.id={$_SESSION['user']['id']} and users.id=device.distr_id) and ";
if ($where) {
    $sql .= "$where";
    $_SESSION['sql'] = [
        "sql" =>  $sql,
        "btn" => true,
    ];
} else {

    $_SESSION['sql'] = [
        "sql" =>  "SELECT DISTINCT  `dev_id`,  `dev_type`,`dev_name`,`dev_marka`,`zav_num`, `location`, `img`,`akt`,`status` FROM `device`, `users` WHERE users.id={$_SESSION['user']['id']} and users.id=device.distr_id",
        "btn" => false,
    ];
}



$response = [

    "status" => true,
    "message" => "Показываю результат!",
];
echo json_encode($response);
