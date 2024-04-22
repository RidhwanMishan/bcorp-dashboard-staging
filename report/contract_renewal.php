<?php
if($_SERVER['REQUEST_METHOD']=='GET') {

	include 'DatabaseConfig_postgre.php';

	// echo("host={$HostName} port={$Port} dbname={$DatabaseName} user={$HostUser} password={$HostPass}");

	$db_connection = pg_connect("host={$HostName} port={$Port} dbname={$DatabaseName} user={$HostUser} password={$HostPass}");

	if (!$db_connection) {
		echo("Failed to connect to database");
		exit();
	}

	$output = array();
	$myArray = array();

	$sql = "SELECT overall_segment, ytd_revenue, ytd_target, ytd_variance FROM edwbcorp.reporting.overallrevenue_lvl";

	$result = pg_query($db_connection, $sql);
	if ($result) {

		while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
			$myArray[] = $row;
		}

		$output['result'] = 'success';
		$output['data'] = $myArray;
		echo json_encode($output);

	}
	else {

		$output['result'] = 'failed';
		$output['data'] = $myArray;
		echo json_encode($output);

	}

	pg_close($db_connection);
}
?>