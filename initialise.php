<?php
/*
 * DB Initialisation PHP Script
 */
require_once('functions.php');
if (isset($_POST['host'])) {
    // create file 'dbinfo.php'
    $fp = fopen('dbinfo.php', 'w');
    $l1 = '$host="' . $_POST['host'] . '";';
    $l2 = '$user="' . $_POST['username'] . '";';
    $l3 = '$password="' . $_POST['password'] . '";';
    $l4 = '$database="' . $_POST['name'] . '";';
    fwrite($fp, "<?php\n$l1\n$l2\n$l3\n$l4\n?>");
    fclose($fp);
    include('dbinfo.php');
    // connect to the mysqli server
    $connection = mysqli_connect($host, $user, $password);
    if (!$connection) {
        die('Error connecting to the database: ' . mysqli_connect_error());
    }
    // create the database
    $createDbQuery = "CREATE DATABASE IF NOT EXISTS $database";
    if (!mysqli_query($connection, $createDbQuery)) {
        die('Error creating database: ' . mysqli_error($connection));
    }
    mysqli_select_db($connection, $database);
    // create the users table
    $createUsersTableQuery = "CREATE TABLE IF NOT EXISTS `users` (
        `uid` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(25) NOT NULL,
        `random` varchar(6) NOT NULL,
        `hash` varchar(80) NOT NULL,
        `email` varchar(100) NOT NULL,
        `gender` varchar(10) NOT NULL,
        `contactno` bigint(50) NOT NULL,
        `description` text,
        `credits` int(11) DEFAULT '0',
        PRIMARY KEY (`uid`)
    )";
    if (!mysqli_query($connection, $createUsersTableQuery)) {
        die('Error creating table "users": ' . mysqli_error($connection));
    }

    // create the offers table
    $createOffersTableQuery = "CREATE TABLE IF NOT EXISTS `offers` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `uid` int(11) NOT NULL,
        `from` varchar(250) NOT NULL,
        `to` varchar(250) NOT NULL,
        `uptime` datetime NOT NULL,
        `downtime` datetime NOT NULL,
        `people` int(11) NOT NULL DEFAULT '1',
        `price` int(11) NOT NULL DEFAULT '0',
        `vehicle` varchar(250) NOT NULL,
        `description` text,
        PRIMARY KEY (`id`)
    )";
    if (!mysqli_query($connection, $createOffersTableQuery)) {
        die('Error creating table "offers": ' . mysqli_error($connection));
    }

    // create the notifications table
    $createNotificationsTableQuery = "CREATE TABLE IF NOT EXISTS `notifications` (
        `slno` int(11) NOT NULL AUTO_INCREMENT,
        `sender` int(11) NOT NULL,
        `receiver` int(11) NOT NULL,
        `type` int(11) NOT NULL,
        `cid` int(11) NOT NULL,
        PRIMARY KEY(`slno`)
    )";
    if (!mysqli_query($connection, $createNotificationsTableQuery)) {
        die('Error creating table "notifications": ' . mysqli_error($connection));
    }

    // create the comments table
    $createCommentsTableQuery = "CREATE TABLE IF NOT EXISTS `comments` (
        `slno` int(11) NOT NULL AUTO_INCREMENT,
        `sender` int(11) NOT NULL,
        `comment` text NOT NULL,
        `cid` int(11) NOT NULL,
        PRIMARY KEY(`slno`)
    )";
    if (!mysqli_query($connection, $createCommentsTableQuery)) {
        die('Error creating table "comments": ' . mysqli_error($connection));
    }

    // create the route table
    $createRouteTableQuery = "CREATE TABLE IF NOT EXISTS `route` (
        `routeid` int(11) NOT NULL AUTO_INCREMENT,
        `cid` int(11) NOT NULL,
        `place` varchar(250) NOT NULL,
        `serialno` int(11) NOT NULL,
        PRIMARY KEY (`routeid`)
    )";
    if (!mysqli_query($connection, $createRouteTableQuery)) {
        die('Error creating table "route": ' . mysqli_error($connection));
    }

    // create the user 'admin' with password 'admin'
    $random = randomNum(5);
    $pass = "admin";
    $hash = crypt($pass, $random);
    $email = $_POST['email'];
    $gender = "M"; // Replace with the appropriate value for gender
    $insertAdminQuery = "INSERT INTO `users` (`name`, `random`, `hash`, `email`, `gender`) VALUES ('$pass', '$random', '$hash', '$email', '$gender')";
    if (!mysqli_query($connection, $insertAdminQuery)) {
      die('Error creating admin user: ' . mysqli_error($connection));
  }

  header("Location: initialise.php?installed=1");
  exit;
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Car Pool</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>

  <body>


    <!-- Part 1: Wrap all page content here -->
    <div id="wrap">

      <!-- Fixed navbar -->
      <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
          <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="brand" href="#">Car Pooling</a>
            </div>
        </div>
      </div>

      <!-- Begin page content -->
      <div class="container">
        <?php
      if(isset($_GET['installed'])) {
    ?>
        <div class="alert alert-success">car-pool is successfully initialised!</div>
        You can login to the car-pool panel <a href="login.php">here</a>.
          <?php  }else if(!file_exists("dbinfo.php")){ ?>
      <h1><small>DB Details</small></h1>
    <form action="initialise.php" method="post">
      Database Host: <input type="text" name="host" value="localhost"/><br/>
      Username: <input type="text" name="username"/><br/>
      Password: <input type="password" name="password"/><br/>
      Database Name: <input type="text" name="name" value="carpool"/><br/>
      Email: <input type="email" name="email"/><br/>
      <input type="submit" class="btn btn-primary" value="Initialise"/>
    </form>
      <?php } else {?>
      <div class="alert alert-error">Database is already initialised. Please remove the dbinfo.php and re-initialise it.</div>
    <?php } ?>
      </div>
      <div id="push"></div>
    </div>

    <div id="footer">
      <div class="container">
        <p class="muted credit">Built with love by <a href="about.php">@sushant @ashish @nilesh.</p>
      </div>
    </div>



    <!-- javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>
