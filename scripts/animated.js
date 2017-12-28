//Javascript de controle de exibição de elementos nos documentos;
//Applied to: Index, Home, User, unlock

var $countChecked = 0;
var $idByClass;
var $objClass;
var $showObjData;
var $checked;
var $checkedCount;
var $allBox;
var lockrefresh = false;
var tempId = "";
var tempDb = "";
var idFilter = "";
var id = "";
var clickCount = false;
var formatURL;
var success;
var erro;
var radNum = 0;
var animation = "";
var clicked = false;
var mouseSelect = false;
var verifyInt;
var insist;
var connectors = [];
	
//Aguarda o documento ser carregado
$(document).ready(function(){
	$('#main').slideDown(500);
	$('[data-toggle="popover"]').popover({html:true});
	$('[data-toggle="tooltip"]').tooltip();
	
	
	if($(window).height() > 700){
		$('#homefooter').fadeIn(500);
	}
	$(".tooltip-show").mouseenter(function(){
		popTip(this.id);
	});
	$(".tooltip-show").mouseleave(function(){
		$("#tooltip-ex").fadeOut(0);
	});
	
	// Opções de mensagem==============================================
	$(".showmsgbody").click(function(){
		var msgbodyID = this.id;
		$(".bodymsg").hide(0);
		$("#message" + msgbodyID).fadeIn(500);
		$(".showmsgbody").css({
			'border-bottom': '0px solid transparent'
		});
		$('#' + this.id).css({
			'border-bottom': '1px solid #0cf'
		});
	});
	
	$(".prevOrNextMsg").click(function(){
		$('.bodymsg').hide(0);
		$('#message_' + this.id).fadeIn(500);
		$(".showmsgbody").css({
			'border-bottom': '0px solid transparent'
		});
		$('#_' + this.id).css({
			'border-bottom': '1px solid #0cf'
		});
		
	});
	$(".turnPage").click(function(){
		$('.msgPage').hide(0);
		$('#page' + this.id).fadeIn(500);
	});
	//===========================================================================
	
	
	//Display error message
	$(".explainOff").fadeIn(1000);
	
	//Variáveis para exibição de mensagens em MsgBox
	var icone = "<i class=\'fa fa-check xxl green\'></i> ";
	var mensagem = "";
	
	//Apresenta campo de acesso
	$("#loginPanel").fadeIn(500);
	
	//Carrega tela de load aleatória---------------------------
	/*
	animation= "gyroscope";
	$("#loadGif").jRoll({
		animation: animation
	});
	*/
	
	//Aplica para os botões a exibição da tela de load----------
	$("#Access").click(function(){
		if (document.forms["login"]["user"].value != "" && document.forms["login"]["password"].value != ""){
			$("#loading").fadeIn();
		}
	});
	
	
	$(".btnj").click(function(){
		var buttonId = this.id;
		//alert(buttonId);
		if(document.getElementById(buttonId).disabled == false){
			if(this.id == "indexLoad"){
				if (document.forms["acessar"]["matricula"].value != "" && document.forms["acessar"]["senha"].value != ""){
					$("#loading").fadeIn();
				}
			}
			else if(this.id == "clean"){
				if (document.forms["cleanUser"]["clean"].value != ""){
					$("#loading").fadeIn();
				}
			}
			else if(this.id == "gotoMsgEditor"){
				if (document.forms["sendMessage"]["msgTo"].value != ""){
					$("#loading").fadeIn();
				}
			}
			else if (this.id == "helpLoad" || this.id == "helpClose"){
				//Do Nothing...
			}
			else if (this.id == "EditModal" || (this.id == "modalAplRmv" || (this.id == "modalCncRmv" || (this.id == "modalAplAdd" || (this.id == "modalAplRmv" || (this.id == "modalAdd" || (this.id == "modalCncAdd" || this.id == "modalRmv"))))))){
				//Do Nothing...
			}
			else if (this.id == "selectRmv" || this.id == "selectAdd"){
				//Do Nothing...
			}
			else if (this.id == "coletiveAdd" || this.id == "cancel-coletiveAdd"){
				//Do Nothing...
			}
			else if (this.id == "cleanNot" || (this.id == "uncheckAll" || (this.id == "dropMenu1" || this.id == "tutorial"))){
				//Do Nothing...
			}
			else {
				$("#loading").fadeIn();
			}
		}
	});
	//----------------------------------------------------------
		
	//loadUserNow();
	verityCheck();
	
	//Mouseover apresenta Status
	var $statusId;
	var $achron;
	var $achronId;
	var $pointx;
	var $pointy;
	
	$(".accountStatus").mouseenter(function(){
		$statusId = this.id;
		$pointx = 25;
		$pointy = -80;
		$achron = ("St" + $statusId);
		$("#" + $achron).fadeIn(250);
		$achronId ="#" + $achron;
		$($achronId).css({
			left: $pointx,
			top: $pointy
		});
	});
	$(".accountStatus").mouseleave(function(){
		$statusId = this.id;
		$achron = "#St" + $statusId;
		$($achron).fadeOut(0);
	});
	
	//Tratamento de eventos registrados na URL do local 'index.php'
	erro = identifyReturnedCode("erro");
	if(erro == 1){
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Não foi possível estabelecer conexão com o servidor!";
		callMessage(mensagem);
	}
	else if(erro == 2){
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Usuário ou senha inválidos!";
		callMessage(mensagem);
	}
	else if(erro == 3){
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Área restrita, faça login para obter o acesso.";
		callMessage(mensagem);
	}
	else if(erro == 4){
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Acesso Negado!";
		callMessage(mensagem);
	}
	else if(erro == 5){
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Acesso Negado!";
		callMessage(mensagem);
	}
	else if(erro == 6){
		formatURL = identifyResultCode("&erro=");
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Falha ao carregar dados!";
		callMessage(mensagem);
	}
	else if(erro == 7){
		formatURL = identifyResultCode("&erro=");
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Falha ao aplicar alterações!";
		callMessage(mensagem);
	}
	else if(erro == 8){
		formatURL = identifyResultCode("&erro=");
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Formato de arquivo inválido!";
		callMessage(mensagem);
	}
	else if(erro == 9){
		formatURL = identifyResultCode("&erro=");
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Equipe não encontrada ou vazia!";
		callMessage(mensagem);
	}
	else if(erro == 10){
		formatURL = identifyResultCode("&erro=");
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Não há usuários na sua equipe!";
		callMessage(mensagem);
	}
	else if(erro == 11){
		formatURL = identifyResultCode("&erro=");
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Usuário não localizado!";
		callMessage(mensagem);
	}
	else if(erro == 12){
		formatURL = identifyResultCode("&erro=");
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Falha ao inserir dados!";
		callMessage(mensagem);
	}
	//Tratamento de eventos registrados na URL do local 'home.php'
	success = identifyReturnedCode("success");	
	if(success == 0){
		formatURL = identifyResultCode("&result=");
		mensagem = icone + "Site adicionado com sucesso!";
		callMessage(mensagem);
	}
	if(success == 1){
		formatURL = identifyResultCode("&result=");
		mensagem = icone + " Backup do sistema carregado com sucesso!";
		callMessage(mensagem);
	}
	if(success == 2){
		formatURL = identifyResultCode("&result=");
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Não foi possível efetuar a operação!<br />Por favor acione o suporte técnico.";
		callMessage(mensagem);
	}
	if(success == 3){
		formatURL = identifyResultCode("&result=");
		mensagem = icone + "Solicitação de <u>hora extra cancelada</u> pelo supervisor!";
		callMessage(mensagem);
	}
	if(success == 4){
		formatURL = identifyResultCode("&result=");
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Não foi possível negar a solicitação. Falha ao alterar atributos";
		callMessage(mensagem);
	}
	if(success == 5){
		formatURL = identifyResultCode("&result=");
		mensagem = icone + "Foi <u>efetuado o logoff</u> do colaborador.";
		callMessage(mensagem);
	}
	if(success == 6){
		formatURL = identifyResultCode("&result=");
		mensagem = icone + "Foi <u>cancelado o logoff</u> do colaborador.";
		callMessage(mensagem);
	}
	//Tratamento do resultado do gerenciamento de grupos----------------------
	if(success == 7){
		formatURL = identifyResultCode("&result=");
		mensagem = icone + "Foi adicionado o colaborador ao(s) grupo(s) selecionado(s).";
		callMessage(mensagem);
	}
	if(success == 8){
		formatURL = identifyResultCode("&result=");
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Não foi possível adicionar o colaborador ao(s) grupo(s).";
		callMessage(mensagem);
	}
	if(success == 9){
		formatURL = identifyResultCode("&result=");
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Favor informar os grupos para inser&ccedil;&atilde;o ou remo&ccedil;&atilde;o.";
		callMessage(mensagem);
	}
	if(success == 10){
		formatURL = identifyResultCode("&result=");
		mensagem = icone + "O colaborador foi removido do(s) grupo(s) selecionado(s).";
		callMessage(mensagem);
	}
	if(success == 11){
		formatURL = identifyResultCode("&result=");
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Não foi possível remover o colaborador do(s) grupo(s).";
		callMessage(mensagem);
	}
	//Tratamento do resultado do reset de dados de logon e logoff----------------------
	if(success == 12){
		formatURL = identifyResultCode("&result=");
		mensagem = icone + "Foram resetados os registros do colaborador.";
		callMessage(mensagem);
	}
	if(success == 13){
		formatURL = identifyResultCode("&result=");
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Não foi possível resetar os dados do colaborador.";
		callMessage(mensagem);
	}
	//-------------------------------------------------------------------------
	if(success == 14){
		formatURL = identifyResultCode("&result=");
		mensagem = icone + "Operador adicionado com sucesso!";
		callMessage(mensagem);
	}
	if(success == 15){
		formatURL = identifyResultCode("&result=");
		mensagem = icone + "Operador removido com sucesso!";
		callMessage(mensagem);
	}
	//--------------------------------------------------------------------------
	if(success == 16){
		formatURL = identifyResultCode("&result=");
		mensagem = icone + "Mensagem enviada com sucesso!";
		callMessage(mensagem);
	}
	
	//Anima objetos da class Blink
	setInterval(function(){
		  $('.blink').each(function(){
			$(this).css('visibility' , $(this).css('visibility') === 'hidden' ? '' : 'hidden')
		  });
		}, 500);

	//Tratamento de checkbox para exibir o botão de adição de tempo
	$(".chkBox").change(function(){
		$idByClass = this.id;
		idFilter = $idByClass;
		$idByClass = "#" + $idByClass;
		$checked = document.querySelector($idByClass).checked;
		$checkedCount = document.querySelectorAll('input[type="checkbox"]:checked').length;
		
		if($checked == true){
			$(".maisTempoChkd").show(500);
			getSelected(idFilter);
		} else {
			if($checkedCount == 0){
				$(".maisTempoChkd").hide(500);
				$("#uncheckedBox").hide(0);
				document.getElementById("allChecked").innerHTML = "<i class='fa fa-square-o fa-lg'></i>";
			}
			findStr(document.forms["variosUsers"]["usersCodes"].value, idFilter);
		}
	});
	
	//Controle de Hover sobre a classe informativoUser --- EXIBE
	$(".informativoUser").click(function(){
		$objClass = this.id;
		$hideInfo = "#" + $objClass + "_hideInfo";
		$showObjData = "#" + $objClass + "Details";
		$($showObjData).show(66);
		$($hideInfo).show(66);
		document.getElementById($objClass + "_maisTempo").style.top = "30px";
		clickCount = true;
	});
	//Controle de Hover sobre a classe informativoUser --- ESCONDE
	$(".btnInfo").click(function(){
		$objClass = this.id;
		$objClass = $objClass.replace("_hideInfo", "");
		$hideInfo = "#" + $objClass + "_hideInfo";
		$showObjData = "#" + $objClass + "Details";
		$($showObjData).hide(66);
		$($hideInfo).hide(66);
		document.getElementById($objClass + "_maisTempo").style.top = "20px";
		$objClass = "#" + $objClass;
		if (clickCount) {
			$($objClass).css({"box-shadow": "none"})
		}
		clickCount = false;
	});
	$(".btnInfo").mouseenter(function(){
		$objClass = this.id;
		$objClass = $objClass.replace("_hideInfo", "");
		$objClass = "#" + $objClass;
		if (clickCount) {
			$($objClass).css({"box-shadow": "0px -8px 10px 0px rgba(80, 80, 80, 0.5)"})
		}
	});
	$(".btnInfo").mouseleave(function(){
		$objClass = this.id;
		$objClass = $objClass.replace("_hideInfo", "");
		$objClass = "#" + $objClass;
		if (clickCount) {
			$($objClass).css({"box-shadow": "none"})
		}
	});
	
	//Scrool to top--------
	$(window).scroll(function() {
        if($(this).scrollTop() > 80){
			$('#toTop').fadeIn(500);
        }
        else{
            $('#toTop').fadeOut(500);
        }
    });
    $('#toTop').click(function() {
        $('html, body').stop().animate({
           scrollTop: 0
        }, 500, function() {
            $('#toTop').fadeOut(500);
        });
    });

	//Exibe e limpa Menu
	$(".active-menu").mouseenter(function(){
		$(".menu-left").css({
			'left': '0px',
			'background-color': 'rgba(24, 52, 70, 0.8)',
			'box-shadow': '0px 3px 10px 5px rgba(50,50,50,0.5)'
			});
		$(".menu-left-bg").fadeIn(500);
		document.getElementById('indicatorLeft').innerHTML = '<span class="fa fa-chevron-left fa-1x"></span>';
	});
	$(".menu-left").mouseleave(function(){
		$(".menu-left").css({
			'left': '-290px',
			'background-color': 'rgba(24, 52, 70, 1.0)',
			'box-shadow': 'none'
			});
		$(".menu-left-bg").fadeOut(500);
		document.getElementById('indicatorLeft').innerHTML = '<span class="fa fa-chevron-right fa-1x"></span>';
	});
	
	//Mensagem de alerta para conta desabilitada
	$("#userDisabled").click(function(){
		//alert("Por favor acione a equipe de suporte para verificar o motivo da conta encontrar-se desabilitada.");
		erro = 700;
		formatURL = identifyResultCode("&result=");
		icone = "<i class=\'fa fa-times fa-3x checkTimes\'></i> ";
		titulo = "<b>Por favor acione a equipe de suporte para verificar o motivo da conta encontrar-se desabilitada.</b>";
		mensagem = icone + "Não foi possível adicionar o colaborador ao(s) grupo(s).";
		cor = "rgba(175, 50, 50, 0.8)";
		callMessage(titulo, mensagem, cor);
	})
	//-----------------------------------------------------------------
	
	$(".form-control").focus(function(){
		var blockId = this.id;
		var divBlock = "#div_" + blockId;
		$(divBlock).css({"border-left": "5px solid #0cf"});
	});
	$(".form-control").blur(function(){
		var blockId = this.id;
		var divBlock = "#div_" + blockId;
		$(divBlock).css({"border-left": "5px solid #eee"});
	});
	$(".opt").mouseenter(function(){
		var elem = document.getElementById(this.id+"Icon");
		elem.classList.add('fa-spin')
	});
	$(".opt").mouseleave(function(){
		var elem = document.getElementById(this.id+"Icon");
		elem.classList.remove('fa-spin');
	});
	
	$(".inputArt").on("focus", function(event){
		//console.log(event.target.id);
		$("#"+event.target.id+"_slider").animate({
			width: $("#"+event.target.id).width()
		}, 300);
	})
	$(".inputArt").on("blur", function(event){
		$("#"+event.target.id+"_slider").animate({
			width: 0
		}, 300);
	})
	
	$(".apllyArt").on("mouseenter", function(event){
		//console.log(event.target.id);
		$("#"+event.target.id+"_slider").animate({
			width: $("#"+event.target.id).width()
		}, 300);
	})
	$(".apllyArt").on("mouseleave", function(event){
		$("#"+event.target.id+"_slider").animate({
			width: 0
		}, 300);
	})
	
	
	$("#ADDive").on("show.bs.modal", function(event){
		getNavigatorPath("DC=call,DC=br");
		var button = $(event.relatedTarget);
		var btn = button.data('opt');
		document.getElementById("applicationButton").innerHTML = "<span class='btn btn-info btn-sm' data-dismiss='modal' onclick='sendValue(\"path\", \"name_"+btn+"\")'><span class='fa fa-check'></span> Selecionar "+btn+"</span>";
	});
	
	$("#Cmodal").on("show.bs.modal", function(event){
		var button = $(event.relatedTarget);
		var btn = button.data('opt');
		var machine = button.data('machine');
		if(btn == "JOIN"){
			document.getElementById("Cmodal-Header").innerHTML = "<span>Deseja <b class='blue'>Inserir no Domínio esta estação de trabalho</b> com o nome <b>" + machine + "</b>?</span><br />";
			document.getElementById("Cmodal-Body").innerHTML = 	"<span class='sm'>Para prosseguir informe usuário e senha de administrador desta estação de trabalho.</span><br />" +
																"<div class='row sm'>" +
																	"<div class='col-xs-12'>" +
																		"<span class='fa fa-user blue'></span> &nbsp;&nbsp;<input type='text' name='inpUser' id='inpUser' placeholder='Usuário Local' onfocus='inputArt(\"inpUser_slider\", \"inpUser\")' onblur='inputArtFade(\"inpUser_slider\")' />" +
																		"<span class='slide_' id='inpUser_slider'></span>" +
																	"</div>" +
																	"<div class='col-xs-12 sm'>" +
																		"<span class='fa fa-lock blue'></span> &nbsp;&nbsp;<input type='password' name='impPass' id='impPass' placeholder='Senha Local' onfocus='inputArt(\"impPass_slider\", \"impPass\")' onblur='inputArtFade(\"impPass_slider\")' />" +
																		"<span class='slide_' id='impPass_slider'></span>" +
																	"</div>" +
																	"<div class='col-xs-12 sm'>" +
																		"<span class='fa fa-lock blue'></span> &nbsp;&nbsp;<input type='password' name='impPassCnf' id='impPassCnf' placeholder='Confirme a senha' onfocus='inputArt(\"impPassCnf_slider\", \"impPassCnf\")' onblur='inputArtFade(\"impPassCnf_slider\")' />" +
																		"<span class='slide_' id='impPassCnf_slider'></span>" +
																	"</div>" +
																"</div>";
			document.getElementById("CmodalButton").innerHTML = "<span class='btn btn-info btn-sm' style='margin:2px;' data-dismiss='modal' onclick='sendValue(\"path\", \"newSite\")'><span class='fa fa-check'></span> Inserir no domínio</span>" +
																"<span class='btn btn-danger btn-sm' style='margin:2px;' data-dismiss='modal'><span class='fa fa-times'></span> Cancelar</span>";
		} else if(btn == "RENAME"){
			document.getElementById("Cmodal-Header").innerHTML = "<span>Deseja <b class='blue'>Renomear esta estação de trabalho</b> para <b>" + machine + "</b>?</span>";
			document.getElementById("Cmodal-Body").innerHTML = "";
			document.getElementById("CmodalButton").innerHTML = "<span class='btn btn-success btn-sm' style='margin:2px;' data-dismiss='modal' onclick='sendValue(\"path\", \"newSite\")'><span class='fa fa-check'></span> Renomear</span>" +
																"<span class='btn btn-danger btn-sm' style='margin:2px;' data-dismiss='modal'><span class='fa fa-times'></span> Cancelar</span>";
		}
	});
			
});
//FUNÇÕES=================================================================

function sendValue(from, to){
	var elem = document.getElementById(from).value;
	document.getElementById(to).value = elem;
}

//Funções para selecionar/deselecionar todos os checkbox
function checkAll(){
	var btnId;
	$allBox = document.querySelectorAll('input[type="checkbox"]').length;
	for(var $i = 0; $i < $allBox; $i++){
		btnId = document.getElementsByClassName('adicionar')[$i].disabled;
		if(btnId == false){
			document.getElementsByClassName('chkBox')[$i].checked = true;
			getSelected(document.getElementsByClassName('chkBox')[$i].id);
		}
	}
	document.getElementById("mycheckbox").innerHTML = "<i class='fa fa-check-square-o fa-lg'></i>";
	$(".maisTempoChkd").show(500);
}
function uncheckAll(){
	$allBox = document.querySelectorAll('input[type="checkbox"]').length;
	document.forms["variosUsers"]["usersCodes"].value = "";
	for(var $i = 0; $i < $allBox; $i++){
		document.getElementsByClassName('chkBox')[$i].checked = false;
	}
	document.getElementById("mycheckbox").innerHTML = "<i class='fa fa-square-o fa-lg'></i>";
	$(".maisTempoChkd").hide(500);
}
function verityCheck(){
	var btnIdentity;
	$boxes = document.querySelectorAll('input[type="checkbox"]').length;
	for(var $i = 0; $i < $boxes; $i++){
		btnIdentity = document.getElementsByClassName('adicionar')[$i].disabled;
		if(btnIdentity == true){
			document.getElementsByClassName('chkBox')[$i].disabled = true;
		}
	}
}

//Funções para alterar a exibição de elementos na estrutura HTML
function fixFooter(){
	lockrefresh = true;
	document.getElementById("homefooter").style.position = "fixed";
	document.getElementById("homefooter").style.bottom = "0px";
	document.getElementById("homefooter").style.width = "100%";
	setTimeout(function(){
		document.getElementById("searchUser").focus();
	}, 200);
}
function unfixFooter(){
	lockrefresh = false;
	if(window.location.href.split('?')[1].split("&")[0].split("=")[1] == "*"){
		$("#homefooter").hide(0);
		document.getElementById("homefooter").style.position = "relative";
		document.getElementById("homefooter").style.bottom = "0px";
		document.getElementById("homefooter").style.width = "100%";
		$("#homefooter").show(1000);
		setTimeout(function(){
			document.getElementById("search").focus();
		}, 200);
	}
}
//Função para o tratamento de resultados
function identifyReturnedCode(val) {
	var url = window.location.search.substring(1);
	var divide = url.split("&");
	
	for (var i=0;i<divide.length;i++) {
		var match = divide[i].split("=");
		if (match[0] == val) {
			return match[1];
		}
	}
}
//Função para a remoção de resultados
function identifyResultCode(val) {
	var url = window.location.search.substring(1);
	var divide = url.split(val);
	return divide[0];
}
//Função de preenchimento de campos durante a seleção de checkbox
function getSelected(userid){
	document.forms["variosUsers"]["usersCodes"].value = document.forms["variosUsers"]["usersCodes"].value + userid;
	document.forms["variosUsers"]["usersDatabase"].value = document.forms["returnResults"]["database"].value;
}

//Função de tratamento de string
function findStr(str, val){
	var mystring = str.replace(val,"");
	document.forms["variosUsers"]["usersCodes"].value = mystring;
}

//Função de tratamento de URL
function setUrl(urlVal) {
	window.location = urlVal;
}

//Função de get em coordenadas do mouse
function showCoords(event){
	var x = event.clientX;
	var y = event.clientY;
}
//Set URL para negar a solicitação de hora extra
function negaSolicitacao(account, options){
	setUrl("negate.php?User=" + account + "&opt=" + options);
}
//Exibe o campo de usuário e foto
function loadUserNow(){
	$(".userNow").fadeIn(1300);
}
//Confirma justificativa para logoff
function justificaLogoff(val, base, conta){
	var initVal = "O colavorador solicita o logoff de sua máquina pelo seguinte motivo: \n\n\"" + val + "\"\n\nVocê confirma o logoff?";
	var strVal = initVal.toUpperCase();
	var inputbox = confirm(strVal);
	if (inputbox) {
		//alert(val);
		setUrl("userlogoff.php?account=" + conta + "&database=" + base + "&opt=" + "ok");
	} else {
		setUrl("userlogoff.php?account=" + conta + "&database=" + base + "&opt=" + "cancel");
	}
	
}
//Acordeon para informações de usuário na Home
function hideUserInfo(selectedId){
	$hideInfo = "#" + selectedId + "_hideInfo";
	$showObjData = "#" + selectedId + "Details";
	$($showObjData).hide(116);
	$($hideInfo).hide(116);
	document.getElementById(selectedId + "_maisTempo").style.top = "20px";
}
//Teste para a verificação dos grupos
function verifyGroups(){
	//alert(document.forms["selectGroupsToUser"]["select-groups"].value);
	alert(document.forms["rmvGroupsToUser"]["tormvDNGroups"].value);
	
}
//Limpa valores de gupos nas textareas
function clearValues(){
	document.forms["selectGroupsToUser"]["toinsertGroups"].value = "";
	document.forms["selectGroupsToUser"]["toinsertDNGroups"].value = "";
	document.forms["rmvGroupsToUser"]["tormvGroups"].value = "";
	document.forms["rmvGroupsToUser"]["tormvDNGroups"].value = "";
}
//Trata nomes de grupos e insere nas suas devidas textareas
//Adicionar grupos
function inputGroups(){
	var groupToInsert = document.forms["selectGroupsToUser"]["select-groups"].value;
	var filterDN = groupToInsert.split(",");
	var filterCN = filterDN[0].split("CN=");
	if (document.forms["selectGroupsToUser"]["toinsertDNGroups"].value.indexOf(groupToInsert) == -1 ){
		document.forms["selectGroupsToUser"]["toinsertDNGroups"].value = document.forms["selectGroupsToUser"]["toinsertDNGroups"].value + groupToInsert + "||";
		groupToInsert = document.forms["selectGroupsToUser"]["toinsertGroups"].value + filterCN[1] + "; ";
		document.forms["selectGroupsToUser"]["toinsertGroups"].value = groupToInsert;
	}
}
//Remover grupos
function rmvGroups(){
	var groupToRmv = document.forms["rmvGroupsToUser"]["select-rmvgroups"].value;
	var filterDN = groupToRmv.split(",");
	var filterCN = filterDN[0].split("CN=");
	if (document.forms["rmvGroupsToUser"]["tormvDNGroups"].value.indexOf(groupToRmv) == -1 ){
		document.forms["rmvGroupsToUser"]["tormvDNGroups"].value = document.forms["rmvGroupsToUser"]["tormvDNGroups"].value + groupToRmv + "||";
		groupToRmv = document.forms["rmvGroupsToUser"]["tormvGroups"].value + filterCN[1] + "; ";
		document.forms["rmvGroupsToUser"]["tormvGroups"].value = groupToRmv;
	}
}
//Apaga registros de login
function eraser(useraccount){
	setUrl("eraser.php?User=" + useraccount);
}
//MessageBox personalizado
function callMessage(message){
	document.getElementById("msgBody").innerHTML = "<div class='fontPlay xl'>"+message+"</div>";
	$("#msgDiv").fadeIn(300);
	$("#dialog").fadeIn(500);
	setTimeout(function(){
		clearMsgBox();
	}, 1500);
}
function clearMsgBox(){
	document.getElementById("msgBody").innerHTML = "";
	$("#dialog").fadeOut(0);
	$("#msgDiv").fadeOut(500);
}
//Gerar número aleatório entre 1 e 4
function getRandom(min, max) {
    return Math.round(Math.random() * (max - min) + min);
}
//Verifica funcionalidade de botões
function verifyBtn() {
	var $btnDisabled;
	var $btnId;
	var $allBtn = 0;
	$allBtn = document.getElementsByClassName('btn').length;
	for(var $i = 0; $i < $allBtn; $i++){
		$btnDisabled = document.getElementsByClassName('btn')[$i].disabled;
		$btnId = document.getElementsByClassName('btn')[$i].id;
		alert($btnId + " - " + $btnDisabled + " <<");
	}
}

// Opções de mensagem==============================================
function readMsg(user, num){
	setUrl("read.php?account=" + user + "&msg=" + num);
}
function callAlertBlock(num){
	$('#alert_' + num).fadeIn(500);
	$('#alert_' + num).css({
		'margin': 'auto'
	})
	$('.confirmationBG').fadeIn(500);
	$('#text_' + num).fadeIn(1000);
	$('#btn_' + num).fadeIn(300);
}
function alertBoxFade(num){
	$('#alert_' + num).hide(0);
	$('.confirmationBG').hide(0);
	$('#text_' + num).hide(0);
	$('#btn_' + num).hide(0);
}

//========================================================================
function dataBackup(target, path){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			document.getElementById("objOUs").innerHTML = "<div class='container'><div class='row'>" + this.responseText + "</div></div>";
		} else {
			document.getElementById("objOUs").innerHTML = "<div align='center' class='centralized' style='margin-top:50px; opacity:0.7;'><img src='./images/load.png' class='spinner_fst' style='width:100px; height:100px;'></i><span class='sr-only'>Loading...</span></div>";
			
		}
	};
	xhttp.open("GET", "getpath.php?target=" + target + "&path=" + path, true);
	xhttp.send();
	
	$('#'+this.id).fadeOut();
	fadeOut("homefooter");
}


function getPath(target, path, ID, name){
	var xhttp = new XMLHttpRequest();
	if(target == "RenameJoin" || target == "castReservation" || target == "Reservation" || target == "administrative" || target == "reqpassword"){
		//("ID que vai fazer toggle", "classe para comparação", "ID da div que também fará toggle", "imagem a fazer toggle")
		slideMain("main", "toggleMenu", "pulldown", "chevronImg");
	}
	xhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			document.getElementById("objOUs").innerHTML = "<div class='container'><div class='row'>" + this.responseText + "</div></div>";
		} else {
			//document.getElementById("objOUs").innerHTML = "<div align='center' style='margin-top:50px;'><i class='spinner_fst fa fa-circle-o-notch fa-4x blue-gradient'></i><span class='sr-only'>Loading...</span><div>";
			document.getElementById("objOUs").innerHTML = "<div align='center' class='centralized' style='margin-top:50px; opacity:0.7;'><img src='./images/load.png' class='spinner_fst' style='width:100px; height:100px;'></i><span class='sr-only'>Loading...</span></div>";
			
		}
	};
	xhttp.open("GET", "getpath.php?target=" + target + "&path=" + path + "&data=" + name, true);
	xhttp.send();
	
	$('#'+this.id).fadeOut();
	fadeOut("homefooter");
}
function reservation(opt, host, description, path){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			document.getElementById("objOUs").innerHTML = "<div class='container'><div class='row'>" + this.responseText + "</div></div>";
		} else {
			//document.getElementById("objOUs").innerHTML = "<div align='center' style='margin-top:50px;'><i class='spinner_fst fa fa-circle-o-notch fa-4x blue-gradient'></i><span class='sr-only'>Loading...</span><div>";
			document.getElementById("objOUs").innerHTML = "<div align='center' class='centralized' style='margin-top:50px; opacity:0.7;'><img src='./images/load.png' class='spinner_fst' style='width:100px; height:100px;'></i><span class='sr-only'>Loading...</span></div>";
		}
	};
	xhttp.open("GET", "getpath.php?target=" + opt + "&host=" + host + "&description=" + description + "&path=" + path, true);
	xhttp.send();
	
	$('#'+this.id).fadeOut();
	fadeOut("homefooter");
}
function startScript(value, path, datatype){
	var inputValue = document.getElementById('Information').value;
	var denied = false;
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			document.getElementById("objOUs").innerHTML = "<div class='container'><div class='row'>" + this.responseText + "</div></div>";
		} else {
			//document.getElementById("objOUs").innerHTML = "<div align='center' style='margin-top:50px;'><span class='fontPlay lg black'><p>Aguarde a conclusão do processo.</p><p>A máquina será reinicializada ao término do processo.</p><span><br /><br /><i class='spinner_fst fa fa-circle-o-notch fa-4x blue-gradient'></i><span class='sr-only'>Loading...</span><div>";
			document.getElementById("objOUs").innerHTML = "<div align='center' class='centralized' style='margin-top:50px; opacity:0.8;'><span class='fontPlay lg black'><p>Aguarde a conclusão do processo.</p><p>A máquina será reinicializada ao término do processo.</p><span><br /><br /><img src='./images/load.png' class='spinner_fst' style='width:100px; height:100px;'></i><span class='sr-only'>Loading...</span></div>";
		}
	};
	
	if(value == "execJoin"){
		var inputUser = document.getElementById('localUser').value;
		var inputPass = document.getElementById('localPass').value;
		var inputPassCnf = document.getElementById('localPassCnf').value;
		if(inputPassCnf == inputPass && (inputUser != "" && inputPass != "")){
			denied = false;
		} else {
			denied = true;
		}
	}
	if(!denied){
		if(inputValue != ""){
			inputValue = " - " + inputValue;
			xhttp.open("GET", "getpath.php?target=" + value + "&path=" + path + "&extra=" + inputValue + "&luser=" + inputUser + "&lpass=" + inputPass, true);
		} else {
			xhttp.open("GET", "getpath.php?target=" + value + "&path=" + path + "&luser=" + inputUser + "&lpass=" + inputPass, true);
		}
	} else {
		icone = "<i class=\'fa fa-times xxl red\'></i> ";
		mensagem = icone + "Por favor informe todos os campos corretamente!";
		callMessage(mensagem);
		xhttp.open("GET", "getpath.php?target=*&path=" + path + "&data=" + datatype + "&norepeat=yes", true);
	}
	
	xhttp.send();
	
	$('#'+this.id).fadeOut();
	fadeOut("homefooter");
}
function administration(value, base, opt){
	var xhttp = new XMLHttpRequest();
	if(value == "reports"){
		verifyInterval("canvas");
	}
	
	//("ID que vai fazer toggle", "classe para comparação", "ID da div que também fará toggle", "imagem a fazer toggle")
	slideMain("main", "toggleMenu", "pulldown", "chevronImg");
	
	xhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			document.getElementById("objOUs").innerHTML = "<div class='container'><div class='row'>" + this.responseText + "</div></div>";
		} else {
			//document.getElementById("objOUs").innerHTML = "<div align='center' style='margin-top:50px;'><i class='spinner_fst fa fa-circle-o-notch fa-4x blue-gradient'></i><span class='sr-only'>Loading...</span><div>";
			document.getElementById("objOUs").innerHTML = "<div align='center' class='centralized' style='margin-top:50px; opacity:0.7;'><img src='./images/load.png' class='spinner_fst' style='width:100px; height:100px;'></i><span class='sr-only'>Loading...</span></div>";
		}
	};
	xhttp.open("GET", "getpath.php?target=" + value + "&path=" + base + "&option=" + opt, true);
	xhttp.send();
	
	$('#'+this.id).fadeOut();
	fadeOut("homefooter");
}
//FUNÇÃO DE ALTERAÇÃO DOS CAMPOS ADMINISTRATIVOS DO SISTEMA###################################
function isSpecial(str){
	if(/^[a-zA-Z0-9- ]*$/.test(str) == false){
		return true;
	} else {
		return false;
	}
}
function administrationExecNotAD(index, file, option, itemDep, path){
	var lockit = false;
	var name;
	var value;
	var dependence = "**";
	var tosend;
	var target = "administrative";
	var strSpecialName = false;
	var strSpecialValue = false;
	if(option == 1){
		var clsId;
		var tabElements = document.getElementsByClassName(index+'_TAB').length;
		for(var $i = 0; $i < tabElements; $i++){
			clsId = document.getElementsByClassName(index+'_TAB')[$i].id;
			clsId = document.getElementById(clsId).value;
			if(clsId == ""){
				lockit = true;
			} else if($i == 0) {
				name = clsId;
				strSpecialName = isSpecial(name);
			} else if($i == 1){
				value = clsId;
				strSpecialValue = isSpecial(value);
			} else if($i == 2) {
				dependence = clsId;
			}
		}
		//console.log(strSpecialName);
		//console.log(strSpecialValue);
		if(lockit){
			icone = "<i class=\'fa fa-times xxl red\'></i> ";
			mensagem = icone + " Por favor preencha todos os campos!<br />";
		} else if(strSpecialName || strSpecialValue) {
			lockit = true;
			icone = "<i class=\'fa fa-times xxl red\'></i> ";
			mensagem = icone + " Por favor não utilize caracteres especiais nos valores inseridos!<br /><span class='lg red'>Utilize apenas valores: A-Z, a-z, espaço ou números.</span>";
		} else {
			icone = "<i class=\'fa fa-check xxl green\'></i> ";
			mensagem = icone + " " + file + " criado(a) com sucesso! <br />";
		}
		tosend = name+"||"+value+"||"+itemDep+"||"+dependence;
		
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				document.getElementById("objOUs").innerHTML = "<div class='container'><div class='row'>" + this.responseText + "</div></div>";
			}
		};
		if(!lockit){
			xhttp.open("GET", "getpath.php?target=" + target + "&path=" + path + "&identify=" + file + "&option=" + index + "&towrite=" + tosend , true);
			xhttp.send();
		}
		callMessage(mensagem);
		$('#'+this.id).fadeOut();
		fadeOut("homefooter");
	} else {
		//console.log("Remover: "+itemDep+" de "+file+".mddb");
		icone = "<i class=\'fa fa-check xxl green\'></i> ";
		mensagem = icone + " " + file + " removido(a) com sucesso! <br />";
		tosend = itemDep;
		
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				document.getElementById("objOUs").innerHTML = "<div class='container'><div class='row'>" + this.responseText + "</div></div>";
			}
		};
		if(!lockit){
			xhttp.open("GET", "getpath.php?target=" + target + "&path=" + path + "&identify=" + file + "&option=" + index + "&toerase=" + tosend , true);
			xhttp.send();
		}
		callMessage(mensagem);
		$('#'+this.id).fadeOut();
		fadeOut("homefooter");
	}
}
function administrationExec(index, opt, act, name, path, depd){
	var dn;
	var value;
	var dependence;
	var lockit = false;
	var target = "administrative";
	if(act == 1){
		//Efetuando o cadastro de um elemento-------------------------------------
		var clsId;
		var tabElements = document.getElementsByClassName(index+'_TAB').length;
		for(var $i = 0; $i < tabElements; $i++){
			clsId = document.getElementsByClassName(index+'_TAB')[$i].id;
			clsId = document.getElementById(clsId).value;
			//console.log(clsId);
			if(clsId == ""){
				lockit = true;
			} else if($i == 0) {
				dn = clsId;
			} else if($i == 1){
				value = clsId;
			} else if($i == 2) {
				dependence = clsId;
			}
		}
		if(lockit){
			icone = "<i class=\'fa fa-times xxl red\'></i> ";
			mensagem = icone + " Por favor preencha todos os campos!<br />";
		} else {
			icone = "<i class=\'fa fa-check xxl green\'></i> ";
			mensagem = icone + " " + opt + " criado(a) com sucesso! <br />";
		}
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				document.getElementById("objOUs").innerHTML = "<div class='container'><div class='row'>" + this.responseText + "</div></div>";
			} else {
				//document.getElementById("objOUs").innerHTML = "<div align='center' style='margin-top:50px;'><i class='spinner_fst fa fa-circle-o-notch fa-4x blue-gradient'></i><span class='sr-only'>Loading...</span><div>";
			}
		};
		if(!lockit){
			xhttp.open("GET", "getpath.php?target=" + target + "&identify=" + opt + "&option=" + index + "&path=" + path + "&DN=" + dn + "&value=" + value + "&dependence=" + dependence, true);
			xhttp.send();
		}
		
		
		callMessage(mensagem);
		$('#'+this.id).fadeOut();
		fadeOut("homefooter");
	} else if(act == 2){
		//Cadastro de uma Exceção-------------
		var exc = document.getElementById('excecao').value;
		if(exc != ""){
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){
					document.getElementById("objOUs").innerHTML = "<div class='container'><div class='row'>" + this.responseText + "</div></div>";
				} else {
					//document.getElementById("objOUs").innerHTML = "<div align='center' style='margin-top:50px;'><i class='spinner_fst fa fa-circle-o-notch fa-4x blue-gradient'></i><span class='sr-only'>Loading...</span><div>";
				}
			};
			
			icone = "<i class=\'fa fa-check xxl green\'></i> ";
			mensagem = icone + opt + " adicionada com sucesso!";
			callMessage(mensagem);
			
			xhttp.open("GET", "getpath.php?target=" + target + "&host=" + path + "&option=" + index + "&path=" + path + "&addExc=" + exc, true);
			xhttp.send();
			
			$('#'+this.id).fadeOut();
			fadeOut("homefooter");
		} else {
			icone = "<i class=\'fa fa-times xxl red\'></i> ";
			mensagem = icone + " Por favor informe a " + opt + "!";
			callMessage(mensagem);
		}
		
		
	} else if(act == 3){
		//Executando a remoção da exceção cadastrada-------------
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				document.getElementById("objOUs").innerHTML = "<div class='container'><div class='row'>" + this.responseText + "</div></div>";
			} else {
				//document.getElementById("objOUs").innerHTML = "<div align='center' style='margin-top:50px;'><i class='spinner_fst fa fa-circle-o-notch fa-4x blue-gradient'></i><span class='sr-only'>Loading...</span><div>";
			}
		};
		
		icone = "<i class=\'fa fa-check xxl green\'></i> ";
		mensagem = icone + opt + " removido com sucesso!";
		callMessage(mensagem);
		
		xhttp.open("GET", "getpath.php?target=" + target + "&identify=" + opt + "&option=" + index + "&path=" + path + "&removeExc=" + name + "&dependence=" + depd, true);	
		xhttp.send();
		
		$('#'+this.id).fadeOut();
		fadeOut("homefooter");
		
	} else {
		//Executando a remoção de um elemento cadastrado-------------
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				document.getElementById("objOUs").innerHTML = "<div class='container'><div class='row'>" + this.responseText + "</div></div>";
			} else {
				//document.getElementById("objOUs").innerHTML = "<div align='center' style='margin-top:50px;'><i class='spinner_fst fa fa-circle-o-notch fa-4x blue-gradient'></i><span class='sr-only'>Loading...</span><div>";
			}
		};
		
		icone = "<i class=\'fa fa-check xxl green\'></i> ";
		mensagem = icone + opt + " removido com sucesso!";
		callMessage(mensagem);
		xhttp.open("GET", "getpath.php?target=" + target + "&option=" + index + "&path=" + path + "&remove=" + name + "&identify=" + opt + "&dependence=" + depd, true);
				
		xhttp.send();
		
		$('#'+this.id).fadeOut();
		fadeOut("homefooter");
	}
}
//#################################################################################################
function getNavigatorPath(target){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			document.getElementById("ADDive-Body").innerHTML = this.responseText;
		} else {
			document.getElementById("ADDive-Body").innerHTML = "<div align='center' class='centralized' style='margin-top:50px; opacity:0.7;'><img src='./images/load.png' class='spinner_fst' style='width:100px; height:100px;'></i><span class='sr-only'>Loading...</span></div>";
		}
	};
	xhttp.open("GET", "navigator.php?targetOU=" + target, true);
	xhttp.send();
	
	$('#'+this.id).fadeOut();
	fadeOut("homefooter");
}
function startDistribution(opt){
	var allSelectors = document.getElementsByClassName('selectedField').length;
	var sortObj = "";
	var action = true;
	var target = "administrative";
	var path = "DC=call,DC=br";
	for(var $i = 0; $i < allSelectors; $i++){
		if(document.getElementsByClassName('selectedField')[$i].value == ""){
			action = false;
			mensagem = "<i class=\'fa fa-times xxl red\'></i> Por favor defina a ordem dos campos corretamente!";
			callMessage(mensagem);
		}
		if(action){
			sortObj = sortObj + "<<" + document.getElementsByClassName('selectedField')[$i].value;
		}
	}
	//console.log(sortObj);
	var allLinked = document.getElementsByClassName('asignInput').length;
	var sortObjLnk = "";
	for(var $i = 0; $i < allLinked; $i++){
		sortObjLnk = sortObjLnk + "<<" + document.getElementsByClassName('asignInput')[$i].value;
	}
	//console.log(sortObjLnk);
	var objReturn = sortObj + "|@|" + sortObjLnk;
	//console.log(objReturn);
	if(action){
		//Executando a criação de um site/cliente-------------
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				document.getElementById("objOUs").innerHTML = "<div class='container'><div class='row'>" + this.responseText + "</div></div>";
			}
		};
		if (opt == "alterar"){
			mensagem = "<i class=\'fa fa-check xxl green\'></i> Definições alteradas com sucesso!";
			callMessage(mensagem);
		} else {
			mensagem = "<i class=\'fa fa-check xxl green\'></i> Definições alteradas com sucesso!<br />Os campos definidos agora estão disponíveis para configuração individual.";
			callMessage(mensagem);
		}
		xhttp.open("GET", "getpath.php?target=" + target + "&input=" + objReturn + "&path=" + path + "&option=" + "0", true);
		xhttp.send();
		
		$('#'+this.id).fadeOut();
		fadeOut("homefooter");
	}
}

//SLIDERS ===================================================================================
//Slide In
function inputArt(elem, elemId){
	$("#"+elem).animate({
		width: $('#'+elemId).width() + 15
	}, 300);
}
//Slide Out
function inputArtFade(elem){
	$("#"+elem).animate({
		width: 0
	}, 300);
}
//==========================================================================================
//Seleção de itens e limpeza de campos no Adição e Renomeação de máquinas===================
function renameselection(cls, name){
	var elem = document.getElementById(cls);
	var icon = document.getElementById(cls+'-icon');
	var anchor = document.getElementById(cls+'-anchor');
	
	var value = $("#"+cls).attr("data-value");
	var countSelect = document.querySelectorAll('.cardHoverBlue').length
	if(countSelect > 0){
		cleanrenameselection(cls);
	}
	if(elem != null){
		elem.classList.remove('cardHover');
		elem.classList.add('cardHoverBlue', 'white');
		icon.classList.add('fa-check', 'white');
		icon.classList.remove('fa-cube');
		anchor.classList.add('white');
		document.getElementById('value_selected').value = value;
		document.getElementById('value_name').value = name;
	}
	document.getElementById("page_next").focus();
}
function cleanrenameselection(cls){
	$allSelectors = document.querySelectorAll('.selectInput').length;
	for(var $i = 0; $i < $allSelectors; $i++){
		var elem = document.getElementsByClassName('selectInput')[$i];
		var icon = document.getElementsByClassName('selectIcon')[$i];
		var anchor = document.getElementsByClassName('selectAnchor')[$i];
		elem.classList.remove('cardHoverBlue', 'white');
		elem.classList.add('cardHover');
		icon.classList.remove('fa-check', 'white');
		icon.classList.add('fa-cube');
		anchor.classList.remove('white');
		document.getElementById('value_selected').value = "";
	}
}
//=============================================================================
//Navegação sessões de objetos=================================================
function nextpage(index, target, database){
	index++;
	var val = document.getElementById('value_selected').value;
	var valname = document.getElementById('value_name').value;
	if(val == ""){
		mensagem = "<i class=\'fa fa-times xxl red\'></i> Selecione uma opção!";
		callMessage(mensagem);
	} else {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				document.getElementById("objOUs").innerHTML = "<div class='container'><div class='row'>" + this.responseText + "</div></div>";
			} else {
				document.getElementById("objOUs").innerHTML = "<div align='center' class='centralized' style='margin-top:50px; opacity:0.7;'><img src='./images/load.png' class='spinner_fst' style='width:100px; height:100px;'></i><span class='sr-only'>Loading...</span></div>";
			}
		};
		
		xhttp.open("GET", "getpath.php?target=" + target + "&path=" + database + "&inputObj=" + val + "&inputObjName=" + valname + "&page=" + index, true);
		xhttp.send();
		
		$('#'+this.id).fadeOut();
		fadeOut("homefooter");
	}
	
}
function nextpageEnter(index, target, database, e){
	console.log(e.keyCode);
	if(e.keyCode === 13){
		index++;
		var val = document.getElementById('value_selected').value;
		if(val == ""){
			mensagem = "<i class=\'fa fa-times xxl red\'></i> Selecione uma opção!";
			callMessage(mensagem);
		} else {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){
					document.getElementById("objOUs").innerHTML = "<div class='container'><div class='row'>" + this.responseText + "</div></div>";
				} else {
					document.getElementById("objOUs").innerHTML = "<div align='center' class='centralized' style='margin-top:50px; opacity:0.7;'><img src='./images/load.png' class='spinner_fst' style='width:100px; height:100px;'></i><span class='sr-only'>Loading...</span></div>";
				}
			};
			
			xhttp.open("GET", "getpath.php?target=" + target + "&path=" + database + "&inputObj=" + val + "&page=" + index, true);
			xhttp.send();
			
			$('#'+this.id).fadeOut();
			fadeOut("homefooter");
		}
	}
}
function previouspage(index, target, database, val){
	index--;
	var value = val.split("-")[0];
	var prevName = val.split("-")[1];
	
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			document.getElementById("objOUs").innerHTML = "<div class='container'><div class='row'>" + this.responseText + "</div></div>";
		} else {
			document.getElementById("objOUs").innerHTML = "<div align='center' class='centralized' style='margin-top:50px; opacity:0.7;'><img src='./images/load.png' class='spinner_fst' style='width:100px; height:100px;'></i><span class='sr-only'>Loading...</span></div>";
		}
	};
	xhttp.open("GET", "getpath.php?target=" + target + "&path=" + database + "&inputObj=" + value + "&inputObjName=" + prevName + "&page=" + index, true);
	xhttp.send();
	
	$('#'+this.id).fadeOut();
	fadeOut("homefooter");
}

//=============================================================================
//Seleção de itens e limpeza de campos no DIVE=================================
function makeselection(cls){
	var elem = document.getElementById(cls);
	var icon = document.getElementById(cls+'-icon');
	var anchor = document.getElementById(cls+'-anchor');
	var dn = $("#"+cls).attr("data-path");
	var value = $("#"+cls).attr("data-value");
	var countSelect = document.querySelectorAll('.cardHoverBlue').length
	if(countSelect > 0){
		cleanselection(cls);
	}
	if(elem != null){
		elem.classList.remove('cardHover');
		elem.classList.add('cardHoverBlue', 'white');
		icon.classList.add('fa-check', 'white');
		icon.classList.remove('fa-folder-open', 'yellow');
		anchor.classList.add('white');
		document.getElementById('path').value = dn;
		document.getElementById('folder').value = value;
	}
}
function cleanselection(cls){
	$allSelectors = document.querySelectorAll('.selectInput').length;
	for(var $i = 0; $i < $allSelectors; $i++){
		var elem = document.getElementsByClassName('selectInput')[$i];
		var icon = document.getElementsByClassName('selectIcon')[$i];
		var anchor = document.getElementsByClassName('selectAnchor')[$i];
		elem.classList.remove('cardHoverBlue', 'white');
		elem.classList.add('cardHover');
		icon.classList.remove('fa-check', 'white');
		icon.classList.add('fa-folder-open', 'yellow');
		anchor.classList.remove('white');
		document.getElementById('path').value = "";
		document.getElementById('folder').value = "";
	}
}
//=============================================================================
//Seleciona arquivo============================================================
function selectFile(file, target){
	var val = document.getElementById(file).value;
	val = val.replace("C:\\fakepath\\", "");
	document.getElementById(target).value = val;
}
//=============================================================================
//Upload de arquivo============================================================
function uploadFile(target, database, file){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			document.getElementById("objOUs").innerHTML = "<div class='container'><div class='row'>" + this.responseText + "</div></div>";
		} else {
			document.getElementById("objOUs").innerHTML = "<div align='center' class='centralized' style='margin-top:50px; opacity:0.7;'><img src='./images/load.png' class='spinner_fst' style='width:100px; height:100px;'></i><span class='sr-only'>Loading...</span></div>";
		}
	};
	xhttp.open("GET", "getpath.php?target=" + target + "&path=" + database, true);
	xhttp.send();
	
	$('#'+this.id).fadeOut();
	fadeOut("homefooter");
}
//=============================================================================
//Gira qualquer elemento 180º==================================================
function turnChevron(itemId){
	var elem = document.getElementById(itemId);
	if(hasClass(elem, 'turn180')){
		elem.classList.remove('turn180');
		elem.classList.add('turn0');
	} else {
		elem.classList.remove('turn0');
		elem.classList.add('turn180');
	}
}
//=============================================================================
//Executa toggle e slide de classe em determinado elemento=====================
function slideMain(cls, clsName, clsToggle, clsitem){
	var elem = document.getElementById(cls);
	toggleClass(cls, clsName);
	if(hasClass(elem, clsName)){
		$('#'+cls).slideUp(500);
	} else {
		$('#'+cls).slideDown(500);
	}
	$("#"+clsToggle).fadeIn(500);
	turnChevron(clsitem);
}
//=============================================================================
//Executa toggle de classe em determinado elemento=============================
function toggleClass(itemId, cls){
	var elem = document.getElementById(itemId);
	if(hasClass(elem, cls)){
		elem.classList.remove(cls);
	} else {
		elem.classList.add(cls);
	}
}
//=============================================================================
//Verifica se o elemento possui determinada classe=============================
function hasClass(element, cls) {
    return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
}
//=============================================================================
//TOOLTIP FUNCTIONS============================================================
var lastScrolledLeft = 0;
var lastScrolledTop = 0;
var xMousePos = 0;
var yMousePos = 0;
function popTip(cls){
	
	$(window).scroll(function(event) {
        if(lastScrolledLeft != $(document).scrollLeft()){
            xMousePos -= lastScrolledLeft;
            lastScrolledLeft = $(document).scrollLeft();
            xMousePos += lastScrolledLeft;
        }
        if(lastScrolledTop != $(document).scrollTop()){
            yMousePos -= lastScrolledTop;
            lastScrolledTop = $(document).scrollTop();
            yMousePos += lastScrolledTop;
        }
        //window.status = "x = " + xMousePos + " y = " + yMousePos;
		//console.log(window.status);
    });
	$("#"+cls).on( "mousemove", function( event ) {
		//console.log(event.pageY-yMousePos);
		var msg = $("#"+cls).attr("data-msg");
		document.getElementById('tooltipText').innerHTML = msg;
		var lenX = ($("#tooltip-ex").width()/2);
		$("#tooltip-ex").css({display: 'inline-block', margin: '25px -'+lenX+'px', opacity: 1, position: 'fixed', top: event.pageY-yMousePos, left: event.pageX-xMousePos});
	});
}

//=============================================================================
//Trata Selects================================================================
function changeInput(cls){
	var elem = document.getElementById(cls);
	var selected =  elem.value;
	var splitSelect = selected.split("||");
	selected = splitSelect[1];
	var val;
	var valcls;
	var splitSelectVal;
	//console.log(elem.id + ":" + selected);
	var allSelectors = document.querySelectorAll('.selectedField');
	for(var $i = 0; $i < allSelectors.length; $i++){
		valcls = document.getElementsByClassName('selectedField')[$i];
		val = valcls.value;
		splitSelectVal = val.split("||");
		val = splitSelectVal[1];
		if(val == selected && elem.id != valcls.id){
			valcls.value = "";
		}
		//console.log(val + " = " + selected + "  |  " + elem.id + " = " + valcls.id);
	}
}
//=============================================================================
//Trata Input Asign============================================================
function asign(cls){
	var elem = document.getElementById(cls);
	var val = $("#"+cls).attr("data-value");
	document.getElementById("div_"+cls).innerHTML = "<a class='pointer' id='" + cls + "' data-value='" + cls + "' onclick='unsign(\"" + cls + "\")'><span class='fa fa-check-circle green'></span> Vinculado</a>";
	document.getElementById("asign_"+cls+"_Input").value = val;
}
function unsign(cls){
	document.getElementById("div_"+cls).innerHTML = "<a class='pointer' id='" + cls + "' data-value='" + cls + "' onclick='asign(\"" + cls + "\")'><span class='fa fa-times-circle red'></span> Não Vinculado</a>";
	document.getElementById("asign_"+cls+"_Input").value = "";
}
//=============================================================================
//Funções básicas aplicáveis à campos coletados por resultados em AJAX=========
function fadeOut(cls){
	$('#'+cls).fadeOut(500);
}
function fadeOut0(cls){
	$('#'+cls).fadeOut(0);
}
function fadeIn(cls){
	$('#'+cls).fadeIn(500);
}
function slideUp(cls){
	$('#'+cls).slideUp(500);
}
function slideDown(cls){
	$('#'+cls).slideDown(500);
}
function openLoad(){
	$("#loading").fadeIn();
}
function onhover(id){
	$('#'+id).fadeIn(300);
}
//=============================================================================
//Função de teste==============================================================
function getPosition(cls){
	var elem = document.getElementById(cls);
	var position = $("#"+cls).position();
	var posX = position.left;
	posY = ($("#"+cls).offset().top - $(window).scrollTop());
	var posY = position.top;
	var lenX = $("#"+cls).width();
	var lenY = $("#"+cls).height();
	alert("posX: "+posX+", "+"posY: "+posY+", "+"lenX: "+lenX+", "+"lenY: "+lenY);
	$(".popover").css({top: lenY+posY+50, left: lenX+posX+100, display: 'block'});
}
//=============================================================================
//RELATÓRIOS
//=============================================================================
var $retry = 0;
function relatorio(target){
	if($retry == 0){
		document.getElementById("ETcanvas_info").innerHTML = "<div class='' style='margin-top:20px; margin-left:-60px; opacity:0.8;'><img src='./images/load.png' class='spinner_fst' style='width:90px; height:90px;'></i><span class='sr-only'>Loading...</span></div>";
		document.getElementById("SVcanvas_info").innerHTML = "<div class='' style='margin-top:20px; margin-left:-60px; opacity:0.8;'><img src='./images/load.png' class='spinner_fst' style='width:90px; height:90px;'></i><span class='sr-only'>Loading...</span></div>";
		document.getElementById("QTcanvas_info").innerHTML = "<div align='center' style='margin-bottom:150px; opacity:0.8;'><img src='./images/load.png' class='spinner_fst' style='width:90px; height:90px;'></i><span class='sr-only'>Loading...</span></div>";
		document.getElementById("Offcanvas_info").innerHTML = "<div align='center' style='margin-bottom:150px; opacity:0.8;'><img src='./images/load.png' class='spinner_fst' style='width:90px; height:90px;'></i><span class='sr-only'>Loading...</span></div>";
	}
	$.post( "reports.php", { report: target },
	function(data,status){
		try{
			obj = JSON.parse(data);
			loadGraph(target, obj, data, status);
		} catch(err) {
			$retry++;
			console.log("Erro ao coletar página reports.php: " + target);
			relatorio(target);
		}
	});
}

function loadGraph(target, obj, data, status){
			$retry = 0;
			if(status == "success"){
				if(target == 1){
					if(obj.length > 0 && data != null){
						var chart = document.getElementById('ETChart');
						var chartData = {
								labels: [],
								datasets: [
									{
										data: [],
										label: "Estações de Trabalho",
										backgroundColor: [],
										borderColor: [],
										hoverBackgroundColor: [],
										borderWidth: 2
									}]
							};
						var red;
						var green;
						var blue;
						var color = [];											
						for(var $j = 0; $j < obj.length; $j++){
							blue = Math.floor((Math.random() * 200) + 50);
							red = Math.floor((Math.random() * 200) + 50);
							green = Math.floor((Math.random() * 250) + 50);
							color[$j] = "color:rgba("+red+","+green+","+blue+",1);";
							chartData.labels[$j] = obj[$j].split(":")[0];
							chartData.datasets[0].data[$j] = obj[$j].split(":")[1];
							chartData.datasets[0].backgroundColor[$j] = "rgba("+red+","+green+","+blue+",0.7)";
							chartData.datasets[0].borderColor[$j] = "rgba("+red+","+green+","+blue+",1)";
							chartData.datasets[0].hoverBackgroundColor[$j] = "rgba("+red+","+green+","+blue+",1)";
						};
							
						var myChart = new Chart(chart, {
							type: 'pie',
							data: chartData,
							options: {
								animation: {
									duration: 1000
								},
								legend:{
									display: false
								}
							}
						});
						
						var bg;
						var total = 0;
						var datainsert = "<div class='row'><div class='col-xs-12 blue'>Sistemas Operacionais <span class='blue' style='position:absolute; right:5px;'>Qtd.</span></div>";
						for(var $i = 0; $i < chartData.labels.length; $i++){
							bg = "transparent";
							if($i%2 == 0){
								bg = "rgba(200,200,240,0.5)";
							}
							datainsert+= "<div class='col-xs-12' style='background-color:" + bg + "'><span class='fa fa-square' style='" + color[$i] + "'></span> " + chartData.labels[$i] + ": <span class='blue' style='position:absolute; right:5px;'>" + chartData.datasets[0].data[$i] + "</span></div>";
							total += parseInt(chartData.datasets[0].data[$i]);
						}
						datainsert+= "<hr /><div class='col-xs-12 red'>TOTAL: <span class='red' style='position:absolute; right:5px;'>" + total + "</span></div>";
						datainsert+= "</div>";
						document.getElementById("ETcanvas_info").innerHTML = datainsert;
					}
				} else if (target == 2){
					if(obj.length > 0 && data != null){
						var chart = document.getElementById('SVChart');
						var chartData = {
								labels: [],
								datasets: [
									{
										data: [],
										label: "Servidores",
										backgroundColor: [],
										borderColor: [],
										hoverBackgroundColor: [],
										borderWidth: 2
									}]
							};
						var red;
						var green;
						var blue;
						var color = [];
						for(var $j = 0; $j < obj.length; $j++){
							red = Math.floor((Math.random() * 200) + 50);
							green = Math.floor((Math.random() * 200) + 50);						
							blue = Math.floor((Math.random() * 200) + 50);
							color[$j] = "color:rgba("+red+","+green+","+blue+",1);";
							chartData.labels[$j] = obj[$j].split(":")[0];
							chartData.datasets[0].data[$j] = obj[$j].split(":")[1];
							chartData.datasets[0].backgroundColor[$j] = "rgba("+red+","+green+","+blue+",0.7)";
							chartData.datasets[0].borderColor[$j] = "rgba("+red+","+green+","+blue+",1)";
							chartData.datasets[0].hoverBackgroundColor[$j] = "rgba("+red+","+green+","+blue+",1)";
						};
							
						var myChart = new Chart(chart, {
							type: 'pie',
							data: chartData,
							options: {
								animation: {
									duration: 1000
								},
								legend:{
									display: false
								}
							}
						});
						
						var bg;
						var total = 0;
						var datainsert = "<div class='row'><div class='col-xs-12 blue'>Siatemas Operacionais <span class='blue' style='position:absolute; right:5px;'>Qtd.</span></div>";
						for(var $i = 0; $i < chartData.labels.length; $i++){
							bg = "transparent";
							if($i%2 == 0){
								bg = "rgba(200,200,240,0.5)";
							}
							datainsert+= "<div class='col-xs-12' style='background-color:" + bg + "'><span class='fa fa-square' style='" + color[$i] + "'></span> " + chartData.labels[$i] + ": <span class='blue' style='position:absolute; right:5px;'>" + chartData.datasets[0].data[$i] + "</span></div>";
							total += parseInt(chartData.datasets[0].data[$i]);
						}
						datainsert+= "<hr /><div class='col-xs-12 red'>TOTAL: <span class='red' style='position:absolute; right:5px;'>" + total + "</span></div>";
						datainsert+= "</div>";
						document.getElementById("SVcanvas_info").innerHTML = datainsert;
					}
					
				} else if (target == 3){
					if(obj.length > 0 && data != null){
						var chart = document.getElementById('QTChart');
						var chartData = {
								labels: [],
								datasets: [
									{
										data: [],
										label: "Máquinas Adicionadas",
										backgroundColor: [],
										borderColor: [],
										hoverBackgroundColor: [],
										borderWidth: 2
									}]
							};
							
							for(var $j = 0; $j < obj.length; $j++){
								chartData.labels[$j] = obj[$j].split(":")[0];
								chartData.datasets[0].data[$j] = obj[$j].split(":")[1];
								chartData.datasets[0].backgroundColor[$j] = "rgba(0,120,200,0.7)";
								chartData.datasets[0].borderColor[$j] = "rgba(0,120,200,1)";
								chartData.datasets[0].hoverBackgroundColor[$j] = "rgba(0,120,200,1)";
							}
						var myChart = new Chart(chart, {
							type: 'bar',
							data: chartData,
							options: {
								animation: {
									duration: 1000
								},
								legend:{
									display: false
								}
							}
						});
						
						var bg;
						var datainsert = "<hr /><div class='row'><div class='col-xs-12 blue'>Data <span style='position:absolute; right:5px;'>Qtd.</span></div>";
						for(var $i = 0; $i < chartData.labels.length; $i++){
							bg = "transparent";
							if($i%2 == 0){
								bg = "rgba(200,200,240,0.5)"
							}
							datainsert+= "<div class='col-xs-12' style='background-color:" + bg + "'>" + chartData.labels[$i] + ": <span style='position:absolute; right:5px;' align='center' class='blue'>" + chartData.datasets[0].data[$i] + "</span></div>";
						}
						datainsert+= "</div>";
						document.getElementById("QTcanvas_info").innerHTML = datainsert;
					}
				} else if (target == 4){
					if(obj.length > 0 && data != null){
						var chart = document.getElementById('OffChart');
						var chartData = {
								labels: [],
								datasets: [
									{
										data: [],
										label: "Quantitativo",
										backgroundColor: [],
										borderColor: [],
										hoverBackgroundColor: [],
										borderWidth: 2
									}]
							};
							var red;
							var green;
							var blue;
							var color = [];
							for(var $j = 0; $j < obj.length; $j++){
								red = Math.floor((Math.random() * 200) + 50);
								green = Math.floor((Math.random() * 200) + 50);						
								blue = Math.floor((Math.random() * 200) + 50);
								color[$j] = "color:rgba("+red+","+green+","+blue+",1);";
								chartData.labels[$j] = obj[$j].split(":")[0];
								chartData.datasets[0].data[$j] = obj[$j].split(":")[1];
								chartData.datasets[0].backgroundColor[$j] = "rgba("+red+","+green+","+blue+",0.7)";
								chartData.datasets[0].borderColor[$j] = "rgba("+red+","+green+","+blue+",1)";
								chartData.datasets[0].hoverBackgroundColor[$j] = "rgba("+red+","+green+","+blue+",1)";
							}
						var myChart = new Chart(chart, {
							type: 'doughnut',
							data: chartData,
							options: {
								animation: {
									duration: 1000
								},
								legend:{
									display: false
								}
							}
						});
						var bg;
						var total = 0;
						var inativas = 0;
						var datainsert = "<hr /><div class='row'><div class='col-xs-12 blue'>Data <span class='blue' style='position:absolute; right:5px;'>Qtd.</span></div>";
						for(var $i = 0; $i < chartData.labels.length; $i++){
							bg = "transparent";
							if($i%2 == 0){
								bg = "rgba(200,200,240,0.5)"
							}
							total += parseInt(chartData.datasets[0].data[$i]);
							if($i != (chartData.labels.length - 1)){
								datainsert+= "<div class='col-xs-12' style='background-color:" + bg + "'><span class='fa fa-square' style='" + color[$i] + "'></span> " + chartData.labels[$i] + ": <span class='blue' style='position:absolute; right:5px;'><a href='#!' class='blue' data-toggle='modal' data-target='#report_modal' onclick='callList(\"" + chartData.labels[$i].split(" ")[0] + "\")'>" + chartData.datasets[0].data[$i] + " <span class='fa fa-file'></span></a></span></div>";
								inativas += parseInt(chartData.datasets[0].data[$i]);
							} else {
								datainsert+= "<div class='col-xs-12' style='background-color:transparent;'><span class='fa fa-power-off green'></span> " + chartData.labels[$i] + ": <span class='black' style='position:absolute; right:5px;'>" + chartData.datasets[0].data[$i] + "</span></div>";
							}
						}
						datainsert+= "<div class='col-xs-12'><span class='fa fa-power-off red'></span> Inativas: <span class='black' style='position:absolute; right:5px;'>" + inativas + "</span></div>";
						datainsert+= "<div class='col-xs-12 red'>TOTAL: <span class='red' style='position:absolute; right:5px;'>" + total + "</span></div>";
						datainsert+= "</div>";
						document.getElementById("Offcanvas_info").innerHTML = datainsert;
					}
				}
			} else {
				console.log("ERROR");
			}
}

function callList(target){
	console.log(target);
	$.post( "machinelist.php", { report: target },
		function(data,status){
			obj = JSON.parse(data);
			var datainsert = "";
			var computer;
			var timestamp;
			var os;
				if(status == "success"){
					for(var $i = 0; $i < obj.length; $i++){
						computer = obj[$i].split("|")[0];
						os = obj[$i].split("|")[1];
						timestamp = obj[$i].split("|")[2];
						datainsert += "<div class='col-md-6'><div class='cardStyle' style='margin:5px;'>" + (parseInt($i)+1) + ". <br /><span class='blue fa fa-desktop'></span> <b>Nome:</b> " + computer + "<br /><span class='green fa fa-microchip'></span> <b>SO:</b> " + os + "<br /><span class='red fa fa-clock-o'></span> <b>Último Logon:</b> " + timestamp + "</div></div>"; 
					}
					document.getElementById("report-Body").innerHTML = datainsert;
					document.getElementById("report-Header").innerHTML = "<span class='fa fa-desktop'></span> Máquinas offline a " + target + " dias <a href='pdfFile.php?content=" + target + "' class='btn btn-danger' target='_blank' style='position:absolute; right:5px;'><span class='fa fa-download'></span> Arquivo</a>";
				} else {
					console.log("ERROR");
				}
		}
	);
}

function verifyElement(elemtype){
	var verifyCanvas = document.createElement(elemtype);
	var verifyedCanvas = (verifyCanvas.getContext)? true : false;
	if(verifyedCanvas){
		var contentDiv = document.getElementById("ET"+elemtype+"_graph");
		if(contentDiv != null){
			var myChart = $("#ETChart");
			if(myChart != null){
				relatorio(1);
			}
			var myChart = $("#SVChart");
			if(myChart != null){
				relatorio(2);
			}
			var myChart = $("#QTChart");
			if(myChart != null){
				relatorio(3);
			}
			var myChart = $("#OffChart");
			if(myChart != null){
				relatorio(4);
			}
		}
	} else {
		var mydiv = document.getElementById(elemtype+"_graph");
		if(mydiv != null){
			mydiv.innerHTML = "<span class='fontPlay red'>Não há suporte a gráficos nesta versão do seu navegador.</span>";
		}
	}
	clearInterval(verifyInt);
}

function verifyExists(cls){
	//var verify = document.createElement(elemtype);
	var verify = document.getElementById(cls);
	var verifyed = (verify.getContext)? true : false;
	if(verifyed){
		console.log("Existe");
	} else {
		console.log("Não Existe");
	}
}

//Simula eventos-----------------------------------------------------
function eventFire(elem, etype){
  if (elem.fireEvent) {
    elem.fireEvent('on' + etype);
  } else {
    var evObj = document.createEvent('Events');
    evObj.initEvent(etype, true, false);
    elem.dispatchEvent(evObj);
  }
}

//Verifica existencia de campos em intervalos-----------------------
function verifyInterval(opt){
	clearInterval(verifyInt);
	verifyInt = setInterval(function(){
		verifyElement(opt);
	}, 500);
}


	

