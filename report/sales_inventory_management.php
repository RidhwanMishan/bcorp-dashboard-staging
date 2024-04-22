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
		$type = $_POST['type'];
		if($type == 1){
			date_default_timezone_set('Asia/Kuala_Lumpur');
			$current_month = date('m');
			$current_year = date('Y');
			$current_date = date('Y-m-d');
			$filter_date = date('Y-m-01');
			$sql = "SELECT sku, SUM(monthly_sales_metric_ton) as mtdsales, SUM(ma) as ams, SUM(case when cast(csr_forecast as VARCHAR) ='NaN' then 0 else csr_forecast end) as csr_forecast, max(total_unrestricted_stock) as mt FROM edwcsr.analytics.sales_sku_forecast_app WHERE date='".$filter_date."' GROUP BY sku ORDER BY mtdsales DESC limit 10";
			//	$result = $mysqli->query($sql);
			$result = pg_query($db_connection, $sql);
			if ($result) {

		//		while($row = $result->fetch_array(MYSQLI_ASSOC)) {
				while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
					$myArray[] = $row;
				}
				$output['result'] = 'success';
				$output['sales_data'] = $myArray;
				$output['current_date'] = $current_date;
				echo json_encode($output);
			}
		}
		else if($type == 2){
			$sales_segment = array();
			$managers = array();
			$sql = "SELECT data_table.sales_segment as sales_segment FROM edwcsr.analytics.sales_sku_forecast  data_table GROUP BY data_table.sales_segment";
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