<?php
session_start();
require_once '../vendor/connect.php';
unset($_SESSION["sql"]["sql"]);
$type = $_POST['type'];
$name = $_POST['name'];
$marka = $_POST['marka'];
$zav_number = $_POST['zav_number'];
$location2 = $_POST['location2'];


$distr_name = $_POST['distr_name'];


$_SESSION['form_select'] = [
    "distr_name" => $distr_name,
    "type" => $type,
    "name" => $name,
    "marka" => $marka,
    "zav_number" => $zav_number,
    "location2" => $location2,



];








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
// Принимаем принадлежность
$search = explode(' ', $_POST['distr_name']);


//если оно есть
if (!empty($_POST['distr_name'])) {
    $where = " users.id=device.distr_id AND (";
    if (count($search) === 1) {
        //  если оно одно 
        $where .= addWhere($where, " users.distr LIKE '%" . htmlspecialchars($search[0]), false, true) . "%'";
        $where .= ")";
    } else {
        // если их несколько
        $where .= addWhere($where, " users.distr LIKE '%" . htmlspecialchars($search[0]), false, true) . "%'";
        unset($search[0]);
        foreach ($search as $name) {

            $where = addWhere($where, " users.distr LIKE '%" . htmlspecialchars($name), false) . "%'";
        }

        $where .= ")";
    }
}


// Принимаем тип
$search = explode(' ', $_POST['type']);


//если оно есть
if (!empty($_POST['type'])) {
    // и если имя есть
    if ($where) {
        $where .= "AND (";
    } else $where = "(";

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










$sql = "SELECT DISTINCT `dev_id`, `dev_type`,`dev_name`,`dev_marka`,`zav_num`, `location`, `img`, `akt`,`status`,`distr_id` FROM `device`, `users` WHERE ";
if ($where) {
    $sql .= "$where";
    $_SESSION['sql'] = [
        "sql" =>  $sql,
        "btn" => true,
    ];
} else {

    $_SESSION['sql'] = [
        "sql" =>  "SELECT DISTINCT `dev_id`, `dev_type`,`dev_name`,`dev_marka`,`zav_num`, `location`, `img`, `akt`,`status`,`distr_id` FROM `device`, `users`",
        "btn" => false,
    ];
}






//$_SESSION['sql'] = "SELECT DISTINCT `id`,`dev_name`,`dev_marka`,`dev_zav_number`, `dev_data_pred_poverki`,`dev_data_release`,`dev_data_poverki`, `dev_img` FROM `device`, `users` WHERE users.distr_id={$_SESSION['user']['distr_id']} and users.distr_id=device.dist_id and `dev_name`='$name' or `dev_name`=null ";





$response = [

    "status" => true,
    "message" => "Показываю результат!",
];
echo json_encode($response);
