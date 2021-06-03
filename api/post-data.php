<?php
include('../connection.php');
$connect = mysqli_connect($server, $user, $pass, $db);
if (!$connect) die('Connection Failed' . mysqli_connect_error());

$temperature = $_POST['temperature'];
$humidity = $_POST['humidity'];

$query = mysqli_query($connect, "INSERT INTO datas (temperature, humidity) VALUES ('$temperature', '$humidity')");

if ($query) echo "Data berhasil ditambahkan";
