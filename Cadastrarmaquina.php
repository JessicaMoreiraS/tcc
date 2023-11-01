<?php
include("conexao.php");

// Verificar a conexão
if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}
$mensagem = '';

// Se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_maquina = $_POST["id_maquina"];
    $id_tipo_maquina = $_POST["tipo_maquina"];
    $modelo = $_POST["modelo"];
    $fabricante = $_POST["fabricante"];
    $imagem = $_FILES["imagem"]["tmp_name"] ? file_get_contents($_FILES["imagem"]["tmp_name"]) : null;

    // Verificar se o tipo de máquina já existe
    $stmt = $mysqli->prepare("SELECT id FROM tipo_maquina WHERE id = ?");
    $stmt->bind_param("i", $id_tipo_maquina);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Tipo de máquina existe
        $stmt = $mysqli->prepare("INSERT INTO maquina (id, id_tipo_maquina, modelo, fabricante, imagem) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iissb", $id_maquina, $id_tipo_maquina, $modelo, $fabricante, $imagem);
        $stmt->send_long_data(4, $imagem);  // Para dados BLOB (imagem)

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $mensagem = "Máquina cadastrada com sucesso!";
        } else {
            $mensagem = "Erro ao cadastrar a máquina. Verifique se todos os campos estão preenchidos corretamente.";
            $mensagem .= " Erro MySQL: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // Tipo de máquina não existe, redirecionar para cadastrar novo tipo
        header("Location: cadastrarNovaMaquina.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Criar Máquina</title>
</head>
<body>
    <h2>Criar Máquina</h2>

    <?php echo $mensagem; ?>

    <form action="" method="post" enctype="multipart/form-data">
        ID da Máquina:
        <input type="text" name="id_maquina" required><br>
        Tipo de Máquina:
        <select name="tipo_maquina">
            <?php
                // Recuperar tipos de máquinas para exibir no formulário
                $result = $mysqli->query("SELECT id, tipo FROM tipo_maquina");
                $tipos_maquinas = $result->fetch_all(MYSQLI_ASSOC);

                foreach ($tipos_maquinas as $tipo_maquina) {
                    echo '<option value="' . $tipo_maquina['id'] . '">' . $tipo_maquina['tipo'] . '</option>';
                }
            ?>
        </select><br>
        Modelo: <input type="text" name="modelo" required><br>
        Fabricante: <input type="text" name="fabricante" required><br>
        Imagem: <input type="file" name="imagem"><br>
        <input type="submit" value="Cadastrar">
    </form>
    <form action="cadastrarNovaMaquina.php" method="get">
        <input type="submit" value="Cadastrar nova máquina">
    </form>
</body>
</html>
