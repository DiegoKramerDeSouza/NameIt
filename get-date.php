<?php
//Set time zone
	date_default_timezone_set("America/Sao_Paulo");
	
//Data/Hora Atual
	$data = getdate();
	$mesPadrao = 31;
	
	$addH = 0;
	$addM = 0;
	
	$horas = (Int)$data["hours"];
	$minutos = (Int)$data["minutes"];
	$segundos = (Int)$data["seconds"];
	$dias = (Int)$data["mday"];
	$meses = (Int)$data["mon"];
	$anos = (Int)$data["year"];
	$dataAtual = $horas . ":" . $minutos . " - " . $dias . "/" . $meses . "/" . $anos;
	
	if((($meses == 4 || $meses == 6) || $meses == 9) || $meses == 11){
		$mesPadrao = 30;
	}
	elseif($meses == 2){
		$mesPadrao = 28;
	}
	
	$dia = $dias;
	$mes = $meses;
	$hora = $horas;
	$minuto = $minutos;
	if($dia < 10){
		$dia = "0" . $dias;
	}
	if($mes < 10){
		$mes = "0" . $mes;
	}
	if($hora < 10){
		$hora = "0" . $hora;
	}
	if($minuto < 10){
		$minuto = "0" . $minuto;
	}
	//$datetime = "<span class='datetime'>(" . $dia . "/" . $mes . "/" . $anos . " " . $hora . ":" . $minuto .")</sapn>";

//Trata data/hora para mensagem de boas vindas=====================================================
	$dia = $dias;
	$mes = $meses;
	$hora = $horas;
	$minuto = $minutos;
	if($dia < 10){
		$dia = "0" . $dias;
	}
	if($mes < 10){
		$mes = "0" . $mes;
	}
	if($hora < 10){
		$hora = "0" . $hora;
	}
	if($minuto < 10){
		$minuto = "0" . $minuto;
	}
	
	switch($mes){
		case "01":
			$mesNome = "Janeiro";
			break;
		case "02":
			$mesNome = "Fevereiro";
			break;
		case "03":
			$mesNome = "Março";
			break;
		case "04":
			$mesNome = "Abril";
			break;
		case "05":
			$mesNome = "Maio";
			break;
		case "06":
			$mesNome = "Junho";
			break;
		case "07":
			$mesNome = "Julho";
			break;
		case "08":
			$mesNome = "Agosto";
			break;
		case "09":
			$mesNome = "Setembro";
			break;
		case "10":
			$mesNome = "Outubro";
			break;
		case "11":
			$mesNome = "Novembro";
			break;
		case "12":
			$mesNome = "Dezembro";
			break;
	}
	
	$datetime = $dia . " de " . $mesNome . " de " . $anos . " as " . $hora . ":" . $minuto;
?>