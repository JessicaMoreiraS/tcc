<?php
session_start();
include 'conexao.php';

// Verificando a permissão do usuário
if ($_SESSION['idAcesso'] != 'gestaoDefault' || $_SESSION['tipo'] != 'defalt') {
    header('Location: index.html');
    exit();
}

//Função pra excluir o Gestor
function excluirGestor($idGestor) {
    global $mysqli;
    $stmt = $mysqli->prepare("DELETE FROM gestor WHERE id = ?");
    $stmt->bind_param("i", $idGestor);

    // Executar a query
    if ($stmt->execute()) {
        echo "Gestor excluído com sucesso!";
    } else {
        echo "Erro ao excluir gestor: " . $stmt->error;
    }

    $stmt->close();
}

// Consultar gestores no banco de dados
$gestores = [];
$result = $mysqli->query("SELECT * FROM gestor");

if ($result) {
    // Transformar o resultado em um array associativo
    while ($row = $result->fetch_assoc()) {
        $gestores[] = $row;
    }

    // Liberar o resultado da consulta
    $result->free();
} else {
    echo "Erro ao consultar gestores: " . $mysqli->error;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cadastrarGestor'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];

    // Validar e inserir gestor no banco de dados
    $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

    $stmt = $mysqli->prepare("INSERT INTO gestor (nome, cpf, email, senha) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $nome, $cpf, $email, $senhaHash);

    if ($stmt->execute()) {
        echo "Gestor cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar gestor: " . $stmt->error;
    }

    $stmt->close();
}

// Função para validar a senha do Gestor Default
function senhaGestorDefaultCorreta($senha) {
    global $mysqli;

    $senhaGestorDefaultHashed = '$2y$10$P6KoVMxrnt0rlpLFdFv8LOCUEMzZEbASF940OS0tsQJpzuThkbG1C'; 

    // Verificar se a senha fornecida corresponde à senha do Gestor Default
    return password_verify($senha, $senhaGestorDefaultHashed);
}

// Verificar se foi excluído
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmarExclusao'])) {
    $idGestorExcluir = $_POST['idGestorExcluir'];
    $senhaGestorDefault = $_POST['senhaGestorDefault'];

    // Validar a senha do Gestor Default
    if (senhaGestorDefaultCorreta($senhaGestorDefault)) {
        excluirGestor($idGestorExcluir);
        // Redirecionar para a mesma página após a exclusão
        // echo '<script>location.reload();</script>';
        
        exit();
    } else {
        echo "Senha do Gestor Default incorreta!";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Gestor Default</title>
</head>
<body>
    <header>
       
    </header>

    <main>
        <h2>Gerenciamento de Gestores</h2>

        <!-- tabela com as informações dos gestores -->
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
            <?php
            foreach ($gestores as $gestor) {
                echo "<tr>
                        <td>{$gestor['cpf']}</td>
                        <td>{$gestor['nome']}</td>
                        <td>{$gestor['email']}</td>
                        <td>
                            <button onclick='confirmarExclusao({$gestor['id']})'>Excluir</button>
                        </td>
                      </tr>";
            }
            ?>
        </table>

        <!-- Pop-up de confirmação de exclusão -->
        <div id="confirmacaoExclusao" style="display:none;">
            <p>Você tem certeza que deseja excluir este gestor?</p>
            <form method="post">
                <input type="hidden" name="idGestorExcluir" id="idGestorExcluir">
                <label for="senhaGestorDefault">Senha do Gestor Default:</label>
                <input type="password" name="senhaGestorDefault" required>
                <button type="submit" name="confirmarExclusao">Sim</button>
                <button type="button" onclick="fecharConfirmacaoExclusao()">Não</button>
            </form>
        </div>

        <!-- Formulário pop-up para adicionar novo gestor -->
        <div id="cadastroGestor" style="display:none;">
            <form method="post">
                <label for="nome">Nome Completo:</label>
                <input type="text" name="nome" id="nome" required>
                <label for="cpf">CPF:</label>
                <input type="text" name="cpf" id="cpf" required>
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" required>
                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required>

                <button type="submit" name="cadastrarGestor">Cadastrar</button>
            </form>
            <button type="button" onclick="fecharCadastroGestor()">Fechar</button>
        </div>

        <!-- Botão para cadastrar novo gestor -->
        <button type="button" onclick="abrirCadastroGestor()">Cadastrar Novo Gestor</button>
    </main>

    <script>
        function confirmarExclusao(idGestor) {
            document.getElementById('idGestorExcluir').value = idGestor;
            document.getElementById('confirmacaoExclusao').style.display = 'block';
        }

        function fecharConfirmacaoExclusao() {
            document.getElementById('confirmacaoExclusao').style.display = 'none';
        }

        function abrirCadastroGestor() {
            document.getElementById('cadastroGestor').style.display = 'block';
        }

        function fecharCadastroGestor() {
            document.getElementById('cadastroGestor').style.display = 'none';
        }
    </script>
</body>
</html>
