<?php

$connect = mysqli_connect('localhost', 'root', 'root', 'comunik');

if (!$connect) {
    die('Error connect to DataBase');
}
