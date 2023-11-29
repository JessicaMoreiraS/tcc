<?php
session_start();
$servername = "servidor_mysql";
$username = "usuario_mysql";
$password = "senha_mysql";
$dbname = "banco_de_dados";

// Crie uma conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifique a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
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
    $sqlAtributos = "SELECT * FROM maquina INNER JOIN lista_tipo_maquina_atributo ON maquina.id_tipo_maquina = lista_tipo_maquina_atributo.id_tipo_maquina INNER JOIN atributo_tipo ON atributo_tipo.id = lista_tipo_maquina_atributo.id_atributo WHERE maquina.id = $idMaquina";
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
        if(!isset($_POST[$name])){
            $camposDefinidos = false;
            break;
        }
    }

    if($camposDefinidos){
        $tipoResponsavel = $_SESSION['tipo'];
        $idResponsavel = $_SESSION['idAcesso']; 
        $dataHora = date("Y-m-d H:i:s");

        $sqlCheck = "INSERT INTO checklist (id_$tipoResponsavel, id_maquina, date_time) VALUES (?, ?, ?)";
        $sqlLuzVerde = "UPDATE maquina SET led_verde='ON', led_amarelo='OFF', led_vermelho='OFF' WHERE id = $idDaMaquina";

        $stmt = mysqli_prepare($conexao, $sqlCheck);
    
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "iss", $idResponsavel, $idDaMaquina, $dataHora);
    
            if(mysqli_stmt_execute($stmt) && $mysqli->query($sqlLuzVerde)){
                // echo "Inserção bem-sucedida!";
                header('Location: Checklist.php?id_maquina='$idDaMaquina);
            }else{
                echo "Erro na inserção: ".mysqli_error($conexao);
            }
    
            mysqli_stmt_close($stmt);
        } else {
            echo "Erro na preparação da consulta: ".mysqli_error($conexao);
        }

    }else{
        //nao chickou todos os itens da checklist
         $sqlLuzVermelha = "UPDATE maquina SET led_verde='OFF', led_amarelo='OFF', led_vermelho='ON' WHERE id = $idDaMaquina";
         if($mysqli->query($sqlLuzVermelha)){
            echo "Inserção bem-sucedida!";
        }else{
            echo "Erro na inserção: ".mysqli_error($conexao);
        }
    }
}

/*
    // Certifique-se de que todos os campos do formulário estão definidos
    if (isset($_POST["item_1"]) && isset($_POST["item_2"]) && isset($_POST["item_3"]) && isset($_POST["item_4"]) && isset($_POST["item_5"]) &&
        isset($_POST["item_6"]) && isset($_POST["item_7"]) && isset($_POST["item_8"]) && isset($_POST["item_9"]) && isset($_POST["item_10"])) {

        // Obtenha os valores dos campos do formulário
        $item_1 = $_POST["item_1"];
        $item_2 = $_POST["item_2"];
        $item_3 = $_POST["item_3"];
        $item_4 = $_POST["item_4"];
        $item_5 = $_POST["item_5"];
        $item_6 = $_POST["item_6"];
        $item_7 = $_POST["item_7"];
        $item_8 = $_POST["item_8"];
        $item_9 = $_POST["item_9"];
        $item_10 = $_POST["item_10"];

        // enviando os dados para o banco de dados
        $sql = "INSERT INTO checklist (item_1, item_2, item_3, item_4, item_5, item_6, item_7, item_8, item_9, item_10) 
                VALUES ('$item_1', '$item_2', '$item_3', '$item_4', '$item_5', '$item_6', '$item_7', '$item_8', '$item_9', '$item_10')";

        if ($conn->query($sql) === TRUE) {
            echo "Checklist enviado com sucesso";
        } else {
            echo "Erro ao enviar o checklist: " . $conn->error;
        }
    } else {
        echo "Todos os campos do formulário devem estar definidos.";
    }
}

// Feche a conexão
$conn->close();*/
?>
