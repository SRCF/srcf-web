<?php
require_once('/societies/srcf-web/paypal/donate-mysql.inc.php');
$conn = mysqli_connect($mysql_host, $mysql_username, $mysql_password);
mysqli_select_db($conn, $mysql_database);
$result = mysqli_query($conn, "SELECT SUM(amount) FROM donations WHERE ts >= NOW() - INTERVAL 1 YEAR");
if (!$result)
	echo mysqli_error($conn);
else {
	$donationtotal = mysqli_fetch_row($result);
	echo ($donationtotal[0] ? $donationtotal[0] : "0.00");
}
?>
