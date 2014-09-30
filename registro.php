<?php
	require_once('recaptchalib.php');
	$publickey = "6Ld1EeASAAAAACwrO30LfF8lon3BWvVFEbiv-wCH";
	$privatekey = "6Ld1EeASAAAAAA_fKr4BUDecKos60eQyWYfMRFdo";
	
	// Comprueba si se ha enviado una respuesta al captcha y la imagen del captcha
	if(isset($_POST["recaptcha_response_field"])){
		if (isset($_POST["recaptcha_challenge_field"])){
			
			// Verifica si la solución al captcha es correcta
			$resp = recaptcha_check_answer ($privatekey,
				$_SERVER["REMOTE_ADDR"],
				$_POST["recaptcha_challenge_field"],
				$_POST["recaptcha_response_field"]);
			if ($resp->is_valid){
                		
				// Comprueba si se envió un valor para email y si el email es válido
				if(isset($_POST["email"])){  
        				if(filter_var($_POST["email"], FILTER_SANITIZE_EMAIL)){
						
						// Comprueba si se envió un valor para URL y si la URL es válida
						if(isset($_POST["url"])){ 
							if(filter_var($_POST["url"], FILTER_VALIDATE_URL)){
								
								// Inicia la secuencia de condiciones para el registro
								$hora=time();	//Hora actual - es un numero entero
								$ini_dia=$hora-$hora%86400;	//Inicio del dia
								$ini_reg=$ini_dia+(7*60*60);	//Inicia el registro de URLs - 07:00
								$fin_reg=$ini_dia+(23.85*60*60);	//Finaliza el registro de sitios - 23:51
								$ult_pre=$ini_dia+(24*60*60)-30;	//Última presentación - 23:59:30

								// Comprueba si la hora de registro está entre las 7:00 y 23:51
								if (($hora>$ini_reg) && ($hora<$fin_reg)) {
			
									// Comprueba si aún hay espacio en la base de datos para registrar 
									mysql_connect('172.16.17.214','frnoviyo','frnoviyo')or die ('Ha fallado la conexi&oacute;n a la Base de Datos');
									mysql_select_db('scom_publi')or die ('Error al seleccionar la Base de Datos');
									$consulta= mysql_query("SELECT * FROM maestra WHERE hora_pu=".$ult_pre);
									//mysql_close;
									if (mysql_num_rows($consulta)==0) {

										// Limita el número de registros por IP a 4
										$consulta= mysql_query("SELECT * FROM maestra WHERE IP='".$_SERVER['REMOTE_ADDR']."'");
										if (mysql_num_rows($consulta)<5) {
											
											// Limita el número de registros por Email a 4
											$consulta= mysql_query("SELECT * FROM maestra WHERE email='".$_POST["email"]."'");
											if (mysql_num_rows($consulta)<5) {
												//Inicia la secuencia de condiciones de comprobación de URL
												$rep_dom=6;	//Repeticiones por dominio
												$rep_url=6;	//Repeticiones de URL
												$rep_con=0;	//Repeticiones continuas de url
												
												echo "Hasta aqu&iacute; todo bien!</br>";
											}
											else {
												echo "Lo sentimos, ya hemos recibido demasiados registros con este email: ".$_POST["email"];
											}
										}
										else {
											echo "Lo sentimos, ya hemos recibido demasiados registros desde su IP: ".$_SERVER['REMOTE_ADDR'];
										}
									}
									else {
										echo "Lo sentimos pero se han completado los registros por hoy";
									}
									mysql_close();
								}
								else {
									echo "Fuera de la hora de presentación. Son las: ".date("H:i:s", $hora)." que equivale a ".$hora;
								}
							}
							else {
               							echo "Mal URL!</br>";
        						}
						}
						else {
							echo "No hay URL!</br>";
						}
					}
					else {
               					echo "Mal Email!</br>";
        				}
        				
				}
				else {
               				echo "No hay Email!</br>";
        			}
				
        		} 
			else {
               			echo "Mal Captcha!";
        		}
		}
	}
?>