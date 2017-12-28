<?php
	session_start();
	require_once("pageInfo.php");
	require_once("get-date.php");
	require_once("functions.php");
	if(!(isset($_SESSION['matricula']) && isset($_SESSION['senha']))){
		header("Location:logout.php");
	}
	$fileFields = "./base/MDDB/defaults.mddb";
	if(!file_exists($fileFields)){
		header("Location:logout.php");
	}
?>
<!DOCTYPE html>
<html>
	<?php
		echo $htmlHeader;
	?>
	<body>
		<div id="imgBG">
			<img src="images/bottomflux.png" class="bottomCornerImg" />
		</div>
		<?php
			//Mensagem de Loading
			echo $pageLoaderOut;
			//Navbar
			echo $pageNavbar;
			//Caixa de diálogo
			echo $msgbox;

			$database = $_SESSION['root'];
			$tipo = "";
			$fileDist = "./base/MDDB/distribution.mddb";
			
			$_SESSION['novoNome'] = "";
			$_SESSION['descricao'] = "";
			$_SESSION['alocacao'] = "";
			
			$_SESSION['result'] = "";
			
			$howIm = explode("#", base64_decode($_SESSION['userData']));
			$_SESSION['howI'] = $howIm[1];
			$_SESSION['howK'] = $howIm[0];
			$_SESSION['timedate'] = $datetime;
			$_SESSION['atributes'] = "Nome anterior: " . $_SESSION['hostname'] . " / Executado em:" . $_SESSION['timedate'] . " / Efetuado por: " . base64_decode($_SESSION['howI']) . "/";
			$aria = adjustConfiguration();
			
			$userCard = "<div class='dropdown' id='userCard'>
							<button type='button' class='btn btn-default' data-container='body' data-placement='bottom' id='dropMenu' data-toggle='popover' data-trigger='focus' data-content='
								<div class=\"popoverContent poContent\">
									<a href=\"logout.php\" class=\"blue col-xs-12 underlineHover\" id=\"Logout\" onclick=\"openLoad()\"><span class=\"fa fa-sign-out red\"></span> Sair</a>
									<br />
								</div>
							'>
								<div class='dropContent fontPlay'>
									<span class='fa fa-user lg' style='margin:10px;'></span> 
									<span style='position:relative; top:-5px;'>
										" . $_SESSION['name'] . 
										" <span class='fa fa-caret-down'></span>
									</span>
								</div>
							</button>
						</div>";
			echo $userCard;
			
			echo "<div class='container nmtMenu cardStyle' id='main' >";
				$carousel = 4;
				for($i = 1; $i <= $carousel; $i++){
					$item["start" . $i] = "";
				}
				$random = rand(1,$carousel);
				$item["start" . $random] = "active";
				echo "<div class='row'>
						<div class='col-xs-12 carousel slide' id='carousel-main' data-ride='carousel'>
							<div class='carousel-inner' role='listbox'>
								<div class='thumbnail item " . $item["start1"] . "' style='background-color:transparent;'>
									<img src='images/img01.jpg' alt='Gerenciamento de máquinas'  style='max-height:250px; width:100%; top:-50px; border-radius:5px; border:1px solid rgba(100,100,100,0.3);' />
										<div class='caption blackCardNS' align='right' style='position:relative; top:-170px;'>
											<h2 class='fontPlay white'><span class='fa fa-server'></span> Gerenciamento de máquinas</h2>
											<span class='fontPlay white'>Insira máquinas no domínio e altere nomes ativos de maneira segura, controlada e rápida</span>
										</div>
									</div>
								<div class='thumbnail item " . $item["start2"] . "' style='background-color:transparent;'>
									<img src='images/img02.jpg' alt='Relatórios do ambiente'  style='max-height:250px; width:100%; top:-50px; border-radius:5px; border:1px solid rgba(100,100,100,0.3);' />
									<div class='caption blackCardNS' align='right' style='position:relative; top:-170px;'>
										<h2 class='fontPlay white'><span class='fa fa-file-text'></span> Relatórios do ambiente</h2>
										<span class='fontPlay white'>Acesse relatórios e gráficos que apresentam os quantitatívos de máquinas do ambiente</span>
									</div>
								</div>
								<div class='thumbnail item " . $item["start3"] . "' style='background-color:transparent;'>
									<img src='images/img03.png' alt='Configurações personalizadas'  style='max-height:250px; width:100%; top:-50px; border-radius:5px; border:1px solid rgba(100,100,100,0.3);' />
									<div class='caption blackCardNS' align='right' style='position:relative; top:-170px;'>
										<h2 class='fontPlay white'><span class='fa fa-puzzle-piece'></span> Configurações personalizadas</h2>
										<span class='fontPlay white'>Todas as configurações lógicas, valores e relações entre itens podem ser personalizadas para atender aos padrões que mais se adequam à empresa</span>
									</div>
								</div>
								<div class='thumbnail item " . $item["start4"] . "' style='background-color:transparent;'>
									<img src='images/img04.jpg' alt='Cofre de Senhas'  style='max-height:250px; width:100%; top:-50px; border-radius:5px; border:1px solid rgba(100,100,100,0.3);' />
									<div class='caption blackCardNS' align='right' style='position:relative; top:-170px;'>
										<h2 class='fontPlay white'><span class='fa fa-shield'></span> Cofre de Senhas</h2>
										<span class='fontPlay white'>Mantenha o ambiente protegido gerenciando as senhas das estações de trabalho</span>
									</div>
								</div>		
							</div>
						</div>
					</div>";

				echo "<div style='position:relative; top:-130px;'>
						<div class='row'>
							<div class='col-xs-12 col-sm-5 col-md-4' style='top:-80px;' >
								<div class='col-xs-12' align='left'>
									" . $_SESSION['photolg'] . "
								</div>
								<div class='col-xs-12'>
									<h2 class='fontPlay blue'>Bem vindo(a)<br />" . $_SESSION['name'] . "</h2>
									<span class='fontPlay lblue'>Máquina: <b>" . $_SESSION['hostname'] . "</b> no domínio: <b>" . $_SESSION['currentdomain'] . "</b></span>
								</div>
							</div>";
						
						echo "<div class='col-xs-12 col-sm-7 col-md-8 divider'>
								<br />";
							
							if(file_exists($fileDist)){
								echo "<span class='lg fontPlay lblue'><span class='fa fa-certificate yellow md'></span><b> Selecione uma das opções a seguir:</b></span>";
								echo "<div class='tooltip-show' id='elem1' data-msg='Renomear uma máquina ou inserir no domínio'>";
									echo "<span class='underline col-xs-12'>";
										echo "<a href='#' id='RenJoin' onclick='getPath(\"RenameJoin\", \"" . $database . "\", \"ADM\", \"" . $_SESSION['novoNome'] . "\")' class='lg opt'><span class='fa fa-cog' id='RenJoinIcon'></span> Renomear/Inserir</a>";
									echo "</span>";
								echo "</div>";
								if($_SESSION['currentdomain'] != "1Local"){
									echo "<div class='tooltip-show' id='elem2' data-msg='Reserve o nome desta máquina'>";
										echo "<span class='underline col-xs-12'>";
											echo "<a href='#' id='Reserva' onclick='getPath(\"Reservation\", \"" . $database . "\", \"CALL\", \"" . $_SESSION['novoNome'] . "\")' class='lg opt'><span class='fa fa-cog' id='ReservaIcon'></span> Reservar Nome</a>";
										echo "</span>";
									echo "</div>";
								}
								echo "<div class='tooltip-show' id='elem3' data-msg='Utilize um dos nomes reservados para esta máquina'>";
									echo "<span class='underline col-xs-12'>";
										echo "<a href='#' id='UsaReserva' onclick='getPath(\"castReservation\", \"" . $database . "\", \"CALL\", \"" . $_SESSION['novoNome'] . "\")' class='lg opt'><span class='fa fa-cog' id='UsaReservaIcon'></span> Utilizar Nome Reservado</a>";
									echo "</span>";
								echo "</div>";
								if($_SESSION['currentdomain'] != "1Local"){
									echo "<div class='tooltip-show' id='elem5' data-msg='Relatórios de quantitativos de ativos de tecnologia registrados'>";
										echo "<span class='underline col-xs-12'>";
											echo "<a href='#' id='reports' onclick='administration(\"reports\", \"" . $database . "\", 0)' class='lg opt'><span class='fa fa-cog' id='reportsIcon'></span> Relatórios</a>";
										echo "</span>";
									echo "</div>";
									echo "<div class='tooltip-show' id='elem6' data-msg='Solicitar senha de administrador local para esta estação'>";
										echo "<span class='underline col-xs-12'>";
											echo "<a href='#' id='reqpassword' onclick='getPath(\"reqpassword\",\"" . $database . "\", \"CALL\", \"" . $_SESSION['novoNome'] . "\")' class='lg opt'><span class='fa fa-cog' id='reqpasswordIcon'></span> Solicitar Senha</a>";
										echo "</span>";
									echo "</div>";
								}
							} else {
								echo "<div id='elem3'>";
									echo "<span class='col-xs-12'>";
										echo "<span id='UsaReserva' class='lg opt blue fontPlay'>
													<span class='fa fa-exclamation-triangle red'></span> As definições lógicas do sistema não foram efetuadas pelo administrador.
													<br />
													Todas as configurações devem ser efetuadas antes da utilização do sistema.
												</span>";
										echo "<br />";	
										echo "&nbsp;";
										echo "<br />";		
									echo "</span>";
								echo "</div>";
							}
							if($_SESSION['group'] == $_SESSION['ADMGP'] && $_SESSION['currentdomain'] != "1Local"){
								echo "<div class='tooltip-show' id='elem4' data-msg='Administre o Nomenclatura'>";
									echo "<span class='underline col-xs-12'>";
										echo "<a href='#' id='administrative' onclick='administration(\"administrative\", \"" . $database . "\", 0)' class='lg opt'><span class='fa fa-cog' id='administrativeIcon'></span> Administrar Sistema</a>";
									echo "</span>";
								echo "</div>";
							}
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
			
			echo "<div align='center' class='xl container' id='pulldown' style='display:none; margin-top:60px;'>";
				echo "<a href='#' onclick='slideMain(\"main\", \"toggleMenu\", \"pulldown\", \"chevronImg\")'><span class='fa fa-chevron-up blue' id='chevronImg'></span></a>";
			echo "</div>";
			
			echo "<div id='objOUs' class='container' style='margin-top: 10px;'></div>";
			echo $_SESSION['result'];
			
		?>
		
		<div class='modal fade' id='ADDive' tabindex='-1' role='dialog' aria-labelledby='Dive'>
			<div class='modal-dialog modal-lg' role='document'>
				<div class='modal-content' style='width:100%;'>
					<div class='modal-header navStyle'>
						<span class='fontPlay lg white'>
							<span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
							 Active Directory Dive
						</span>
						<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
							<span class='fa fa-times white lg' aria-hidden='true'></span>
						</button>
					</div>
					<div class='modal-body' id='ADDive-Body' style="padding-bottom:50px;">
					</div>
					<div class='modal-body' id='applicationButton' align='right'>
					</div>
				</div>
			</div>
		</div>
		
		<div class='modal fade' id='Cmodal' tabindex='-1' role='dialog' aria-labelledby='Dive'>
			<div class='modal-dialog' role='document'>
				<div class='modal-content' style='width:100%;'>
					<div class='modal-header'>
						<span class='fontPlay lg' id='Cmodal-Header'>
							...Header Content...
						</span>
					</div>
					<div class='modal-body fontPlay md' id='Cmodal-Body' style="padding-bottom:30px;">
						...Body Content...
					</div>
					<div class='modal-footer' id='CmodalButton'>
						...Footer Content...
					</div>
				</div>
			</div>
		</div>
			
		<div class='tooltipFade'>
			<div class='tooltip bottom' role='tooltip' id='tooltip-ex'>
				<div class='tooltip-arrow'></div>
				<div class='tooltip-inner' id='tooltipText'>
				</div>
			</div>
		</div>
		
		<div id="log"></div>
		
		<div id="toTop" align="center">
			<span class="fa fa-chevron-up fa-2x" id="upToTop"></span>
		</div>
		<div style="padding-top:80px;">&nbsp;</div>			
		<div class="bottomFooter" id="homefooter" style="position:fixed; bottom:0px; width:100%;">
			<?php 
				echo $pageFooter;
			?>
		</div>
		
		<script src='./Scripts/jquery-2.1.4.min.js'></script>
		<script src='./Scripts/bootstrap.min.js'></script>
		<script src='./Scripts/bootstrap-toggle.min.js'></script>
		<script src='./scripts/animated.js'></script>
		<script src='./scripts/jQueryRollPlugin/jRoll.min.js'></script>
		<script type="text/vbscript" src="scripts\VBScript\VBScript.vbs"></script>
		<script src="./scripts/Chart.js"></script>
		
	</body>
</html>


