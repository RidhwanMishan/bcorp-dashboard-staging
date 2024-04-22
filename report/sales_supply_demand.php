<?php
if($_SERVER['REQUEST_METHOD']=='POST') {

	include 'DatabaseConfig_postgre.php';

	$output = array();
	$myArray = array();

	$db_connection = pg_connect("host={$HostName} port={$Port} dbname={$DatabaseName} user={$HostUser} password={$HostPass}");

	if (!$db_connection) {
		echo("Failed to connect to database");
		exit();
	}

	$result_array = array();

	$sql = "select * from edwcsr.reporting.sales_vs_production";

	$result = pg_query($db_connection, $sql);
	if ($result) {
		while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
			$result_array[] = $row;
		}
		$output['result'] = 'success';
		$output['data'] = $result_array;
		echo json_encode($output);
	} else {
		$output['result'] = 'failed';
		$output['data'] = $result_array;
		echo json_encode($output);
	}


	pg_close($db_connection);

}
?>