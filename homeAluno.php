<?php
    session_start();
    $idAluno = $_SESSION['idAcesso'];

    include("conexao.php");
    $sqlAluno = "SELECT * FROM aluno WHERE id = ".$idAluno;
    $sqlSala = "SELECT * FROM sala LEFT JOIN lista_aluno_sala ON lista_aluno_sala.id_aluno = $idAluno";
    $sqlProfessorSala = "SELECT * FROM professor LEFT JOIN sala ON sala.id_professor= professor.id"


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home do aluno</title>
</head>
<body>
    <?php
    $conteudo = $mysqli -> query($sqlSala);
    while ($row = mysqli_fetch_assoc($conteudo)){?>
        <a href="salaAluno.php?sala=<?php echo $row['id']?>"> <!--card-->
            <p><?php echo $row['turma']?></p>
            <?php
            $buscaProfessor = $mysqli->query($sqlProfessorSala);
            if($buscarProf = mysqli_fetch_assoc($buscaProfessor)){?>
            <p><?php echo $buscarProf['nome']?></p>  
        </a>
    <?php
        }
    }?>
</body>
</html>