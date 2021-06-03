<?php
include('connection.php');
$connect = mysqli_connect($server, $user, $pass, $db);
if (!$connect) die('Connection Failed' . mysqli_connect_error());

$query = mysqli_query($connect, 'SELECT * FROM datas ORDER BY id DESC');
$result = mysqli_fetch_array($query);

if ($result != null) echo json_encode($result);
