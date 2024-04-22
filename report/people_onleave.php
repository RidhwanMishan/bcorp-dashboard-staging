<?php
if($_SERVER['REQUEST_METHOD']=='POST'){

//	include 'DatabaseConfig.php';
	include 'DatabaseConfig_postgre.php';

	$output = array();
	$myArray = array();

	if(isset($_POST['type']) && isset($_POST['division']) && isset($_POST['leave_type'])){
		$type = $_POST['type'];
		$division = $_POST['division'];
		$leave_type = $_POST['leave_type'];

//		$mysqli = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
		$db_connection = pg_connect("host={$HostName} port={$Port} dbname={$DatabaseName} user={$HostUser} password={$HostPass}");

//		if ($mysqli->connect_errno) {
		if (!$db_connection) {
			echo("Failed to connect to database");
			exit();
		}
		$sql = "SELECT * FROM edwcsr.reporting.people_leave_mobile WHERE date = (SELECT max(date) FROM edwcsr.reporting.people_leave_mobile) ORDER BY division";
		if($type == 1)
			$sql = "SELECT max(date) as date, division as division, count(division) AS count FROM edwcsr.reporting.people_leave_mobile WHERE date = (SELECT max(date) FROM edwcsr.reporting.people_leave_mobile) GROUP BY division ORDER BY division";
		else if($type == 2)
			if(!empty($division))
				$sql = "SELECT max(date) as date, leave_type as leave_type, count(leave_type) AS count FROM edwcsr.reporting.people_leave_mobile WHERE date = (SELECT max(date) FROM edwcsr.reporting.people_leave_mobile) AND division='".$division."' GROUP BY leave_type ORDER BY leave_type";
			else
				$sql = "SELECT max(date) as date, leave_type as leave_type, count(leave_type) AS count FROM edwcsr.reporting.people_leave_mobile WHERE date = (SELECT max(date) FROM edwcsr.reporting.people_leave_mobile)  GROUP BY leave_type ORDER BY leave_type";
		else if($type == 3){
			if(!empty($division)){
				if(!empty($leave_type))
					$sql = "SELECT * FROM edwcsr.reporting.people_leave_mobile WHERE date = (SELECT max(date) FROM edwcsr.reporting.people_leave_mobile) AND division='".$division."' AND leave_type='".$leave_type."' ORDER BY leave_type";
				else
					$sql = "SELECT * FROM edwcsr.reporting.people_leave_mobile WHERE date = (SELECT max(date) FROM edwcsr.reporting.people_leave_mobile) AND division='".$division."' ORDER BY leave_type";
			}
		}


		$result = pg_query($db_connection, $sql);
//		if ($result = $mysqli->query($sql)) {
		if ($result) {
//			while($row = $result->fetch_array(MYSQLI_ASSOC)) {
			while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
				$myArray[] = $row;
			}
			$output['result'] = 'success';
			$output['data'] = $myArray;
			echo json_encode($output);

		}
		else{
			$output['result'] = 'failed';
			$output['data'] = $myArray;
			echo json_encode($output);

		}
//		$result->close();
//		$mysqli->close();
		pg_close($db_connection);

	}
	else{
		$output['result'] = 'failed';
		$output['data'] = $myArray;
		echo json_encode($output);
	}
}
?>