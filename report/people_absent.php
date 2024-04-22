<?php
if($_SERVER['REQUEST_METHOD']=='POST'){

//	include 'DatabaseConfig.php';
	include 'DatabaseConfig_postgre.php';

	$output = array();
	$myArray = array();

	if(isset($_POST['type']) && isset($_POST['division']) && isset($_POST['department'])){
		$type = $_POST['type'];
		$division = $_POST['division'];
		$department = $_POST['department'];
//		$mysqli = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
		$db_connection = pg_connect("host={$HostName} port={$Port} dbname={$DatabaseName} user={$HostUser} password={$HostPass}");

//		if ($mysqli->connect_errno) {
		if (!$db_connection) {
			echo("Failed to connect to database");
			exit();
		}

		$first_day_this_month = date('m-01-Y');
		$current_day_this_month = date('m-d-Y');

		$sql = "SELECT * FROM edwcsr.reporting.people_absenteeism_daily_app WHERE date = (SELECT max(date) FROM edwcsr.reporting.people_absenteeism_daily_app) ORDER BY division";
		if($type == 1)
			$sql = "SELECT max(date) as date, division as division, count(division) AS count FROM edwcsr.reporting.people_absenteeism_daily_app WHERE date = (SELECT max(date) FROM edwcsr.reporting.people_absenteeism_daily_app) GROUP BY division ORDER BY division";
		else if($type == 2)
			if(!empty($division))
				if (preg_match('/\bMD\b/', $division))
					$sql = "SELECT max(date) as date, department as department, count(department) AS count FROM edwcsr.reporting.people_absenteeism_daily_app WHERE date = (SELECT max(date) FROM edwcsr.reporting.people_absenteeism_daily_app) AND division LIKE '%MD%' GROUP BY department ORDER BY department";
				else
					$sql = "SELECT max(date) as date, department as department, count(department) AS count FROM edwcsr.reporting.people_absenteeism_daily_app WHERE date = (SELECT max(date) FROM edwcsr.reporting.people_absenteeism_daily_app) AND division='".$division."' GROUP BY department ORDER BY department";
			else
				$sql = "SELECT max(date) as date, department as department, count(department) AS count FROM edwcsr.reporting.people_absenteeism_daily_app WHERE date = (SELECT max(date) FROM edwcsr.reporting.people_absenteeism_daily_app)  GROUP BY department ORDER BY department";
		else if($type == 3){
			if(!empty($division)){
				if(!empty($department))
					if (preg_match('/\bMD\b/', $division))
						$sql = "SELECT * FROM edwcsr.reporting.people_absenteeism_daily_app WHERE date = (SELECT max(date) FROM edwcsr.reporting.people_absenteeism_daily_app) AND division LIKE '%MD%' AND department LIKE '%MD%' ORDER BY total_absent_current_month DESC";
					else
						$sql = "SELECT * FROM edwcsr.reporting.people_absenteeism_daily_app WHERE date = (SELECT max(date) FROM edwcsr.reporting.people_absenteeism_daily_app) AND division='".$division."' AND department='".$department."' ORDER BY total_absent_current_month DESC";
				else
					$sql = "SELECT * FROM edwcsr.reporting.people_absenteeism_daily_app WHERE date = (SELECT max(date) FROM edwcsr.reporting.people_absenteeism_daily_app) AND division='".$division."' ORDER BY total_absent_current_month DESC";
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