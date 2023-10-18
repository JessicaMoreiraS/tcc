<?php
include('conexao.php');

function getTiposMaquina($mysqli) {
    $tipos = array();

    $sql = "SELECT id, tipo FROM tipo_maquina";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tipos[$row['id']] = $row['tipo'];
        }
    }

    return $tipos;
}

// Processamento do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo_maquina = $_POST["tipo_maquina"];
    $modelo = $_POST["modelo"];
    $fabricante = $_POST["fabricante"];

    $sql_maquina = "INSERT INTO maquina (id_tipo_maquina, modelo, fabricante) VALUES (?, ?, ?)";
    $stmt_maquina = $mysqli->prepare($sql_maquina);
    $stmt_maquina->bind_param("iss", $tipo_maquina, $modelo, $fabricante);
    $stmt_maquina->execute();
    $maquina_id = $stmt_maquina->insert_id;

    if ($tipo_maquina == 'novo' && isset($_POST['pecas'])) {
        foreach ($_POST['pecas'] as $peca_id) {
            $sql_lista_peca = "INSERT INTO lista_tipo_maquina_peca (id_tipo_maquina, id_peca) VALUES (?, ?)";
            $stmt_lista_peca = $mysqli->prepare($sql_lista_peca);
            $stmt_lista_peca->bind_param("ii", $maquina_id, $peca_id); // Use $maquina_id aqui
            $stmt_lista_peca->execute();
            $stmt_lista_peca->close();
        }
    }

    $stmt_maquina->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Máquina</title>
</head>
<body>

<form action="processa_cadastro.php" method="post" enctype="multipart/form-data">
    <label for="tipo_maquina">Tipo de Máquina:</label>
    <select name="tipo_maquina" id="tipo_maquina">
        <?php
        // Preenche as opções do tipo de máquina
        foreach ($tiposMaquina as $id => $tipo) {
            echo '<option value="' . $id . '">' . $tipo . '</option>';
        }
        ?>
        <option value="novo">Novo Tipo</option>
    </select>

    <label for="modelo">Modelo:</label>
    <input type="text" name="modelo" required>

    <label for="fabricante">Fabricante:</label>
    <input type="text" name="fabricante" required>

    <label for="imagem">Imagem:</label>
    <input type="file" name="imagem">

    <button type="submit">Cadastrar</button>
</form>
</body>
</html>