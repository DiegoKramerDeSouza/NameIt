<!DOCTYPE html>
<html>
	<header>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</header>
	
	<body>

	<?php
		
		
		require_once("ldapConnection.php");
		require_once("getDate.php");
				
		date_default_timezone_set("America/Sao_Paulo");
		
		$account = $_GET["account"];
		$message = $_GET["msg"];
		
		$message = (Int)$message;
		$msgCounter = 0;
		$readedMsg = "";
		$logpath = "//call.br/servicos/LOGS/LogsForceLogoff/";
		
		$filt = '(&(objectClass=User)(sAMAccountname=' . $account . '))';
		$database = "DC=call,DC=br";
		if($lc){
			//Executa Binding de conta LDAP
			$ldapB = ldap_bind($lc, $ldapU, $ldapPw) or die ("<style>.logoff{display:none;}</style><div align='center'><div class='informativo container'><strong>N&atilde;o foi poss&iacute;vel conectar!<br /> Favor verificar seu usu&aacute;rio e senha e tente novamente.</strong><br /><br /><a href='.\index.php' style='color:#fff;'> <i class='fa fa-arrow-left'></i> Voltar</a></div>");
		}
		if($ldapB){
			//Filtro para pesquisa LDAP ================================
			//Search
			$sr = ldap_search($lc, $database, $filt);
			//Organiza
			$sort = ldap_sort($lc, $sr, 'name');
			//Recolhe entradas
			$info = ldap_get_entries($lc, $sr);
			//==========================================================
		}
		for ($i = 0; $i < $info["count"]; $i++) {
			$accountDn = $info[$i]["distinguishedname"][0];
			$msgCounter = explode("|", $info[$i]["extensionattribute13"][0]);
			$msgCount = (Int)$msgCounter[0];
			$atrContent = ($msgCount - 1);
			for ($j = 1; $j <= $msgCount; $j++){
				if($j == $message){
					$content = "";
					$readedMsg = $msgCounter[$j];
				} else {
					$content = "|" . $msgCounter[$j];
				}
				$atrContent = $atrContent . $content;
			}
			$dado["extensionattribute13"] = $atrContent;
			$ldapC = ldap_mod_replace($lc, $accountDn, $dado);
			//Escreve log
			$conteudo = "#" . $horas . ":" . $minutos  . "|-|" . $account . " Confirmou a leitura da mensagem " . $readedMsg . ".\r\n";
			$writeFile = $logpath . "/" . $account . "_" . $anos . "-" . $meses . "-" . $dias . ".log";
			file_put_contents($writeFile, $conteudo, FILE_APPEND);
		}
		ldap_close($lc);
		$account = base64_encode($account);
		
		header("Location:index.php?act=" . base64_encode($account));
		  
	?>

	</body>
	
</html>


