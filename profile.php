<?php
session_start();
require_once 'vendor/connect.php';
require_once 'vendor/functions.php';

if (!$_SESSION['user'] || $_SESSION['user']['access'] == "1") {
    header('Location: /');
}


?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Радиостанции</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/main.css">


</head>

<body>

    <!-- Шапка -->
    <header>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 ">
                    <div class="logout">
                        <a href="vendor/logout.php">Выход</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 ">
                    <h1> <?php echo $_SESSION['user']['distr']; ?> </h1>
                </div>
            </div>
        </div>
    </header>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 ">
                    <button class="button popup-add-btn">
                        Добавить
                    </button>
                    <button class="button popup-select-btn  <?php if ($_SESSION['sql']['btn'])
                                                                echo "select-color";
                                                            ?>">
                        Фильтр
                    </button>

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
                    <table id="tableexl">
                        <thead>
                            <tr>
                                <th>Тип</th>
                                <th>Наименование</th>
                                <th>Модель</th>
                                <th>Заводской номер</th>

                                <th>Место установки</th>
                                <th class="noExl">Фото</th>


                            </tr>
                        </thead>

                        <?php

                        if (empty($_SESSION['sql']['sql'])) {
                            $sql = "SELECT DISTINCT `dev_id`,  `dev_type`,`dev_name`,`dev_marka`,`zav_num`, `location`, `img`, `akt`,`status` FROM `device`, `users` WHERE users.id={$_SESSION['user']['id']} and users.id=device.distr_id";
                        } else {
                            $sql = $_SESSION['sql']['sql'];
                        }
                        $devices = mysqli_query($connect,  $sql);
                        $devices = mysqli_fetch_all($devices);
                        foreach ($devices as $device) {
                            // getExtension  подключенная функция
                            if (!empty($device[6]) && getExtension($device[6]) === "pdf") {
                                $img = $device[6];
                                $device[6] = '<a href="' . $img  . '" target="_blank"> <img src="assets\img\pdf.png" width="50" alt=""></a>';
                            } elseif (!empty($device[6]) && (getExtension($device[6]) === "jpg" || getExtension($device[6]) === "png")) {
                                $img = $device[6];
                                $device[6] = '<a href="' . $device[6] . '" target="_blank"> <img src="assets\img\jpg.png" width="50" alt=""></a>';
                            } else $img = "1";

                            if ($device[8] == "1") {
                                echo '<tr id="tbody" class="eloy">';
                            } else {
                                echo '<tr id="tbody">';
                            }

                            echo '

                    
                                    
                                    <td style="cursor: pointer;">' . $device[1] . '</td>
                                    <td>' . $device[2] . '</td>
                                    <td>' . $device[3] . '</td>
                                    <td>' . $device[4] . '</td>
                                    <td>' . $device[5] . '</td>
                                    <td class="noExl">' . $device[6] . '</td>
                                    <td class="col_id noExl">' . $device[0] . '</td>
                                    <td class="col_id noExl">' . $device[7] . '</td>
                                    <td class="col_id noExl">' . $device[8] . '</td>
                                    <td class="col_id noExl">' . $img . '</td>
                                  
                                  
                                   </tr>
                                ';
                        }

                        ?>

                    </table>
                </div>
            </div>
        </div>

    </section>



    <!-- Модальное окно ДОБАВИТЬ ПРИБОР -->
    <section>

        <div class="popup-add">

            <div class="popup-content">
                <button class="popup-close">&times;</button>
                <h4 class="popup-header">
                    Добавить
                </h4>
                <div id="form_order">
                    <form class="addform">

                        <div>
                            <label>Тип:</label> <input type="text" name="type" />
                        </div>
                        <div>
                            <label>Наименование:</label> <input type="text" name="name" />
                        </div>
                        <div>
                            <label>Модель:</label> <input type="text" name="marka" />
                        </div>
                        <div>
                            <label>Заводской номер:</label> <input type="text" name="zav_number" />
                        </div>
                        <div>
                            <label>Место установки:</label> <input type="text" name="location1" />
                        </div>
                        <div>
                            <label>Фото:</label> <input type="file" name="img" />
                        </div>

                        <div class="popup-add-subbtn">
                            <input type="submit" class="add-btn" value="Добавить" />
                        </div>
                        <div class="popup-add-msg">
                            <p class="gifload  none"></p>
                            <p class="msg  none">LOrem</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>


    <!-- Модальное окно Выборка -->

    <section>
        <div class="popup-select">


            <div class="popup-content">
                <button class="popup-close">&times;</button>
                <h4 class="popup-header">
                    Фильтр
                </h4>
                <div id="form_order">
                    <form class="addform">


                        <div>
                            <label>Тип:</label> <input type="text" name="type2" value="<?php if ($_SESSION['form_select']['type']) { ?><?= $_SESSION['form_select']['type'] ?><?php } ?>" />
                        </div>
                        <div>
                            <label>Наименование:</label> <input type="text" name="name2" value="<?php if ($_SESSION['form_select']['name']) { ?><?= $_SESSION['form_select']['name'] ?><?php } ?>" />
                        </div>
                        <div>
                            <label>Модель:</label> <input type="text" name="marka2" value="<?php if ($_SESSION['form_select']['marka']) { ?><?= $_SESSION['form_select']['marka'] ?><?php } ?>" />
                        </div>
                        <div>
                            <label>Заводской номер:</label> <input type="text" name="zav_number2" value="<?php if ($_SESSION['form_select']['zav_number']) { ?><?= $_SESSION['form_select']['zav_number'] ?><?php } ?>" />
                        </div>
                        <div>
                            <label>Место установки:</label> <input type="text" name="location2" value="<?php if ($_SESSION['form_select']['location2']) { ?><?= $_SESSION['form_select']['location2'] ?><?php } ?>" />
                        </div>


                        <div class="popup-select-subbtn">
                            <input type="submit" class="select-btn" value="Применить" />
                        </div>
                        <div class="popup-select-subbtn">
                            <input type="submit" class="clean-btn" value="Сбросить" />
                        </div>
                        <div class="popup-select-msg">
                            <p class="gifload  none"></p>
                            <p class="msg  none">LOrem</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>

    <!-- Модальное окно Изменить -->

    <section>
        <div class="popup-change">


            <div class="popup-content">
                <button class="popup-close">&times;</button>
                <h4 class="popup-header">
                    Свойства
                </h4>

                <!-- Навигация вкладки -->
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#description">Изменение</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#characteristics">Списание</a>
                    </li>

                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="description">


                        <form class="addform">


                            <div>
                                <input type="hidden" name="dev_id" id="dev_id" />
                            </div>
                            <div>
                                <label>Тип:</label> <input type="text" name="type3" id="type" />
                            </div>
                            <div>
                                <label>Наименование:</label> <input type="text" name="name3" id="name" disabled="" />
                            </div>
                            <div>
                                <label>Модель:</label> <input type="text" name="marka3" id="marka" disabled="" />
                            </div>
                            <div>
                                <label>Заводской номер:</label> <input type="text" name="zav_number3" id="zav_number" disabled="" />
                            </div>
                            <div>
                                <label>Место установки:</label> <input type="text" name="location3" id="location2" />
                            </div>
                            <div>
                                <label>Фото:</label>



                                <input class="vibor" type="file" name="img" />
                                <div class="per"> <a id="seatch_bt" href="" target="_blank">
                                        <img src="assets\img\file.png" width="50"></a>
                                    <img class="del-btn" src="assets\img\del.png" width="20">

                                </div>




                            </div>

                            <div class="popup-add-subbtn">
                                <input type="submit" class="change-btn" value="Сохранить" />
                            </div>
                            <div class="popup-add-msg">
                                <p class="gifload  none"></p>
                                <p class="msg  none">LOrem</p>
                            </div>
                        </form>

                    </div>
                    <div class="tab-pane fade" id="characteristics">
                        <form class="addform">

                            <label>АКТ списания:</label>
                            <input class="vibor1" type="file" name="akt" />
                            <div class="per1"> <a id="akt_bt" href="" target="_blank">
                                    <img src="assets\img\file.png" width="50"></a>
                                <img class="del-btn-akt" src="assets\img\del.png" width="20">

                            </div>

                            <label>Статус:</label> <input type="text" name="status" id="status" disabled="" />

                            <div class="popup-spisat-subbtn">
                                <input type="submit" class="spisat-btn" value="Списать" />
                            </div>
                            <div class="popup-add-msg">
                                <p class="gifload  none"></p>
                                <p class="msg  none">LOrem</p>
                            </div>

                        </form>
                    </div>

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

            $(".popup-add-btn").on("click", function() {
                $(".popup-add").fadeIn();
                $(".msg").addClass("none");
                $(`input`).removeClass("error");

            });



            $(".popup-select-btn").on("click", function() {
                $(".popup-select").fadeIn();
                $(".msg").addClass("none");
                $(`input`).removeClass("error");

            });
            $("tr#tbody").on("dblclick", function() {
                $(".popup-change").fadeIn();
                $(".msg").addClass("none");
                $(`input`).removeClass("error");

                let type = $(this).children('td:first-child').text();
                let name = $(this).children('td:nth-child(2)').text();
                let marka = $(this).children('td:nth-child(3)').text();
                let zav_number = $(this).children('td:nth-child(4)').text();
                let location2 = $(this).children('td:nth-child(5)').text();
                let dev_id = $(this).children('td:nth-child(7)').text();
                pasport = $(this).children('td:nth-child(10)').text();
                let status = $(this).children('td:nth-child(9)').text();
                let akt = $(this).children('td:nth-child(8)').text();
                $('#type').val(type);
                $('#name').val(name);
                $('#marka').val(marka);

                $('#zav_number').val(zav_number);
                $('#location2').val(location2);
                $('#dev_id').val(dev_id);
                $('#status').val(getstringstatus(status));


                function getstringstatus($num) {
                    if ($num === "") {
                        $status = "В работе"
                    }
                    if ($num === "1") {
                        $status = "На подтверждении"
                    }
                    if ($num === "3") {
                        $status = "Списан"
                    }
                    return $status;
                }
                // Обработка поля загрузки файла паспорта
                document.getElementById('seatch_bt').href = pasport;
                if (pasport === "1") {
                    $(".per").removeClass("del");
                    $(".vibor").removeClass("none");
                    $(".per").addClass("none");


                } else {
                    $(".vibor").addClass("none");
                    $(".per").removeClass("none");
                    $(".per").addClass("del");

                }
                // Обработка поля загрузки файла списания
                document.getElementById('akt_bt').href = akt;
                if (akt === "") {
                    $(".per1").removeClass("del");
                    $(".vibor1").removeClass("none");
                    $(".per1").addClass("none");


                } else {
                    $(".vibor1").addClass("none");
                    $(".per1").removeClass("none");
                    $(".per1").addClass("del");

                }



            });



            $(".popup-close").on("click", function() {
                $(".popup-add").fadeOut();
                $(".popup-select").fadeOut();
                $(".popup-change").fadeOut();
            });


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