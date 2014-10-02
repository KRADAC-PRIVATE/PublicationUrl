<?php

include ('dll/config.php');
require_once('recaptchalib.php');
$publickey = "6Ld1EeASAAAAACwrO30LfF8lon3BWvVFEbiv-wCH";
$privatekey = "6Ld1EeASAAAAAA_fKr4BUDecKos60eQyWYfMRFdo";
$email = $_POST["email"];
$url = $_POST["url"];
if (!$mysqli = getConectionDb()) {
    echo " Ha fallado la conexi&oacute;n a la Base de Datos.";
} else {
    if (isset($_POST["recaptcha_response_field"])) {
        if (isset($_POST["recaptcha_challenge_field"])) {
            
            // Verifica si la solución al captcha es correcta
            $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
            if ($resp->is_valid) {
                $email = $_POST["email"];
                $url = $_POST["url"];
                // Comprueba si se envió un valor para email y si el email es válido
                if (isset($email)) {
                    if (filter_var($email, FILTER_SANITIZE_EMAIL)) {
                        
                        // Comprueba si se envió un valor para URL y si la URL es válida
                        if (isset($url)) {
                            if (filter_var($url, FILTER_VALIDATE_URL)) {

                                // Inicia la secuencia de condiciones para el registro
                                $hora = time(); //Hora actual - es un numero entero
                                $ini_dia = $hora - $hora % 86400; //Inicio del dia
                                $ini_reg = $ini_dia + (7 * 60 * 60); //Inicia el registro de URLs - 07:00
                                $fin_reg = $ini_dia + (23.85 * 60 * 60); //Finaliza el registro de sitios - 23:51
                                $ult_pre = $ini_dia + (24 * 60 * 60) - 30; //Última presentación - 23:59:30
                                // Comprueba si la hora de registro está entre las 7:00 y 23:51
                                if (($hora > $ini_reg) && ($hora < $fin_reg)) {

                                    // Comprueba si aún hay espacio en la base de datos para registrar                                 
                                    $consulta = "SELECT * FROM urls WHERE fecha_hora_publicacion=" . $ult_pre;//mysql_query("SELECT * FROM maestra WHERE hora_pu=" . $ult_pre);
                                    $result = $mysqli->query($consulta);
                                    if ($result->num_rows == 0) {

                                        // Limita el número de registros por IP a 4
                                        $consulta = "SELECT * FROM urls WHERE ip ='" . $_SERVER['REMOTE_ADDR'] . "'";//mysql_query("SELECT * FROM maestra WHERE IP='" . $_SERVER['REMOTE_ADDR'] . "'");
                                        $result= $mysqli->query($consulta);
                                        if ($result->num_rows < 5) {

                                            // Limita el número de registros por Email a 4
                                            $consulta = "SELECT * FROM urls WHERE correo='" . $email . "'";//mysql_query("SELECT * FROM maestra WHERE email='" . $_POST["email"] . "'");
                                            $result= $mysqli->query($consulta);
                                            if ($result->num_rows < 5) {
                                                //Inicia la secuencia de condiciones de comprobación de URL
                                                $rep_dom = 6; //Repeticiones por dominio
                                                $rep_url = 6; //Repeticiones de URL
                                                $rep_con = 0; //Repeticiones continuas de url
                                                //Consulta para recuperar el ultimo registro con la fecha de publicacion de la url
                                                $lastRegiter = "SELECT  fecha_hora_publicacion FROM webdb.urls where id_url = (SELECT MAX(id_url) FROM webdb.urls)";
                                                $result = $mysqli->query($lastRegiter);
                                                $row = $result->fetch_assoc();
                               
                                                $lastDate = $row["fecha_hora_publicacion"];
                                                //Convertimos la fecha recuperada que es un string al formato timestamp que es un entero de la fecha en segundos, 
                                                //mediante la funcion strtotime() y y le sumamos 30 segundos 
                                                $intPublicDate = strtotime($lastDate)+30;
                                                //Convertimos la fecha que se encuentra en formato timestap a formato Date
                                                $publicDate = date("Y-m-d H:i:s",$intPublicDate);                           
                                                $consulta= "INSERT INTO webdb.urls (id_usuario, id_tipo, ip, url,fecha_hora_publicacion, correo) VALUES (1, 1, '".$_SERVER['REMOTE_ADDR']."', '".$url."', '".$publicDate."','".$email."')";
                                                $stmt = $mysqli->prepare($consulta);
                                                if($stmt){
                                                    $stmt->execute();
                                                    echo "La url se registro correcatmente!</br>";
                                                }else{
                                                    echo 'La url no se registro en la base de datos';
                                                }
                                            } else {
                                                echo "Lo sentimos, ya hemos recibido demasiados registros con este email: " . $email;
                                            }
                                        } else {
                                            echo "Lo sentimos, ya hemos recibido demasiados registros desde su IP: " . $_SERVER['REMOTE_ADDR'];
                                        }
                                    } else {
                                        echo "Lo sentimos pero se han completado los registros por hoy";
                                    }
                                    mysql_close();
                                } else {
                                    echo "Fuera de la hora de presentación. Son las: " . date("H:i:s", $hora) . " que equivale a " . $hora;
                                }
                            } else {
                                echo "Mal URL!</br>";
                            }
                        } else {
                            echo "No hay URL!</br>";
                        }
                    } else {
                        echo "Mal Email!</br>";
                    }
                } else {
                    echo "No hay Email!</br>";
                }
            } else {
                echo "Mal Captcha!";
            }
        }
    }
}
?>