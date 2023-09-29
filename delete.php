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
    $acao = $_GET['acao'];
    echo $option;

    $listaOption = ["professor", "aluno","maquina","tipo"];
    for ($i=0; $i<count($listaOption);$i++){
        if ($option == $listaOption[$i]) {
            $opcao = $listaOption[$i];

            $alunoId = $_GET["id_delecao"]; ; 
            $sqlDelete = "DELETE FROM $opcao WHERE id = $alunoId";
    
            if ($mysqli->query($sqlDelete)) {
                header('Location: visualizar.php?view='.$opcao);
            } else {
                header('Location: visualizar.php?e=9&view='.$opcao);
            }
        }

        if($acao = 'deletarAlunoDaSala'){
            $alunoId = $_GET["id_delecao"]; ;
            $sqlDelete = "DELETE  FROM lista_aluno_sala  WHERE id_aluno = $alunoId";

            
            if ($mysqli->query($sqlDelete)) {
             echo 'aluno deletado da sala';
            } else {
                echo 'aluno não deletado da sala'->mysql_error;
            }
        }else if($acao = 'deletarAluno'){
            $alunoId = $_GET["id_delecao"]; ;
            $sqlDelete = "DELETE FROM aluno WHERE id = $alunoId";

            if ($mysqli->query($sqlDelete)) {
                echo 'aluno deletado do sistema';
               } else {
                   echo 'aluno não deletado do sistema'->mysql_error;
               }
        }

        $stmt->close();
        $mysqli->close();
    }
}
?>


