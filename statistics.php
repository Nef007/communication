<?php
session_start();
require_once 'vendor/connect.php';
require_once 'vendor/functions.php';

if (!$_SESSION['user'] || $_SESSION['user']['access'] == "0") {
    header('Location: /');
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Метрология</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/main.css">


</head>

<body>

    <!-- Шапка -->
    <header>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 ">
                    <h1>Статистика</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-1 offset-lg-8 ">
                    <div class="logout">
                        <a href="admin.php">Главная</a>
                    </div>
                </div>
                <div class="col-lg-1">
                    <div class="logout">
                        <a href="statistics.php">Статистика</a>
                    </div>
                </div>
                <div class="col-lg-1">
                    <div class="logout">
                        <a href="users.php">Пользователи</a>
                    </div>
                </div>
                <div class="col-lg-1 ">
                    <div class="logout">
                        <a href="vendor/logout.php">Выход</a>
                    </div>
                </div>
            </div>
        </div>
    </header>




    <?php

    // получение районов
    $sql = "SELECT `distr` FROM `users` ";
    $distrs = mysqli_query($connect,  $sql);
    $distrs = mysqli_fetch_all($distrs);
    //получение типов
    $sql1 = "SELECT DiSTINCT `dev_type` FROM `device` ";
    $types = mysqli_query($connect,  $sql1);
    $types = mysqli_fetch_all($types);
    $count_type = mysqli_num_rows(mysqli_query($connect,  $sql1));
    // получение наименований
    $sql2 = "SELECT DiSTINCT `dev_name` FROM `device` ";
    $names = mysqli_query($connect,  $sql2);
    $names  = mysqli_fetch_all($names);
    $count_name = mysqli_num_rows(mysqli_query($connect,  $sql2));


    ?>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 ">

                    <button class="button loadexel">
                        Выгрузить в Exel
                    </button>
                </div>

            </div>
        </div>
    </section>



    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 ">



                    <table class="stat" id="tableexl">
                        <thead>
                            <tr>

                                <td>

                                </td>


                                <td colspan=<?= $count_type ?>>
                                    Типы
                                </td>
                                <td colspan=<?= $count_name ?>>
                                    Наименования
                                </td>
                                <td>
                                    Итого
                                </td>

                            </tr>
                        </thead>

                        <tr>
                            <td>

                            </td>


                            <?php
                            foreach ($types as $type) {


                            ?>

                                <td>
                                    <?= $type[0] ?>
                                </td>

                            <?php } ?>


                            <?php
                            foreach ($names as $name) {


                            ?>

                                <td>
                                    <?= $name[0] ?>
                                </td>

                            <?php } ?>


                            <td>
                                <!-- сюда ставит код -->



                            </td>


                        </tr>





                        <?php

                        foreach ($distrs as $distr) {

                            if ($distr[0] == "admin") continue;



                        ?>

                            <tr>
                                <td>
                                    <?= $distr[0] ?>
                                </td>

                                <?php foreach ($types as $type) {  ?>
                                    <td>
                                        <?php echo mysqli_num_rows(mysqli_query($connect,  "SELECT `dev_type` FROM `device`, `users` WHERE users.id=device.distr_id and `dev_type`='$type[0]' and users.distr='$distr[0]'")); ?>
                                    </td>

                                <?php } ?>

                                <?php foreach ($names as $name) {  ?>
                                    <td>
                                        <?php echo mysqli_num_rows(mysqli_query($connect,  "SELECT `dev_name` FROM `device`, `users` WHERE users.id=device.distr_id and `dev_name`='$name[0]' and users.distr='$distr[0]'")); ?>
                                    </td>

                                <?php } ?>

                                <td class="bol">
                                    <?php echo mysqli_num_rows(mysqli_query($connect,  "SELECT `dev_name` FROM `device`, `users` WHERE users.id=device.distr_id  and users.distr='$distr[0]'")); ?>
                                </td>



                            </tr>


                        <?php

                        }

                        ?>



                        <tr class="bol">
                            <td>
                                Итого:
                            </td>
                            <?php foreach ($types as $type) {  ?>
                                <td>
                                    <?php echo mysqli_num_rows(mysqli_query($connect,  "SELECT `dev_type` FROM `device`, `users` WHERE users.id=device.distr_id and `dev_type`='$type[0]'")); ?>
                                </td>

                            <?php } ?>

                            <?php foreach ($names as $name) {  ?>
                                <td>
                                    <?php echo mysqli_num_rows(mysqli_query($connect,  "SELECT `dev_name` FROM `device`, `users` WHERE users.id=device.distr_id and `dev_name`='$name[0]' ")); ?>
                                </td>

                            <?php } ?>

                            <td>
                                Всего: <?php echo mysqli_num_rows(mysqli_query($connect,  "SELECT `dev_name` FROM `device`, `users` WHERE users.id=device.distr_id ")); ?>
                            </td>


                        </tr>




                    </table>
                </div>
            </div>
        </div>

    </section>






    <!-- подключение jqweri -->
    <script src=" assets/js/jquery-3.5.1.min.js"> </script>
    <script src="assets/js/main.js">
    </script>
    <script src="assets/js/jquery.table2excel.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script> -->

    <script>
        $(document).ready(function() {




            $('.loadexel').click(function() {
                $('#tableexl').table2excel({
                    exclude: ".noExl",
                    name: "SI",
                    filename: "Выгрузка_в_Exel.xls",
                    fileext: ".xls", //File extension type 
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true,


                });


            });
        });
    </script>
    <!-- Конец модального окна -->






</body>

</html>