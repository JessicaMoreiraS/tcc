<?php
    $usuario = "root";
    $senha = "";
    $database = "iot";
    $host = "localhost";

    //$mysqli = new mysqli($host, $usuario, $senha, $database);

    $mysqli = mysqli_connect($host,$usuario,$senha,$database);
    $charset = mysqli_set_charset($mysqli,"utf8");

    if($mysqli->error){
        die("Falha ao conectar com o banco de dados: ".$mysqli->error);
    }
?>