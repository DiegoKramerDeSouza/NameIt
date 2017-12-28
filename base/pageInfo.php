<?php
//Configurações de página
$htmlHeader = '<header>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>NameIt</title>

		<link rel="stylesheet" href="../styles/bootstrap_free.css" />
		<link rel="stylesheet" href="../styles/font-awesome.css" />
		<link rel="stylesheet" href="../styles/style.css" />
		<link rel="stylesheet" href="../scripts/jQueryRollPlugin/jRoll.css" />
		<link href="../Images/hexagon.png" rel="icon" type="image/png" />
		
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->			
	</header>';
	
$pageLoader = '<div id="loading">
				<div id="dialogLoading" align="center">
					<div>
						<span class="fontPlay">Iniciando sessão de administrador.<br />Aguarde...</span><br/>
					</div>
					<div id="loadGif">
						<img src="../images/load.png" class="spinner_md" style="width:120px; height:120px;"></span>
					</div>
				</div>
			</div>';

$pageLoaderOut = '<div id="loading">
				<div id="dialogLoading" align="center">
					<div>
						<span class="fontPlay">Aguarde...</span>
					</div>
					<div id="loadGif">
						<img src="../images/load.png" class="spinner_md" style="width:120px; height:120px;"></span>
					</div>
				</div>
			</div>';

$osWarning = '<div id="warning">
				<div id="dialogWarning">
					<div>
						<span class="fontPlay">
							<div align="center">
								<span class="fa fa-times fa-4x red"></span>
							</div>
							<div class="col-xs-10 col-md-6 col-xs-push-1 col-md-push-3">
								<p>O Sistema Operacional desta máquina não é compatível com esta versão do sistema de Nomenclatura.</p>
								<p>Você deverá utilizar o navegador <span class="blue"><b>Internet Explorer</b></span> acessando a versão anterior.</p>
							</div>
						</span>
					</div>
				</div>
			</div>';
		
$pageHeader = '	<a href="index.php" class="navbar-brand bottomUp">
					<span class="logo white fa fa-gears xxl" style="top:-5px;"></span>
				</a>
				<a href="index.php" class="navbar-brand">
					<span id="imgLogon" style="margin-top:5px; font-size:34px; font-family: Play, roboto, museo, Arial Black, Berlin Sans FB, Arial, Impact;"> <span class="white">Definições</span> </span>
				</a>';
				
$pageNavbar = '<div class="navbar navbar-inverse navbar-fixed-top singleNavegation" role="navegation" >
					<div class="container-fluid" style="padding-bottom:5px;">
						<div class="navbar-header container">' . $pageHeader . '</div>
					</div>
				</div>';

$pageFooter = '<div class="row">
					<div class="col-xs-10 col-sm-10 col-md-4" style="margin-top:-5px;">
						<span class="sm">&copy; 2017</span> &nbsp; <span class="md">Vir<span class="lblue">IT</span></span>
					</div>
					<div class="col-xs-2 col-sm-2 col-md-8 footIconDiv" style="margin-right:0px; text-align:right;">
						<span class="navbar-right">
							<img src="../Images/BCKG_Hexagon.png" class="footIcon"/>
						</span>
					</div>
				</div>';
				
$msgbox = '<div id="msgDiv" align="center">
			<div id="dialog" style="padding:20px;">
				<div id="closebtn" onclick="clearMsgBox()" align="right"><a href="#" class="close-times"><i class="fa fa-times fa-2x"></i></a></div>
				<span id="msgBody"></span><br />
			</div>
		</div>';

?>