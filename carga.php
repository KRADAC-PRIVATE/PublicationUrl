<?php
include ('dll/config.php');	
        $hora=time();	//Hora actual - es un numero entero
        echo $hora."<br>";
	$ini_dia=$hora-$hora%86400;	//Inicio del dia
        echo $ini_dia."<br>";
	$ini_pre=$ini_dia+(10*60*60);	//Inicia la publicación de sitios - 08:00
        echo $ini_pre;
	$archivo=fopen("./urls.txt","read");
	$txt=fgets($archivo);
	fclose($archivo);
	$urls=explode(chr(13),$txt);
        if (!$mysqli = getConectionDb()) {
        echo " Ha fallado la conexi&oacute;n a la Base de Datos.";
        } else {
            foreach ($urls as $url) {
                echo "Prueba";
                    $consulta= "INSERT INTO webdb.urls (id_usuario, id_tipo, ip, url,fecha_hora_publicacion) VALUES (1, 1, '127.0.0.1', 'http://".$url."', '".date("Y-m-d H:i:s",$ini_pre)."');";//mysql_query("INSERT INTO `scom_publi`.`alterna` (`id`, `IP`, `URL`, `email`, `hora_pu`, `visitas`, `hora_re`) VALUES (NULL, '192.168.1.5', 'http://".$url."', 'registro@30s.com.ec', '".$ini_pre."', '0', '".$hora."');");
                    $stmt = $mysqli->prepare($consulta);
                    $stmt->execute();
                    $ini_pre=$ini_pre+30;
            }
            $mysqli->close();
            print " hay ";
            print count($urls);
        }
?>