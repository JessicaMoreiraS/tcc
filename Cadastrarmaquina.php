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
            <select name="sensores[]" multiple>
                <option value="Sensor1">Sensor 1</option>
                <option value="Sensor2">Sensor 2</option>
                <option value="Sensor3">Sensor 3</option>
                <option value="Sensor4">Sensor 4</option>
            </select><br><br>
        </div>
        
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
// Conectando ao banco de dados
$conn = mysqli_connect($host,$usuario,$senha,$database)

// Verifique a conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
//}

// Recebendo os dados do formulário
$nome = $_POST["nome"];
$tipo_maquina = $_POST["tipo_maquina"];
$novo_tipo_maquina = $_POST["novo_tipo_maquina"];
$sensores = implode(", ", $_POST["sensores"]);

// Verificando tipo de máquina
if ($tipo_maquina === "outro" && !empty($novo_tipo_maquina)) {
    $tipo_maquina = $novo_tipo_maquina;
}

// Insira os dados na tabela
$sql = "INSERT INTO maquinas (modelo, tipo_maquina, fabricante, sensores) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $modelo, $tipo_maquina, $fabricante, $sensores);

if ($stmt->execute()) {
    echo "Máquina cadastrada com sucesso!";
} else {
    echo "Erro ao cadastrar a máquina: " . $conn->error;
}

// Feche a conexão
$stmt->close();
$conn->close();
// Lógica para gerar o código

//tipo de conteúdo como um arquivo para download
header('Content-Disposition: attachment; filename="codigo.c"');
header('Content-Type: text/plain');

// Saída do código
echo "Seu código gerado aqui.";

?>



