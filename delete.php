<?php
session_start();
if ($_SESSION['idAcesso'] != 'gs') {
    header('Location: index.html');
}
?>
<?php
include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["option"]) && isset($_POST["confirm"])) {
    $option = $_POST["option"];

    if ($option === "professor") {
        //Processo deletar professor
        $professorId = $_POST["professor_id"]; // 
        $sqlDeleteProfessor = "DELETE FROM professor WHERE id = $professorId";

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
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Dados</title>
</head>
<body>
    <h1>Excluir Dados</h1>
    <form action="delete_process.php" method="POST">
        <select name="option" id="option">
            <option value="delete_tipo">Excluir Tipo de Máquina</option>
            <option value="delete_maquina">Excluir Máquina</option>
            <option value="delete_aluno">Excluir Aluno</option>
            <option value="delete_professor">Excluir Professor</option>
        </select>
        <input type="submit" value="Selecionar Opção">
    </form>
</body>
</html>

