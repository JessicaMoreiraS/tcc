<?php
session_start();
include 'conexao.php';

// Verificando a permissão do usuário
if ($_SESSION['idAcesso'] != 'gestaoDefault') {
    header('Location: index.html');
    exit();
}

//Função pra excluir o Gestor
function excluirGestor($idGestor) {
    global $mysqli;
    $stmt = $mysqli->prepare("DELETE FROM gestores WHERE id = ?");
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
$result = $mysqli->query("SELECT id, nome_completo FROM gestores");

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
    $nomeCompleto = $_POST['nomeCompleto'];

    // Validar e inserir gestor no banco de dados
    $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

    $stmt = $mysqli->prepare("INSERT INTO gestores (email, senha, nome_completo) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $senhaHash, $nomeCompleto);

    if ($stmt->execute()) {
        echo "Gestor cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar gestor: " . $stmt->error;
    }

    $stmt->close();
}

// Verificar se foi excluído
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmarExclusao'])) {
    $idGestorExcluir = $_POST['idGestorExcluir'];
    $senhaGestorDefault = $_POST['senhaGestorDefault'];

    // Função para validar a senha do Gestor Default
    function senhaGestorDefaultCorreta($senha) {
        global $mysqli;

        $senhaGestorDefaultHashed = 'HASH_DA_SENHA_GESTOR_DEFAULT'; 

        // Verificar se a senha fornecida corresponde à senha do Gestor Default
        return password_verify($senha, $senhaGestorDefaultHashed);
    }

    // Validar a senha do Gestor Default
    if (senhaGestorDefaultCorreta($senhaGestorDefault)) {
        excluirGestor($idGestorExcluir);
        // Redirecionar para a mesma página após a exclusão
        header('Location: homeGestorDefault.php');
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
                        <td>{$gestor['id']}</td>
                        <td>{$gestor['nome_completo']}</td>
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
                <label for="email">Email:</label>
                <input type="text" name="email" required>
                <label for="senha">Senha:</label>
                <input type="password" name="senha" required>
                <label for="nomeCompleto">Nome Completo:</label>
                <input type="text" name="nomeCompleto" required>
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
