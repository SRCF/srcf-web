<?php
require_once('/groups/srcf-web/paypal/donate-mysql.inc.php');
$conn = mysqli_connect($mysql_server, $mysql_username, $mysql_password);
mysqli_select_db($conn, $mysql_database);

do {
	$result = mysqli_query($conn, "SELECT amount, name, url, DATE_FORMAT(ts, '%D %M %Y') AS date FROM donations ORDER BY ts DESC, amount DESC");
	if (!$result) {
		echo mysqli_error($conn);
		break;
	}
	echo '<table class="donors table table-sm">' . "\n";
	echo '<thead><tr><th>Amount</th><th>Donor</th><th>Date</th></tr></thead><tbody>' . "\n";
	while ($data = mysqli_fetch_assoc($result)) {
		echo '<tr><td>&pound;' . htmlspecialchars($data['amount']) . '</td><td>';
		if ($data['name'] != '') {
			if ($data['url'] != '')
				echo '<a rel="nofollow" href="' . htmlspecialchars($data['url']) . '">';
			echo htmlspecialchars($data['name']);
			if ($data['url'] != '')
				echo '</a>';
		} else
			echo '<i>Anonymous donation</i>';
		echo '</td><td>' . htmlspecialchars($data['date']) . '</td></tr>' . "\n";
	}
	echo '</tbody></table>' . "\n";
} while(0);
?>
