<?php
function getConectionDb() {
    /* DATOS DE MI SERVIDOR */
    $db_name = "webdb";
    $db_host = "172.16.17.214";
    $db_user = "frnoviyo";
    $db_password = "frnoviyo";
    @$mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);
    return ($mysqli->connect_errno) ? false : $mysqli;
}
