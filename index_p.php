<?php
	$hora=time();	//Hora actual - es un numero entero
	$ini_dia=$hora-$hora%86400;	//Inicio del dia
	$ini_reg=$ini_dia+(7*60*60);	//Inicia el registro de URLs - 07:00
	$ini_pre=$ini_dia+(8*60*60);	//Inicia la publicación de sitios - 08:00
	$ult_pre=$ini_dia+(24*60*60)-30;	//Última presentación - 23:59:30
	if ($hora<$ini_pre) {
		$URL="http://localhost/30s.com.ec/en_espera.php";	//Pagina de espera hasta que inicie la publicacion
	}
	else {
		mysql_connect('localhost','scom_jefe','2H#^[AG0aoVk')or die ('Ha fallado la conexi&oacute;n a la Base de Datos');
		mysql_select_db('scom_publi')or die ('Error al seleccionar la Base de Datos');
		$periodo=$hora-$hora%30;	//Determina el inicio del periodo de 30 segundos actual
		$consulta= mysql_query("SELECT * FROM maestra WHERE hora_pu=".$periodo);
		if (mysql_num_rows($consulta)==1) {
			$fila=mysql_fetch_array($consulta);
			$URL=$fila['URL'];  //"http://localhost/fondo/index.php";
			$visitas=$fila['visitas']+1;
			$consulta= mysql_query("UPDATE  `scom_publi`.`maestra` SET  `visitas` =  '".$visitas."' WHERE  `maestra`.`id` =".$fila[id]);
		}
		else {
			$consulta= mysql_query("SELECT * FROM alterna WHERE hora_pu=".$periodo);
			if (mysql_num_rows($consulta)==1) {
				$fila=mysql_fetch_array($consulta);
				$URL=$fila['URL'];  //"http://localhost/fondo/index.php";
				$visitas=$fila['visitas']+1;
				$consulta= mysql_query("UPDATE  `scom_publi`.`alterna` SET  `visitas` =  '".$visitas."' WHERE  `alterna`.`id` =".$fila[id]);
			}
			else {
				print "Nada que presentar a las ".date("H:i:s", $periodo)." que equivale a ".$periodo;
			}
		}
		mysql_close;
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<script type="text/javascript" src="./js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="./js/scom.js"></script>
		<link rel="stylesheet" type="text/css" href="./css/estilo.css">
		<script type="text/javascript">
			var RecaptchaOptions = {
			theme : 'custom',
			custom_theme_widget: 'recaptcha_widget',
			};
		</script>
		<title> Un sitio diferente cada 30 segundos </title>
	</head>
	<body id="fondo">
		<div lang="es" id="barra_sup">
			<img src="./imagenes/logo.png" alt="30s.com.ec" style="vertical-align: 1px; margin-right: 0.5%;">
			<img src="./imagenes/separador.png" alt="separador" style="margin-right: 0.5%; vertical-align: -6px;">
			<span id="slogan">Un sitio diferente cada 30 segundos</span>
			<a href="http://localhost/30s.com.ec" title="Recargar"><img src="./imagenes/recargar.png" alt="Recargar" style="margin-right: 5%; vertical-align: -1px;"></a>
			<span id="txt1">URL:</span>
			<a target="_blank" href="<?php echo $URL ?>" id="enlace1"><span id="enlace2" ><?php echo $URL ?></span></a>
			<div style="display:inline-block; margin-right: 0.5%;">
				<a href="#publicar"><ul id="publicar">
					<li><span style="color:#E6E6E6; text-shadow: 1px 1px 1px rgb(79, 133, 156);">Publicar</span></li>
					<li><img src="./imagenes/flecha.png" alt="Publicar"></li>
				</ul></a>
				<ul id="datos">
					<?php
						if ($hora<$ini_reg) {
							echo "<li style=\"float: left;padding-right: 9px;padding-left: 9px;padding-top: 8px; padding-bottom: 8px;\">Lo sentimos, pero el registro inicia a las 07:00</li>
							<li style=\"padding-right: 9px;padding-left: 9px;padding-top: 8px; padding-bottom: 8px;\">Por favor intente m&aacute;s tarde.</li>";
						}
						else {
							mysql_connect('localhost','scom_jefe','2H#^[AG0aoVk')or die ('Ha fallado la conexi&oacute;n a la Base de Datos');
							mysql_select_db('scom_publi')or die ('Error al seleccionar la Base de Datos');
							$consulta= mysql_query("SELECT * FROM maestra WHERE hora_pu=".$ult_pre);
							mysql_close;
							if (mysql_num_rows($consulta)>0) {
								echo "<li style=\"float: left;padding-right: 9px;padding-left: 9px;padding-top: 8px; padding-bottom: 8px;\">Lo sentimos, pero se han terminado los registros por hoy.</li>
							<li style=\"padding-right: 9px;padding-left: 9px;padding-top: 8px; padding-bottom: 8px;\">Por favor intente mañana desde las 07:00</li>";
							}
							else {
								echo "<form name=\"registro\" action=\"registro.php\" onsubmit=\"return validacion()\" method=\"post\" style=\"margin-bottom: 5px;\">
								<li style=\"float: left;padding-left: 9px;padding-top: 8px; padding-bottom: 8px; margin-top: 2px;\">URL:</li>
								<li style=\"float: left; padding-left: 11px; padding-top: 8px; padding-bottom: 8px;\"><input type=\"text\" name=\"url\" style=\"margin: 0px; color: grey; width: 165px;\" value=\"http://30s.com.ec\" onfocus=\"if(this.value=='http://30s.com.ec'){this.value=''; this.style.color='black';}\" onblur=\"if(this.value==''){this.value='http://30s.com.ec'; this.style.color='grey';} else if(this.value.indexOf('://')==-1){this.value='http://'+this.value;}\"></li>
								<li style=\"float: left;padding-left: 9px;padding-top: 5px; padding-bottom: 1px; margin-top: 2px;\">Email:</li>
								<li style=\"float: left; padding-left: 4px; padding-top: 5px; padding-bottom: 1px;\"><input type=\"text\" name=\"email\" style=\"margin: 0px;  color: grey; width: 165px;\" value=\"info@30s.com.ec\" onfocus=\"if(this.value=='info@30s.com.ec'){this.value=''; this.style.color='black';}\" onblur=\"if(this.value==''){this.value='info@30s.com.ec'; this.style.color='grey';}\"></li>
								<li style=\"font-size: 10px; display: inline;\">Sólo para envío de estadísticas, luego será eliminado</li>
								<li class=\"divisor\"></li>";
								echo ' <div id="recaptcha_widget" style="display:none">

								<div id="recaptcha_image"></div>
   								<div class="recaptcha_only_if_incorrect_sol" style="color:red">Por favor intente otra vez</div>

   								<span class="recaptcha_only_if_image">Inserte el texto:</span>
								<span class="recaptcha_only_if_audio">Inserte los números:</span>

								<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />

								<div><a href="javascript:Recaptcha.reload()">Recargar CAPTCHA</a></div>
								</div>
								<li class="divisor"></li>
								<input type="submit" value="Enviar">
								</form>

								<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=6Ld1EeASAAAAACwrO30LfF8lon3BWvVFEbiv-wCH"></script>
								<noscript>
								<iframe src="http://www.google.com/recaptcha/api/noscript?k=6Ld1EeASAAAAACwrO30LfF8lon3BWvVFEbiv-wCH" height="300" width="500" frameborder="0"></iframe><br>
								<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
								<input type="hidden" name="recaptcha_response_field" value="manual_challenge">
								</noscript>';
							}
						}
					?>
				</ul>
			</div>
			<img src="./imagenes/separador.png" alt="separador" style="margin-right: 0.5%; vertical-align: -6px;">
			<span id="sesion">Invitado</span>
			<img style="vertical-align: 1px;" src="./imagenes/invitado.png" alt="invitado">
		</div>
		<iframe id="objetos" src="<?php echo $URL ?>" frameborder="0" noresize="noresize" style="height: 328px;">
</iframe>
		<!-- <div id="objetos" style="margin: 0; width: 100%; min-height: 600px;">
		  <object id="objeto" type="text/html" data="<?php echo $URL ?>" style="width: 100%; min-height: 600px; margin: 0;"></object>
		</div> -->
		<div lang="es" id="barra_inf">
			<script type="text/javascript">
			//Presentación de la hora del servidor
			var currenttime = '<? print date("F d, Y H:i:s", time())?>'
			var serverdate=new Date(currenttime);
			//serverdate.setHours(serverdate.getHours()-1);
			
			function padlength(what){
			var output=(what.toString().length==1)? "0"+what : what;
			return output;
			}
			
			function displaytime(){
			serverdate.setSeconds(serverdate.getSeconds()+1);
			var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds());
			document.getElementById("hora").innerHTML=timestring;
			}
			
			//window.onload=function(){
			setInterval("displaytime()", 1000);
			altura();
			//}
			// Script obtenido en http://www.javascriptkit.com/script/script2/servertime.shtml
			</script>
			<span id="txt2">Hora del servidor:</span>
			<span id="hora"></span>
			<span id="txt2"><?php echo $_SERVER['REMOTE_ADDR'];?></span>
			<span id="derechos">Todos los derechos reservados 2011 Yamburara.com</span>

		</div>
	</body>
</html>