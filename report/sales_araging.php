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
			$sales_person = $_POST['sales_person'];
			
			$sql = "SELECT data_table.customer_name, SUM(data_table.not_due) as not_due, SUM(\"1-30\") as due_30, SUM(\"31-60\") as due_60, SUM(\"61-90\") as due_90, SUM(\"91-120\") as due_120, SUM(\"121-150\") as due_150, SUM(\"151-180\") as due_180, SUM(\"> 180\") as due_up_180 , SUM(data_table.year_to_date_sales_amount_rm) as total_sales, SUM(data_table.previous_92_days_sales_rm) as previous_92_days_sales  FROM edwcsr.reporting.finance_ar_ageing_per_customer  data_table GROUP BY data_table.customer_name ORDER BY data_table.customer_name";
			if($sales_person != ''){
				$sales_type = $_POST['sales_type'];
				if($sales_type == 0){
					$sql = "SELECT data_table.customer_name, SUM(data_table.not_due) as not_due, SUM(\"1-30\") as due_30, SUM(\"31-60\") as due_60, SUM(\"61-90\") as due_90, SUM(\"91-120\") as due_120, SUM(\"121-150\") as due_150, SUM(\"151-180\") as due_180, SUM(\"> 180\") as due_up_180, SUM(data_table.year_to_date_sales_amount_rm) as total_sales, SUM(data_table.previous_92_days_sales_rm) as previous_92_days_sales FROM edwcsr.reporting.finance_ar_ageing_per_customer  data_table WHERE data_table.sales_person_name='".$sales_person."' GROUP BY data_table.customer_name ORDER BY data_table.customer_name";
				}
				else{
					$sql = "SELECT data_table.customer_name, SUM(data_table.not_due) as not_due, SUM(\"1-30\") as due_30, SUM(\"31-60\") as due_60, SUM(\"61-90\") as due_90, SUM(\"91-120\") as due_120, SUM(\"121-150\") as due_150, SUM(\"151-180\") as due_180, SUM(\"> 180\") as due_up_180, SUM(data_table.year_to_date_sales_amount_rm) as total_sales, SUM(data_table.previous_92_days_sales_rm) as previous_92_days_sales FROM edwcsr.reporting.finance_ar_ageing_per_customer  data_table WHERE data_table.manager_employee_name='".$sales_person."' GROUP BY data_table.customer_name ORDER BY data_table.customer_name";
				}
			}
			//	$result = $mysqli->query($sql);
			$result = pg_query($db_connection, $sql);
			if ($result) {

		//		while($row = $result->fetch_array(MYSQLI_ASSOC)) {
				while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
					$myArray[] = $row;
				}
				$output['result'] = 'success';
				$output['data'] = $myArray;
				echo json_encode($output);
			}
		}
		else if($type == 2){
			$sales_persons = array();
			$managers = array();
			$sql = "SELECT data_table.sales_person_name as person_name FROM edwcsr.reporting.finance_ar_ageing_per_customer  data_table GROUP BY data_table.sales_person_name";
			$result = pg_query($db_connection, $sql);
			if ($result) {
				while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
					$sales_persons[] = $row;
				}
			}
			$sql = "SELECT data_table.manager_employee_name as person_name FROM edwcsr.reporting.finance_ar_ageing_per_customer  data_table GROUP BY data_table.manager_employee_name";
			$result = pg_query($db_connection, $sql);
			if ($result) {
				while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
					$managers[] = $row;
				}
			}

			$output['result'] = 'success';
			$output['sales_person'] = $sales_persons;
			$output['managers'] = $managers;
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