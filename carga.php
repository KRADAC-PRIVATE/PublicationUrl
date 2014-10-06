<?php
	$hora=time();	//Hora actual - es un numero entero
	$ini_dia=$hora-$hora%86400;	//Inicio del dia
	$ini_pre=$ini_dia+(8*60*60);	//Inicia la publicación de sitios - 08:00
	$archivo=fopen("./urls_2.txt","read");
	$txt=fgets($archivo);
	fclose($archivo);
	$urls=explode(chr(13),$txt);
        if (!$mysqli = getConectionDb()) {
        echo " Ha fallado la conexi&oacute;n a la Base de Datos.";
        } else {

    //	mysql_connect('172.16.17.214','frnoviyo','frnoviyo')or die ('Ha fallado la conexi&oacute;n a la Base de Datos');
    //	mysql_select_db('scom_publi')or die ('Error al seleccionar la Base de Datos');
            foreach ($urls as $url) {
                    $consulta= "INSERT INTO 'urls' ('id_usuario', 'id_tipo', 'ip', 'url','feha_hora_publicacion') VALUES ('1', '1', '127.0.0.1', 'http://".$url."', '".$ini_pre."');";//mysql_query("INSERT INTO `scom_publi`.`alterna` (`id`, `IP`, `URL`, `email`, `hora_pu`, `visitas`, `hora_re`) VALUES (NULL, '192.168.1.5', 'http://".$url."', 'registro@30s.com.ec', '".$ini_pre."', '0', '".$hora."');");
                    $result = $mysql->query($consulta);
                    $stmt = $mysqli->prepare($resu);
                    $stmt->execute();
                    $ini_pre=$ini_pre+30;
            }
            mysql_close;
            print " hay ";
            print count($urls);
        }
?>