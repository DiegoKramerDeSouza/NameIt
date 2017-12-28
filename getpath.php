<?php
	session_start();
	require_once("functions.php");
	
	if(isset($_GET["target"]) && isset($_GET["path"])){
		$target = $_GET["target"];
		$database = $_GET["path"];
	}
	if(isset($_FILES['BKPFile'])){
		$target = "";
		$database = "";
		if ($_FILES['BKPFile']['error'] > 0) {
			header("Location:index.php?erro=6");
		} else {
			$targetDir = "./uploads/";
			$targetFile = $targetDir . basename($_FILES["BKPFile"]["name"]);
			$validExtensions = array('.bkp');
			$fileExtension = strrchr($_FILES['BKPFile']['name'], ".");
			if (in_array($fileExtension, $validExtensions)) {
				if(move_uploaded_file($_FILES["BKPFile"]["tmp_name"], $targetFile)){
					$result = loadBkpFile(basename($_FILES["BKPFile"]["name"]));
					header("Location:index.php?success=1");
				} else {
					header("Location:index.php?erro=7");
				}
			} else {
				header("Location:index.php?erro=8");
			}
		}
	}

	$siteData = array();
	$siteDataContent = collectSites();
	foreach($siteDataContent as $siteItem){
		$eachDataContent = explode(">", $siteItem, 2);
		array_push($siteData, $eachDataContent[0]);
	}

	if(isset($_GET["data"])){
		if($_GET["data"] == "Operacao"){
			$_SESSION["Setor"] = "B";
		} else {
			$_SESSION["Setor"] = "A";
		}
		
		$data = explode("|", $_GET["data"]);
		if(isset($data[1]) && isset($data[2])){
			$_SESSION["Cliente"] = $data[0];
			$_SESSION["Site"] = $data[1];
			$_SESSION["UF"] = $data[2];
		}
		$dtec = explode("!", $_GET["data"]);
		if(isset($dtec[1])){
			$_SESSION["UF"] = $dtec[1];
		}
	}
	
	if(isset($_GET["extra"])){
		$extraValue = $_GET["extra"];
	} else {
		$extraValue = "";
	}
	
	//Etapa 1-------------------------------------------------------------------------------------------------------------------------
	//Início de verificação: definição de campos e tratamento de dados definidos nas configurações da aplicação-----------------------
	$fileDist = "./base/MDDB/distribution.mddb";
	if(file_exists($fileDist)){
		$activeted = true;
		if(!isset($_SESSION['distribution'])){
			$distContent = file_get_contents($fileDist);
			$distContent = base64_decode(base64_decode($distContent));
			$_SESSION['distribution'] = $distContent;
		}
		$splitContent = explode("|@|", $_SESSION['distribution']);
		$order = $splitContent[0];
		$orderVal = explode("<<", $order);
		$linked = $splitContent[1];
		$linkedVal = explode("<<", $linked);
	} else {
		$activeted = false;
	}
	
	//--------------------------------------------------------------------------------------------------------------------------------
	
	if($target == "RenameJoin" || $target == "RenameJoinBack"){
		if(isset($_GET['page'])){
			$index = $_GET['page'];
		} else {
			$index = 1;
		}
		if(!isset($_GET['pastislinked'])){
			$_GET['pastislinked'] = 0;
		}
		switch($index){
			case 1:
				$divider = "cardStyleDBlue";
				$buttomType = "bgblue";
				$color = "blue";
				break;
			case 2:
				$divider = "cardStyleGreen";
				$buttomType = "bggreen";
				$color = "green";
				break;
			case 3:
				$divider = "cardStyleYellow";
				$buttomType = "bgyellow";
				$color = "yellow";
				break;
			case 4:
				$divider = "cardStyleLBlue";
				$buttomType = "bglblue";
				$color = "lblue";
				break;
			case 5:
				$divider = "cardStyleOrange";
				$buttomType = "bgorange";
				$color = "orange";
				break;
			default:
				$divider = "cardStyleBlack";
				$buttomType = "bgblack";
				$color = "black";
				break;
		}
		if($index == count($orderVal)){
			$completed = true;
		} else {
			$completed = false;
			$information = explode("||", $orderVal[$index]);
			$selection = $information[1];
		}
		
		$islinked = false;
		for($i = $index; $i < count($linkedVal); $i++){
			if($selection == $linkedVal[$i]){
				$islinked = true;
			}
		}
		//Cria as sessões a partir dos campos---------
		if($index == 1){
			for($i = 0; $i < count($orderVal); $i++){
				if($i == 0){
					$_SESSION['prep'] = "*";
				} else {
					$session = explode("||", $orderVal[$i]);
					$sessionName = $session[1];
					$_SESSION[$sessionName] = "";
				}
			}
		}
		//--------------------------------------------
		//Definições de campos -----------------------
		$fields = array();
		array_push($fields, "prep");
		for($i = 1; $i < count($orderVal); $i++){
			$session = explode("||", $orderVal[$i]);
			$sessionName = $session[1];
			array_push($fields, $sessionName);
		}
		//--------------------------------------------
		
		if(!$completed){
			//Atribui o valor recebido às sessões---------
			if(!(isset($_GET['inputObj']) && isset($_GET['inputObjName'])) || $index <= 1){
				$_GET['inputObj'] = "";
				$_GET['inputObjName'] = "";
			} else {
				if($index > 1){
					$_SESSION[$fields[$index-1]] = $_GET['inputObj'] . "-" . $_GET['inputObjName'];
				}
			}
			if($target == "RenameJoinBack"){
				$_SESSION[$fields[$index]] = "";
			}
			$_SESSION['novoNome'] = "";
			//--------------------------------------------

			//Exibe campos selecionados-------------------
			echo "<div class='blue fontPlay md container-fluid' style='padding-bottom:0px; margin-left:0px; max-height:47px;' >";
				echo "<b>NOME FORMADO:</b>
					<br />
					<span class='red lg' style='margin:10px;'>";
						echo "<span class='fa fa-terminal lblue lg'></span> ";
						for($i = 1; $i < count($fields); $i++){
							if($_SESSION[$fields[$i]] != ""){
								$fieldExp = explode("-", $_SESSION[$fields[$i]]);
								$_SESSION['novoNome'] = $_SESSION['novoNome'] . $fieldExp[0];
							}
						}
						echo "<b>" . $_SESSION['novoNome'] . "</b>";
				echo "</span>";
				echo "	<div align='right' style='width:100%; margin-top:0px;'>
							<div id='ps' class='pages bgblue fontPlay white' style='position:relative; top:-50px;' align='center'>
								<div style='margin-top:5px;'>" . $index . "/" . (count($fields)-1) . "</div>
							</div>
						</div>";
			echo "</div>";
			echo "<br />";
			
			echo "<div class='row container " . $divider . "' style='margin-top:20px;'>";
				echo "<div class='row container'>";
					if($index > 1){
						$nliObjName = $_GET['inputObjName'];
					} else {
						$nliObjName = "";
					}
					if($islinked){
						if($_GET['inputObj'] != ""){
							//echo "Pesquisa: *" . $_GET['inputObj'] . "@" . $_GET['inputObjName'] . "*/" . $selection;
							$adlist = getOUDescriptionAD("*" . $_GET['inputObj'] . "@" . $_GET['inputObjName'] . "*/" . $selection, $database);
						} else {
							//echo "Pesquisa: */" . $selection;
							$adlist = getOUDescriptionAD("*/" . $selection, $database);
						}
						echo "<div class='row' style='margin-top:-10px; margin-right:-35px;'>";
							echo "<div class='col-xs-12' align='center'>";
								if($index > 1){
									if($_GET['pastislinked'] == 1){
										echo "<span class='btn btn-success btn-sm " . $buttomType . "' style='border:0px; margin:5px; min-width:150px;' onclick='previouspage(" . $index . ", \"RenameJoinBack\", \"" . $_SESSION['root'] . "\", \"" . $_SESSION[$fields[$index-2]] . "\")'> <span class='fa fa-chevron-left'></span> Voltar </span>";
									} else {
										echo "<span class='btn btn-success btn-sm " . $buttomType . "' style='border:0px; margin:5px; min-width:150px;' onclick='previouspage(" . $index . ", \"RenameJoinBack\", \"" . $_SESSION['root'] . "\", \"" . $_SESSION[$fields[$index-2]] . "\")'> <span class='fa fa-chevron-left'></span> Voltar </span>";
									}
								}
								if(count($adlist) > 0){
									echo "<button id='page_next' class='btn btn-success btn-sm " . $buttomType . "' style='border:0px; margin:5px; min-width:150px;' onclick='nextpage(" . $index . ", \"RenameJoin\", \"" . $_SESSION['root'] . "\")' > Avançar <span class='fa fa-chevron-right'></span> </button>";
								}
							echo "</div>";
						echo "</div>";
						
						echo "<div class='navContent container-fluid' style='padding:20px;'>";
							echo "<div class='row'>";
								echo "<div class='col-xs-12'>";
									echo "<div class='col-xs-12 lg fontPlay " . $color . "' style='padding-bottom:30px; margin-left:-30px;'>";
										echo "<div class='col-xs-12'>";
											echo "<span class='fa fa-cubes'></span> Selecione um(a) " . $selection . ":<br />";
											echo "<input type='text' style='color:#f00; width:100%;' name='value_selected' id='value_selected' readonly placeholder='" . $selection . " selecionado(a)' />";
											echo "<input type='hidden' name='value_name' id='value_name' readonly />";
										echo "</div>";
									echo "</div>";
									for($j = 0; $j < count($adlist); $j++){
										$aditem = explode("/", $adlist[$j]);
										$addata = $aditem[0];
										$adval = $aditem[1];
										$adtype = $aditem[2];
										$itemdata = explode("|", $addata);
										$adname = $itemdata[0];
										$addn = $itemdata[1];
										$itemdep = explode(";", $itemdata[2]);				
										
										echo "<div class='col-xs-12 col-sm-6 cardHover selectInput lg' id='" . $j . "_Select' data-value='" . $adval . "' onclick='renameselection(\"" . $j . "_Select\", \"" . $adname . "\")' style='cursor: pointer;'>";
											echo "<span class='fa fa-cube " . $color . " lg selectIcon' id='" . $j . "_Select-icon' ></span> <span class='selectAnchor black fontPlay' id='" . $j . "_Select-anchor' >" . $adname . "</span>";
										echo "</div>";
									}
									if(count($adlist) == 0){
										echo "<div class='fontPlay col-xs-12 lg'>";
											echo "<p><span class='fa fa-times red lg'></span> <b style='opacity: 0.5'> Não há registros para este objeto!</b></p>";
										echo "</div>";
									}
								echo "</div>";
							echo "</div>";
						echo "</div>";
						
						$_GET['pastislinked'] = 1;
					} else {
						//echo "Procurando: " . $_GET['inputObj'] . "@" . $nliObjName;
						$nlifile = "./base/MDDB/NLI/" . $selection . ".mddb";
						$nlicontent = file_get_contents($nlifile);
						$nliobj = explode(";", $nlicontent);
						$nliCount = 0;
						
						echo "<div class='row' style='margin-top:-10px; margin-right:-35px;'>";
							echo "<div class='col-xs-12' align='center'>";
								if($index > 1){
									if($_GET['pastislinked'] == 1){
										echo "<span class='btn btn-success btn-sm " . $buttomType . "' style='border:0px; margin:5px; min-width:150px;' onclick='previouspage(" . $index . ", \"RenameJoinBack\", \"" . $_SESSION['root'] . "\", \"" . $_SESSION[$fields[$index-2]] . "\")'> <span class='fa fa-chevron-left'></span> Voltar </span>";
									} else {
										echo "<span class='btn btn-success btn-sm " . $buttomType . "' style='border:0px; margin:5px; min-width:150px;' onclick='previouspage(" . $index . ", \"RenameJoinBack\", \"" . $_SESSION['root'] . "\", \"" . $_SESSION[$fields[$index-2]] . "\")'> <span class='fa fa-chevron-left'></span> Voltar </span>";
									}
								}
								if(count($nliobj) > 0){
									echo "<button id='page_next' class='btn btn-success btn-sm " . $buttomType . "' style='border:0px; margin:5px; min-width:150px;' onclick='nextpage(" . $index . ", \"RenameJoin\", \"" . $_SESSION['root'] . "\")' > Avançar <span class='fa fa-chevron-right'></span> </button>";
								}
							echo "</div>";
						echo "</div>";
						
						echo "<div class='navContent container-fluid' style='padding:20px;'>";
							echo "<div class='row'>";
								echo "<div class='col-xs-12'>"; 
									echo "<div class='col-xs-12 lg fontPlay " . $color . "' style='padding-bottom:30px; margin-left:-30px;'>";
										echo "<div class='col-xs-12'>";
											echo "<span class='fa fa-cubes'></span> Selecione um(a) " . $selection . ":<br />";
											echo "<input type='text' style='color:#f00; width:100%;' name='value_selected' id='value_selected' readonly placeholder='" . $selection . " selecionado(a)' />";
											echo "<input type='hidden' name='value_name' id='value_name' readonly />";
										echo "</div>";
									echo "</div>";
									
									for($j = 1; $j < count($nliobj); $j++){
										$nliitem = explode("||", $nliobj[$j]);
										$nliname = $nliitem[0];
										$nlivalue = $nliitem[1];
										$nlidep = $nliitem[2];
										$nlidepval = $nliitem[3];
										
										
										if($nlidepval == $_GET['inputObj'] . "@" . $nliObjName || $index == 1){
											echo "<div class='col-xs-12 col-sm-6 cardHover selectInput lg' id='" . $j . "_Select' data-value='" . $nlivalue . "' onclick='renameselection(\"" . $j . "_Select\", \"" . $nliname . "\")' style='cursor: pointer;'>";
												echo "<span class='fa fa-cube " . $color . " lg selectIcon' id='" . $j . "_Select-icon' ></span> <span class='selectAnchor black fontPlay' id='" . $j . "_Select-anchor' >" . $nliname . "</span>";
											echo "</div>";
											$nliCount++;
										}
									}
									if(count($nliobj) == 0 || $nliCount == 0){
										echo "<div class='fontPlay col-xs-12 lg'>";
											echo "<p><span class='fa fa-times red lg'></span> <b style='opacity: 0.5'> Não há registros para este objeto!</b></p>";
										echo "</div>";
									}
								echo "</div>";
							echo "</div>";
						echo "</div>";
						
						$_GET['pastislinked'] = 0;
					}
				echo "</div>";
			echo "</div>";
		} else {
		//COMPLETED!---------------------------------------------------------------			
			$_SESSION['novoNome'] = $_SESSION['novoNome'] . $_GET['inputObj'];
			for($i = 1; $i < count($fields); $i++){
				if($i == (count($fields)-1)){
					$filter = $fields[$i];
				}
			}
			$filter = "*" . $_GET['inputObj'] . "/" . $filter;
			$result = getOUDescription($filter, $database, $_GET['inputObjName']);
			$expOU = explode("|", $result[0], 2);
			$OU = getOU($expOU[1]);
			$_SESSION['alocacao'] = $expOU[1];
			$expdescription = explode(",", $expOU[1], 2);
			$rpcdescription = str_replace("OU=", "", $expdescription[0]);
			$_SESSION['descricao'] = $rpcdescription;
			
			echo "<div class='row container'>
					<h1 class='row fontPlay'>
						<span class='col-xs-12'><span class='fa fa-cube'></span> Informe a alocação:</span>
					</h1>
				</div>
				<div class='row container'>";
					for ($i = 0; $i < count($OU); $i++) {
						$result = explode("|", $OU[$i]);
						$database = $result[1];
						echo "<div class='col-xs-12 col-sm-6'><a href='#' id='" . $i . "_li' onclick='getPath(\"RenameJoinFinishing\", \"" . $expOU[1] . "\", \"CALL\", \"" . $result[0] . "\")' class='list-group-item cardHover lg'><span class='fa fa-desktop blue'></span> " . $result[0] . "</a></div>";
					}
			echo "</div>";
		}
	//################################################################################################################################	
	} elseif($target == "RenameJoinFinishing"){
		$_SESSION['alocacao'] = "OU=" . $_GET['data'] . "," . $_SESSION['alocacao'];
		//$_SESSION['descricao'] = $_SESSION['descricao'] . " - " . $_GET['data'];
		$counter = 1;
		do{
			$length = strlen((string)$counter);
			if($length == 1){
				$zeros = "000";
			} elseif ($length == 2){
				$zeros = "00";
			} elseif ($length == 3){
				$zeros = "0";
			} else {
				$zeros = "";
			}
			$verifyName = $_SESSION['novoNome'] . $zeros . $counter;
			$verifyFile = file_exists("./database/" . $verifyName . ".log");
			if(!$verifyFile){
				//Grava o novo nome!!!!!!!!!!!!!!!!!.
			} else {
				$counter++;
			}
		} while($verifyFile);
		$_SESSION['newName'] = $verifyName;
		echo "<div class='container-fluid row'>";
			echo "<div class='row black cardStyleLBlue fontPlay'>";
					if($_SESSION['currentdomain'] == "1Local"){
						echo "<div class='row col-xs-12 col-md-6 container-fluid top10'>"; 
							echo "<label class='container-fluid'>Informe Usuário e Senha de administrador desta máquina: (Obrigatório)</label><br />";
							echo "<div class='col-xs-12'>
									<span class='fa fa-user blue'></span> &nbsp;&nbsp;<input type='text' name='localUser' id='localUser' placeholder='Usuário Local' onfocus='inputArt(\"localUser_slider\", \"localUser\")' onblur='inputArtFade(\"localUser_slider\")' autofocus />
									<span class='slide_' id='localUser_slider'></span>
								</div>
								<br />";
							echo "<div class='col-xs-12'>
									<span class='fa fa-lock blue'></span> &nbsp;&nbsp;<input type='password' name='localPass' id='localPass' placeholder='Senha Local' onfocus='inputArt(\"localPass_slider\", \"localPass\")' onblur='inputArtFade(\"localPass_slider\")' />
									<span class='slide_' id='localPass_slider'></span>
								</div>";
							echo "<div class='col-xs-12'>
									<span class='fa fa-lock blue'></span> &nbsp;&nbsp;<input type='password' name='localPassCnf' id='localPassCnf' placeholder='Confirme a senha' onfocus='inputArt(\"localPassCnf_slider\", \"localPassCnf\")' onblur='inputArtFade(\"localPassCnf_slider\")' />
									<span class='slide_' id='localPassCnf_slider'></span>
								</div>";
						echo "</div>";
						echo"<div class='col-xs-12 col-md-6 top10'>
						<label>Informação extra para esta máquina: (Opcional)</label><br />
						<span class='fa fa-arrow-right blue'></span> &nbsp;&nbsp;<input type='text' name='Information' id='Information' placeholder='EX: PA 150' onfocus='inputArt(\"Information_slider\", \"Information\")' onblur='inputArtFade(\"Information_slider\")' size='50' />
						<span class='slide_' id='Information_slider'></span>
					</div>
				</div>
			<br />";
					} else {
								echo"<div class='col-xs-12 top10'>
								<label>Informação extra para esta máquina: (Opcional)</label><br />
								<span class='fa fa-arrow-right blue'></span> &nbsp;&nbsp;<input type='text' name='Information' id='Information' placeholder='EX: PA 150' onfocus='inputArt(\"Information_slider\", \"Information\")' onblur='inputArtFade(\"Information_slider\")' size='50' autofocus />
								<span class='slide_' id='Information_slider'></span>
							</div>
						</div>
					<br />";
					}
					
			echo "<div class='row cardStyleYellow'>";		
				echo "<div class='col-xs-12 col-sm-12 col-md-4' align='center'>
						<h1 class='fontPlay'>Nome da Máquina:</h1>
						<h3 class='fontPlay red'>" . $verifyName . "</h3>
						<h4 class='fontPlay blue'><span class='black'>Descrição: </span>" . $_SESSION['descricao'] . "</h4>
					</div>";
				echo "<div class='col-xs-12 col-sm-12 col-md-7 col-sm-push-1 divider' align='center'>";
				if($_SESSION['currentdomain'] == "1Local"){
					echo "<span class='btn btn-info btn-lg bgblue lg' style='margin-top:5%;' id='btn-Join' onclick='startScript(\"execJoin\", \"" . $database . "\", \"" . $_SESSION['descricao'] . "\")'><span class='fa fa-desktop'></span> Adicionar ao Domínio</span>";
				} else {
					echo "<span class='btn btn-success btn-lg lg' style='margin-top:5%;' id='btn-Rename' onclick='startScript(\"execRename\", \"" . $database . "\", \"" . $_SESSION['descricao'] . "\")'><span class='fa fa-desktop'></span> Renomear Máquina</span>";
				}
					echo "<br /><br />";
				echo "</div>";
			echo "</div>";
		echo "</div>";	
		
	//CONCLUI A INSERÇÃO/RENOMEAÇÃO DE MÁQUINAS NO DOMÍNIO################################################# 
	} elseif($target == "castReservation") {
		$writeFile = "./reservations/" . base64_decode($_SESSION['matricula']) . ".log";
		if(file_exists($writeFile)){
			$current = file_get_contents($writeFile);
			$split = explode("#", $current);
			if(count($split) > 1){
				echo "<div class='container cardStyle' style='padding:20px;'>";
					echo "<div class='row container'>
							<h1 class='fontPlay'>
								<span class='col-xs-12'> <span class='fa fa-download'></span> Selecione o nome a ser utilizado: </span>
							</h1>
						</div>";
					echo "<div class='row container'>";
					foreach($split as $hosts){
						$hostname = explode("|", $hosts);
						if($hostname[0] != ""){
							$hostname[0] = strtoupper($hostname[0]);
							echo "<div class='fontPlay' aling='center' style='margin:20px; display:inline-block;'>";
								echo "<div class='row cardHover cardStyle' align='center'>";		
									echo "<h4 class='fontPlay black'>
												<span class='fa fa-desktop blue'></span> " . $hostname[0] . "
											</h4>";
									echo "<div id='" . $hostname[0] . "'>";
											if($_SESSION['currentdomain'] == "1Local"){
												echo "<span class='btn btn-info btn-sm xs bottom10' style='margin:2px;' data-toggle='modal' data-target='#Cmodal' data-opt='JOIN' data-machine='" . $hostname[0] . "'><span class='fa fa-check sm'></span> Utilizar Nome</span>";
											} else {
												echo "<span class='btn btn-success btn-sm xs bottom10' style='margin:2px;' data-toggle='modal' data-target='#Cmodal' data-opt='RENAME' data-machine='" . $hostname[0] . "'><span class='fa fa-check sm'></span> Utilizar Nome</span>";
											}
										echo"<span class='btn btn-danger btn-sm xs bottom10' style='margin:2px;' onclick='reservation(\"eraseReservation\", \"" . $hostname[0] . "\", \"" . $hostname[1] . "\", \"" . $hostname[2] . "\")'><span class='fa fa-times sm'></span> Excluir Reserva</span>
										</div>";
								echo "</div>";
							echo "</div>";
						}
					}
					echo "</div>";
				echo "</div>";
			} else {
				echo "<div id='Area' class='container row'>";
					echo "<div aling='center'>";
						echo "<div class='row nmtMenu cardStyle' align='center'>";		
							echo "<h1 class='fontPlay red'>
										<span class='fa fa-exclamation-circle fa-2x red'></span><br />
										Não há reservas efetuadas em seu nome!
									</h1>";
						echo "</div>";	
					echo "</div>";
				echo "</div>";
			}
		} else {
			echo "<div id='Area' class='container row'>";
				echo "<div aling='center'>";
					echo "<div class='row nmtMenu cardStyle' align='center'>";		
						echo "<h1 class='fontPlay red'>
									<span class='fa fa-exclamation-circle fa-2x red'></span><br />
									Não há reservas efetuadas em seu nome!
								</h1>";
					echo "</div>";	
				echo "</div>";
			echo "</div>";
		}
		
	} elseif($target == "Reservation"){
		if($_SESSION['currentdomain'] == "1Local"){
			echo "<div id='Area' class='container row'>";
				echo "<div aling='center'>";
					echo "<div class='row nmtMenu cardStyle' align='center'>";		
						echo "<h1 class='fontPlay red'>
									<span class='fa fa-exclamation-circle fa-2x red'></span><br />
									Esta máquina deve estar no domínio para ser efetuada a reserva de nome!
								</h1>";
					echo "</div>";	
				echo "</div>";
			echo "</div>";
		} else {
			$hostData = getHostData($_SESSION['hostname']);
			$_SESSION['hostDescription'] = $hostData[1];
			$hostDN = explode(",", $hostData[2], 2);
			$_SESSION['hostDN'] = $hostDN[1];
			echo "<div id='Area' class='container row'>";
				echo "<div class='row nmtMenu cardStyle'>";
					echo "<div class='col-sm-6'><h1 class='fontPlay blue'>Deseja reservar o nome dessa máquina?<h1>";		
						echo "<h5 class='fontPlay red'>
								Máquina: <b>" . $_SESSION['hostname'] . "</b><br />
								Descrição: <b>" . $_SESSION['hostDescription'] . "</b><br />
							</h5>";
					echo "</div>";
					echo "<div class='divider col-sm-6 minHeight200' align='center'>";
						echo "<span class='btn btn-danger btn-lg lg' style='margin-top:10%;' onclick='reservation(\"makeReservation\", \"" . $_SESSION['hostname'] . "\", \"" . $_SESSION['hostDescription'] . "\", \"" . $_SESSION['hostDN'] . "\")'><span class='fa fa-calendar-check-o'></span> Reservar!</span><br />";
					echo "</div>";	
				echo "</div>";
			echo "</div>";
		}
	//Relatórios e gráficos---------------------------------------------------------------------
	} elseif($target == "reports"){
		echo "<div class='row'>";
			echo "<div id='ETcanvas_graph' class='col-xs-12 col-md-7 fontPlay'>";
				echo "<div class='row' style='margin:5px;'>";
					echo "	<div class='cardStyle col-xs-12'>
								<div class='col-xs-12'>
									<h1 class='fontPlay black'><span class='fa fa-desktop'></span> Estações de Trabalho</h1>
								</div>
								<div class='col-xs-12 col-sm-6'>
									<canvas id='ETChart' >
									</canvas>
								</div>
								<div class='col-xs-12 col-sm-6'>
									<div id='ETcanvas_info' class='md'>
									</div>
								</div>
							</div>";	
				echo "</div>";
				echo "<div class='row' style='margin:5px;'>";
					echo "	<div class='cardStyle col-xs-12'>
								<div class='col-xs-12'>
									<h1 class='fontPlay black'><span class='fa fa-hdd-o'></span> Servidores</h1>
								</div>
								<div class='col-xs-12 col-sm-6'>
									<canvas id='SVChart' >
									</canvas>
								</div>
								<div class='col-xs-12 col-sm-6'>
									<div id='SVcanvas_info' class='md'>
									</div>
								</div>
							</div>";
				echo "</div>";	
			echo "</div>";
			echo "<div id='Offcanvas_graph' class='col-xs-12 col-md-5 fontPlay'>";
				echo "<div class='row' style='margin:5px;'>";
					echo "	<div class='cardStyle col-xs-12'>	
								<div class='col-xs-12'>
									<h1 class='fontPlay black'><span class='fa fa-power-off'></span> Máquinas Inativas</h1>
								</div>
								<div class='col-xs-12'>
									<canvas id='OffChart' >
									</canvas>
								</div>
								<div class='col-xs-12'>
									<div id='Offcanvas_info' class='md'>
									</div>
								</div>
							</div>";
				echo"</div>";
			echo"</div>";
		echo "</div>";
		echo "	<div class='row'>";
			echo "	<div id='QTcanvas_graph' class='col-xs-12 fontPlay' style='margin:5px;'>
						<div class='cardStyle col-xs-12'>
							<div class='col-xs-12'>
								<h1 class='fontPlay black'><span class='fa fa-bar-chart'></span> Máquinas adicionadas nos últimos 10 dias</h1>
							</div>
							<div class='col-xs-12 col-md-8'>
								<canvas id='QTChart' >
								</canvas>
							</div>
							<div class='col-xs-12 col-md-4'>
								<div id='QTcanvas_info' class='md'>
								</div>
							</div>
						</div>
					</div>";
		echo "	</div>";
		//MODAL----------------------------------------
		echo "<div class='modal fade' id='report_modal' tabindex='-1' role='dialog' aria-labelledby='Report'>
				<div class='modal-dialog modal-lg' role='document'>
					<div class='modal-content' style='width:100%;'>
						<div class='modal-header navStyle'>
							<span class='fontPlay lg white' id='report-Header'>
								...Header Content...
							</span>
						</div>
						<div class='modal-body fontPlay md row' id='report-Body' style='padding-bottom:30px;'>
							<div align='center' style='margin-top:50px; opacity:0.7;'><img src='./images/load.png' class='spinner_fst' style='width:50px; height:50px;'></i><span class='sr-only'>Loading...</span></div>
						</div>
					</div>
				</div>
			</div>";
	
	//Área Administrativa-----------------------------------------------------------------------
	} elseif($target == "administrative" && $_SESSION['group'] == $_SESSION['ADMGP']){
		if(isset($_GET['DN'])){
			changeDescriptionAD($_GET['DN'], $_GET['value'], $_GET['dependence'], $_GET['identify']);			
		} elseif(isset($_GET['remove'])){
			removeDescriptionAD($_GET['remove'], $_GET['identify'], $_GET['dependence']);
		} elseif(isset($_GET['removeExc'])){
			$getDescription = $_GET['removeExc'];
			writeException($getDescription, "REMOVE");
		} elseif(isset($_GET['addExc'])){
			$getDescription = $_GET['addExc'];
			writeException($getDescription, "ADD");
		}
		if(isset($_GET['input'])){
			$codefile = "./base/MDDB/distribution.mddb";
			$hiddenfile = "./base/MDDB/hidden.mddb";
			$inputCodeFile = $_GET['input'];
			$inputCodeFile = base64_encode($inputCodeFile);
			$inputCodeFile = base64_encode($inputCodeFile);
			$writecodex = file_put_contents($codefile, $inputCodeFile);
			if(!file_exists($hiddenfile)){
				file_put_contents($hiddenfile, "");
			}
		}
		if(isset($_GET['towrite'])){
			$codefile = "./base/MDDB/NLI/" . $_GET['identify'] . ".mddb";
			if(file_exists($codefile)){
				$currentContent = file_get_contents($codefile);
				$inputCodeFile = $currentContent . ";" . $_GET['towrite'];
			} else {
				$inputCodeFile = ";" . $_GET['towrite'];
			}
			$writecodex = file_put_contents($codefile, $inputCodeFile);
		}
		if(isset($_GET['toerase'])){
			$codefile = "./base/MDDB/NLI/" . $_GET['identify'] . ".mddb";
			$currentContent = file_get_contents($codefile);
			$inputCodeFile = str_replace(";" . $_GET['toerase'], "", $currentContent);
			$writecodex = file_put_contents($codefile, $inputCodeFile);
		}
		$opt0 = "";	
		$opt0A = "";
		$opt1 = "";
		$optA = "";
		$opt2 = "";
		$optB = "";
		$opt3 = "";
		$optC = "";
		$opt4 = "";
		$optD = "";
		$opt5 = "";
		$optE = "";
		$opt6 = "";
		$optF = "";
		
		if(isset($_GET['option'])){
			$options = $_GET['option'];
			if($options == 2){
				$opt2 = "active";
				$optB = "in active";
			} elseif($options == 3){
				$opt3 = "active";
				$optC = "in active";
			} elseif($options == 4){
				$opt4 = "active";
				$optD = "in active";
			} elseif($options == 5){
				$opt5 = "active";
				$optE = "in active";
			} elseif($options == 6){
				$opt6 = "active";
				$optF = "in active";
			} elseif($options == 1) {
				$opt1 = "active";
				$optA = "in active";
			} else {
				$opt0 = "active";
				$opt0A = "in active";
			}
		} else {
			$opt0 = "active";
			$opt0A = "in active";
		}
		//Início de verificação: definição de campos e tratamento de dados definidos nas configurações da aplicação-----------------------
		$fileDist = "./base/MDDB/distribution.mddb";
		if(file_exists($fileDist)){
			$activeted = true;
			$distContent = file_get_contents($fileDist);
			$distContent = base64_decode(base64_decode($distContent));
			$_SESSION['distribution'] = $distContent;
			$splitContent = explode("|@|", $_SESSION['distribution']);
			$order = $splitContent[0];
			$orderVal = explode("<<", $order);
			$linked = $splitContent[1];
			$linkedVal = explode("<<", $linked);
		} else {
			$activeted = false;
		}
		$fileFields = "./base/MDDB/defaults.mddb";
		if(!file_exists($fileFields)){
			header("Location:logout.php");
		}
		$fields = file_get_contents($fileFields);
		$fields = base64_decode(base64_decode($fields));
		$_SESSION['fields'] = $fields;
		$field = explode("|@", $_SESSION['fields']);
		$countFields = count($field);
		//--------------------------------------------------------------------------------------------------------------------------------
		//Header-----------------------------------------
		echo "<div style='padding:0px; background-color:#fff;' class='row container cardStyle'>";
			echo "<ul class='nav nav-tabs md' role='tablist' style='background-color: rgba(150,150,150,0.1); border-radius:2px;'>
					<li role='presentation' class='" . $opt0 . "'><a href='#ADM_Distribuicao' aria-controls='ADM_Distribuicao' role='tab' data-toggle='tab'>Distribuição</a></li>";
					for($i = 1; $i < $countFields; $i++){
						$fieldData = explode("#", $field[$i]);
						if($options == $i){
							$selected = "active";
						} else {
							$selected = "";
						}
						if($activeted){
							$liSort = explode("||", $orderVal[$i]);
							echo "<li role='presentation' class='" . $selected . "'><a href='#ADM_" . $liSort[1] . "' aria-controls='ADM_" . $liSort[1] . "' role='tab' data-toggle='tab'>" . $liSort[1] . "</a></li>";
						}
					}
			echo "	<li role='presentation' class='" . $opt6 . "'><a href='#ADM_excecoes' aria-controls='excecoes' role='tab' data-toggle='tab'>Exceções</a></li>
				</ul>";
		
			//---------------------------------------------------------
			//Configuração de Distribuição Lógica
			//---------------------------------------------------------
			echo "<div class='tab-content fontPlay black' style='padding:5px; border-radius:2px;'>";
				echo "<div role='tabpanel' class='tab-pane fade " . $opt0A . "' id='ADM_Distribuicao'>";
						echo "<div class='row'>	";
							echo "<div class='col-sm-12 col-md-6'>";
								echo "<h2 class='fontPlay blue container'><span class='fa fa-cubes'></span> Distribuição Lógica de Campos </h2>";
							echo "</div>";
							echo "<div class='col-sm-12 col-md-6' align='right'>";
								if($activeted){
									echo "<span class='btn btn-success btn-sm bggreen' onclick='dataBackup(\"BKP\", \"" . $database . "\")'><span class='fa fa-save'></span> <b>Fazer Backup de Configuração</b></span> ";
								}
								echo "<span class='btn btn-info btn-sm' data-toggle='modal' data-target='#bkp_modal'><span class='fa fa-download'></span> <b>Carregar Configuração</b></span>";
							echo "</div>";
						echo "</div>";
						
						echo "<div class='modal fade' id='bkp_modal' tabindex='-1' role='dialog' aria-labelledby='Backup'>
								<div class='modal-dialog modal-lg' role='document'>
									<div class='modal-content' style='width:100%;'>
										<div class='modal-header navStyle'>
											<span class='fontPlay lg white' id='bkp-Header'>
												Selecione o arquivo de backup
											</span>
										</div>
										<div class='modal-body fontPlay md row' id='bkp-Body'>
											<div class='container-fluid'>
												<form action='getpath.php' method='post' enctype='multipart/form-data'>
													<div class='fileUpload btn btn-success btn-sm' for='BKPFile'>
														<span for='BKPFile'><span class='fa fa-file-o'></span> Arquivo</span>
														<input type='file' name='BKPFile' id='BKPFile' class='upload' accept='.bkp' onchange='selectFile(\"BKPFile\", \"uploadFile\")' />
													</div>
													<input id='uploadFile' size='30' style='background-color:white; border-bottom:1px solid #0cf;' placeholder='Arquivo de backup' disabled='disabled' />
													<div style='width:100%;' align='right'>
														<input type='submit' class='btn btn-info' value='Carregar Configurações' />
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>";
						
						echo "<div class='container row'>";
							echo "<div class='col-xs-12'>
									<br /><p class='md'>Defina a <span class='blue'>Dempendência</span> e o <span class='blue'>Vínculo ao <i>Active Directory</i> (AD)</span> e sua estrutura de OUs nos formulários abaixo.</p>
									<br /><label class='blue md'>1. Seleção de Ordem e Precedência:</label><br />
									<div class='black fontPlay' style='background-color:rgba(80,80,80,0.2); padding:20px; border-radius:2px;'>
										Selecione a ordem de execução dos <b>atributos</b> a partir dos campos definidos para a nomenclatura. Isso irá definir a <b>dependência de um campo a outro</b>.
										<br />
										<br />
										<span style='opacity: 0.8;'>
											<span style='text-align:justify; text-justify:inter-word;'><b>Ex:</b> <span class='btn btn-info btn-xs'><span class='fa fa-certificate yellow'></span> 1º Campo = Ativo</span> <b class='btn btn-success btn-xs'><span class='fa fa-arrow-left'></span> 2º Campo = UF</b> <b class='btn btn-danger btn-xs'><span class='fa fa-arrow-left'></span> 3º Campo = Site</b> <b class='btn btn-warning btn-xs'><span class='fa fa-arrow-left'></span> 4º Campo = Area</b> <b class='btn btn-default btn-xs'><span class='fa fa-arrow-left'></span> 5º Campo = Setor</b></span>
											<br />
											<br />
											<span class='fa fa-arrow-right xs'></span> Neste caso o 1º Atributo é o campo Ativo e representa a raiz.
											<br />
											<span class='fa fa-arrow-right xs'></span> O 2º Atributo será a UF que deverá estar associada ao Atributo anterior, no caso o Ativo.
											<br />
											<span class='fa fa-arrow-right xs'></span> O mesmo acontece com os atributos subsequêntes.
											<br />
											<span class='fa fa-arrow-right xs'></span> Esta relação de associação cria a dependência entre os campos, definindo uma ordem lógica para elaboração do nome e o caminho para alocação do ativo.
											<br />
											<span class='fa fa-arrow-right xs'></span> Como resultado, uma ordenação de nomenclatura possível seria <b class='blue'>PA</b><b class='green'>DF</b><b class='red'>05</b><b class='yellow'>X</b><b class='black'>01</b> por exemplo.
										</span>
										<br />
										<br />
										De preferência efetue a ordenação de atributos o mais próximo possível da estrutura de OUs do <i>Active Directory</i> ou adeque a mesma para os padrões definidos aqui.
									</div><br />";
									
									for($i = 1; $i < $countFields; $i++){
										$fieldData = explode("#", $field[$i]);
										echo "<div class='col-xs-3 col-md-2' style='margin-top:10px;'>";
											echo "<div class='blue' align='center'><b>";
											if($i != 1){
												echo "<span class='fa fa-arrow-left'></span> &nbsp; ";
											} else {
												echo "<span class='fa fa-certificate yellow'></span> ";
											}
											echo $i . "º Atributo</b></div>";
											echo "<select class='form-control selectedField' name='select_" . $i . "' id='select_" . $i . "' onchange='changeInput(\"select_" . $i . "\")' onfocus='inputArt(\"select_" . $i . "_slider\", \"select_" . $i . "\")' onblur='inputArtFade(\"select_" . $i . "_slider\")'>";
												echo "<option value='' selected>-</option>";
												for($j = 1; $j < $countFields; $j++){
													$fieldDataEx = explode("#", $field[$j]);
													if($activeted){
														$elemval = explode("||", $orderVal[$j]);
														if(intval($elemval[0]) == $i){
															echo "<option value='" . $i . "||" . $elemval[1] . "||" . $elemval[2] . "' selected >" . $elemval[1] . "</option>";
														} else {
															echo "<option value='" . $i . "||" . $elemval[1] . "||" . $elemval[2] . "' >" . $elemval[1] . "</option>";
														}
													} else {
														if($fieldDataEx[0] == $fieldData[0]){
															echo "<option value='" . $i . "||" . $fieldDataEx[0] . "||" . $fieldDataEx[1] . "' selected>" . $fieldDataEx[0] . "</option>";
														} else {
															echo "<option value='" . $i . "||" . $fieldDataEx[0] . "||" . $fieldDataEx[1] . "' >" . $fieldDataEx[0] . "</option>";
														}
													}
												}	
											echo "</select>";
											echo "<span class='slider' id='select_" . $i . "_slider' style='left:20px;'></span>";
										echo "</div>";
									}
							echo "</div>";
							echo "<br />";
							echo "<div class='col-xs-12' style='padding-bottom:30px;'>
									<br /><label class='blue md'>2. Definição de Vinculação ao AD:</label><br />
									<div class='black fontPlay' style='background-color:rgba(80,80,80,0.2); padding:20px; border-radius:2px;'>
										Marque os campos que serão vínculados diretamente ao <i>Active directory</i>.
										<br />
										Cada objeto pertencente a este campo deverá ser vinculado a uma OU no momento de sua criação.
										<br />
										<br />
										<span class='red'><span class='fa fa-exclamation-triangle'></span> <b>Atenção:</b> Ao menos um campo deve estar vinculado à estrutura de OUs do AD. Por este motivo o último atributo sempre será vinculado à estrutura de OUs. Isto deve ocorrer para determinar o caminho onde serão alocados os ativos registrados pelo sistema.</span>
									</div><br />";
									for($i = 1; $i < $countFields; $i++){
										$fieldData = explode("#", $field[$i]);
										if($activeted){
											$lnkval = explode("||", $orderVal[$i]);
											if($i == ($countFields-1)){
												echo "<div class='col-xs-6 col-sm-4 col-md-2' style='margin-top:10px;'>";
													echo "<div class='black' align='center'><b><span class='fa fa-cube'></span> " . $lnkval[1] . " </b></div>";
													echo "<div align='center' class='cardHover' id='div_" . $lnkval[1] . "'><span id='" . $lnkval[1] . "' data-value='" . $lnkval[1] . "' ><span class='fa fa-check-circle black'></span> Vinculado</span></div>";
													echo "<input type='hidden' readonly value='" . $lnkval[1] . "' class='asignInput' id='asign_" . $lnkval[1] . "_Input' />";
												echo "</div>";
											} elseif($linkedVal[$i] == ""){
												echo "<div class='col-xs-6 col-sm-4 col-md-2' style='margin-top:10px;'>";
													echo "<div class='blue' align='center'><b><span class='fa fa-cube'></span> " . $lnkval[1] . " </b></div>";
													echo "<div align='center' class='cardHover' id='div_" . $lnkval[1] . "'><a id='" . $lnkval[1] . "' class='pointer' data-value='" . $lnkval[1] . "' onclick='asign(\"" . $lnkval[1] . "\")'><span class='fa fa-times-circle red'></span> Não Vinculado</a></div>";
													echo "<input type='hidden' readonly value='' class='asignInput' id='asign_" . $lnkval[1] . "_Input' />";
												echo "</div>";
											} else {
												echo "<div class='col-xs-6 col-sm-4 col-md-2' style='margin-top:10px;'>";
													echo "<div class='blue' align='center'><b><span class='fa fa-cube'></span> " . $lnkval[1] . " </b></div>";
													echo "<div align='center' class='cardHover' id='div_" . $lnkval[1] . "'><a id='" . $lnkval[1] . "' class='pointer' data-value='" . $lnkval[1] . "' onclick='unsign(\"" . $lnkval[1] . "\")'><span class='fa fa-check-circle green'></span> Vinculado</a></div>";
													echo "<input type='hidden' readonly value='" . $lnkval[1] . "' class='asignInput' id='asign_" . $lnkval[1] . "_Input' />";
												echo "</div>";
											}
														
										} else {
											if ($i == ($countFields-1)){
												echo "<div class='col-xs-6 col-sm-4 col-md-2' style='margin-top:10px;'>";
													echo "<div class='black' align='center'><b><span class='fa fa-cube'></span> " . $fieldData[0] . " </b></div>";
													echo "<div align='center' class='cardHover' id='div_" . $fieldData[0] . "'><span id='" . $fieldData[0] . "' data-value='" . $fieldData[0] . "' ><span class='fa fa-check-circle black'></span> Vinculado</span></div>";
													echo "<input type='hidden' readonly value='" . $fieldData[0] . "' class='asignInput' id='asign_" . $fieldData[0] . "_Input' />";
												echo "</div>";
											} elseif ($i == 1){
												echo "<div class='col-xs-6 col-sm-4 col-md-2' style='margin-top:10px;'>";
													echo "<div class='blue' align='center'><b><span class='fa fa-cube'></span> " . $fieldData[0] . " </b></div>";
													echo "<div align='center' class='cardHover' id='div_" . $fieldData[0] . "'><a id='" . $fieldData[0] . "' class='pointer' data-value='" . $fieldData[0] . "' onclick='asign(\"" . $fieldData[0] . "\")'><span class='fa fa-times-circle red'></span> Não Vinculado</a></div>";
													echo "<input type='hidden' readonly value='' class='asignInput' id='asign_" . $fieldData[0] . "_Input' />";
												echo "</div>";
											} else {
												echo "<div class='col-xs-6 col-sm-4 col-md-2' style='margin-top:10px;'>";
													echo "<div class='blue' align='center'><b><span class='fa fa-cube'></span> " . $fieldData[0] . " </b></div>";
													echo "<div align='center' class='cardHover' id='div_" . $fieldData[0] . "'><a id='" . $fieldData[0] . "' class='pointer' data-value='" . $fieldData[0] . "' onclick='unsign(\"" . $fieldData[0] . "\")'><span class='fa fa-check-circle green'></span> Vinculado</a></div>";
													echo "<input type='hidden' readonly value='" . $fieldData[0] . "' class='asignInput' id='asign_" . $fieldData[0] . "_Input' />";
												echo "</div>";
											}
										} 
									}
								echo "<div class='col-xs-12 top10' align='center'>";
									echo "<hr />";
									if($activeted){
										echo "<span class='btn btn-info' onclick='startDistribution(\"alterar\")'><span class='fa fa-edit'></span> Alterar Distribuição Lógica</span>";
									} else {
										echo "<span class='btn btn-info' onclick='startDistribution(\"aplicar\")'><span class='fa fa-check'></span> Aplicar Distribuição Lógica</span>";
									}
								echo "</div>";
							echo "</div>";
								
								
							
						echo "</div>";
				echo "</div>";
				
				//---------------------------------------------------------
				//Gerenciamento de Itens Configuráveis. 1~5 Itens
				//---------------------------------------------------------
				//Depende da configuração de distriuição MDDB ($fileDist)-------------------
				for($i = 1; $i < $countFields; $i++){
					$fieldData = explode("#", $field[$i]);
					if($options == $i){
						$selected = "in active";
					} else {
						$selected = "";
					}
					switch($i){
						case 1:
							$divider = "cardStyleDBlue";
							$buttomType = "bgblue";
							$color = "blue";
							break;
						case 2:
							$divider = "cardStyleGreen";
							$buttomType = "bggreen";
							$color = "green";
							break;
						case 3:
							$divider = "cardStyleYellow";
							$buttomType = "bgyellow";
							$color = "yellow";
							break;
						case 4:
							$divider = "cardStyleLBlue";
							$buttomType = "bglblue";
							$color = "lblue";
							break;
						case 5:
							$divider = "cardStyleOrange";
							$buttomType = "bgorange";
							$color = "orange";
							break;
						default:
							$divider = "cardStyleBlack";
							$buttomType = "bgblack";
							$color = "black";
							break;
					}
					echo "<div role='tabpanel' class='tab-pane fade " . $selected . "' id='ADM_" . $fieldData[0] . "'>
							<h2 class='fontPlay blue container'><span class='fa fa-cubes'></span> Configuração de " . $fieldData[0] . " </h2>";
							echo "<div class='container row'>";
								$general = explode("||", $orderVal[$i]);
								$sector = $general[1];
								$length = $general[2];
								if($i > 1){
									$islinked = false;
									if($linkedVal[$i-1] != ""){
										$islinked = true;
									}
								} else {
									$islinked = false;
								}
								if($linkedVal[$i] == $fieldData[0]){
									//Vinculados ao AD----------------------------------
									$searchItem = "*/" . $sector;
									$adfile = getOUDescriptionAD($searchItem, $database);
									$countItens = count($adfile);
									$itens = "";
									echo "<div class='container row'>
											<div class='col-xs-12 col-sm-12 col-md-5'>
												<label>Adicionar Novo(a) " . $sector . ":<br /></label>
												<br />
												<span class='black'>Selecione o(a) " . $sector . " correspondente no <i>Active Directory</i>.</span>
												<br />
												<div class='row'>
													<div class='col-xs-2 col-md-1' align='right'>
														<span class='fa fa-cube " . $color . " lg top10'></span>
													</div>
													<div class='col-xs-10 col-md-11' align='left' style=''>
														<button type='button' class='btn btn-default " . $buttomType . " btn-sm' style='border: 0px;' data-toggle='modal' data-target='#ADDive' data-opt='" . $sector . "'><span class='fa fa-sitemap'></span> Informe o(a) " . $sector . "</button>
														<input type='text' readonly class='" . $i . "_TAB' name='name_" . $sector . "' id='name_" . $sector . "' placeholder='DN do(a) " . $sector . "' size='45' />
													</div>
												</div>
												<br />
												
												<label>Informe o código de nomenclatura para este(a) novo(a) " . $sector . ":<br /></label>
												<br />
												<span class='black'>Este valor deve obedecer a quantidade máxima de dígitos definidos para este campo.</span>
												<br />
												<span class='red'><i>O valor deve possuir no máximo <b>" . $length . " dígito(s)</b>.</i></span>
												<br />
												<div class='row'>
													<div class='col-xs-2 col-md-1' align='right'>
														<span class='fa fa-terminal " . $color . " lg top10'></span>
													</div>
													<div class='col-xs-10 col-md-11' align='left' style=''>
														<input type='text' class='" . $i . "_TAB' name='value_" . $sector . "' id='value_" . $sector . "' placeholder='--' onfocus='inputArt(\"value_" . $sector . "_slider\", \"value_" . $sector . "\")' onblur='inputArtFade(\"value_" . $sector . "_slider\")' size='" . $length . "' maxlength='" . $length . "' />
														<span class='slider' id='value_" . $sector . "_slider'></span>
													</div>
												</div>
												<br />";
												if($i > 1){
													$strDep = explode("||", $orderVal[$i-1]);
													$dependence = $strDep[1];
													$depFile = "./base/MDDB/NLI/" . $dependence . ".mddb";
													echo "<label>Informe a qual " . $dependence . " este(a) " . $sector . " está vinculado(a):<br /></label>
															<br />
															<div class='row'>
																<div class='col-xs-12' style='left:-10px;'>
																	<select class='form-control " . $i . "_TAB' name='dependence_" . $sector . "' id='dependence_" . $sector . "' onfocus='inputArt(\"dependence_" . $sector . "_slider\", \"dependence_" . $sector . "\")' onblur='inputArtFade(\"dependence_" . $sector . "_slider\")'>
																		<option value='' selected>Selecione</option>";
																		if(file_exists($depFile) && !$islinked){
																			$depFile = file_get_contents($depFile);
																			$depFile = utf8_decode($depFile);
																			$depContent = explode(";", $depFile);
																			for($k = 0; $k < count($depContent); $k++){
																				if($depContent[$k] != ""){
																					$depValues = explode("||", $depContent[$k]);
																					$depName = utf8_encode($depValues[0]);
																					$depValue = $depValues[1];
																					echo "<option value='" . $depValue . "@" . $depName . "'>" . $depName . " (" . $depValue . ")</option>";
																				}
																			}
																		} else {
																			$adDep = getOUDescriptionAD("*/" . $dependence, $database);
																			for($l = 0; $l < count($adDep); $l++){
																				$adsector = explode("|", $adDep[$l]);
																				$adDepObj = explode("/", $adsector[2]);
																				echo "<option value='" . $adDepObj[1] . "@" . $adsector[0] . "'>" . $adsector[0] . " (" . $adDepObj[1] . ")</option>";
																			}
																		}
																echo "</select>
																	<span class='slider' id='dependence_" . $sector . "_slider' style='left:20px;'></span>
																</div>
															</div>";
												}
											echo "<hr />
												<div class='row'>
													<div align='center'>
														<span class='btn btn-default btn-lg " . $buttomType . " fontPlay' style='border: 0px;' onclick='administrationExec(\"" . $i . "\", \"" . $sector . "\", 1, \"0\", \"" . $database . "\", \"SD\")'> <span class='fa fa-plus'></span> Adicionar " . $sector . " </span>
													</div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-12 col-md-7'>
												<h3 class='fontPlay'>Lista de itens configurados como " . $sector . ":</h3>";
												if(count($adfile) > 0){
													echo "<div class='" . $divider . "'>";
														echo "<div class='row'>";
															for($j = 0; $j < count($adfile); $j++){
																if($adfile[$j] != ""){
																	$adobj = explode("|", $adfile[$j]);
																	$objDep = explode("/", $adobj[2]);
																	$dependences = explode(";", $objDep[0]);
																	foreach($dependences as $objDepItem){
																		if($objDepItem != ""){
																			$objDepItem = utf8_decode($objDepItem);
																			$objDepName = utf8_encode(getName($objDepItem));
																			$objDepItem = getValue($objDepItem);
																			echo "<div class='col-xs-12 col-md-6 blueHover md'>";
																				echo "<a href='#' id='rmvExc_" . $sector . $j . "' onclick='administrationExec(\"" . $i . "\", \"" . $sector . "\", 0, \"" . utf8_encode($adobj[0]) . "||" . $objDep[1] . "\", \"" . $database . "\", \"" . $objDepItem . "\")' onmouseover='popTip(\"rmvExc_" . $sector . $j . "\");' onmouseout='fadeOut0(\"tooltip-ex\")' data-msg='Remover " . $sector . "'><i class='fa fa-times red lg'></i></a>";
																					if($i > 1){
																						$sectorDeps = explode("||", $orderVal[$i-1]);
																						$sectorDep = $sectorDeps[1];
																						echo " <span id='obj_" . $sector . $j . "' onmouseover='popTip(\"obj_" . $sector . $j . "\");' onmouseout='fadeOut0(\"tooltip-ex\")' data-msg='Vinculo: " . $objDepName . "<br />" . $sectorDep . " - " . $objDepItem  . "<br />Valor: " . $objDep[1] . "'>" . utf8_encode($adobj[0]) . "</span>";
																					} else {
																						echo " <span id='obj_" . $sector . $j . "' onmouseover='popTip(\"obj_" . $sector . $j . "\");' onmouseout='fadeOut0(\"tooltip-ex\")' data-msg='Valor: " . $objDep[1] . "'>" . utf8_encode($adobj[0]) . "</span>";
																					}
																			echo "</div> ";
																		}
																	}
																}
															}
														echo "</div>";
													echo "</div>";
												} else {
													echo "<div class='" . $divider . "'>";
														echo "<span class='red lg'>Não há registro de " . $sector . " no sistema!</span>";
													echo "</div>";
												}
											echo "</div>";
										echo "</div>";
																		
								} else {
									//Não Vinculados ao AD----------------------------------
									$nlifile = "./base/MDDB/NLI/" . $sector . ".mddb";
									if(file_exists($nlifile)){
										$nliString = file_get_contents($nlifile);
									} else {
										$nliString = "";
									}
									$nliList = explode(";", $nliString);
									$countItens = count($nliList);
									$itens = "";
									echo "<div class='container row'>
											<div class='col-xs-12 col-sm-12 col-md-5'>
												<label>Adicionar Novo(a) " . $sector . ":<br /></label>
												<br />
												<span class='black'>Informe o nome do " . $sector . " que deseja cadastrar.</span>
												<br />
												<div class='row'>
													<div class='col-xs-2 col-md-1' align='right'>
														<span class='fa fa-cube " . $color . " lg top10'></span>
													</div>
													<div class='col-xs-10 col-md-11' align='left' style=''>
														<input type='text' class='" . $i . "_TAB' name='name_" . $sector . "' id='name_" . $sector . "' placeholder='Ex: " . $sector . " 01' onfocus='inputArt(\"name_" . $sector . "_slider\", \"name_" . $sector . "\")' onblur='inputArtFade(\"name_" . $sector . "_slider\")' size='45' autofocus />
														<span class='slider' id='name_" . $sector . "_slider'></span>
													</div>
												</div>
												<br />
												
												<label>Informe o código de nomenclatura para este(a) novo(a) " . $sector . ":<br /></label>
												<br />
												<span class='black'>Este valor deve obedecer a quantidade máxima de dígitos definidos para este campo.</span>
												<br />
												<span class='red'><i>O valor deve possuir no máximo <b>" . $length . " dígito(s)</b>.</i></span>
												<br />
												<div class='row'>
													<div class='col-xs-2 col-md-1' align='right'>
														<span class='fa fa-terminal " . $color . " lg top10'></span>
													</div>
													<div class='col-xs-10 col-md-11' align='left' style=''>
														<input type='text' class='" . $i . "_TAB' name='value_" . $sector . "' id='value_" . $sector . "' placeholder='--' onfocus='inputArt(\"value_" . $sector . "_slider\", \"value_" . $sector . "\")' onblur='inputArtFade(\"value_" . $sector . "_slider\")' size='" . $length . "' maxlength='" . $length . "' />
														<span class='slider' id='value_" . $sector . "_slider'></span>
													</div>
												</div>
												<br />";
												if($i > 1){
													$strDep = explode("||", $orderVal[$i-1]);
													$dependence = $strDep[1];
													$depFile = "./base/MDDB/NLI/" . $dependence . ".mddb";

													echo "<label>Informe a qual " . $dependence . " este(a) " . $sector . " está vinculado(a):<br /></label>
															<br />
															<div class='row'>
																<div class='col-xs-12' style='left:-10px;'>
																	<select class='form-control " . $i . "_TAB' name='dependence_" . $sector . "' id='dependence_" . $sector . "' onfocus='inputArt(\"dependence_" . $sector . "_slider\", \"dependence_" . $sector . "\")' onblur='inputArtFade(\"dependence_" . $sector . "_slider\")'>
																		<option value='' selected>Selecione</option>";
																																				
																		if(file_exists($depFile) && !$islinked){
																			$depFile = file_get_contents($depFile);
																			$depContent = explode(";", $depFile);
																			for($k = 0; $k < count($depContent); $k++){
																				if($depContent[$k] != ""){
																					$depValues = explode("||", $depContent[$k]);
																					$depName = utf8_encode($depValues[0]);
																					$depValue = $depValues[1];
																					echo "<option value='" . $depContent[$k] . "@" . $depName . "'>" . $depName . " (" . $depContent[$k] . ")</option>";
																				}
																			}
																		} else {
																			$adDep = getOUDescriptionAD("*/" . $dependence, $database);
																			for($l = 0; $l < count($adDep); $l++){
																				$adsector = explode("|", $adDep[$l]);
																				$adDepObj = explode("/", $adsector[2]);
																				echo "<option value='" . $adDepObj[1] . "@" . $adsector[0] . "'>" . $adsector[0] . " (" . $adDepObj[1] . ")</option>";
																			}
																		}
																echo "</select>
																	<span class='slider' id='dependence_" . $sector . "_slider' style='left:20px;'></span>
																</div>
															</div>";
												}
											if(!isset($dependence)){
												$dependence = "**";
											}
											echo "<hr />
												<div class='row'>
													<div align='center'>
														<span class='btn btn-default btn-lg " . $buttomType . " fontPlay' style='border: 0px;' onclick='administrationExecNotAD(" . $i . ", \"" . $sector . "\", \"1\", \"" . $dependence . "\", \"" . $database . "\")'> <span class='fa fa-plus'></span> Adicionar " . $sector . " </span>
													</div>
												</div>
											</div>
											<div class='col-xs-12 col-sm-12 col-md-7'>
												<h3 class='fontPlay'>Lista de itens configurados como " . $sector . ":</h3>";
												if($nliString != ""){
													echo "<div class='" . $divider . "'>";
														echo "<div class='row'>";
															for($j = 0; $j < count($nliList); $j++){
																if($nliList[$j] != ""){
																	$nliList[$j]= utf8_decode($nliList[$j]);
																	$nliobj = explode("||", $nliList[$j]);
																	$objDepItem = utf8_decode($nliobj[3]);
																	$objDepName = utf8_encode(getName($objDepItem));
																	$objDepItem = getValue($objDepItem);
																	echo "<div class='col-xs-12 col-md-6 blueHover md'>";
																			
																	if($i > 1){
																		$sectorDeps = explode("||", $orderVal[$i-1]);
																		$sectorDep = $sectorDeps[1];
																		echo "<a href='#' id='rmvExc_" . $sector . $j . "' onclick='administrationExecNotAD(" . $i . ", \"" . $sector . "\", \"0\", \"" . utf8_encode($nliobj[0]) . "||" . $nliobj[1] . "||" . $dependence . "||" . $nliobj[3] . "\", \"" . $database . "\")' onmouseover='popTip(\"rmvExc_" . $sector . $j . "\");' onmouseout='fadeOut0(\"tooltip-ex\")' data-msg='Remover " . $sector . "'><i class='fa fa-times red lg'></i></a>";
																		echo " <span id='obj_" . $sector . $j . "' onmouseover='popTip(\"obj_" . $sector . $j . "\");' onmouseout='fadeOut0(\"tooltip-ex\")' data-msg='Vinculo: " . $objDepName . "<br />" . $dependence . " - " . $objDepItem . "<br />Valor: " . $nliobj[1] . "'>" . utf8_encode($nliobj[0]) . "</span>";
																	} else {
																		echo "<a href='#' id='rmvExc_" . $sector . $j . "' onclick='administrationExecNotAD(" . $i . ", \"" . $sector . "\", \"0\", \"" . utf8_encode($nliobj[0]) . "||" . $nliobj[1] . "||**||**\", \"" . $database . "\")' onmouseover='popTip(\"rmvExc_" . $sector . $j . "\");' onmouseout='fadeOut0(\"tooltip-ex\")' data-msg='Remover " . $sector . "'><i class='fa fa-times red lg'></i></a>";
																		echo " <span id='obj_" . $sector . $j . "' onmouseover='popTip(\"obj_" . $sector . $j . "\");' onmouseout='fadeOut0(\"tooltip-ex\")' data-msg='Valor: " . $nliobj[1] . "'>" . utf8_encode($nliobj[0]) . "</span>";
																	}
																	echo "</div>";
																}
															}
														echo "</div>";
													echo "</div>";
												} else {
													echo "<div class='" . $divider . "'>";
														echo "<span class='red lg'>Não há registro de " . $sector . " no sistema!</span>";
													echo "</div>";
												}
											echo "</div>";
										echo "</div>";
								}

							echo "</div>";
					echo "</div>";
				}
				//---------------------------------------------------------
				//Gerenciamento de Exceções
				//---------------------------------------------------------
				echo "<div role='tabpanel' class='tab-pane fade " . $optF . "' id='ADM_excecoes'>
						<h2 class='fontPlay red container'><span class='fa fa-calendar-times-o red'></span> Gerenciamento de Exceções </h2>";
						$hiddenfile = "./base/MDDB/hidden.mddb";
						if(file_exists($hiddenfile)){
							$hiddenString = file_get_contents($hiddenfile);
							$hiddenList = explode(";", $hiddenString);
							$countItens = count($hiddenList);
						} else {
							$countItens = 0;
						}
						$itens = "";
						echo "<div class='container row'>
								<div class='col-xs-12 col-sm-12 col-md-5'>
									<label>Adicionar Novas Exceções:<br /><span class='black'>(Utilize \";\" para inserir mais de uma exceção)</span></label><br />
									<div class='row'>
										<div class='col-xs-2 col-md-1' align='center'>
											<span class='fa fa-calendar-times-o red lg top10'></span>
										</div>
										<div class='col-xs-10 col-md-11' align='left' style=''>
											<input type='text' name='excecao' id='excecao' placeholder='EX: Grupos;Usuários;Outros' onfocus='inputArt(\"excecao_slider\", \"excecao\")' onblur='inputArtFade(\"excecao_slider\")' size='45' autofocus />
											<span class='slider' id='excecao_slider'></span>
										</div>
									</div>
									<hr />
									<div align='center'>";
										if($countItens > 0){
											echo "<span class='btn btn-danger btn-lg fontPlay' onclick='administrationExec(\"6\", \"Exceção\", 2, \"0\", \"" . $database . "\", \"SD\")'> <span class='fa fa-plus'></span> Adicionar Exceções</span>";
										} else {
											echo "<span class='btn btn-default btn-lg fontPlay' disabled><span class='fa fa-plus'></span> Adicionar Exceções</span>";
										}
								echo "</div>
								</div>
								<div class='col-xs-12 col-sm-12 col-md-7'>
									<h3 class='fontPlay'>Lista de nomes de OUs que não devem ser verificadas pelo sistema:</h3>
									<div class='cardStyleRed'>
										<div class='row'>";
											if($countItens > 0 && strlen($hiddenString) > 0){
												for($i = 0; $i < $countItens; $i++){
													if($hiddenList[$i] != ""){
														echo "<div class='col-xs-12 col-md-6 blueHover md'><a href='#' id='rmvExc_" . $i . "' onclick='administrationExec(\"6\", \"Exceção\", 3, \"" . $hiddenList[$i] . "\", \"" . $database . "\", \"SD\")' onmouseover='popTip(\"rmvExc_" . $i . "\");' onmouseout='fadeOut0(\"tooltip-ex\")' data-msg='Remover Exceção'><i class='fa fa-times red lg'></i></a> " . $hiddenList[$i] . "</div> ";																												
													}
												}
											} else{
												echo "<div class='fontPlay col-xs-12 lg'>";
													echo "<p><span class='fa fa-times red lg'></span> <b style='opacity: 0.5'> Não há registros para este objeto!</b></p>";
												echo "</div>";
											}
											
										echo "</div>
									</div>
								</div>
							</div>";
							
				echo "</div>";
			echo "</div>";		
			//-----------------------------------------------------------------------------------------------------
		echo "</div>";
	
	//Etapa 2-------------------------------------------------------------------------------------------------------------------------
	} elseif($target == "makeReservation") {
		$conclude = true;
		$writeFile = "reservations/" . base64_decode($_SESSION['matricula']) . ".log";
		if(file_exists($writeFile)){
			$current = file_get_contents($writeFile);
			$split = explode("#", $current);
			foreach($split as $hosts){
				$hostname = explode("|", $hosts, 2);
				if($hostname[0] == $_GET['host']){
					$conclude = false;
				}
			}
		}
		if($conclude){
			$txt = "#" . $_GET['host'] . "|" . $_GET['description'] . "|" . $_GET['path'];
			$write = file_put_contents($writeFile, $txt, FILE_APPEND);
			if($write){
				echo "<div id='Area' class='container row'>";
					echo "<div aling='center'>";
						echo "<div class='row nmtMenu cardStyle' align='center'>";		
							echo "<h1 class='fontPlay blue'>
										<span class='fa fa-check fa-2x green'></span><br />
										Reserva efetuada com sucesso!
									</h1>";
						echo "</div>";	
					echo "</div>";
				echo "</div>";
			} else {
				echo "<div id='Area' class='container row'>";
					echo "<div aling='center'>";
						echo "<div class='row nmtMenu cardStyle' align='center'>";		
							echo "<h1 class='fontPlay red'>
										<span class='fa fa-times fa-2x red'></span><br />
										Não foi possível efetuar a reserva!
									</h1>";
						echo "</div>";	
					echo "</div>";
				echo "</div>";
			}
		} else {
			echo "<div id='Area' class='container row'>";
				echo "<div aling='center'>";
					echo "<div class='row nmtMenu cardStyle' align='center'>";		
						echo "<h1 class='fontPlay red'>
									<span class='fa fa-times fa-2x red'></span><br />
									Já existe uma reserva para este nome de máquina em seu nome!
								</h1>";
					echo "</div>";	
				echo "</div>";
			echo "</div>";
		}
		
	} elseif($target == "eraseReservation") {
		$writeFile = "reservations/" . base64_decode($_SESSION['matricula']) . ".log";
		$txt = "#" . $_GET['host'] . "|" . $_GET['description'] . "|" . $_GET['path'];
		
		if(file_exists($writeFile)){
			$current = file_get_contents($writeFile);
			$current = str_replace($txt, "", $current);
			$write = file_put_contents($writeFile, $current);
		}
		if($write || $current == ""){
			echo "<div id='Area' class='container row'>";
				echo "<div aling='center'>";
					echo "<div class='row nmtMenu cardStyle' align='center'>";		
						echo "<h1 class='fontPlay blue'>
									<span class='fa fa-check fa-2x green'></span><br />
									Reserva excluída com sucesso!
								</h1>";
					echo "</div>";	
				echo "</div>";
			echo "</div>";
		} else {
			echo "<div id='Area' class='container row'>";
				echo "<div aling='center'>";
					echo "<div class='row nmtMenu cardStyle' align='center'>";		
						echo "<h1 class='fontPlay red'>
									<span class='fa fa-times fa-2x red'></span><br />
									Não foi possível excluir a reserva!
								</h1>";
					echo "</div>";	
				echo "</div>";
			echo "</div>";
		}
	
	//Etapa 6-------------------------------------------------------------------------------------------------------------------------	
	} elseif($target == "execRename") {
		$msgbox = "<div id='Area' class='container row'>
					<div aling='center'>
						<div class='row cardStyleGreen' align='center'>
							<h1 class='fontPlay blue'>
								<span class='fa fa-check fa-2x green'></span><br />
								Processo de Renomear máquina em andamento. Por favor aguarde a conclusão do processo!
							</h1>
						</div>
					</div>
				</div>";
		echo $msgbox;
		$execopt = "RENAME";

		$codexFile = "./scripts/PowerShell/TEMP/" . base64_decode($_SESSION['howI']) . ".log";
		$codexTxt = $_SESSION['toDomain'] . "\r\n" .
					"-\r\n" . 
					"-\r\n" . 
					$_SESSION['DC'] . "\r\n" . 
					$_SESSION['newName'] . "\r\n" . 
					$_SESSION['alocacao'] . "\r\n" . 
					$execopt . "\r\n" . 
					$_SESSION['descricao'] . $extraValue . "\r\n" . 
					$_SESSION['atributes'] . "\r\n" .
					$_SESSION['hostname'];
		$writecodex = file_put_contents($codexFile, $codexTxt);
				
		$cmd = "powershell.exe -ExecutionPolicy ByPass -file \"C:\\xampp\\htdocs\\NameIt\\scripts\\PowerShell\\JoinDomain.ps1\" -User \"" . base64_decode($_SESSION['howI']) . "\" -Password \"" . base64_decode(base64_decode($_SESSION['howK'])) . "\"";
		//echo $cmd;
		//$_SESSION['result'] = exec($cmd);
		
	} elseif($target == "execJoin") {
		$msgbox = "<div id='Area' class='container row'>
					<div aling='center'>
						<div class='row cardStyleGreen' align='center'>
							<h1 class='fontPlay blue'>
								<span class='fa fa-check fa-2x green'></span><br />
								Processo de Inserção no domínio em andamento. Por favor aguarde a conclusão do processo!
							</h1>
						</div>
					</div>
				</div>";
		echo $msgbox;
		$execopt = "JOIN";
		
		$codexFile = "./scripts/PowerShell/TEMP/" . base64_decode($_SESSION['howI']) . ".log";
		$codexTxt = $_SESSION['toDomain'] . "\r\n" .
					$_GET['luser'] . "\r\n" . 
					$_GET['lpass'] . "\r\n" .  
					$_SESSION['DC'] . "\r\n" . 
					$_SESSION['newName'] . "\r\n" . 
					$_SESSION['alocacao'] . "\r\n" . 
					$execopt . "\r\n" . 
					$_SESSION['descricao'] . $extraValue . "\r\n" . 
					$_SESSION['atributes'] . "\r\n" .
					$_SESSION['IP'];
		$writecodex = file_put_contents($codexFile, $codexTxt);
				
		$cmd = "powershell.exe -ExecutionPolicy ByPass -file \"C:\\xampp\\htdocs\\NameIt\\scripts\\PowerShell\\JoinDomain.ps1\" -User \"" . base64_decode($_SESSION['howI']) . "\" -Password \"" . base64_decode(base64_decode($_SESSION['howK'])) . "\"";
		//echo $cmd;
		//$_SESSION['result'] = exec($cmd);
		
	} elseif($target == "BKP"){
		$createFile = createBKP();
		echo "<div class='container-fluid row'>";
			echo "<div class='row black cardStyleLBlue fontPlay' align='center'>";
				echo "<span class='xl'>O arquivo de Backup das configurações do sistema foi gerado com sucesso.</span><br />";
				echo "<span class='xl'>Selecione <a href='./TEMP/" . $createFile . "' target='_blank' class='red' download>aqui</a> o arquivo para salva-lo.</span><br />";
			echo "</div>";
		echo "</div>";
	} elseif($target == "reqpassword" || $target == "refpassword"){
		require_once("cofre.php");
		$newpass = generetePassword();
		echo "<div class='white fontPlay redCard' align='center'>";
			echo "<span class='xl'><span class='fa fa-lock'></span> Senha criada:</span> <br />";
			echo "<input type='text' readonly class='black lg form-control' style='width:50%; border-radius:5px;' value='" . $newpass . "' />";
			echo "<br />";
			echo "<div style='border:2px dashed white; border-radius:5px; width:70%;'>";
				echo "<span>A senha gerada pelo sistema será definida para o ADMINISTRADOR local da estação de trabalho.</span><br />";
				echo "<span><b>Sua validade é até à 00 hora.</b></span>";
			echo "</div>";
		echo "</div>";
		echo "<div class='fontPlay' align='center' style='margin:10px;'>";
			echo "<span class='btn btn-success bggreen' style='margin:10px;'><b><span class='fa fa-check'></span> Aplicar senha a esta estação</b></span><br />";
			echo "<span class='btn btn-info bgblue' style='margin:10px;' onclick='getPath(\"refpassword\",\"" . $database . "\", \"0\", \"0\")'><b><span class='fa fa-refresh'></span> Solicitar nova senha</b></span>";
		echo "</div>";
	} elseif($target == "sendpassword"){
		
	}
	//Libera conexão
	ldap_close($ldapConnection);
	
	
?>