<?php
	//require_once("conf.php");
	//========================================
	$conf = "./base/MDDB/conf.mddb";
	$fileExists = file_exists($conf);
	if($fileExists){
		$configuration = file_get_contents($conf);
		$configuration = base64_decode(base64_decode($configuration));
		$configuration = explode("|@", $configuration);
		$domain = $configuration[0];
		$domainEx = explode(".", $domain);
		$domainName = $domainEx[0];
		$dc = $configuration[1];
		$port = $configuration[2];
		$acc = base64_encode(base64_encode($configuration[3])) . "||" . base64_encode(base64_encode($configuration[4]));
		
	} else {
		header("Location:logout.php");
		exit();
	}
	//========================================
	
	//Dados de Conexão LDAP================================================================================
	$tempAcc = explode("||", $acc);
	
	//Usuário de conexão
	//$ldapU = "call\\" . base64_decode($sysAcc);
	$accounts = explode("||", $acc);
	$ldapU = $domainName . "\\" . base64_decode(base64_decode($accounts[0]));
	
	//Senha de conexão
	//$ldapPw = base64_decode($sysPwd);
	$ldapPw = base64_decode(base64_decode($accounts[1]));
	
	//Caminho - OU
	//$base = "DC=call,DC=br";
	$base = "@" . $domain;
	$base = str_replace("@", "DC=", $base);
	$base = str_replace(".", ",DC=", $base);
	
	//Host de conexão
	//$ldapH = "LDAP://SVDF07W000005.call.br";
	//$ldapH = "LDAP://SVDF07W000010.call.br";
	
	$ldapHTemp = "LDAP://" . $dc . "." . $domain;
	$ldapH = $ldapHTemp;
	
	//Porta de conexão
	$ldapP = $port;
	//=====================================================================================================
		
	//Estabelece conexão com LDAP	
	$ldapConnection = ldap_connect($ldapH, $ldapP) or die (header("Location:login.php?erro=NotConnected"));
	ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ldapConnection, LDAP_OPT_REFERRALS, 0);
	
	if($ldapConnection){
		//Executa Binding de conta LDAP
		$ldapB = ldap_bind($ldapConnection, $ldapU, $ldapPw) or die (header("Location:login.php?erro=NotBinded"));
	}
	//===========================================================================================================
	
	
?>