<?php
session_start();
if ($_SESSION['idAcesso'] != 'gestaoSenai') {
    header('Location: index.html');
}
?>
<?php
include('conexao.php');

if (isset($_GET["option"]) ) {
    $option = $_GET["option"];
    echo $option;

    $listaOption = ["professor", "aluno","maquina","tipo"];
    for ($i=0; $i<count($listaOption);$i++){
        if ($option == $listaOption[$i]) {
            $opcao = $listaOption[$i];

            $alunoId = $_GET["id_delecao"]; ; 
            $sqlDeleteAluno = "DELETE FROM $opcao WHERE id = $alunoId";
    
            if ($mysqli->query($sqlDeleteAluno)) {
                header('Location: visualizar.php?view='.$opcao);
            } else {
                header('Location: visualizar.php?e=9&view='.$opcao);
            }
        }
    }
}
?>


