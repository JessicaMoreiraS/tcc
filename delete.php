<?php
session_start();
if ($_SESSION['idAcesso'] != 'gestaoSenai') {
    header('Location: index.html');
}
?>
<?php
include('conexao.php');

if (isset($_GET["option"])) {
    $option = $_GET["option"];


    $listaOption = ["professor", "maquina", "tipo"];
    for ($i = 0; $i < count($listaOption); $i++) {
        if ($option == $listaOption[$i]) {
            $opcao = $listaOption[$i];

            $id = $_GET["id_delecao"];
            ;
            $sqlDelete = "DELETE FROM $opcao WHERE id = $id";

            if ($mysqli->query($sqlDelete)) {
                header('Location: visualizar.php?view=' . $opcao);
            } else {
                header('Location: visualizar.php?e=9&view=' . $opcao);
            }
        }
        $stmt->close();
        $mysqli->close();
    }
}
if ( isset($_GET['acao'])) {

    $acao = $_GET['acao'];
    if ($acao == 'deletarAlunoDaSala') {
        $id = $_GET["id_delecao"];
        ;
        $sqlDelete == "DELETE  FROM lista_aluno_sala  WHERE id_aluno = $id";
        if ($mysqli->query($sqlDelete)) {
            echo 'aluno deletado da sala';
        } else {
            echo 'aluno não deletado da sala'->mysql_error;
        }
    } else if ($acao == 'deletarAluno') {
        $id = $_GET["id_delecao"];
        ;
        $sqlDelete = "DELETE FROM aluno WHERE id = $id";

        if ($mysqli->query($sqlDelete)) {
            echo 'aluno deletado do sistema';
        } else {
            echo 'aluno não deletado do sistema'->mysql_error;
        }
    }
}
?>