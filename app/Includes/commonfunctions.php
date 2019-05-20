<?php
/**
 * Get all months in a date range
 *
 * @date1 String $mysqldate The date in MySQL format 
 * @date2
 * @return array of dates in the date range 
 */
function get_months($date1, $date2) {

    $time1 = strtotime($date1);
    $time2 = strtotime($date2);

    $my = date('mY', $time2);
    $months = array(date('Y-m-t', $time1));
    $f = '';

    while($time1 < $time2) {
        $time1 = strtotime((date('Y-m-d', $time1).' +15days'));

        if(date('F', $time1) != $f) {
            $f = date('F', $time1);

            if(date('mY', $time1) != $my && ($time1 < $time2))
                $months[] = date('Y-m-t', $time1);
        }

    }

    $months[] = date('Y-m-d', $time2);
    return $months;
}
function getSundayDatesInDateRange($from,$to){
	$begin = new DateTime($from);
	$end = new DateTime($to);

	$interval = new DateInterval('P1D');
	$daterange = new DatePeriod($begin, $interval, $end);
	$weekends = array();

	foreach($daterange as $date) {
	    if (in_array($date->format('N'), [7])) {
	        $weekends[$date->format('W')][] = $date->format('Y-m-d');
	    }
	}
}

function getDaysInDateRange($from,$to){
	$date_from = strtotime($from); // Convert date to a UNIX timestamp  

	// convert the end date to UNIX timestamp.   
	$date_to = strtotime($to);   
	$days = array();
	if($from != '' && $to != ''){
		// Specify the start date. This date can be any English textual format  
		$date_from = $from;   
		$date_from = strtotime($date_from); // Convert date to a UNIX timestamp  

		// Specify the end date. This date can be any English textual format  
		$date_to = $to;  
		$date_to = strtotime($date_to); // Convert date to a UNIX timestamp 

		// Loop from the start date to end date and output all dates inbetween 
		// 86400 =  24x60x60
		$dates_array = array();
		for ($i=$date_from; $i<=$date_to; $i+=86400) {
		    $dates_array[] = date("M-d-y", $i);  
		}
		return $dates_array;
	} 
}
/*
* Get the raw TAT for given period of time
*/
function getRawTaT($labSection, $testType, $date_array,$filter_type){
	//DATE(startTime) = '2010-04-29';
	$series_array = array(
							array('name'=>'Avg TAT','data'=>array()),
							array('name'=>'Avg wait time','data'=>array()),
							array('name'=>'target TAT','data'=>array()),
						);
	//get the target TAT for the selected test type
		$test_type_object  = \DB::select("SELECT targetTAT, TargetTAT_unit FROM test_types 
			WHERE id = ".$testType);

		$target_tat = $test_type_object[0]->targetTAT;
		$tat_units = $test_type_object[0]->TargetTAT_unit;
	
	for($i = 0; $i< sizeof($date_array); $i++){
		$query = "select UNIX_TIMESTAMP(ut.time_created) as timeCreated, ut.time_created, UNIX_TIMESTAMP(ut.time_started) as timeStarted, ut.time_started, UNIX_TIMESTAMP(ut.time_completed) as timeCompleted,ut.time_completed FROM `unhls_tests` AS ut
INNER JOIN `unhls_visits` uv ON `ut`.`visit_id` = `uv`.`id` 
INNER JOIN `test_types` tt ON `ut`.`test_type_id` = `tt`.`id`
	where `ut`.`test_status_id` in (4, 5) and DATE(`ut`.`time_created`) = '".date('Y-m-d',strtotime($date_array[$i]))."' and `tt`.`test_category_id` = ".$labSection." 
	and `ut`.`test_type_id` = ".$testType;

		$tests = \DB::select($query);

		//if tests, update the arrays else update with 0
		$no_tests = count($tests);
		if($no_tests > 0){
			$total_actual_tat = 0; 
			$total_wait_time = 0;
			foreach ($tests as $test){
				//compute tat based on unit of mesure
				$total_actual_tat = $total_actual_tat + (strtotime($test->time_completed) - strtotime($test->time_started));
				$avg_actual_tat = $total_actual_tat/$no_tests;

				$total_wait_time = $total_wait_time + (strtotime($test->time_completed) - strtotime($test->time_created));
				$avg_wait_time = $total_wait_time/$no_tests;

				$actual_tat = convertToUnitofMeasure($avg_actual_tat,$tat_units);
				$wait_time = convertToUnitofMeasure($avg_wait_time,$tat_units);

			}
		}else{
			$actual_tat = 0;
			$wait_time = 0;
		}
		
		//update the series array with the correct values.
		$series_array[0]['data'][] = $actual_tat;
		$series_array[1]['data'][] = $wait_time;
		$series_array[2]['data'][] = (int)$target_tat;
	}
	$return_data['series_array'] = $series_array;
	$return_data['tat_units'] = $tat_units;
	
	return $return_data;
}

function convertToUnitofMeasure($seconds,$tat_units){
	if(strtolower($tat_units) == 'minutes'){
		return $seconds/60;
	}else if(strtolower($tat_units) == 'hours'){
		return $seconds/(60*60);
	}else{
		return ($seconds/60*60)/24;
	}
}
?>