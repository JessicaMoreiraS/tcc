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

// Verificar se foi excluído
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmarExclusao'])) {
    $idGestorExcluir = $_POST['idGestorExcluir'];
    excluirGestor($idGestorExcluir);
    // Redirecionar para a mesma página após a exclusão
    header('Location: homeGestorDefault.php');
    exit();
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
       <!-- cabeçalho -->
    </header>

    <main>
        <h2>Gerenciamento de Gestores</h2>

        <!-- tabela com as informações dos gestores -->
        <!-- Colocar checkbox --> 
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
            <?php
            $gestores = [
                ['id' => 1, 'nome' => 'Gestor 1'],
                ['id' => 2, 'nome' => 'Gestor 2'],
            ];//pegar dados do banco

            foreach ($gestores as $gestor) {
                echo "<tr>
                        <td>{$gestor['id']}</td>
                        <td>{$gestor['nome']}</td>
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
                <button type="submit" name="confirmarExclusao">Sim</button>
                <button type="button" onclick="fecharConfirmacaoExclusao()">Não</button>
            </form>
        </div>

        <!-- Botão para cadastrar novo gestor -->
        <a href="cadastrarGestor.php">Cadastrar Novo Gestor</a>
    </main>

    <script>
        function confirmarExclusao(idGestor) {
            document.getElementById('idGestorExcluir').value = idGestor;
            document.getElementById('confirmacaoExclusao').style.display = 'block';
        }

        function fecharConfirmacaoExclusao() {
            document.getElementById('confirmacaoExclusao').style.display = 'none';
        }
    </script>
</body>
</html>
