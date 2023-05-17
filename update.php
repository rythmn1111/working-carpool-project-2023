<?php
include('functions.php');
connectdb();

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'shareride') {
        $uid = getUserid();
        $from = $_POST['from'];
        $to = $_POST['to'];
        $uptime = $_POST['uptime'];
        $vehicle = "car";
        $time = $_POST['time'];
        $cost = $_POST['cost'];
        $desc = $_POST['description'];
        $mode = $_POST['vehicle'];
        $number = $_POST['number'];
        if ($mode == "car") {
            $vehicle = "car";
        } else if ($mode == "auto") {
            $vehicle = "auto";
        } else if ($mode == "taxi") {
            $vehicle = "taxi";
        }
		$connection = mysqli_connect('localhost:2023', 'root', '', 'carpool'); // Replace with your database credentials

        mysqli_query($connection, "INSERT INTO `offers` (`uid`, `from`, `to`, `uptime`, `people`, `price`, `vehicle`, `description`) VALUES 
            ('$uid', '$from', '$to', '$uptime', '$number', '$cost', '$vehicle', '$desc')") or die(mysqli_error($connection));

        $query = "SELECT id FROM offers WHERE `uid`='$uid' AND `from`='$from' AND `to`='$to' AND `uptime`='$uptime' AND `people`='$number' AND `price`='$cost' AND `vehicle`='$vehicle' AND `description`='$desc'";
        $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
        $ans = mysqli_fetch_array($result);
        $cid = $ans['id'];
        mysqli_query($connection, "INSERT INTO route (`cid`, `place`, `serialno`) VALUES ('$cid', '$from', 1)") or die("pehla" . mysqli_error($connection));
        $num = $_POST['totalRequests'];
        for ($i = 1; $i <= $num; $i++) {
            $id = "dynamic" . (string)$i;
            $data = $_POST[$id];
            $j = $i + 1;
            mysqli_query($connection, "INSERT INTO route (`cid`, `place`, `serialno`) VALUES ('$cid', '$data', '$j')") or die("bichka" . mysqli_error($connection));
        }
        $j = $i + 1;
        mysqli_query($connection, "INSERT INTO route (`cid`, `place`, `serialno`) VALUES ('$cid', '$to', '$j')") or die("aakhri" . mysqli_error($connection));

        header("Location: index.php?share=1");
    }
}
?>
