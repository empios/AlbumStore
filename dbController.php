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


if (isset($_GET['edit'])){
    $id = $_GET['edit'];
    $update = true;
    $result = $mysqlConnection->query("SELECT utwor.utwor_id as 'id', utwor.nazwa AS 'utwor',
    wykonawca.nazwa AS 'wykonawca', album.nazwa AS 'album', album.rok_wydania 
    FROM wykonawca inner JOIN album ON album.wykonawca_id = wykonawca.wykonawca_id 
    inner JOIN utwor ON utwor.album_id = album.album_id
    WHERE utwor.utwor_id = $id") or die($mysqlConnection->error);
    if ($result -> num_rows) {
        $row = $result->fetch_array();
        $songName = $row['utwor'];
        $artistName = $row['wykonawca'];
        $albumName = $row['album'];
        $year = $row['rok_wydania'];
    }

    if (isset($_POST['update'])){
        $id = $_POST['id'];
        $songName = $_POST['utwor'];
        $artistName = $_POST['wykonawca'];
        $albumName = $_POST['album'];
        $year = $_POST['year'];
        $mysqlConnection->query("UPDATE utwor SET nazwa='$songName', wzrost=$height WHERE id=$id") or die ($mysqlConnection->error);
        header('location: index.php');
    }


}