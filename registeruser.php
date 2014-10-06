<?php

include ('dll/config.php');
$email = $_POST["email"];
$clave = $_POST["pass"];
$reclave = $_POST["repass"];
if (!$mysqli = getConectionDb()) {
    echo " Ha fallado la conexi&oacute;n a la Base de Datos.";
} else {
    if(isset($_POST['enviar'])){
        if($email == '' or $clave == '' or $reclave == ""){
            echo 'Por favor llene todos los campos';
        }  else {
            $consulta = "SELECT correo FROM usuarios";
            $result = $mysqli->query($consulta);
            $verificarUser = 1;
            while ($row = $result->fetch_assoc()) {
                if($row["correo"] == $email){
                    $verificarUser = 0;
                }
            }
            if($verificarUser){
                if($clave == $reclave){
                    $insertSql = "INSERT INTO usuarios (correo, clave, bloqueado) VALUES ('" . $email . "','" . $clave . "', 0)";
                    $stmt = $mysqli->prepare($insertSql);
                    if ($stmt) {
                        $stmt->execute();
                        echo "El usuario se registro correcatmente!</br>";
                    } else {
                        echo 'La url no se registro en la base de datos';
                    }
                }else{
                    echo'Las claves no son iguales';
                }
            }else{
                echo 'Esta cuenta de correo ya ha sido regsitrada';
            }
        }
    }
}


