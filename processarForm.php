<?php
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

// Processando dados do formulário e inserindo no banco de dados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
$conn->close();
?>
