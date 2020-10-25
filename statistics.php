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
                    <h1>Пользователи </h1>
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



    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 ">

                    <table id="tableexl">



                        <tr>
                            <td>
                                Чернянский район
                            </td>


                        </tr>








                        <?php


                        $sql = "SELECT `first_name`,`last_name`,`patronymic`,`distr`,`login`,`password`,`access`, `id`  FROM `users` ";




                        $users = mysqli_query($connect,  $sql);
                        $users = mysqli_fetch_all($users);


                        ?>




                    </table>
                </div>
            </div>
        </div>

    </section>






    <!-- подключение jqweri -->
    <script src="assets/js/jquery-3.5.1.min.js"></script>
    <script src="assets/js/main.js"></script>
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