<?php
    session_start();
    $idAluno = $_SESSION['idAcesso'];
    include("conexao.php");

    $salaNaoEncontrada = false;
    $erroInsert = false;

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $codigoTentado = filter_input(INPUT_POST, 'codigoTurma');

        $encontrarSala = "SELECT * FROM sala WHERE sala.codigo_acesso = $codigoTentado";
        if($sala = $mysqli->query($encontrarSala)){
            $salaEncontrada = $sala['id'];
            $adicionaAlunoEmSala = "INSERT INTO lista_aluno_sala ('id_aluno', 'id_sala') VALUES ('$idAluno', '$salaEncontrada')";
            if($sala = $mysqli->query($adicionaAlunoEmSala)){
                //sucess
                header('location: homeAluno.php');
            }else{
                //error
                $erroInsert = true;
            }
        }else{
            //error
            $salaNaoEncontrada = true;
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar em uma nova turma</title>
</head>
<body>

    <form action="" method="GET">
        <input type="text" name="codigoTurma" placeholder="Código da nova turma">
        <input type="submit" value="Entrar">
    </form>
    <?php
        if($erroInsert){
            echo "<p>Ops, não foi possível entrar nessa sala</p>";
        }
        if($salaNaoEncontrada){
            echo "<p>Ops, sala não encontrada</p>";
        }
    ?>
    
</body>
</html>