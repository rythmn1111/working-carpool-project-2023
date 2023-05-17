<?php
/*
 * Common functions used throughout Codejudge
 */
session_start();

// Connects to the database
function connectdb() {
  include('dbinfo.php');
  $conn = new mysqli($host, $user, $password, $database);
  if ($conn->connect_error) {
    die('Could not connect to MySQL: ' . $conn->connect_error);
  }
  return $conn;
}

// Generates a random number
function randomNum($length) {
  $rangeMin = pow(36, $length-1);
  $rangeMax = pow(36, $length)-1;
  $base10Rand = mt_rand($rangeMin, $rangeMax);
  $newRand = base_convert($base10Rand, 10, 36);
  return $newRand;
}

// Checks if any user is logged in
function loggedin() {
  return isset($_SESSION['username']);
}

// Gets the user ID
function getUserid() {
  $emailid = $_SESSION['username'];
  $conn = connectdb();
  $stmt = $conn->prepare("SELECT uid, name FROM users WHERE email = ?");
  $stmt->bind_param("s", $emailid);
  $stmt->execute();
  $stmt->bind_result($uid, $name);
  $stmt->fetch();
  $stmt->close();
  $conn->close();
  return $uid;
}

// Gets the user's name
function name() {
  $emailid = $_SESSION['username'];
  $conn = connectdb();
  $stmt = $conn->prepare("SELECT uid, name FROM users WHERE email = ?");
  $stmt->bind_param("s", $emailid);
  $stmt->execute();
  $stmt->bind_result($uid, $name);
  $stmt->fetch();
  $stmt->close();
  $conn->close();
  return $name;
}

// Gets the name by user ID
function getName($uid) {
  $conn = connectdb();
  $stmt = $conn->prepare("SELECT name FROM users WHERE uid = ?");
  $stmt->bind_param("i", $uid);
  $stmt->execute();
  $stmt->bind_result($name);
  $stmt->fetch();
  $stmt->close();
  $conn->close();
  return $name;
}
?>
