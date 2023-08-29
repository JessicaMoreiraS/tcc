<?php
session_start();
if ($_SESSION['idAcesso'] != 'gs') {
    header('Location: index.html');
}

if (isset($_GET['option'])) {
    $option = $_GET['option'];

    if ($option === "aluno") {
        $confirmationMessage = "Tem certeza que deseja excluir este aluno?";
    }

    $_SESSION['delete_option'] = $option;
}
?>
<?php
session_start();
include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm"])) {
    $option = $_SESSION['delete_option'];

    if ($mysqli->query($sqlDeleteProfessor)) {
        header('Location: criarConta.php?ProfessorDeleted=true');
    } else {
        header('Location: criarConta.php?ProfessorDeleted=false');
    }
} elseif ($option === "tipo") {
    // processo deletar tipo de máquina
    $tipoId = $_POST["tipo_id"]; 
    $sqlDeleteTipo = "DELETE FROM tipo_maquina WHERE id = $tipoId";

    if ($mysqli->query($sqlDeleteTipo)) {
        header('Location: criarConta.php?TipoDeleted=true');
    } else {
        header('Location: criarConta.php?TipoDeleted=false');
    }
} elseif ($option === "maquina") {
    // deletar máquina
    $maquinaId = $_POST["maquina_id"]; 
    $sqlDeleteMaquina = "DELETE FROM maquina WHERE id = $maquinaId";

    if ($mysqli->query($sqlDeleteMaquina)) {
        header('Location: criarConta.php?MaquinaDeleted=true');
    } else {
        header('Location: criarConta.php?MaquinaDeleted=false');
    }
} elseif ($option === "aluno") {
    //deletar aluno
    $alunoId = $_POST["aluno_id"]; 
    $sqlDeleteAluno = "DELETE FROM aluno WHERE id = $alunoId";

    if ($mysqli->query($sqlDeleteAluno)) {
        header('Location: criarConta.php?AlunoDeleted=true');
    } else {
        header('Location: criarConta.php?AlunoDeleted=false');
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Exclusão de Aluno</title>
</head>
<body>
    <h1>Confirmação de Exclusão de Aluno</h1>
    <p><?php echo $confirmationMessage; ?></p>

    <form action="delete_process.php" method="post">
        <input type="submit" name="confirm" value="Confirmar">
        <a href="criarConta.php">Cancelar</a>
    </form>
</body>
</html>
<?php
session_start();
if ($_SESSION['idAcesso'] != 'gestaoSenai') {
    header('Location: index.html');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Dados</title>
</head>
<body>
    <h1>Excluir Dados</h1>
    <a href="confirm_delete_aluno.php?option=aluno">Excluir Aluno</a>
</body>
</html>

