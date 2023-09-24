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
$sqlConteudoRadios = "SELECT * FROM tipo_maquina";


echo $professorNome;

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor</title>
</head>

<body>

    <div class="turmas">
        <?php
        echo '<button onclick="exibirFormCriarSala()">Criar Turma</button>';


        echo '
     <a href="update.php?option=professor&id_atualizacao=' . $idProfessor . '">
      Atualizar perfil
     </a>
      ';
        $conteudo = $mysqli->query($sqlConteudoCard);
        while ($row = mysqli_fetch_assoc($conteudo)) { ?>
            <div class="card">
                <div class="infos">
                    <p id="first_p">
                        Turma:
                        <?php echo $row['turma']; ?>
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

        <form  id="form_cria_sala" action="cadastros.php" method="POST" style="opacity:0;">
            <input name="idProfessor" type="text" value="1" hidden>
            <input type="text" placeholder="nome da turma"  name="nomeSala" required>
            <label for="codigo da sua turma"></label>
            <input type="text" id="codigoTurma" readonly name="codigoSala">
            <a onclick=" gerarCodigoAcesso(10)" style="cursor:pointer">Gerar Código</a>
            <div class="radios">
                <?php
                $conteudo = $mysqli->query($sqlConteudoRadios);
                while ($radio = mysqli_fetch_assoc($conteudo)) {
                    echo
                        '<label for="tipo_maquina">' . $radio['tipo'] . '</label>
                        <span><input type="radio" required value="' . $radio["tipo"] . ' " ></span';
                }
                ;
                ?>
            </div>
            <input name="cadastrarSala" id="submitCadastrarSala" type="submit" value="Criar" d>
            <button onclick="document.querySelector('#form_cria_sala').style.display = 'none';">cancelar</button>
        </form>
    </div>

</body>
<script src="js/script.js"></script>

</html>