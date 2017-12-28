<?php
	require_once("ldapConnection.php");
	
	function login12345($user, $password){
		//Dados de Conexão LDAP================================================================================
		//Usuário de conexão
		$ldapU = "call\\" . base64_decode($user);
		//Senha de conexão
		$ldapPw = base64_decode($password);
		//Caminho - OU
		$base = "DC=call,DC=br";
		//Host de conexão
		$ldapH = "LDAP://SVDF07W000005.call.br";
		//Porta de conexão
		$ldapP = "389";
		$ldapB = false;
		//=====================================================================================================
			
		//Estabelece conexão com LDAP	
		$ldapConnection = ldap_connect($ldapH, $ldapP) or die (header("Location:index.php?erro=1"));
		ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($ldapConnection, LDAP_OPT_REFERRALS, 0);
		
		if($ldapConnection){
			//Executa Binding de conta LDAP
			$ldapB = ldap_bind($ldapConnection, $ldapU, $ldapPw) or die (header("Location:index.php?erro=2"));
		}
		//===========================================================================================================
		return $ldapB;
	}
	
	function adjustConfiguration(){
		$newContent = "";
		$configuration = file_get_contents("./base/MDDB/defaults.mddb");
		$configuration = base64_decode(base64_decode($configuration));
		$current = file_get_contents("./base/MDDB/distribution.mddb");
		$current = base64_decode(base64_decode($current));
		$data = explode("|@", $configuration);
		$currentData = explode("|@|", $current, 2);
		for($i = 1; $i < count($data); $i++){
			$expdata = explode("#", $data[$i], 2);
			$section = $expdata[0];
			$chars = $expdata[1];
			$newContent = $newContent . "<<" . $i . "||" . $section . "||" . $chars;
		}
		if($currentData[0] != $newContent){
			if(file_exists("./base/MDDB/distribution.mddb")){
				unlink("./base/MDDB/distribution.mddb");
			}
			$returnItem = false;
		} else {
			$returnItem = true;
		}
		return $returnItem;
	}
	
	function createBKP(){
		$conf = "./base/MDDB/conf.mddb";
		$defaults = "./base/MDDB/defaults.mddb";
		$distribution = "./base/MDDB/distribution.mddb";
		$hidden = "./base/MDDB/hidden.mddb";
		$init = "./base/MDDB/init.mddb";
		$nliFolder = "./base/MDDB/NLI";
		$NLI = scandir($nliFolder);
		$date = getCurrentDate();
		$subdate = explode("/", $date);
		$bkpfile = "./TEMP/Backup_" . $subdate[2] . $subdate[1] . $subdate[0] . ".bkp";
		$fileName = "Backup_" . $subdate[2] . $subdate[1] . $subdate[0] . ".bkp";
		file_put_contents($bkpfile, "");
		if(file_exists($conf)){
			$conf = file_get_contents($conf);
		} else {
			$conf = "";
		}
		if(file_exists($defaults)){
			$defaults = file_get_contents($defaults);
		} else {
			$defaults = "";
		}
		if(file_exists($distribution)){
			$distribution = file_get_contents($distribution);
		} else {
			$distribution = "";
		}
		if(file_exists($hidden)){
			$hidden = file_get_contents($hidden);
		} else {
			$hidden = "";
		}
		if(file_exists($init)){
			$init = file_get_contents($init);
		} else {
			$init = "";
		}
		//$toInsert = "@|" . $conf . "@|" . $defaults . "@|" . $distribution . "@|" . $hidden . "@|" . $init . "#";
		$toInsert = "@|" . $conf . "@|" . $defaults . "@|" . $distribution . "@|" . $hidden . "@|" . $init;
		for($i = 2; $i < count($NLI); $i++){
			$current = file_get_contents($nliFolder . "/" . $NLI[$i]);
			$nliName = explode(".", $NLI[$i]);
			$toInsert = $toInsert . "@|" . $nliName[0] . "::" . $current;
		}
		file_put_contents($bkpfile, $toInsert);
		return $fileName;
	}
	
	function loadBkpFile($filename){
		$bkp = "./uploads/" . $filename;
		$file = file_get_contents($bkp);
		$exp = explode("@|", $file);
		$result = "";
		for($i = 1; $i < count($exp); $i++){
			if($i == 1){
				$result = $result . $exp[$i] . "<br />";
				if($exp[$i] != "")file_put_contents("./base/MDDB/conf.mddb", $exp[$i]);
			} elseif($i == 2){
				$result = $result . $exp[$i] . "<br />";
				if($exp[$i] != "")file_put_contents("./base/MDDB/defaults.mddb", $exp[$i]);
			} elseif($i == 3){
				$result = $result . $exp[$i] . "<br />";
				if($exp[$i] != "")file_put_contents("./base/MDDB/distribution.mddb", $exp[$i]);
			} elseif($i == 4){
				$result = $result . $exp[$i] . "<br />";
				if($exp[$i] != "")file_put_contents("./base/MDDB/hidden.mddb", $exp[$i]);
			} elseif($i == 5){
				$result = $result . $exp[$i] . "<br />";
				if($exp[$i] != "")file_put_contents("./base/MDDB/init.mddb", $exp[$i]);
			} else {
				$result = $result . $exp[$i] . "<br />";
				if(!file_exists("./base/MDDB/NLI")){
					mkdir("./base/MDDB/NLI", 0777, true);
				}
				$nliName = explode("::", $exp[$i], 2);
				if($exp[$i] != "")file_put_contents("./base/MDDB/NLI/" . $nliName[0] . ".mddb", $nliName[1]);
			}
		}
		unlink($bkp);
		return $result;
	}
	
	function wmiData($ipadds, $uacc, $upss){
		$domain = "NotDefined";
		$WbemLocator = new COM ("WbemScripting.SWbemLocator");
		if($ipadds == "10.61.194.42" || $ipadds == "10.61.200.111"){
			return $_SESSION['domain'];	
		} else {
			try{
				$obj = $WbemLocator->ConnectServer($ipadds, 'root\\cimv2' , $uacc, $upss);
			} catch (Exception $e){
				return "Local";
			}
			
			$fso = new COM ( "Scripting.FileSystemObject" );    
			$domainName = $obj->ExecQuery("Select * from Win32_NTDomain");

			$pcDomain = array();
			foreach ( $domainName as $wmi_domain ){
				if($wmi_domain == ""){
					array_push($pcDomain, "Local");
				} else {
					$expDomain = explode(":", $wmi_domain->Name);
					if(isset($expDomain[1])){
						array_push($pcDomain, $expDomain[1]);
					} else {
						array_push($pcDomain, $expDomain[0]);
					}
				}
			}
			if(count($pcDomain) > 1){
				$domain = $pcDomain[1];
			} else {
				$domain = "Local";
			}
			return $domain;	
		}
	}
	
	function getOUDescription($description, $database, $name){
		global $ldapB, $ldapConnection, $info;
		
		$limit = file_get_contents("./base/MDDB/hidden.mddb");
		$hidden = explode(";", $limit);
		$dbView = $database;
		$filt = '(&(objectClass=OrganizationalUnit)(' . $_SESSION['Attr'] . '=' . $description . ')(name=' . $name . '))';
		$OUs = array();
		if($ldapB){
			//Search
			$sr = ldap_search($ldapConnection, $dbView, $filt);
			//Organiza
			$sort = ldap_sort($ldapConnection, $sr, 'name');
			//Recolhe entradas
			$info = ldap_get_entries($ldapConnection, $sr);
		}
		
		for ($i = 0; $i < $info["count"]; $i++) {
			$descriptonRule = explode(">", $info[$i][$_SESSION['Attr']][0]);
			$container = true;
			$lock = false;
			$thisCount = explode(",", $info[$i]["distinguishedname"][0]);
			for($j = 0; $j < count($hidden); $j++){
				if($info[$i]["name"][0] == $hidden[$j] && $lock == false){
					$container = false;
					$lock = true;
				}
			}
			if($container){
				if(isset($descriptonRule[1]) && isset($descriptonRule[2])){
					array_push($OUs, $info[$i]["name"][0] . "#" . $descriptonRule[1] . "#" . $descriptonRule[2] . "|" . $info[$i]["distinguishedname"][0] . "|" . $descriptonRule[2]);
				}else{
					array_push($OUs, $info[$i]["name"][0] . "|" . $info[$i]["distinguishedname"][0]);
				}
			}
		}
		
		return $OUs;
	}
	
	function getOUDescriptionAD($description, $database){
		global $ldapB, $ldapConnection, $info;
		
		$limit = file_get_contents("./base/MDDB/hidden.mddb");
		$hidden = explode(";", $limit);
		$dbView = $database;
		$filt = '(&(objectClass=OrganizationalUnit)(' . $_SESSION['Attr'] . '=' . $description . '))';
		$OUs = array();
		if($ldapB){
			//Search
			$sr = ldap_search($ldapConnection, $dbView, $filt);
			//Organiza
			$sort = ldap_sort($ldapConnection, $sr, 'name');
			//Recolhe entradas
			$info = ldap_get_entries($ldapConnection, $sr);
		}
		
		for ($i = 0; $i < $info["count"]; $i++) {
			$container = true;
			$lock = false;
			$thisCount = explode(",", $info[$i]["distinguishedname"][0]);
			for($j = 0; $j < count($hidden); $j++){
				if($info[$i]["name"][0] == $hidden[$j] && $lock == false){
					$container = false;
					$lock = true;
				}
			}
			if($container){
				array_push($OUs, $info[$i]["name"][0] . "|" . $info[$i]["distinguishedname"][0] . "|" . $info[$i][$_SESSION['Attr']][0]);
			}
		}
		return $OUs;
	}
	
	function getOU($database){
		global $ldapB, $ldapConnection, $info;
		
		$countDatabase = explode(",", $database);
		$limit = file_get_contents("./base/MDDB/hidden.mddb");
		$hidden = explode(";", $limit);
		$dbView = $database;
		$filt = '(&(objectClass=OrganizationalUnit))';
		$OUs = array();
		if($ldapB){
			//Search
			$sr = ldap_search($ldapConnection, $dbView, $filt);
			//Organiza
			$sort = ldap_sort($ldapConnection, $sr, 'name');
			//Recolhe entradas
			$info = ldap_get_entries($ldapConnection, $sr);
		}
		for ($i = 0; $i < $info["count"]; $i++) {
			$container = true;
			$lock = false;
			$thisCount = explode(",", $info[$i]["distinguishedname"][0]);
			for($j = 0; $j < count($hidden); $j++){
				if($info[$i]["name"][0] == $hidden[$j] && $lock == false){
					$container = false;
					$lock = true;
				}
			}
			if(count($thisCount) == count($countDatabase) + 1 && $container){
				array_push($OUs, $info[$i]["name"][0] . "|" . $info[$i]["distinguishedname"][0]);
			}
		}
		return $OUs;
	}
	
	function collectSites(){
		global $ldapB, $ldapConnection, $info;
		
		$dbView = $_SESSION['root'];
		$filt = '(&(objectClass=OrganizationalUnit)(' . $_SESSION['Attr'] . '=Site*))';
		$OUs = array();
		if($ldapB){
			//Search
			$sr = ldap_search($ldapConnection, $dbView, $filt);
			//Organiza
			$sort = ldap_sort($ldapConnection, $sr, 'name');
			//Recolhe entradas
			$info = ldap_get_entries($ldapConnection, $sr);
		}
		for ($i = 0; $i < $info["count"]; $i++) {
			$description = explode(">", $info[$i][$_SESSION['Attr']][0]);
			array_push($OUs, $info[$i]["name"][0] . ">" . $description[1]);
		}
		
		return $OUs;
	}
	
	function collectSiteUF(){
		global $ldapB, $ldapConnection, $info;
		
		$dbView = $_SESSION['root'];
		$filt = '(&(objectClass=OrganizationalUnit)(' . $_SESSION['Attr'] . '=Site*))';
		$OUs = array();
		if($ldapB){
			//Search
			$sr = ldap_search($ldapConnection, $dbView, $filt);
			//Organiza
			$sort = ldap_sort($ldapConnection, $sr, 'name');
			//Recolhe entradas
			$info = ldap_get_entries($ldapConnection, $sr);
		}
		for ($i = 0; $i < $info["count"]; $i++) {
			$UF = explode(">", $info[$i][$_SESSION['Attr']][0]);
			array_push($OUs, $info[$i]["name"][0] . "|" . $UF[1]);
		}
		
		return $OUs;
		
	}
	
	function verifyCon(){
		$DCList = Array();
		global $ldapB, $ldapConnection, $info;
		
		$dbView = "OU=Domain Controllers," . $_SESSION['root'];
		$filt = '(&(objectClass=Computer))';
		$OUs = array();
		if($ldapB){
			//Search
			$sr = ldap_search($ldapConnection, $dbView, $filt);
			//Organiza
			$sort = ldap_sort($ldapConnection, $sr, 'name');
			//Recolhe entradas
			$info = ldap_get_entries($ldapConnection, $sr);
		}
		
		for ($i = 0; $i < $info["count"]; $i++) {
			$DC = $info[$i]["name"][0];
			$timeout = 10;
			$socket = @fsockopen( $DC, 3389, $errno, $errstr, $timeout );
			$online = ( $socket !== false );
			if($online){
				array_push($DCList, $DC);
			}
		}
		
		return $DCList;
	}
	
	function getHostData($hostname){
		global $ldapB, $ldapConnection, $info;
		
		$dbView = $_SESSION['root'];
		$filt = '(&(objectClass=computer)(name=' . $hostname . '))';
		$OUs = array();
		if($ldapB){
			//Search
			$sr = ldap_search($ldapConnection, $dbView, $filt);
			//Organiza
			$sort = ldap_sort($ldapConnection, $sr, 'name');
			//Recolhe entradas
			$info = ldap_get_entries($ldapConnection, $sr);
		}
		for ($i = 0; $i < $info["count"]; $i++) {
			array_push($OUs, $info[$i]["name"][0]);
			array_push($OUs, $info[$i][$_SESSION['Attr']][0]);
			array_push($OUs, $info[$i]["distinguishedname"][0]);
		}
		if($info["count"] == 0){
			array_push($OUs, "TESTE-NOME");
			array_push($OUs, "TESTE-DESCRIÇÃO");
			array_push($OUs, "NOME,TESTE-DN");
		}
		
		return $OUs;
	}
	
	function getUserData($information){
		$userData = array();
		$value = base64_decode($information);
		$userPoint = explode("#", $value);
		array_push($userData, $userPoint[1]);
		array_push($userData, $userPoint[0]);
		return $userData;
	}
	
	function getOUNameByDescription($description){
		global $ldapB, $ldapConnection, $info;
		
		$dbView = $_SESSION['root'];
		$filt = '(&(objectClass=OrganizationalUnit)(' . $_SESSION['Attr'] . '=' . $description . '))';
		$OUs = array();
		if($ldapB){
			//Search
			$sr = ldap_search($ldapConnection, $dbView, $filt);
			//Organiza
			$sort = ldap_sort($ldapConnection, $sr, 'name');
			//Recolhe entradas
			$info = ldap_get_entries($ldapConnection, $sr);
		}
		
		for ($i = 0; $i < $info["count"]; $i++) {
			$descriptonRule = explode(">", $info[$i][$_SESSION['Attr']][0]);
			if(isset($descriptonRule[1])){
				array_push($OUs, $info[$i]["name"][0] . " (" . $descriptonRule[1] . ")");
			} else {
				array_push($OUs, $info[$i]["name"][0]);
			}
		}
		return $OUs;
	}
	
	function changeDescription($DN, $description){
		global $ldapB, $ldapConnection, $info;
		
		$dbView = $_SESSION['root'];
		$filt = '(&(objectClass=OrganizationalUnit)(distinguishedName=' . $DN . '))';
		if($ldapB){
			//Search
			$sr = ldap_search($ldapConnection, $dbView, $filt);
			//Organiza
			$sort = ldap_sort($ldapConnection, $sr, 'name');
			//Recolhe entradas
			$info = ldap_get_entries($ldapConnection, $sr);
		}
		for ($i = 0; $i < $info["count"]; $i++) {
			if(isset($info[$i]["name"][0])){
				$dado[$_SESSION['Attr']] = $description; 
				$ldapReplace = ldap_mod_replace($ldapConnection, $info[$i]["distinguishedname"][0], $dado) or die ("<div class='xl red'>Falha ao inserir</div>");
			}
		}
	}
	
	function changeDescriptionAD($DN, $description , $dependence, $identify){
		global $ldapB, $ldapConnection, $info;
		
		$dbView = $_SESSION['root'];
		$filt = '(&(objectClass=OrganizationalUnit)(distinguishedName=' . $DN . '))';
		if($ldapB){
			//Search
			$sr = ldap_search($ldapConnection, $dbView, $filt);
			//Organiza
			$sort = ldap_sort($ldapConnection, $sr, 'name');
			//Recolhe entradas
			$info = ldap_get_entries($ldapConnection, $sr);
		}
		for ($i = 0; $i < $info["count"]; $i++) {
			if(isset($info[$i]["name"][0])){
				if(isset($info[$i][$_SESSION['Attr']][0]) && $info[$i][$_SESSION['Attr']][0] != ""){
					$tempDesc = explode("/", $info[$i][$_SESSION['Attr']][0]);
					$dependence = $tempDesc[0] . $dependence;
				}
				$description = $dependence . ";/" . $description . "/" . $identify; 
				$dado[$_SESSION['Attr']] = $description; 
				$ldapReplace = ldap_mod_replace($ldapConnection, $info[$i]["distinguishedname"][0], $dado) or die ("<div class='xl red'>Falha ao inserir</div>");
			}
		}
	}
	
	function removeDescription($obj, $name){
		global $ldapB, $ldapConnection, $info;
		
		$dbView = $_SESSION['root'];
		$filt = '(&(objectClass=OrganizationalUnit)(' . $_SESSION['Attr'] . '=' . $obj . ')(Name=' . $name . '))';
		if($ldapB){
			//Search
			$sr = ldap_search($ldapConnection, $dbView, $filt);
			//Organiza
			$sort = ldap_sort($ldapConnection, $sr, 'name');
			//Recolhe entradas
			$info = ldap_get_entries($ldapConnection, $sr);
		}
		for ($i = 0; $i < $info["count"]; $i++) {
			if(isset($info[$i]["name"][0])){
				$dado[$_SESSION['Attr']] = $info[$i][$_SESSION['Attr']][0]; 
				$ldapReplace = ldap_mod_del($ldapConnection, $info[$i]["distinguishedname"][0], $dado) or die ("<div class='xl red'>Falha ao inserir</div>");
			}
		}
	}
	
	function removeDescriptionAD($name, $obj, $dependence){
		global $ldapB, $ldapConnection, $info;
		
		$dbView = $_SESSION['root'];
		$value = explode("||", $name);
		$filt = '(&(objectClass=OrganizationalUnit)(' . $_SESSION['Attr'] . '=*' . $value[1] . '/' . $obj . ')(Name=' . $value[0] . '))';
		if($ldapB){
			//Search
			$sr = ldap_search($ldapConnection, $dbView, $filt);
			//Organiza
			$sort = ldap_sort($ldapConnection, $sr, 'name');
			//Recolhe entradas
			$info = ldap_get_entries($ldapConnection, $sr);
		}
		for ($i = 0; $i < $info["count"]; $i++) {
			if(isset($info[$i]["name"][0])){
				if(isset($info[$i][$_SESSION['Attr']][0])){
					$verify = explode(";", $info[$i][$_SESSION['Attr']][0]);
					if(count($verify) > 2){
						$description = str_replace($dependence . ";", "", $info[$i][$_SESSION['Attr']][0]);
						$dado[$_SESSION['Attr']] = $description; 
						$ldapReplace = ldap_mod_replace($ldapConnection, $info[$i]["distinguishedname"][0], $dado) or die ("<div class='xl red'>Falha ao inserir</div>");
					} else {
						$dado[$_SESSION['Attr']] = $info[$i][$_SESSION['Attr']][0]; 
						$ldapReplace = ldap_mod_del($ldapConnection, $info[$i]["distinguishedname"][0], $dado) or die ("<div class='xl red'>Falha ao inserir</div>");
					}
				}
			}
		}
	}
	
	function writeException($text, $opt){
		$writeFile = "./base/MDDB/hidden.mddb";
		if(file_exists($writeFile)){
			$current = file_get_contents($writeFile);
		}
		if($opt == "ADD"){
			$txt = $text;
			$current = $current . $txt . ";";
			$write = file_put_contents($writeFile, $current);
		} elseif($opt == "REMOVE"){
			$txt = str_replace(";" . $text, "", $current);
			$write = file_put_contents($writeFile, $txt);
		}
	
	}
	
	function ldapNavigator($database){
		global $ldapB, $ldapConnection, $info;
		
		$dbView = $database;
		$db = explode(",", $database);
		$dbCount = count($db);
		$limit = file_get_contents("./base/MDDB/hidden.mddb");
		$hidden = explode(";", $limit);
		$filt = '(&(objectClass=OrganizationalUnit))';
		$OUs = array();
		if($ldapB){
			//Search
			$sr = ldap_search($ldapConnection, $dbView, $filt);
			//Organiza
			$sort = ldap_sort($ldapConnection, $sr, 'name');
			//Recolhe entradas
			$info = ldap_get_entries($ldapConnection, $sr);
		}

		for ($i = 0; $i < $info["count"]; $i++) {
			$countOU = explode(",", $info[$i]["distinguishedname"][0]);
			$countOUs = count($countOU);
			$container = true;
			$lock = false;
			for($j = 0; $j < count($hidden); $j++){
				if($info[$i]["name"][0] == $hidden[$j] && $lock == false){
					$container = false;
					$lock = true;
				}
			}
			if($countOUs == $dbCount+1 && $container){
				array_push($OUs, $info[$i]["name"][0] . "|" . $info[$i]["distinguishedname"][0]);
			}
		}		
		return $OUs;
	}
	
	function getComputerData($data){
		
		$arrayData = array();
		$computerContent = file_get_contents("./scripts/PowerShell/Results/computers.txt");
		$computers = explode("#", $computerContent);
		for ($i = 1; $i < count($computers); $i++) {
			$computerData = explode("|", $computers[$i]);
			if($data == "name"){
				array_push($arrayData, $computerData[0]);
			} elseif($data == "operatingsystem"){
				array_push($arrayData, $computerData[1]);
			} elseif($data == "created"){
				$format = explode(" ", $computerData[2], 2);
				$formatDate = explode("/", $format[0]);
				array_push($arrayData, $formatDate[2] . "/" . $formatDate[1] . "/" . $formatDate[0]);
			} elseif($data == "lastlogontimestamp"){
				array_push($arrayData, $computerData[3]);
			}
			
		}	
		return $arrayData;
	}
	
	function getLastLogonData($data){
		if($data == "total"){
			$arrayData = array();
			$computerContent = file_get_contents("./scripts/PowerShell/Results/computers.txt");
			$days30 = file_get_contents("./scripts/PowerShell/Results/30DaysOff.txt");
			$days45 = file_get_contents("./scripts/PowerShell/Results/45DaysOff.txt");
			$days60 = file_get_contents("./scripts/PowerShell/Results/60DaysOff.txt");
			$days75 = file_get_contents("./scripts/PowerShell/Results/75DaysOff.txt");
			$days90 = file_get_contents("./scripts/PowerShell/Results/90DaysOff.txt");
			$computers = explode("#", $computerContent);
			$days30off = explode("#", $days30);
			$days45off = explode("#", $days45);
			$days60off = explode("#", $days60);
			$days75off = explode("#", $days75);
			$days90off = explode("#", $days90);
			
			$total = (count($computers) - 1);
			$days30 = (count($days30off) - 1);
			$days45 = (count($days45off) - 1);
			$days60 = (count($days60off) - 1);
			$days75 = (count($days75off) - 1);
			$days90 = (count($days90off) - 1);
			
			$activated = $total - ($days30 + $days45 + $days60 + $days75 + $days90);
			array_push($arrayData, "30 dias:" . $days30);
			array_push($arrayData, "45 dias:" . $days45);
			array_push($arrayData, "60 dias:" . $days60);
			array_push($arrayData, "75 dias:" . $days75);
			array_push($arrayData, "90 dias:" . $days90);
			array_push($arrayData, "Ativas:" . $activated);
			return $arrayData;
		} 
	}
	
	function getDayOff($data){
		if($data == "30"){
			$arrayData = array();
			$d30 = file_get_contents("./scripts/PowerShell/Results/30DaysOff.txt");
			$d30off = explode("#", $d30);
			for($i = 1; $i < count($d30off); $i++){
				array_push($arrayData, $d30off[$i]);
			}
			return $arrayData;
		} elseif($data == "45"){
			$arrayData = array();
			$d45 = file_get_contents("./scripts/PowerShell/Results/45DaysOff.txt");
			$d45off = explode("#", $d45);
			for($i = 1; $i < count($d45off); $i++){
				array_push($arrayData, $d45off[$i]);
			}
			return $arrayData;
		} elseif($data == "60"){
			$arrayData = array();
			$d60 = file_get_contents("./scripts/PowerShell/Results/60DaysOff.txt");
			$d60off = explode("#", $d60);
			for($i = 1; $i < count($d60off); $i++){
				array_push($arrayData, $d60off[$i]);
			}
			return $arrayData;
		} elseif($data == "75"){
			$arrayData = array();
			$d75 = file_get_contents("./scripts/PowerShell/Results/75DaysOff.txt");
			$d75off = explode("#", $d75);
			for($i = 1; $i < count($d75off); $i++){
				array_push($arrayData, $d75off[$i]);
			}
			return $arrayData;
		} elseif($data == "90"){
			$arrayData = array();
			$d90 = file_get_contents("./scripts/PowerShell/Results/90DaysOff.txt");
			$d90off = explode("#", $d90);
			for($i = 1; $i < count($d90off); $i++){
				array_push($arrayData, $d90off[$i]);
			}
			return $arrayData;
		}
	}
	
	function getCurrentDate(){
		//Set time zone
		date_default_timezone_set("America/Sao_Paulo");
		//Data/Hora Atual
		$data = getdate();
		$horas = (Int)$data["hours"];
		$minutos = (Int)$data["minutes"];
		$segundos = (Int)$data["seconds"];
		$dias = (Int)$data["mday"];
		$meses = (Int)$data["mon"];
		$anos = (Int)$data["year"];
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
		$currentDate = $anos . "/" . $mes . "/" . $dia;
		
		return $currentDate;
	}
	
function getValue($item){
	$expItem = explode("@", $item, 2);
	$value = $expItem[0];
	return $value;
}

function getName($item){
	$expItem = explode("@", $item, 2);
	if(isset($expItem[1])){
		$name = $expItem[1];
	} else {
		$name = "";
	}
	return $name;
}
				
?>