<?php
	require_once("functions.php");
	
	//$OU = getOU("OU=Departamento de Tecnologia,DC=call,DC=br");
	//echo "<pre>";
	//print_r($OU);
	//echo "</pre>";
	
	$DC = verifyCon();
	echo "<pre>";
	print_r($DC);
	echo "</pre>";
	
	

?>