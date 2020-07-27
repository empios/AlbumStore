<?php


$mysqlConnection = new mysqli('localhost', 'root', '', 'interview') or die(mysqli_error($mysqlConnection));
$year = '';
$update = false;

if (isset($_POST['saveartist'])){
    $artistName = $_POST['artistName'];
    header("location: index.php");
    $mysqlConnection -> query("INSERT INTO wykonawca(nazwa) VALUES ('$artistName')") or die($mysqlConnection -> error);
}

if(isset($_POST['savealbum'])){
    $albumName = $_POST['albumName'];
    $artists = $_POST['artists'];

    $result = $mysqlConnection -> query("SELECT * from wykonawca WHERE nazwa = '$artists'");
    $fetch = $result -> fetch_row();
    $artistId = $fetch[0];
    $year = $_POST['year'];
    header("location: index.php");
    $mysqlConnection -> query("INSERT INTO album(nazwa, wykonawca_id, rok_wydania) VALUES ('$albumName', $artistId, '$year')") or die($mysqlConnection -> error);
}

if(isset($_POST['savesong'])){
    $songName = $_POST['songName'];
    $songs = $_POST['songs'];

    $result = $mysqlConnection -> query("SELECT * from album WHERE nazwa = '$songs'");
    $fetch = $result -> fetch_row();
    $songId = $fetch[0];
    header("location: index.php");
    $mysqlConnection -> query("INSERT INTO utwor(nazwa, album_id) VALUES ('$songName', $songId)") or die($mysqlConnection -> error);
}


if (isset($_GET['delete'])){
    $id = $_GET['delete']; 
    $mysqlConnection->query("DELETE from utwor WHERE utwor_id=$id") or die($mysqlConnection->error);
    header("location: index.php");
}


    if (isset($_POST['editSave'])){
        $id = $_POST['id'];
        $songName = $_POST['songName'];
        $artistName = $_POST['artistName'];
        $albumName = $_POST['albumName'];
        $year = $_POST['year'];

        header('location: index.php');
    }
