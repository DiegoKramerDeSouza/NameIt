<?php
	require_once("pageInfo.php");
	if(!file_exists("./MDDB/init.mddb")){
		file_put_contents("./MDDB/init.mddb", "V1ZkU2RHRlhOWEJqTTFKNVdWZFNkbU5uUFQwPXx8VVVkU2RHRlhOVUZhUnpGd1ltYzlQUT09");
	}
?>
<!DOCTYPE html>
<html>
	<?php
		echo $htmlHeader;
	?>
	<body>
		<?php 
			echo $pageLoader;
			echo $osWarning;
			echo $pageNavbar;
			echo $msgbox;
		?>
		
		<div id="imgBG">
			<img src="../images/flux.png" id="bgLayer" />
		</div>
		<div class="fontPlay" style="margin-top:100px;">
			<div class="centro col-xs-12 col-sm-8 col-md-6 col-lg-5 col-sm-push-2 col-md-push-5 col-lg-push-6" id="loginPanel">
				<div class="cardStyle">
					<form method="post" name="login" id="login" action="access.php">
						<div class="padding10">
							<div class="row">
								<div class="col-sm-6" >
									<span class="blue" style="font-size:36px;">Login</span>
								</div>
								<div class="col-sm-6" style="border-left: 1px solid rgba(80,80,80,0.2);">
									<div class="row">
										<div class="col-xs-1 col-sm-2 col-md-2">
											<label for="user" class='top10'><span class="fa fa-user fa-lg black"></span></label>
										</div>
										<div class="col-xs-11 col-sm-10 col-md-10">
											<input type="text" name="user" class="inputArt" id="user" required placeholder="Usuário" maxlength="15" />
											<div class="slider" id="user_slider"></div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-1 col-sm-2 col-md-2">
											<label for="password" class='top10'><span class="fa fa-lock fa-lg black"></span></label>
										</div>
										<div class="col-xs-11 col-sm-10 col-md-10">
											<input type="password" name="password" class="inputArt" id="password" required placeholder="Senha" />
											<div class="slider" id="password_slider"></div>
										</div>
									</div>
									<input type="hidden" class="validade" name="browser" id="browser" value="" />
								</div>
							</div>
						</div>
						<div class="padding10" align="right" style="max-height:60px;">
							<input type="submit" class="btn btn-info" style='border: 0px;' value="Acessar" id="Access" name="Access" />
						</div>
					</form>
				</div>
				<div class="sm" style="margin-left:10px;">Esta é a interface de configuração do sistema <a href="../index.php" target="_self">NameIT</a>.</div>
			</div>			
		</div>
				
		<div id="help">
			<a href="#"><span class="white fa fa-question xl"></span></a>
		</div>
		<div class="bootomSingle" id="homefooter" style="position:fixed; bottom:0px; width:100%;">
			<?php 
				echo $pageFooter;
			?>
		</div>
		<script src='../Scripts/jquery-2.1.4.min.js'></script>
		<script src='../Scripts/bootstrap.min.js'></script>
		<script src='../Scripts/bootstrap-toggle.min.js'></script>
		<script src='../scripts/animated.js'></script>
		
		<script>
			$(document).ready(function(){
				
				navigator.sayswho= (function(){
					var ua= navigator.userAgent, tem,
					M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
					if(/trident/i.test(M[1])){
						tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
						return 'IE';
					}
					if(M[1]=== 'Chrome'){
						tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
						if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
					}
					M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
					if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);
					return M.join(' ');
				})();
							
				var OSName='Valid';
				if (navigator.appVersion.indexOf("Windows NT 5.0")!=-1) OSName="Windows 2000";
				if (navigator.appVersion.indexOf("Windows NT 5.1")!=-1) OSName="Windows XP";
				if (navigator.appVersion.indexOf("Windows NT 5.2")!=-1) OSName="Windows XP";
				if (navigator.appVersion.indexOf("Windows NT 5.3")!=-1) OSName="Windows XP";
				if (navigator.appVersion.indexOf("Windows NT 5.4")!=-1) OSName="Windows XP";
				if (navigator.appVersion.indexOf("Windows NT 5.5")!=-1) OSName="Windows XP";
				if (navigator.appVersion.indexOf("Windows NT 5.6")!=-1) OSName="Windows XP";
				if (navigator.appVersion.indexOf("Windows NT 5.7")!=-1) OSName="Windows XP";
				if (navigator.appVersion.indexOf("Windows NT 5.8")!=-1) OSName="Windows XP";
				if (navigator.appVersion.indexOf("Windows NT 5.9")!=-1) OSName="Windows XP";
				if (navigator.appVersion.indexOf("Windows NT 6.0")!=-1) OSName="Windows Vista";
				document.forms['login']['browser'].value = OSName;
				if(OSName != "Valid"){
					$("#warning").fadeIn();
				}
				
				document.querySelector('#user').focus();
				$("#user_slider").animate({
					width: $('#user').width()
				}, 300);
				
				$(".form-control").focus(function(){
					var elemId = this.id;
					$("#"+elemId+"_slider").animate({
						width: $('#'+elemId).width()
					}, 300);
				})
				$(".form-control").blur(function(){
					var elemId = this.id;
					$("#"+elemId+"_slider").animate({
						width: 0
					}, 300);
				})
				
			});
		</script>
	</body>