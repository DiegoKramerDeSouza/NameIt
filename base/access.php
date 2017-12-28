<?php
	session_start();
	$init = file_get_contents("./MDDB/init.mddb");
	
	if (isset($_POST['user']) && isset($_POST['password'])){
		$user = base64_encode($_POST['user']);
		$user = base64_encode($user);
		$pass = base64_encode($_POST['password']);
		$pass = base64_encode($pass);
		$access = base64_encode($user . "||" . $pass);

		if($access === $init){
			$_SESSION['adm'] = $_POST['user'];
			$_SESSION['acc'] = "granted";
			$photo = "<img class='img-circle' src='../images/user_icon.png'>";
			$_SESSION['photo'] = $photo;
			$_SESSION['name'] = "Administrador";
			header("Location:configuration.php");
		} else {
			//echo $access . "<br />" . $init;
			header("Location:logout.php?erro=1");
		}
	} else {
		header("location:logout.php?erro=0");
	}
?>