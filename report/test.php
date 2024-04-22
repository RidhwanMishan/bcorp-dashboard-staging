<?php
if($_SERVER['REQUEST_METHOD']=='POST'){

	// include 'DatabaseConfig2.php';

	$mysqli = mysqli_connect("52.77.70.5", "redshiftadmin", "8t_HhyFr9Yx63dL", "edwcsr", "5439");

	if ($mysqli->connect_errno) {
		echo("Failed to connect to database");
		exit();
	}
	$output = array();
	$myArray = array();

	$sql = "SELECT
		contract_number,
		contract_end_date
	FROM
		edwcsr.reporting.sales_contract_balance_latest

	ORDER BY contract_end_date";


	

// 	if ($mysqli->connect_errno) {
// 		echo("Failed to connect to database");
// 		exit();
// 	}
// 	$output = array();
// 	$myArray = array();

// 	$sql = "SELECT
// 			-- contract_balance_latest_skey,
// 			contract_number,
// 			-- customer_name,
// 			-- contract_start_date,
// 			contract_end_date
// 			-- contract_period_short,
// 			-- contract_amount_metric_ton,
// 			-- contract_balance_percentage,
// 			-- actual_sales_to_date_ton,
// 			-- expected_sales_to_date_ton
// 		FROM
// 			edwcsr.reporting.sales_contract_balance_latest
// 		ORDER BY contract_end_date";
// 	if ($result = $mysqli->query($sql)) {

// 		while($row = $result->fetch_array(MYSQLI_ASSOC)) {
// 			$myArray[] = $row;
// 		}
// 		$output['result'] = 'success';
// 		$output['data'] = $myArray;
// 		echo json_encode($output);

// 	}
// 	else{
// 		$output['result'] = 'failed';
// 		$output['data'] = $myArray;
// 		echo json_encode($output);

// 	}
// 	$result->close();
// 	$mysqli->close();
// }
?>