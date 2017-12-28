<?php
	session_start();
	require_once("functions.php");
	require_once("pageInfo.php");
	
	$showDomain = $_SESSION['toDomain'] . ".br";
	$breadCrumb = array();
	if(isset($_GET['targetOU'])){
		$database = $_GET['targetOU'];
		$breadData = str_replace($_SESSION['root'], $showDomain, $database);
		$exDatabase = explode(",", $breadData);
		
		$breadPieces = str_replace("OU=", "", $breadData);
		$pieceDN = explode(",", $breadPieces);
		
		$databases = "";
		for($j = count($exDatabase)-1; $j >= 0; $j--){
			$databases = $exDatabase[$j] . "," . $databases;
			$databases = str_replace($showDomain . ",", $_SESSION['root'], $databases);
			array_push($breadCrumb, $pieceDN[$j] . "|" .  $databases);
		}
	} else {
		$database = $_SESSION['root'];
		$breadData = str_replace($_SESSION['root'], $showDomain, $database);
		array_push($breadCrumb, $breadData . "|" . $database);
	}
?>

	<?php
		echo $htmlHeader;
	?>

		<?php
			echo $pageLoader;
			echo $msgbox;
			
			echo "<div class='container-fluid'>";
				echo "<ol class='breadcrumb'>";
					echo "<li><span class='fa fa-home blue lg'></span></li>";
					foreach($breadCrumb as $bread){
						if($bread != ""){
							$targetDNs = explode("|", $bread);
							$dnName = $targetDNs[0];
							$dnPath = $targetDNs[1];
							echo "<li><a href='#' onclick='getNavigatorPath(\"" . $dnPath . "\")'>" . $dnName . "</a></li>";
						}
					}
				echo "</ol>";
			echo "</div>";
			echo "<div class='navContent cardStyleYellow container-fluid' style='padding:20px;'>";
				echo "<div class='row'>";
					echo "<div class='col-xs-12 col-sm-6'>"; 
						echo "<form name='selectedOU' id='selectedOU'>";
							$OUlist = ldapNavigator($database);
							if(count($OUlist) == 0){
								echo "<div class='fontPlay'>";
									echo "<p><span class='fa fa-times red lg'></span> <b style='opacity: 0.5'>Não há outras OUs!</b></p>";
								echo "</div>";
							}
							for($i = 0; $i < count($OUlist); $i++){
								$dataLvl = explode("|", $OUlist[$i]);
								$folder = $dataLvl[0];
								$DN = $dataLvl[1];
								echo "<div class='cardHover selectInput' id='" . $i . "_Selector' data-path='" . $DN . "' data-value='" . $folder . "' onclick='makeselection(\"" . $i . "_Selector\")'>";
									echo "<span class='fa fa-folder-open yellow lg selectIcon' id='" . $i . "_Selector-icon' ></span> <b><a href='#' class='selectAnchor' id='" . $i . "_Selector-anchor' onclick='getNavigatorPath(\"" . $DN . "\")'>" . $folder . "</a></b>";
								echo "</div>";
							}
						echo "</form>";
					echo "</div>";
					echo "<div class='col-xs-12 col-sm-6'>";
						echo "<h3 class='fontPlay blue'>OU Selecionada:</h3>
								<div class='row'>
									<div class='col-xs-3 col-sm-2 col-md-2' align='right'>
										<label for='path' class='top10'><span class='fa fa-arrow-right fa-lg blue'></span></label>
									</div>
									<div class='col-xs-9 col-sm-10 col-md-10'>
										<input type='text' readonly name='path' id='path' required placeholder='Caminho' style='width:100%; color:#222;' />
									</div>
								</div>
								<div class='row'>
									<div class='col-xs-3 col-sm-2 col-md-2' align='right'>
										<label for='folder' class='top10'><span class='fa fa-folder fa-lg blue'></span></label>
									</div>
									<div class='col-xs-9 col-sm-10 col-md-10'>
										<input type='text' readonly name='folder' id='folder' required placeholder='Pasta' style='width:100%; color:#222;' />
									</div>
								</div>
								<div class='row top10'>
									<div class='col-xs-12' align='right'>
										
									</div>
								</div>
								<br />";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		?>
		
