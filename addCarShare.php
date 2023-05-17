<?php
require_once('functions.php');
connectdb();
$connection = mysqli_connect('localhost:2023', 'root', '', 'carpool'); // Replace with your database credentials

$userid = $_POST["uid"];
$from = $_POST["from"];
$to = $_POST["to"];
$cid = $_POST["cid"];
$query = 'SELECT * FROM offers WHERE id="' . $cid . '"';
$result = mysqli_query($connection, $query) or die("error!!!" . mysqli_error($connection));
$row = mysqli_fetch_array($result);
$timestamp = date("Y-m-d H:i:s");

$sender = $row["uid"];
// pooler waiting for approval from owner

$query = 'INSERT INTO notifications (sender, receiver, type, cid, timestamp) VALUES("' . $userid . '","' . $userid . '","4","' . $cid . '","' . $timestamp . '")';

$result = mysqli_query($connection, $query) or die("error23!");

// sent for approval to car owner
$query = 'INSERT INTO notifications (sender, receiver, type, cid, timestamp) VALUES("' . $userid . '","' . $sender . '","1","' . $cid . '","' . $timestamp . '")';

$result = mysqli_query($connection, $query) or die("error!45!");
header("Location: index.php?success=1");
?>
