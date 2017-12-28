<?php
	session_start();
	require_once("functions.php");
	
	
	if (isset($_POST['user']) && isset($_POST['password'])){
		$login = $_POST['user'];
		//========================================
		$conf = "./base/MDDB/conf.mddb";
		$fileExists = file_exists($conf);
		if($fileExists){
			$configuration = file_get_contents($conf);
			$configuration = base64_decode(base64_decode($configuration));
			$configuration = explode("|@", $configuration);
			$_SESSION['domain'] = $configuration[0];
			$_SESSION['DC'] = $configuration[1];
			$_SESSION['port'] = $configuration[2];
			$_SESSION['acc'] = base64_encode(base64_encode($configuration[3])) . "||" . base64_encode(base64_encode($configuration[4]));
			$_SESSION['ADMGP'] = $configuration[5];
			$_SESSION['GP'] = $configuration[6];
			$_SESSION['Attr'] = $configuration[7];
			$_SESSION['adm'] = $configuration[8];
			$_SESSION['pwadm'] = $configuration[9];
		} else {
			header("Location:logout.php");
			exit();
		}
		//========================================
	
		//Dados de Conexão LDAP================================================================================
		//Usuário de conexão
		$domain = explode(".", $_SESSION['domain']);
		$_SESSION['domainName'] = $domain[0];
		$_SESSION['toDomain'] = $domain[0];
		$ldapU = $_SESSION['domainName'] . "\\" . $_POST['user'];
		//Senha de conexão
		$ldapPw = $_POST['password'];
		//-------------------------------------------------
		//DN da raiz de pesquisa
		$base = "@" . $_SESSION['domain'];
		$base = str_replace("@", "DC=", $base);
		$base = str_replace(".", ",DC=", $base);
		$_SESSION['root'] = $base;
		//Host de conexão
		$ldapH = "LDAP://" . $_SESSION['DC'] . "." . $_SESSION['domain'];
		//Porta de conexão
		$ldapP = $_SESSION['port'];
		//=====================================================================================================
		//Estabelece conexão com LDAP==========================================================================
			$ldapConnection = ldap_connect($ldapH, $ldapP) or die (header("Location:login.php?erro=1"));
			ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($ldapConnection, LDAP_OPT_REFERRALS, 0);
			
			if($ldapConnection){
				//Executa Binding de conta LDAP
				$ldapBind = ldap_bind($ldapConnection, $ldapU, $ldapPw) or die (header("Location:login.php?erro=2"));
			}
			if($ldapBind){
				$ldapPw = "";
				$ldapU = "";
				$filter = '(&(objectClass=User)(sAMAccountname=' . $login . '))';
				//Search
				$search = ldap_search($ldapConnection, $_SESSION['root'], $filter);
				//Recolhe entradas
				$info = ldap_get_entries($ldapConnection, $search);
				
				if (isset($info[0]["displayname"][0])){
					$grantAccess = 0;
					$name = $info[0]["displayname"][0];
					$countGroups = count($info[0]["memberof"]);
					for($i = 0; $i < $countGroups; $i++){
						$filterGroup = str_replace("CN=", "", $info[0]["memberof"][$i]);
						$filterGroup = explode(",", $filterGroup, 2);
						if($filterGroup[0] != ""){
							if($filterGroup[0] == $_SESSION['GP'] || $filterGroup[0] == $_SESSION['ADMGP']){
								$grantAccess++;
								$_SESSION['group'] = $filterGroup[0];
							}
						}
					}
					if($grantAccess == 2){
						$_SESSION['group'] = $_SESSION['ADMGP'];
					}				
				}
				//Get photo--------------------
				if (isset($info[0]["thumbnailphoto"])){
					$photo = $info[0]["thumbnailphoto"][0];
					$photolg = "<img src='data:image/jpeg;base64," . base64_encode($photo) . "' style='width:120px; height:120px; border-radius:5px; margin-top:20px; border:1px solid rgba(100,100,100,0.3); box-shadow:0px 2px 1px 0px rgba(80,80,80,0.4);'/>";
				} else {
					$photolg = "<img src='images/user_icon.png' style='width:120px; height:120px; border-radius:5px; margin-top:20px; border:1px solid rgba(100,100,100,0.3); box-shadow:0px 2px 1px 0px rgba(80,80,80,0.4);'>";
				}
				
				$_SESSION['matricula'] = $_POST['user'];
				$_SESSION['senha'] = base64_encode($_POST['password']);
				$_SESSION['matricula'] = base64_encode($_SESSION['matricula']);
				$_SESSION['senha'] = base64_encode($_SESSION['senha']);
				
				$_SESSION['name'] = $name;
				$_SESSION['photolg'] = $photolg;
				$_SESSION['browser'] = $_POST['browser'];
				$_SESSION['userData'] = base64_encode($_SESSION['senha'] . "#" . $_SESSION['matricula']);
				$_SESSION['senha'] = "OK";
				
				ldap_close($ldapConnection);
				if($grantAccess == 0){
					header("Location:login.php?erro=5");
				} else {
					$ipaddress = $_SERVER['REMOTE_ADDR'];
					$fqdn = gethostbyaddr($ipaddress);
					$expFqdn = explode(".", $fqdn, 2);
					$_SESSION['hostname'] = strtoupper($expFqdn[0]);
					
					if(strstr($ipaddress, ',')){
						$ips = explode(', ', $ipaddress);
						$_SESSION['IP'] = $ips[0];
					} else {
						$_SESSION['IP'] = $ipaddress;
					}
					$currentdomain = wmiData($_SESSION['IP'], $login, $_POST['password']);
					$_SESSION['currentdomain'] = $currentdomain;
					header("Location:index.php");
				}
				
				
			} else {
				header("Location:logout.php");
				exit();
			}
		//===========================================================================================================
	} else {
		header("Location:logout.php");
		exit();
	}
?>