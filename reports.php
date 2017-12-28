<?php
	session_start();
	require_once("functions.php");
	
	if(isset($_POST['report'])){
		$report = $_POST['report'];
		if($report == 1){
			$os = getComputerData("operatingsystem");
			$osDistinct = array_unique($os);
			sort($osDistinct);
			$osDistinctCount = array_count_values($os);
			$values = array();
			foreach($osDistinct as $osName) {
				if($osName == "Windows 7 Professional" || ($osName == "Windows XP Professional" || ($osName == "Windows 10 Pro" || ($osName == "Windows 8.1 Enterprise" || ($osName == "Windows 8.1 Pro" || $osName == "Windows 7 Ultimate"))))){
					array_push($values, $osName . ":" . $osDistinctCount[$osName]);
					//echo $osName . ":" . $osDistinctCount[$osName] . "<br />";
				}
			}			
			echo json_encode($values, JSON_PRETTY_PRINT);
		}
		if($report == 2){
			$os = getComputerData("operatingsystem");
			$osDistinct = array_unique($os);
			rsort($osDistinct);
			$osDistinctCount = array_count_values($os);
			$values = array();
			foreach($osDistinct as $osName) {
				if($osName != "Windows 7 Professional" && ($osName != "Windows XP Professional" && ($osName != "Windows 10 Pro" && ($osName != "Windows 8.1 Enterprise" && ($osName != "Windows 8.1 Pro" && $osName != "Windows 7 Ultimate"))))){
				//if($osName == "Windows Server 2012 R2 Standard" || ($osName == "Windows Server 2008 R2 Enterprise" || ($osName == "Windows Server 2008 R2 Standard" || ($osName == "Windows Server 2003" || ($osName == "Windows Server 2012 Standard" || ($osName == "Windows 2000 Server" || ($osName == "Windows Server 2012 R2 Datacenter" || ($osName == "Hyper-V Server 2012 R2" || ($osName == "Hyper-V Server 2012" || $osName == "Outros"))))))))){
					array_push($values, $osName . ":" . $osDistinctCount[$osName]);
					//echo $osName . ":" . $osDistinctCount[$osName] . "<br />";
				}
			}			
			echo json_encode($values, JSON_PRETTY_PRINT);
		}
		if($report == 3){
			$created = getComputerData("created");
			$currentDate = getCurrentDate();
			$createdDistinct = array_unique($created);
			rsort($createdDistinct);
			$dates = array();
			for($i = 0; $i < 10; $i++){
				$daysago = date('Y/m/d', strtotime('-' . $i . ' days', strtotime($currentDate)));
				array_push($dates, $daysago);
			}
			$createdDistinctCount = array_count_values($created);
			$values = array();
			$countDays = 0;
			foreach($dates as $days){
				if(isset($createdDistinctCount[$days])){
					$format = explode("/", $days);
					array_push($values, $format[2] . "/" . $format[1] . "/" . $format[0] . ":" . $createdDistinctCount[$days]);
					//echo $days . " - " . $createdDistinctCount[$days] . "<br>";
				} else {
					$format = explode("/", $days);
					array_push($values, $format[2] . "/" . $format[1] . "/" . $format[0] . ":" . 0);
					//echo $days . " - 0<br>";
				}
			}
			echo json_encode($values, JSON_PRETTY_PRINT);
		}
		if($report == 4){
			$daysoff = getLastLogonData("total");
			sort($daysoff);
			$values = array();
			foreach($daysoff as $qtd) {
				array_push($values, $qtd);
			}			
			echo json_encode($values, JSON_PRETTY_PRINT);
		}
		
	}
	
	
?>

