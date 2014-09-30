 <?php
	if(isset($_POST["epr"]) and $_POST["epr"]=="1"){
		echo "Pregunta si es posible publicar";}
	else if (count($_POST)==5){
		echo "Solicita registro";}
	else {
		echo "Aqui no hay nada";}
?>
<html>
	<head>
		<title>Pruebas</title>
	</head>
	<body>
		<form action="registro.php" method="get">
			epr: <input type="text" name="epr">
			<input type="submit">
		</form>
		<?php
			$hora=time();
			echo "Hora actual es: ".date("H:i:s",$hora).", equivale a: ".$hora."</br>"; 
			$ini_dia=$hora-$hora%86400;
			echo "Inicio del dia: ".date("H:i:s",$ini_dia).", equivale a: ".$ini_dia."</br>";
			$ini_pre=$ini_dia+(8*60*60);
			echo "Inicio de la presentacion: ".date("H:i:s",$ini_pre).", equivale a: ".$ini_pre."</br>";
			$fin_pre=$ini_dia+(24*60*60)-30;
			echo "Fin de la presentacion: ".date("H:i:s",$fin_pre).", equivale a: ".$fin_pre."</br>";
			$periodo=$hora-$hora%30;
			echo "Inicio del periodo: ".date("H:i:s",$periodo).", equivale a: ".$periodo."</br>";
			
			$url="http://tinyurl.com/ybw2956";
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, true);
			curl_setopt($curl, CURLOPT_TIMEOUT, 10);
			curl_setopt($curl, CURLOPT_NOBODY, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$data = curl_exec($curl);
			$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
			echo "</br>Respuesta: ".$data;
			echo "</br>C&oacute;digo: ".$statusCode;
		?>
	
	</body>
</html>