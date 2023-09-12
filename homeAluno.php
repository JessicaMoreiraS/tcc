<?php
    session_start();
    $idAluno = $_SESSION['idAcesso'];

    include("conexao.php");
    $sqlAluno = "SELECT * FROM aluno WHERE id = ".$idAluno;

    $sqlConteudoCard = "SELECT * FROM lista_aluno_sala LEFT JOIN sala ON sala.id = lista_aluno_sala.id_sala LEFT JOIN professor ON professor.id = sala.id_professor WHERE lista_aluno_sala.id_aluno = $idAluno";


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
    <div>
        <a href="entrarTurmaNova.php">Entrar em uma nova turma</a>
    </div>

    <!-- barra de busca -->

    <?php
    //corrigir pq acho que em cima ta errado
    $conteudo = $mysqli -> query($sqlConteudoCard);
    while ($row = mysqli_fetch_assoc($conteudo)){?>
        <a href="salaAluno.php?sala=<?php echo $row['id']?>"> <!--card-->
            <p><?php echo $row['turma']?></p>     
            <p><?php echo $row['nome']?></p>  
        </a>
        <?php
    }?>
</body>
</html>