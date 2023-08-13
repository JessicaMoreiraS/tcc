<?php
    $usuario = "root";
    $senha = "";
    $database = "tcc";
    $host = "localhost";

    $mysqli = new mysqli($host, $username, $senha, $database);

    if($mysqli->error){
        die("Falha ao conectar com o banco de dados: ".$mysqli->error);
    }
?>