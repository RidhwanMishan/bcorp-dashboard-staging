<?php
if($_SERVER['REQUEST_METHOD']=='POST'){

	include 'DatabaseConfig_postgre.php';

	$output = array();
	$myArray = array();

	$db_connection = pg_connect("host={$HostName} port={$Port} dbname={$DatabaseName} user={$HostUser} password={$HostPass}");

//	if ($mysqli->connect_errno) {
	if (!$db_connection) {
		echo("Failed to connect to database");
		exit();
	}

	if(isset($_POST['type'])){
		date_default_timezone_set('Asia/Kuala_Lumpur');
		$type = $_POST['type'];
		if($type == 1){
			$sales_segment = $_POST['sales_segment'];
			
			$current_month = date('m');
			$current_year = date('Y');
			$last_year = $current_year - 1;
			$current_date = date('Y-m-d');
			$yesterday_date = date('Y-m-d', strtotime("-1 days"));

			$sql = "SELECT SUM(sales_target_day_metric_ton) as monthly_target_mt, SUM(sales_metric_ton) as mtd_sales_mt, SUM(sales_target_day_amount_rm) as monthly_target_rm, SUM(sales_amount_rm) as mtd_sales_rm FROM edwcsr.reporting.sales_per_segment_daily  data_table WHERE year = '".$current_year."' AND month='". $current_month. "'";
			if($sales_segment != ''){
				$sql = "SELECT SUM(sales_target_day_metric_ton) as monthly_target_mt, SUM(sales_metric_ton) as mtd_sales_mt, SUM(sales_target_day_amount_rm) as monthly_target_rm, SUM(sales_amount_rm) as mtd_sales_rm FROM edwcsr.reporting.sales_per_segment_daily  data_table WHERE year = '".$current_year."' AND month='". $current_month. "' AND sales_segment='".$sales_segment."'";
			}

			//	$result = $mysqli->query($sql);
			$result = pg_query($db_connection, $sql);
			if ($result) {

		//		while($row = $result->fetch_array(MYSQLI_ASSOC)) {
				while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
					$myArray[] = $row;
				}
				$output['monthly_data'] = $myArray;
			}

			$result_array = array();
			$sql = "SELECT SUM(sales_target_day_metric_ton) as monthly_target_mt, SUM(sales_metric_ton) as mtd_sales_mt, SUM(sales_target_day_amount_rm) as monthly_target_rm, SUM(sales_amount_rm) as mtd_sales_rm FROM edwcsr.reporting.sales_per_segment_daily  data_table WHERE year = '".$last_year."' AND month='". $current_month. "'";
			if($sales_segment != ''){
				$sql = "SELECT SUM(sales_target_day_metric_ton) as monthly_target_mt, SUM(sales_metric_ton) as mtd_sales_mt, SUM(sales_target_day_amount_rm) as monthly_target_rm, SUM(sales_amount_rm) as mtd_sales_rm FROM edwcsr.reporting.sales_per_segment_daily  data_table WHERE year = '".$last_year."' AND month='". $current_month. "' AND sales_segment='".$sales_segment."'";
			}
			$result = pg_query($db_connection, $sql);
			if ($result) {

				while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
					$result_array[] = $row;
				}
				$output['last_monthly_data'] = $result_array;
			}

			$result_array = array();
			$sql = "SELECT SUM(sales_target_day_metric_ton) as daily_target_mt, SUM(sales_target_day_amount_rm) as daily_target_rm FROM edwcsr.reporting.sales_per_segment_daily  data_table WHERE sales_date='". $current_date. "'";
			if($sales_segment != ''){
				$sql = "SELECT SUM(sales_target_day_metric_ton) as daily_target_mt, SUM(sales_target_day_amount_rm) as daily_target_rm FROM edwcsr.reporting.sales_per_segment_daily  data_table WHERE sales_date='". $current_date."' AND sales_segment='".$sales_segment."'";
			}
			$result = pg_query($db_connection, $sql);
			if ($result) {

				while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
					$result_array[] = $row;
				}
				$output['daily_data'] = $result_array;
			}

			$result_array = array();
			$sql = "SELECT SUM(sales_metric_ton) as daily_target_mt, SUM(sales_amount_rm) as daily_target_rm FROM edwcsr.reporting.sales_per_segment_daily  data_table WHERE sales_date='". $yesterday_date. "'";
			if($sales_segment != ''){
				$sql = "SELECT SUM(sales_metric_ton) as daily_target_mt, SUM(sales_amount_rm) as daily_target_rm FROM edwcsr.reporting.sales_per_segment_daily  data_table WHERE sales_date='". $yesterday_date."' AND sales_segment='".$sales_segment."'";
			}
			$result = pg_query($db_connection, $sql);
			if ($result) {

				while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
					$result_array[] = $row;
				}
				$output['yesterday_daily_data'] = $result_array;
			}

			$output['current_date'] = $current_date;
			$output['yesterday_date'] = $yesterday_date;
			$output['result'] = 'success';
			echo json_encode($output);

		}
		else if($type == 2){
			$sales_segment = array();
			$managers = array();
			$sql = "SELECT data_table.sales_segment as sales_segment FROM edwcsr.reporting.sales_per_segment_daily  data_table GROUP BY data_table.sales_segment";
			$result = pg_query($db_connection, $sql);
			if ($result) {
				while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
					$sales_segment[] = $row;
				}
			}

			$output['result'] = 'success';
			$output['sales_segment'] = $sales_segment;
			echo json_encode($output);

		}
	}
	else{
		$output['result'] = 'failed';
		$output['data'] = $myArray;
		echo json_encode($output);

	}
//	$result->close();
//	$mysqli->close();
	pg_close($db_connection);
}
?>