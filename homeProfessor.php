<?php
//inicisnd sessão 
session_start();
$paginasPermitemAcesso = "login.php";
if (!strpos($_SERVER['HTTP_REFERER'], $paginasPermitemAcesso)) {
    header('Location: index.html');
}

//capturando o id do professor
$idProfessor = $_SESSION['idAcesso'];

//incluindo o banco de dados
include("conexao.php");

//sql para busca do professor
$sqlBuscaProfessor = "SELECT * FROM professor WHERE id =" . $idProfessor;

//busca do nome do professor
$professorNome = mysqli_fetch_assoc(($mysqli->query($sqlBuscaProfessor)))['nome'];

//sql que armazena as infos da sala que lhe esta atribuida
$sqlConteudoCard = "SELECT DISTINCT sala.*, sala.turma
                   FROM sala
                   LEFT JOIN professor ON professor.id = sala.id_professor
                   WHERE sala.id_professor = $idProfessor";

//sql para coletar os tipos de maquinas já cadastrados
$sqlConteudoRadios = "SELECT * FROM tipo_maquina";


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/script.js"></script>
    <script
      src="https://code.jquery.com/jquery-3.7.0.min.js"
      integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g="
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/mediaQuery.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <script src="https://unpkg.com/scrollreveal"></script>
    <title><?php echo $professorNome?>- Inicial</title>
</head>

<body id="body_homeProfessor">

<header class="topo-inicial">
      <img
        width="140"
        class="logo-inicial"
        src="img/logo-senai-branco.png"
        alt=""
      />

      <div class="icons">
      <?php 
      echo '
      <a href="update.php?option=professor&id_atualizacao=' . $idProfessor . '">
         <i
           class="fa fa-user-circle"
           style="color: rgb(255, 255, 255); cursor: pointer"
         ></i>
      </a>';
     
     ?>
        <input
          type="checkbox"
          role="button"
          aria-label="Display the menu"
          class="menu"
        />
      </div>
</header>
    <main id="main_homeProfessor">
    <section>
        <div class="bem-vindo">
          <h2>Bem vindo(a),<b> <?php echo $professorNome ?></b>!</h2>
        </div>
        <div class="btnCriarSala">
          <a onclick="exibirFormCriarSala()">
            <i class="fa fa-plus"></i>
            Criar Turma
          </a>
        </div>
        <div class="pesquisa">
          <input
            style="font-size: 16px"
            placeholder="Pesquise por uma turma"
            class="pesquisar"
            type="text"
          />
        </div>
      </section>

    <div class="turmas">
        <?php
        // //button exibe o formulario criar sala
        // echo '<button onclick="exibirFormCriarSala()">Criar Turma</button>';

        //http que redireciona para o professor editar suas infos
    //     echo '
    //  <a href="update.php?option=professor&id_atualizacao=' . $idProfessor . '">
    //   Atualizar perfil
    //  </a>
    //   ';
        //while que imprime os card (logica do Home Aluno)
        $conteudo = $mysqli->query($sqlConteudoCard);
        if( $conteudo &&  $conteudo->num_rows > 0){
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
                </a>
            </div>
            <?php
        }
        }else{
          echo '
            <section class="sem_turma">
            <div>
            <p>Ops!</p>
            </div>
            <p> Você não está participando de nenhuma turma.</p>
            </section>
            ';
        }
    
        
           
        
        
        
        ?>

       

</main>

        <!-- formulario para criar uma nova sala -->
        <form id="form_cria_sala" action="cadastrarTurma.php" method="POST" style="opacity:0;">
        
            <input name="idProfessor" type="text" value="1" hidden>
          
            <input type="text" placeholder="nome da turma" name="nomeSala" required>
       
            <label for="codigo da sua turma"></label>
            <input type="text" id="codigoTurma" readonly name="codigoSala">
            <a onclick=" gerarCodigoAcesso(10)" style="cursor:pointer">Gerar Código</a>

          
            <div class="radios">
                <?php
                $conteudo = $mysqli->query($sqlConteudoRadios);
                while ($radio = mysqli_fetch_assoc($conteudo)) {
                    echo
                        ' <label for="tipo_maquina">
                        <input type="checkbox" id="' . $radio['tipo'] . '" name="maquinas[]" value="' . $radio['tipo'] . '">  
                        ' . $radio['tipo'] . '
                    </label>';
                }
                ;
                ?>

            </div>
            <input name="cadastrarSala" id="submitCadastrarSala" type="submit" value="Criar" disabled>
            <button onclick="document.querySelector('#form_cria_sala').style.display = 'none';">cancelar</button>
        </form>
    

</body>
<script src="js/reveal.js"></script>
</html>