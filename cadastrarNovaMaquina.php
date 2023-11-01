<?php
include("conexao.php");


if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}

$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["id_maquina"])) {
        $id_maquina = $_POST["id_maquina"];
    } else {
        die("ID da máquina não fornecido.");
    }

    $modelo = $_POST["modelo"];
    $fabricante = $_POST["fabricante"];
if ($_FILES["imagem"]["error"] == UPLOAD_ERR_OK) {
    $imagem = file_get_contents($_FILES["imagem"]["tmp_name"]);
} else {
    $imagem = null; 
}

    $stmt_maquina = $mysqli->prepare("INSERT INTO maquina (id_tipo_maquina, modelo, fabricante, imagem) VALUES (?, ?, ?, ?)");
    if (!$stmt_maquina) {
        die('Erro na preparação da query: ' . $mysqli->error);
    }

$bind_result = $stmt_maquina->bind_param("sssb", $id_maquina, $modelo, $fabricante, $imagem);


if (!$bind_result) {
    die('Erro ao vincular os parâmetros: ' . $stmt_maquina->error);
}
    $stmt_maquina->send_long_data(3, $imagem);  

    $execute_result = $stmt_maquina->execute();

    if (!$execute_result) {
        die('Erro ao executar a query: ' . $stmt_maquina->error);
    }

    if ($stmt_maquina->affected_rows > 0) {
        $id_tipo_maquina = $id_maquina;
        $stmt_maquina->close();
    } else {
        $mensagem = "Erro ao cadastrar novo tipo de máquina. Verifique se todos os campos estão preenchidos corretamente.";
        $mensagem .= " Erro MySQL: " . $stmt_maquina->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastrar Novo Tipo de Máquina</title>
</head>
<body>
    <h2>Cadastrar Novo Tipo de Máquina</h2>

    <?php echo $mensagem; ?>

    <form action="" method="post" enctype="multipart/form-data">
        ID da Máquina: <input type="text" name="id_maquina" required><br>
        Modelo: <input type="text" name="modelo" required><br>
        Fabricante: <input type="text" name="fabricante" required><br>
        Imagem: <input type="file" name="imagem" required><br>

        <h3>Selecionar Peças:</h3>
        <?php
            // Recuperar peças da tabela peca_tipo
            $result_peca = $mysqli->query("SELECT id, codigo, peca FROM peca_tipo");
            $pecas = $result_peca->fetch_all(MYSQLI_ASSOC);

            foreach ($pecas as $peca) {
                echo '<label><input type="checkbox" name="pecas[]" value="' . $peca['id'] . '"> ' . $peca['peca'] . '</label><br>';
            }
        ?>

        <h3>Selecionar Atributos:</h3>
        <?php
            // Recuperar atributos da tabela atributo_tipo
            $result_atributo = $mysqli->query("SELECT id, atributo FROM atributo_tipo");
            $atributos = $result_atributo->fetch_all(MYSQLI_ASSOC);

            foreach ($atributos as $atributo) {
                echo '<label><input type="checkbox" name="atributos[]" value="' . $atributo['id'] . '"> ' . $atributo['atributo'] . '</label><br>';
            }
        ?>

        <input type="submit" value="Cadastrar Novo Tipo de Máquina">
    </form>
</body>
</html>


