<?php
	require_once("functions.php");
	if(isset($_GET['date'])){
		$currentDate = getCurrentDate();
		$dates = array();
		for($i = 0; $i < 10; $i++){
			$daysago = date('d/m/Y', strtotime('-' . $i . ' days', strtotime($currentDate)));
			array_push($dates, $daysago);
			//echo $daysago . ", ";
		}
				
		echo json_encode($dates, JSON_PRETTY_PRINT);
	}

?>