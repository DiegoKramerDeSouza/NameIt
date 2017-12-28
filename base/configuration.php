<?php
	session_start();
	require_once("pageInfo.php");
	require_once("../get-date.php");
	if(!isset($_SESSION['adm']) && !isset($_SESSION['acc'])){
		header("Location:logout.php");
	}
	$conf = "./MDDB/conf.mddb";
	if(file_exists($conf)){
		$buttom = "<span class='btn btn-warning' onclick='apply()'><span class='fa fa-edit'></span> Alterar Configuração</span>";
	} else {
		$buttom = "<span class='btn btn-info' onclick='apply()'><span class='fa fa-check'></span> Aplicar Configuração</span>";
	}
	
?>
<!DOCTYPE html>
<html>
	<?php
		echo $htmlHeader;
	?>
	<body>
		<div id="imgBG">
			<img src="../images/bottomflux.png" class="bottomCornerImg" />
		</div>
		<?php
			
			echo $pageLoaderOut;
			echo $pageNavbar;
			echo $msgbox;
			
			$userCard = "<div class='dropdown' id='userCard'>
							<button type='button' class='btn btn-default' data-container='body' data-placement='bottom' id='dropMenuADM' data-toggle='popover' data-trigger='focus' data-content='
								<div class=\"popoverContent poContent\">
									<a href=\"logout.php\" class=\"blue col-xs-12 underlineHover\" id=\"Logout\" onclick=\"openLoad()\"><span class=\"fa fa-sign-out red\"></span> Sair</a>
									<br />
								</div>
							'>
								<div class='dropContent'>" .
									$_SESSION['photo'] . " " . $_SESSION['name'] .
									" <span class='fa fa-caret-down'></span>
								</div>
							</button>
						</div>";
			echo $userCard;
			
		echo "<div class='container' style='margin-top:50px;'>";
			echo "<div aling='center' class='cardStyle nmtMenu' id='LDAP'>
					<div class='row'>
						<div class='col-xs-12 col-sm-6 col-md-6' >
							<h1 class='fontPlay blue'>Informe os campos de para configuração:</h1>
							<div class='black fontPlay' style='background-color:rgba(80,80,80,0.2); padding:20px; border-radius:2px;'>
								<p class='sm'>Estes campos representam as configurações básicas do sistema para estabelecer a conexão LDAP.</p>
								<!--<p class='red'><span class='fa fa-exclamation-triangle'></span> <b>Atenção:</b> Ao definir o usuário e senha para acesso LDAP estes também serão definidos como a credencial padrão para acessar esta central administrativa.</p>-->
							</div>
						</div>";
					echo "<div class='col-xs-12 col-sm-6 col-md-6 divider'>";
						echo "<div class='tooltip-show' id='elem1' data-msg='Informe o nome do domínio'>";
							echo "<div class='col-xs-12' style='margin-top:10px;'>
									<label id='domain_label' for='domain'><span class='fa fa-home blue '></span> Nome do Domínio:</label><br />
									<input type='text' name='domain' class='form-control' id='domain' placeholder='Domínio' size='45' style='margin-left:0px;' />
									<span class='slider' id='domain_slider' style='left:15px;'></span>
								</div>";
						echo "</div>";
						echo"<div class='tooltip-show' id='elem2' data-msg='Informe o nome do DC/GC a que o sistema irá se reportar'>";
							echo "<div class='col-xs-12' style='margin-top:10px;'>
									<label id='DC_label' for='DC'><span class='glyphicon glyphicon-hdd blue sm'></span> Nome do Domain Controller:</label><br />
									<input type='text' name='DC' class='form-control' id='DC' placeholder='Domain Controller' size='45' style='margin-left:0px;'  />
									<span class='slider' id='DC_slider' style='left:15px;'></span>
								</div>";
						echo "</div>";
						echo "<div class='tooltip-show' id='elem3' data-msg='Informe a porta de comunicação com o DC/GC'>";
							echo "<div class='col-xs-12' style='margin-top:10px;'>
									<label id='door_label' for='door'><span class='fa fa-sign-in blue '></span> Porta de Comunicação:</label><br />
									<input type='text' name='door' class='form-control' id='door' placeholder='Porta' size='45' style='margin-left:0px;'  />
									<span class='slider' id='door_slider' style='left:15px;'></span>
								</div>";
						echo "</div>";
						echo "<div class='tooltip-show' id='elem4' data-msg='Informe a conta que será utilizada para autenticação com o DC/GC'>";
							echo "<div class='col-xs-12' style='margin-top:10px;'>
									<label id='account_label' for='account'><span class='fa fa-user blue '></span> Usuário:</label><br />
									<input type='text' name='account' class='form-control' id='account' placeholder='Conta de usuário do domínio' size='45' style='margin-left:0px;'  />
									<span class='slider' id='account_slider' style='left:15px;'></span>
								</div>";
						echo "</div>";
						echo "<div class='tooltip-show' id='elem5' data-msg='Informe a senha da conta que será utilizada'>";
							echo "<div class='col-xs-12' style='margin-top:10px;'>
									<label id='pass_label' for='pass'><span class='fa fa-lock blue '></span> Senha:</label><br />
									<input type='password' name='pass' class='form-control' id='pass' placeholder='Senha da conta' size='45' style='margin-left:0px;'  />
									<span class='slider' id='pass_slider' style='left:15px;'></span>
								</div>";
						echo "</div>";
						echo "<div class='tooltip-show' id='elem6' data-msg='Confirme a senha informada'>";
							echo "<div class='col-xs-12' style='margin-top:10px;'>
									<label id='passCnf_label' for='passCnf'><span class='fa fa-lock blue '></span> Confirmação:</label><br />
									<input type='password' name='passCnf' class='form-control' id='passCnf' placeholder='Confirme a senha' size='45' style='margin-left:0px;'  />
									<span class='slider' id='passCnf_slider' style='left:15px;'></span>
								</div>";
						echo "</div>";
						echo "<hr />";
						echo "<div class='tooltip-show' id='elem7' data-msg='Informe o grupo de acesso de administradores do sistema'>";
							echo "<div class='col-xs-12' style='margin-top:10px;'>
									<label id='ADMgp_label' for='ADMgp'><span class='fa fa-users blue '></span> Grupo de Usuários Administratores:</label><br />
									<input type='text' name='ADMgp' class='form-control' id='ADMgp' placeholder='Informe o grupo de administradores' size='45' style='margin-left:0px;'  />
									<span class='slider' id='ADMgp_slider' style='left:15px;'></span>
								</div>";
						echo "</div>";
						echo "<div class='tooltip-show' id='elem8' data-msg='Informe o grupo de usuários autorizados para acesso ao sistema'>";
							echo "<div class='col-xs-12' style='margin-top:10px;'>
									<label id='GP_label' for='GP'><span class='fa fa-users blue '></span> Grupo de Usuários Autorizados:</label><br />
									<input type='text' name='GP' class='form-control' id='GP' placeholder='Informe o grupo de usuários' size='45' style='margin-left:0px;'  />
									<span class='slider' id='GP_slider' style='left:15px;'></span>
								</div>";
						echo "</div>";
						echo "<div class='tooltip-show' id='elem9' data-msg='Informe o atributo do <i>Active Directory</i> que será utilizado para definição dos valores de nomenclatura'>";
							echo "<div class='col-xs-12' style='margin-top:10px;'>
									<label id='Attr_label' for='Attr'><span class='fa fa-folder-open blue '></span> Atributo do AD:</label><br />
									<input type='text' name='Attr' class='form-control' id='Attr' placeholder='Informe um atributo do AD' size='45' style='margin-left:0px;'  />
									<span class='slider' id='Attr_slider' style='left:15px;'></span>
								</div>";
						echo "</div>";
						echo "<div class='tooltip-show' id='elem10' data-msg='Informe o usuário administrador local das estações de trabalho'>";
							echo "<div class='col-xs-12' style='margin-top:10px;'>
									<label id='adm_label' for='adm'><span class='fa fa-desktop blue '></span> Administrador Local:</label><br />
									<input type='text' name='adm' class='form-control' id='adm' placeholder='Conta de Administrador local' size='45' style='margin-left:0px;'  />
									<span class='slider' id='adm_slider' style='left:15px;'></span>
								</div>";
						echo "</div>";
						echo "<div class='tooltip-show' id='elem11' data-msg='Informe a senha de administrador local das estações de trabalho'>";
							echo "<div class='col-xs-12' style='margin-top:10px;'>
									<label id='pwadm_label' for='pwadm'><span class='fa fa-lock blue '></span>Senha de Administrador Local:</label><br />
									<input type='password' name='pwadm' class='form-control' id='pwadm' placeholder='Senha de Administrador local' size='45' style='margin-left:0px;'  />
									<span class='slider' id='pwadm_slider' style='left:15px;'></span>
								</div>";
						echo "</div>";
						
						echo "<div class='col-xs-12' style='margin:10px;'>";
							echo "<div align='center'>";
								echo $buttom;
							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
			
			echo "<br />";
			
			echo "<div class='cardStyleLBlue ' style='padding:20px;'>";
				echo "	<div class=''>
							<h1 class='fontPlay blue'>Definição dos padrões de nomenclatura:</h1>
						</div>
						<div class='black fontPlay'>
							<p class='lg'>Por padrão a composição dos nomes são definidas por:</p>
							<span ><span class='btn btn-primary btn-sm active'>Tipo de ativo</span> <span class='btn btn-primary btn-sm active'>Unidade Federativa</span> <span class='btn btn-primary btn-sm active'>Código do site</span> <span class='btn btn-primary btn-sm active'>Área</span> <span class='btn btn-primary btn-sm active'>Código do setor</span> <span class='btn btn-info btn-sm active'>sequencial numérico de 4 dígitos</span></span>
							<div style='background-color:rgba(80,80,80,0.2); padding:20px; border-radius:2px;'>
								<p class='sm'>Estes 5 campos podem ser alterados de acordo com a nomenclatura de ativos adotada para companhia.</p>
								<p class='sm'>O número máximo de campos para a composição de um padão de nomes é de 5. Isso por que o sistema de nomenclatura procura estabelecer no máximo 16 caracteres para nomes de ativos, já incluído a sequência numérica criada automaticamente. Cada campo pode possuir qualquer quantidade de caracteres desde que a soma de todos os campos não ultrapasse 12 caracteres que juntamente com o sequencial totalizam 16 caracteres.</p>
								<p class='sm'>Não utilize caracteres especiais como acentos ou símbolos nos campos abaixo.</p>
							</div>
						</div>
						<br />";
				echo "	<div>
							<div class='fontPlay black'>
								<span class='lg'>
									<b class='blue'><span class='fa fa-cubes'></span> Definição de Nomenclatura</b>
								</span>
								<br />
								<span class='sm'>
									<span class='fa fa-arrow-right'></span> Deixe um campo em branco para deixar de utiliza-lo e em seguida confirme a alteração de campos.<br />
									<span class='fa fa-arrow-right'></span> Preencha um campo e sua quantidade de caracteres para utiliza-lo e em seguida confirme a alteração de campos.
								</span>
							</div><br />
							<div class='fontPlay sm'>";
						
					$defaults = "./MDDB/defaults.mddb";
					if(!file_exists($defaults)){
						file_put_contents($defaults, "ZkVCQmRHbDJieU15ZkVCVlJpTXlmRUJUYVhSbEl6SjhRRUZ5WldFak1YeEFVMlYwYjNJak1nPT0=");
					}
					$fields = file_get_contents($defaults);
					$fields = base64_decode(base64_decode($fields));
					$fieldsData = explode("|@", $fields);
					$countData = count($fieldsData);
					if($countData > 6){
						$countData = 6;
					}
					for($i = 1; $i < $countData; $i++){
						$data = explode("#", $fieldsData[$i]);
						
						echo "	<div class='row'>
									<div class='col-xs-8' style='padding-top: 10px;'>
										<label id='Campo" . $i . "_label' for='Campo" . $i . "'><span class='fa fa-cube blue '></span> Campo 0" . $i . ":</label><br />
										<input type='text' name='Campo" . $i . "' class='form-control campo' id='Campo" . $i . "' placeholder='Nome do " . $i . "º Campo' size='45' style='margin-left:0px;' value='" . $data[0] . "' />
										<span class='slider' id='Campo" . $i . "_slider' style='left:15px;'></span>
									</div>
									<div class='col-xs-3' style='padding-top: 10px;'>
										<label id='Campo" . $i . "_number_label' for='Campo" . $i . "_number'>Caracteres:</label>
										<br />
										<input type='text' name='Campo" . $i . "_number' class='form-control contador' id='Campo" . $i . "_number' placeholder='2' size='2' maxlength='2' style='margin-left:0px;' value='" . $data[1] . "' />
										<span class='slider' id='Campo" . $i . "_number_slider' style='left:15px;'></span>
									</div>
									<div class='col-xs-1' style='padding-top: 10px;' align='left'>
										<br />
										<span class='fa fa-check-circle green lg tooltipItem' id='tooltip_" . $i . "'></span>
										<div class='tooltipFadeIn'>
											<div class='tooltip bottom' role='tooltip' id='tooltip_" . $i . "_val'>
												<div class='tooltip-arrow'></div>
												<div class='tooltip-inner'>
													Campo Habilitado
												</div>
											</div>
										</div>
									</div>
								</div>";
						
					}
					
					$diference = 6 - $countData;
					for($i = 0; $i < $diference; $i++){
						$val = ($countData) + ($i);
						echo "	<div class='row'>
									<div class='col-xs-8' style='padding-top: 10px;'>
										<label id='Campo" . $val . "_label' class='black' for='Campo" . $val . "'><span class='fa fa-cube'></span> Campo 0" . $val . ":</label><br />
										<input type='text' name='Campo" . $val . "' class='form-control campo' id='Campo" . $val . "' placeholder='Nome do " . $val . "º Campo' size='45' style='margin-left:0px;'  />
										<span class='slider' id='Campo" . $val . "_slider' style='left:15px;'></span>
									</div>
									<div class='col-xs-3' style='padding-top: 10px;'>
										<label id='Campo" . $val . "_number_label' class='black' for='Campo" . $val . "_number'>Caracteres:</label>
										<br />
										<input type='text' name='Campo" . $val . "_number' class='form-control contador' id='Campo" . $val . "_number' placeholder='0' size='2' maxlength='2' style='margin-left:0px;' />
										<span class='slider' id='Campo" . $val . "_number_slider' style='left:15px;'></span>
									</div>
									<div class='col-xs-1' style='padding-top: 10px;' align='left'>
										<br />
										<span class='fa fa-times-circle red lg tooltipItem' id='tooltip_" . $val . "'></span>
										<div class='tooltipFadeIn'>
											<div class='tooltip bottom' role='tooltip' id='tooltip_" . $val . "_val'>
												<div class='tooltip-arrow'></div>
												<div class='tooltip-inner'>
													Campo Desabilitado
												</div>
											</div>
										</div>
									</div>
								</div>";
					}
					
				echo "	<div class='row' align='center' style='margin:50px;'>
							<span class='btn btn-warning' onclick='defaultApply()'><span class='fa fa-edit'></span> Alterar campos de nomenclatura</span>
						</div>";
				echo "</div>
					</div>
				</div>
			</div>";
		
		
		
		echo "<div id='objOUs' class='container' style='margin-top: 10px;'></div>";
			
		?>
			
		<div class='tooltipFade'>
			<div class='tooltip bottom' role='tooltip' id='tooltip-ex'>
				<div class='tooltip-arrow'></div>
				<div class='tooltip-inner' id='tooltipText'>
				</div>
			</div>
		</div>
		
		<div style="padding-top:80px;">&nbsp;</div>	
		<div class="bootomSingle" id="homefooter" style="position:fixed; bottom:0px; width:100%;">
			<?php 
				echo $pageFooter;
			?>
		</div>
		
		<script src='../Scripts/jquery-2.1.4.min.js'></script>
		<script src='../Scripts/bootstrap.min.js'></script>
		<script src='../Scripts/bootstrap-toggle.min.js'></script>
		<script src='../scripts/animated.js'></script>
		<script src='../scripts/jQueryRollPlugin/jRoll.min.js'></script>
		<script type="text/vbscript" src="..\scripts\VBScript\VBScript.vbs"></script>
		
		<?php
			$fileExists = file_exists($conf);
			if($fileExists){
				$configuration = file_get_contents($conf);
				$configuration = base64_decode(base64_decode($configuration));
				$configuration = explode("|@", $configuration);
				echo 	"<script>
							document.getElementById('domain').value = \"" . $configuration[0] . "\";
							document.getElementById('DC').value = \"" . $configuration[1] . "\";
							document.getElementById('door').value = \"" . $configuration[2] . "\";
							document.getElementById('account').value = \"" . $configuration[3] . "\";
							document.getElementById('pass').value = \"" . $configuration[4] . "\";
							document.getElementById('passCnf').value = \"" . $configuration[4] . "\";
							document.getElementById('ADMgp').value = \"" . $configuration[5] . "\";
							document.getElementById('GP').value = \"" . $configuration[6] . "\";
							document.getElementById('Attr').value = \"" . $configuration[7] . "\";
							document.getElementById('adm').value = \"" . $configuration[8] . "\";
							document.getElementById('pwadm').value = \"" . $configuration[9] . "\";
						</script>";
			}
		?>
		
		<script>
			$(document).ready(function(){
								
				$(".form-control").focus(function(){
					var elemId = this.id;
					var elem = document.getElementById(elemId+"_label");
					$("#"+elemId+"_slider").animate({
						width: $('#'+elemId).width() + 30
					}, 300);
					elem.classList.add('blue');
				});
				$(".form-control").blur(function(){
					var elemId = this.id;
					var elem = document.getElementById(elemId+"_label");
					$("#"+elemId+"_slider").animate({
						width: 0
					}, 300);
					elem.classList.remove('blue');
				});
				$(".tooltipItem").mouseenter(function(){
					var myId = this.id;
					$("#"+myId+"_val").css({display: 'inline-block', opacity: 0.8, position: 'absolute', left: '-50px', 'min-width': '150px'});
				});
				$(".tooltipItem").mouseleave(function(){
					var myId = this.id;
					$("#"+myId+"_val").fadeOut(100);
				});
				
			});
			
			function apply(){
				var domain = document.getElementById("domain").value;
				var server = document.getElementById("DC").value;
				var port = document.getElementById("door").value;
				var user = document.getElementById("account").value;
				var pass = document.getElementById("pass").value;
				var passCnf = document.getElementById("passCnf").value;
				var admGP = document.getElementById("ADMgp").value;
				var GP = document.getElementById("GP").value;
				var Attr = document.getElementById("Attr").value;
				var adm = document.getElementById("adm").value;
				var pwadm = document.getElementById("pwadm").value;
				var label;
				var proceed = true;
				
				$allFields = document.querySelectorAll('input').length
				for(var $i = 0; $i < ($allFields - 5); $i++){
					label = document.querySelectorAll('input')[$i].id;
					label = document.getElementById(label+"_label");
					label.classList.remove('red');
					if(document.querySelectorAll('input')[$i].value == ""){
						label.classList.add('red');
						//console.log(document.querySelectorAll('input')[$i].name);
						proceed = false;
					}
				}
				
				if(proceed){
					if(pass === passCnf){
						$.post( "return.php", { domain: domain, server: server, port: port, user: user, pass: pass, admGP: admGP, gp: GP, attr: Attr, adm: adm, pwadm: pwadm },
							function(data,status){
								//alert(data);
								if(status == "success"){
									var mensagem = "<i class=\'fa fa-check xxl green\'></i> Configuração efetuada com sucesso!";
									message(mensagem);
								} else {
									mensagem = "<i class=\'fa fa-times xxl red\'></i> Falha a aplicar a configuração!";
									message(mensagem);
								}
							}
						);
					} else {
						document.getElementById("pass").focus();
						var elem = document.getElementById("pass_label");
						elem.classList.add('red');
						elem = document.getElementById("passCnf_label");
						elem.classList.add('red');
						mensagem = "<i class=\'fa fa-times xxl red\'></i> Confirmação de senha incorreta!";
						message(mensagem);
					} 
				} else {
					mensagem = "<i class=\'fa fa-times xxl red\'></i> Por favor preencha todos os campos!";
					message(mensagem);
				}
			}
			
			function defaultApply(){
				var elem;
				var elen;
				var label;
				var exec = "";
				var total = 0;
				var floor = Math.floor;
				var proc = true;
				var allFields = document.querySelectorAll('.campo').length;
				if(proc == true){
					for(var $i=0; $i < allFields; $i++){
						elemID = document.querySelectorAll('.campo')[$i].id;
						elem = document.getElementById(elemID);
						elenID = document.querySelectorAll('.contador')[$i].id;
						elen = document.getElementById(elenID);
						//console.log(document.querySelectorAll('.campo')[$i].id);
						if(elem.value != "" && elen.value != ""){
							if(elen.value > 0){
								exec = exec + "|@" + elem.value + "#" + elen.value; 
								//mensagem = "<i class=\'fa fa-check xxl green\'></i> Alteração efetuada com sucesso!";
								//message(mensagem);
							} else {
								proc = false;
								label = document.getElementById(elenID+"_label");
								label.classList.remove('black');
								label.classList.add('red');
								mensagem = "<i class=\'fa fa-times xxl red\'></i> Informe um número entre 1 e 12!";
								message(mensagem);
							}
						} else if(elem.value == "" && elen.value == ""){
							//mensagem = "<i class=\'fa fa-check xxl green\'></i> Alteração efetuada com sucesso!";
							//message(mensagem);
						} else {
							proc = false;
							label = document.getElementById(elenID+"_label");
							label.classList.remove('black');
							label.classList.add('red');
							label = document.getElementById(elemID+"_label");
							label.classList.remove('black');
							label.classList.add('red');
							mensagem = "<i class=\'fa fa-times xxl red\'></i> Por favor preencha o nome do campo e sue tamanho em caracteres!";
							message(mensagem);
						}
					}
				}
				if(proc == true){
					for(var $j=0; $j < allFields; $j++){
						elenValue = document.querySelectorAll('.contador')[$j].value;
						if(elenValue != ""){
							itotal = parseInt(total);
							ielenValue = parseInt(elenValue);
							total = itotal + ielenValue;
						}
					}
					if(total > 12){
						proc = false;
						mensagem = "<i class=\'fa fa-times xxl red\'></i> A quantidade de caracteres dos seus campos está ultrapassando o limite de 12!";
						message(mensagem);
					}
				}
				if(proc == true){
					$.post( "return.php", { value: exec },
						function(data,status){
							if(status == "success"){
								var mensagem = "<i class=\'fa fa-check xxl green\'></i> Configuração efetuada com sucesso!";
								message(mensagem);
								location.reload();
							} else {
								mensagem = "<i class=\'fa fa-times xxl red\'></i> Falha a aplicar a configuração!";
								message(mensagem);
							}
						}
					);
				}
			}
			
			function message(message){
				document.getElementById("msgBody").innerHTML = message;
				$("#msgDiv").fadeIn(300);
				$("#dialog").fadeIn(500);
				setTimeout(function(){
					clearmsg();
				}, 1500);
			}
			function clearmsg(){
				document.getElementById("msgBody").innerHTML = "";
				$("#dialog").fadeOut(0);
				$("#msgDiv").fadeOut(500);
			}
			
		</script>
		
	</body>
</html>