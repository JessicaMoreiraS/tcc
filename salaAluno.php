<?php
    session_start();
    $idAluno = $_SESSION['idAcesso'];

    include("conexao.php");

    $sqlBuscaMaquinas = "SELECT * FROM tipo_maquina RIGHT JOIN lista_sala_tipo_maquina ON tipo_maquina.id = lista_sala_tipo_maquina.id_tipo_maquina";

?>