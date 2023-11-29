<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

$idDaMaquina;
if($_POST['id_maquina']){
    $idDaMaquina = $_POST['id_maquina'];
}

function buscarNoBanco($sql){
    include('conexao.php');
    $result = mysqli_query($mysqli, $sql);
    if (!$result) {
        die("Erro na consulta: " . mysqli_error($mysqli));
    }
    return $result;
}

function buscarNames($idMaquina){
    $names = [];
    $sqlItens = "SELECT * FROM maquina INNER JOIN lista_tipo_maquina_item_checklist ON lista_tipo_maquina_item_checklist.id_tipo_maquina = maquina.id_tipo_maquina INNER JOIN item_checklist ON item_checklist.id = lista_tipo_maquina_item_checklist.id_item_checklist WHERE maquina.id = $idMaquina";
    $resultItens = BuscarNoBanco($sqlItens);
    while ($row = mysqli_fetch_assoc($resultItens)) {
        $names[]= $row['name_item'];
    }
    $sqlAtributos = "SELECT * FROM esp32 INNER JOIN atributo_tipo ON esp32.id_atributos = atributo_tipo.id WHERE esp32.id_maquina = $idMaquina";
    $resultAtributos = BuscarNoBanco($sqlAtributos);
    while ($row = mysqli_fetch_assoc($resultAtributos)) {
        $names[]= $row['atributo_esp'];
    }
    return $names;
}

// Processando dados do formulário e inserindo checklist no banco de dados
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $names = buscarNames($idDaMaquina);
    $camposDefinidos = true;
    foreach($names as $name){
        if($_POST[$name] != "on"){
            echo "aqui";
            $camposDefinidos = false;
            break;
        }
    }

    if($camposDefinidos){
        include('conexao.php');
        $tipoResponsavel = $_SESSION['tipo'];
        $idResponsavel = $_SESSION['idAcesso']; 
        $dataHora = date("Y-m-d H:i:s");
        // $dataHora = mysqli_real_escape_string($mysqli, date('Y-m-d H:i:s'));

        $sqlCheck = "INSERT INTO checklist (id_$tipoResponsavel, id_maquina, date_time) VALUES ('$idResponsavel', '$idDaMaquina', '$dataHora')";
        $sqlLuzVerde = "UPDATE maquina SET led_verde='ON', led_amarelo='OFF', led_vermelho='OFF' WHERE id = $idDaMaquina";

        echo $dataHora;
        if($mysqli->query($sqlCheck) && $mysqli->query($sqlLuzVerde)){
            // echo "Inserção bem-sucedida!";
            header('Location: Checklist.php?id_maquina='.$idDaMaquina);
            return;
        }else{
            echo "Erro na inserção: ".mysqli_error($mysqli);
        }

    }else{
        include('conexao.php');
        //nao chickou todos os itens da checklist
        $sqlLuzVermelha = "UPDATE maquina SET led_verde='OFF', led_amarelo='OFF', led_vermelho='ON' WHERE id = $idDaMaquina";
        if($mysqli->query($sqlLuzVermelha)){
            header('Location: Checklist.php?id_maquina='.$idDaMaquina);
            // echo "Inserção bem-sucedida!";
        }else{
            echo "Erro na inserção: ".mysqli_error($conexao);
        }
    }
}

?>
