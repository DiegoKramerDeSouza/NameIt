<?php
	if(isset($_POST['domain']) && (isset($_POST['server']) && (isset($_POST['port']) && (isset($_POST['user']) && (isset($_POST['pass']) && (isset($_POST['admGP']) && (isset($_POST['gp']) && (isset($_POST['attr']) && (isset($_POST['adm']) && isset($_POST['pwadm'])))))))))){
		$codefile = "./MDDB/conf.mddb";
		$inputCodeFile = $_POST['domain'] . "|@" . $_POST['server'] . "|@" . $_POST['port'] . "|@" . $_POST['user'] . "|@" . $_POST['pass'] . "|@" . $_POST['admGP'] . "|@" . $_POST['gp'] . "|@" . $_POST['attr'] . "|@" . $_POST['adm'] . "|@" . $_POST['pwadm'];
		$inputCodeFile = base64_encode($inputCodeFile);
		$inputCodeFile = base64_encode($inputCodeFile);
		$writecodex = file_put_contents($codefile, $inputCodeFile);		
	}
	if(isset($_POST['value'])){
		$codefile = "./MDDB/defaults.mddb";
		$inputCodeFile = $_POST['value'];
		$inputCodeFile = base64_encode($inputCodeFile);
		$inputCodeFile = base64_encode($inputCodeFile);
		$writecodex = file_put_contents($codefile, $inputCodeFile);
	}
	
?>