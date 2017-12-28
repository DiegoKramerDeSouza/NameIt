<?php
	session_start();
	require_once("functions.php");
	
	if(isset($_POST['report'])){
		$report = $_POST['report'];
		//$data = explode(" ", $report);
		$daysoff = getDayOff($report);
		sort($daysoff);
		$values = array();
		//$count = 0;
		//echo $report . "<br />";
		foreach($daysoff as $qtd) {
			array_push($values, $qtd);
			//$count++;
			//echo $count . " - " . $qtd . "<br />";
		}	
		
		echo json_encode($values, JSON_PRETTY_PRINT);
	}
	
	
?>

