<?php
if($_SERVER['REQUEST_METHOD']=='POST') {

	include 'DatabaseConfig_postgre.php';

	$output = array();

	$db_connection = pg_connect("host={$HostName} port={$Port} dbname={$DatabaseName} user={$HostUser} password={$HostPass}");

	if (!$db_connection) {
		echo("Failed to connect to database");
		exit();
	}

	if(isset($_POST['type'])) {
		date_default_timezone_set('Asia/Kuala_Lumpur');
		$type = $_POST['type'];

		if($type == 1) {

            // Daily Carbonator PH by Shift

            $result_array = array();

            $sql = "WITH ld AS (SELECT carbonator_id, max(date) AS latest 
                FROM reporting.operations_qc_lab_carbonator_ph GROUP BY carbonator_id) select c.carbonator_id as Carbonator
            , SUM(CASE WHEN c.qc_lab_shift = 'Morning' THEN c.carbonator_ph ELSE NULL END) as Morning
            , SUM(CASE WHEN c.qc_lab_shift = 'Afternoon' THEN c.carbonator_ph ELSE NULL END) as Afternoon
            , SUM(CASE WHEN c.qc_lab_shift = 'Evening' THEN c.carbonator_ph ELSE NULL END) as Night
            from reporting.operations_qc_lab_carbonator_ph c JOIN ld ON ld.carbonator_id = c.carbonator_id
            WHERE c.date = ld.latest GROUP BY Carbonator ORDER BY Carbonator ASC";

            $result = pg_query($db_connection, $sql);
            if ($result) {
                while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                    $result_array[] = $row;
                }
                $output['daily_carbonator'] = $result_array;
            } 


            // Daily Raw Fine Liquor Colour Reduction%

            // Morning 
            $result_array = array();

            $sql = "WITH ld AS (
                SELECT production_process_stage_id, max(date) AS latest 
                FROM reporting.operations_qc_lab_colour_reduction GROUP BY production_process_stage_id)
              select c.colour_reduction_percent from reporting.operations_qc_lab_colour_reduction c
              JOIN ld ON ld.production_process_stage_id = c.production_process_stage_id
              WHERE c.date = ld.latest and c.qc_lab_shift = 'Morning'";

            $result = pg_query($db_connection, $sql);
            if ($result) {
                while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                    $result_array[] = $row;
                }
                $output['daily_raw_morning'] = $result_array;
            } 

            // Afternoon 
            $result_array = array();

            $sql = "WITH ld AS (
                SELECT production_process_stage_id, max(date) AS latest 
                FROM reporting.operations_qc_lab_colour_reduction GROUP BY production_process_stage_id)
                select c.colour_reduction_percent from reporting.operations_qc_lab_colour_reduction c
                JOIN ld ON ld.production_process_stage_id = c.production_process_stage_id
                WHERE c.date = ld.latest and c.qc_lab_shift = 'Afternoon'";

            $result = pg_query($db_connection, $sql);
            if ($result) {
                while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                    $result_array[] = $row;
                }
                $output['daily_raw_afternoon'] = $result_array;
            } 

             // Night 
            $result_array = array();

            $sql = "WITH ld AS (
                SELECT production_process_stage_id, max(date) AS latest 
                FROM reporting.operations_qc_lab_colour_reduction GROUP BY production_process_stage_id)
              select c.colour_reduction_percent from reporting.operations_qc_lab_colour_reduction c
              JOIN ld ON ld.production_process_stage_id = c.production_process_stage_id
              WHERE c.date = ld.latest and c.qc_lab_shift = 'Evening'";

            $result = pg_query($db_connection, $sql);
            if ($result) {
                while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                    $result_array[] = $row;
                }
                $output['daily_raw_night'] = $result_array;
            } 
            

            // Daily Average Fine Liquor Colour

            // Morning 
            $result_array = array();

            $sql = "WITH ld AS (SELECT qc_sample_id, max(date) AS latest 
              FROM reporting.operations_qc_lab_colour_icumsa GROUP BY qc_sample_id )
              select c.colour_icumsa_average from reporting.operations_qc_lab_colour_icumsa c
              JOIN ld ON ld.qc_sample_id = c.qc_sample_id
              WHERE c.date = ld.latest and c.qc_lab_shift = 'Morning'";

            $result = pg_query($db_connection, $sql);
            if ($result) {
                while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                    $result_array[] = $row;
                }
                $output['daily_avg_morning'] = $result_array;
            } 

            // Afternoon 
            $result_array = array();

            $sql = "WITH ld AS (SELECT qc_sample_id, max(date) AS latest 
                FROM reporting.operations_qc_lab_colour_icumsa GROUP BY qc_sample_id )
                select c.colour_icumsa_average from reporting.operations_qc_lab_colour_icumsa c
                JOIN ld ON ld.qc_sample_id = c.qc_sample_id
                WHERE c.date = ld.latest and c.qc_lab_shift = 'Afternoon'";

            $result = pg_query($db_connection, $sql);
            if ($result) {
                while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                    $result_array[] = $row;
                }
                $output['daily_avg_afternoon'] = $result_array;
            } 

            // Night 
            $result_array = array();

            $sql = "WITH ld AS (SELECT qc_sample_id, max(date) AS latest 
              FROM reporting.operations_qc_lab_colour_icumsa GROUP BY qc_sample_id )
              select c.colour_icumsa_average from reporting.operations_qc_lab_colour_icumsa c
              JOIN ld ON ld.qc_sample_id = c.qc_sample_id
              WHERE c.date = ld.latest and c.qc_lab_shift = 'Night'";

            $result = pg_query($db_connection, $sql);
            if ($result) {
                while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                    $result_array[] = $row;
                }
                $output['daily_avg_night'] = $result_array;
            } 


            // Number of Strikes Today

            $result_array = array();

            $sql = "select sum(total_strikes) as total from reporting.operations_production_performance_output where date = current_date ";

            $result = pg_query($db_connection, $sql);
            if ($result) {
                while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                    $result_array[] = $row;
                }
                $output['strikes_today'] = $result_array;
            } 

            // Number of Strikes MTD

            $result_array = array();

            $sql = "select sum(total_strikes) as total from reporting.operations_production_performance_output where 
            date_part(month,date) = (SELECT EXTRACT(month FROM CURRENT_DATE)) and DATE_PART_year(date) = DATE_PART_YEAR(current_date)";

            $result = pg_query($db_connection, $sql);
            if ($result) {
                while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                    $result_array[] = $row;
                }
                $output['strikes_mtd'] = $result_array;
            } 
            
            $output['result'] = 'success';
            echo json_encode($output);
            
        } else {

            $output['result'] = 'failed';
            echo json_encode($output);

        }
    }


	pg_close($db_connection);

}
?>