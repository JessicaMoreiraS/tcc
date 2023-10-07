<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Máquinas</title>
</head>
<body>
    <h1>Cadastro de Máquinas</h1>
    
    <form method="POST" action="processar_cadastro.php">
        <label for="nome">Nome da Máquina:</label>
        <input type="text" name="nome" required><br><br>
        
        <label for="tipo_maquina">Tipo de Máquina:</label>
        <select name="tipo_maquina" id="tipo_maquina" required>
            <option value="Opcao1">Opção 1</option>
            <option value="Opcao2">Opção 2</option>
            <option value="Opcao3">Opção 3</option>
            <option value="Opcao4">Opção 4</option>
            <option value="outro">Outro tipo de máquina</option>
        </select><br><br>
        
        <div id="outro_tipo_maquina" style="display:none;">
            <label for="novo_tipo_maquina">Novo Tipo de Máquina:</label>
            <input type="text" name="novo_tipo_maquina"><br><br>
            
            <label for="sensores">Sensores:</label>
            <input type="checkbox" name="sensores" value="Sensor1">Sensor 1
            <input type="checkbox" name="sensores" value="Sensor2">Sensor 2
            <input type="checkbox" name="sensores" value="Sensor3">Sensor 3
            <input type="checkbox" name="sensores" value="Sensor4">Sensor 4<br><br>
        </div>
        
        <!-- Adicionando campos para modelo, fabricante -->
        <label for="modelo">Modelo:</label>
        <input type="text" name="modelo"><br><br>

        <label for="fabricante">Fabricante:</label>
        <input type="text" name="fabricante"><br><br>
        
        <input type="submit" value="Cadastrar">
    </form>
    <a href="gerar_codigo.php">Gerar Código</a>

    <script>
        document.getElementById('tipo_maquina').addEventListener('change', function() {
            if (this.value === 'outro') {
                document.getElementById('outro_tipo_maquina').style.display = 'block';
            } else {
                document.getElementById('outro_tipo_maquina').style.display = 'none';
            }
        });
    </script>
</body>
</html>

<?php
include('conexao.php');

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectando ao banco de dados
    $conn = mysqli_connect($host, $usuario, $senha, $database);

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Recebendo os dados do formulário
    $nome = $_POST["nome"];
    $tipo_maquina = $_POST["tipo_maquina"];
    $novo_tipo_maquina = $_POST["novo_tipo_maquina"];
    $sensores = isset($_POST["sensores"]) ? implode(", ", $_POST["sensores"]) : "";
    $modelo = $_POST["modelo"];
    $fabricante = $_POST["fabricante"];

    // Verificando tipo de máquina
    if ($tipo_maquina === "outro" && !empty($novo_tipo_maquina)) {
        $tipo_maquina = $novo_tipo_maquina;
    }

    // enviando os dados p tabela
    $sql = "INSERT INTO maquinas (nome, tipo_maquina, sensores, modelo, fabricante) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nome, $tipo_maquina, $sensores, $modelo, $fabricante);

    if ($stmt->execute()) {
        echo "Máquina cadastrada com sucesso!";
    } else {
        echo "Erro ao cadastrar a máquina: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>