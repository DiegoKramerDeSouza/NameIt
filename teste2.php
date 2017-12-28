<?php
	session_start();
	require_once("pageInfo.php");
	require_once("get-date.php");
	require_once("functions.php");
?>
<!DOCTYPE html>
<html>
	<header>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>NameIT</title>

		<link rel="stylesheet" href="./styles/bootstrap_free.css" />
		<link rel="stylesheet" href="./styles/font-awesome.css" />
		<link rel="stylesheet" href="./styles/style.css" />
	</header>
	<body>
		
		<?php
			//Mensagem de Loading
			echo $pageLoaderOut;
			//Navbar
			echo $pageNavbar;
			//Caixa de diálogo
			echo $msgbox;
		?>
		
		
		<div id='main-carousel' class='carousel slide' data-ride='carousel' >
			<div class='carousel-inner' role='listbox'>
				<div class='item active' style='max-height:300px; top:-50px;'>
					<img src='images/img01.jpg' alt='Gerenciamento de máquinas'>
					<div class='carousel-caption blackCard'>
						<h2 class='fontPlay white'>Gerenciamento de máquinas</h2>
						<span class='fontPlay white'>Insira máquinas no domínio e altere nomes destes ativos de maneira segura e rápida</span>
					</div>
				</div>
				<div class='item' style='max-height:300px; top:-50px;'>
					<img src='images/img02.jpg' alt='Relatórios do ambiente'>
					<div class='carousel-caption blackCard' align='center'>
						<h2 class='fontPlay white'>Relatórios do ambiente</h2>
						<span class='fontPlay white'>Acesse relatórios e gráficos que apresentam os quantitatívos de máquinas do ambiente</span>
					</div>
				</div>
				<div class='item' style='max-height:300px; top:-50px;'>
					<img src='images/img03.png' alt='Configurações personalizadas'>
					<div class='carousel-caption blackCard' align='center'>
						<h2 class='fontPlay white'>Configurações personalizadas</h2>
						<span class='fontPlay white'>Todas as configurações lógicas de distribuição de valores e relações entre itens podem ser personalizadas para atender às necessidades e aos padrões que mais se adequam á empresa</span>
					</div>
				</div>
					
			</div>
			<a class='left carousel-control' href='#main-carousel' role='button' data-slide='prev'>
				<span class='glyphicon glyphicon-chevron-left' aria-hidden='true'></span>
				<span class='sr-only'>Previous</span>
			</a>
			<a class='right carousel-control' href='#main-carousel' role='button' data-slide='next'>
				<span class='glyphicon glyphicon-chevron-right' aria-hidden='true'></span>
				<span class='sr-only'>Next</span>
			</a>
		</div>
	
		
		
		
		
		
		
		<script src='./Scripts/jquery-2.1.4.min.js'></script>
		<script src='./Scripts/bootstrap.min.js'></script>
		

		<script>
			$(document).ready(function(){
				$('.carousel').carousel();
			});
		</script>
		
	</body>
</html>