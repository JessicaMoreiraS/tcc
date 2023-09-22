<?php
//inicisnd sessão 
session_start();
//capturando o id do professor
$idProfessor = $_SESSION['idAcesso'];

//incluindo o banco de dados
include("conexao.php");

//sql para busca do professor
$sqlBuscaProfessor = "SELECT * FROM professor WHERE id =" . $idProfessor;

//busca do nome do professor
$professorNome = mysqli_fetch_assoc(($mysqli->query($sqlBuscaProfessor)))['nome'];

$sqlConteudoCard = "SELECT DISTINCT sala.*, sala.turma
                   FROM sala
                   LEFT JOIN professor ON professor.id = sala.id_professor
                   WHERE sala.id_professor = $idProfessor";



echo $professorNome;


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
<div class="turmas">
    <?php
    $conteudo = $mysqli->query($sqlConteudoCard);
    while ($row = mysqli_fetch_assoc($conteudo)) { ?>
        <div class="card">
            <div class="infos">
                <p id="first_p">
                    Turma: <?php echo $row['turma']; ?>
                </p>
            </div>
            <a href="salaProfessor.php?sala=<?php echo $row['id'] ?>">
                <div class="rodape">
                    <i>
                        <img src="img/svg/seta.svg" alt="" />
                    </i>
                </div>
                entrar
            </a>
        </div>
    <?php
    } ?>
</div>

</body>

</html>