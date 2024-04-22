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

	if(isset($_POST['type'])) {
		date_default_timezone_set('Asia/Kuala_Lumpur');
		$type = $_POST['type'];

		if($type == 1) {

			$sales_segment = $_POST['sales_segment'];
			
			$current_month = date('m');
            $current_year = date('Y');
            $year_month = date('Ym');

			// MTD Sales

			$result_array = array();

			$sql = "SELECT SUM(monthly_sales_metric_ton) AS mtd_sales FROM edwcsr.reporting.sales_customer_product_monthly_granular data_table WHERE month_year = '".$year_month."' GROUP BY month_year";

			if($sales_segment != '') {

				$sql = "SELECT SUM(monthly_sales_metric_ton) AS mtd_sales FROM edwcsr.reporting.sales_customer_product_monthly_granular data_table WHERE month_year = '".$year_month."' AND sales_segment ='".$sales_segment."' GROUP BY data_table.month_year, data_table.sales_segment";

			}

			$result = pg_query($db_connection, $sql);
			if ($result) {
				while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
					$result_array[] = $row;
				}
				$output['mtd_sales_data'] = $result_array;
			}

			// MTD Return

			$result_array = array();

			$sql = "SELECT SUM(total_sales_metric_ton) AS mtd_return FROM edwcsr.reporting.sales_return_product data_table WHERE month_year = '".$year_month."' GROUP BY data_table.month_year";

			if($sales_segment != '') {

				$sql = "SELECT SUM(total_sales_metric_ton) AS mtd_return FROM edwcsr.reporting.sales_return_product data_table WHERE month_year = '".$year_month."' AND sales_segment = '".$sales_segment."' GROUP BY data_table.sales_segment";

			}

			$result = pg_query($db_connection, $sql);
			if ($result) {
				while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
					$result_array[] = $row;
				}
				$output['mtd_return_data'] = $result_array;
			}

			// YTD Sales

			$result_array = array();

			$sql = "SELECT SUM(monthly_sales_metric_ton) AS ytd_sales FROM edwcsr.reporting.sales_customer_product_monthly_granular data_table WHERE month_year >= '".$current_year."01' AND month_year <= '".$current_year."12' GROUP BY data_table.month_year";

			if($sales_segment != '') {

				$sql = "SELECT SUM(monthly_sales_metric_ton) AS ytd_sales FROM edwcsr.reporting.sales_customer_product_monthly_granular data_table WHERE month_year >= '".$current_year."01' AND month_year <= '".$current_year."12' AND sales_segment = '".$sales_segment."' GROUP BY data_table.month_year, data_table.sales_segment";

			}

			$result = pg_query($db_connection, $sql);
			if ($result) {
				while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
					$result_array[] = $row;
				}
				$output['ytd_sales_data'] = $result_array;
			}

			// YTD Return

			$result_array = array();

			$sql = "SELECT SUM(ABS(total_sales_metric_ton)) AS ytd_return, month_year FROM edwcsr.reporting.sales_return_product data_table WHERE month_year >= '".$current_year."01' AND month_year <= '".$current_year."12' GROUP BY data_table.month_year";

			if($sales_segment != '') {

				$sql = "SELECT SUM(ABS(total_sales_metric_ton)) AS ytd_return, month_year FROM edwcsr.reporting.sales_return_product data_table WHERE month_year >= '".$current_year."01' AND month_year <= '".$current_year."12' AND sales_segment = '".$sales_segment."' GROUP BY data_table.month_year, data_table.sales_segment";

			}

			$result = pg_query($db_connection, $sql);
			if ($result) {
				while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
					$result_array[] = $row;
				}
				$output['ytd_return_data'] = $result_array;
			}


			$output['result'] = 'success';
			echo json_encode($output);

		} else if($type == 2) {

			$sales_segment = array();
			$sql = "SELECT data_table.sales_segment as sales_segment FROM edwcsr.reporting.sales_return_product data_table GROUP BY data_table.sales_segment";
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

	} else {

		$output['result'] = 'failed';
		$output['data'] = $myArray;
		echo json_encode($output);

	}

	pg_close($db_connection);

}
?>