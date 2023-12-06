<?php
//inicisnd sessão 
session_start();
$paginasPermitemAcesso = ["login.php", "cadastro.php", "salaProfessor.php", "homeProfessor.php", "update.php", "visualizar.php", "Checklist.php"];
foreach ($paginasPermitemAcesso as $pagina) {
  $len = count($paginasPermitemAcesso);
  if (!strpos($_SERVER['HTTP_REFERER'], $pagina) && $pagina == $paginasPermitemAcesso[$len - 1] || $_SESSION['tipo'] != 'professor') {
    header('Location: index.html');
  } else {
    if (strpos($_SERVER['HTTP_REFERER'], $pagina)) {
      break;
    }
  }
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
  <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="css/mediaQuery.css" />
  <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png" />
  <script src="https://unpkg.com/scrollreveal"></script>
  <title>
    <?php echo $professorNome ?>- Inicial
  </title>
  <style>
    .setaVoltar {
      width: 50px;
      align-self: center;
      justify-self: start;
    }
    .logo-inicial{
      margin: 0 auto;
      padding: 0;
    }
  </style>
</head>

<body id="body_homeProfessor">

  <header class="topo-inicial">
    <a href="login.php" class="setaVoltar">
      <img src="img/svg/setaVoltar.svg" alt="voltar">
    </a>
    <img width="200" class="logo-inicial" src="img/svg/logo_senai_vermelho.svg" />

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

    </div>

  </header>


  <main id="main_homeProfessor">
    <section>
      <div class="bem-vindo">
        <h2>Bem vindo(a),<b>
            <?php echo $professorNome ?>
          </b>!</h2>
      </div>
      <div class="btnCriarSala">
        <a onclick="exibirFormCriarSala()" id="criar_turma" href="#form_cria_sala">
          <i class="fa fa-plus"></i>
          Criar Turma
        </a>
      </div>
      <div class="pesquisa">
        <input id="pesquisa-campo" style="font-size: 16px" placeholder="Pesquise por uma turma" class="pesquisar" type="text" />
      </div>
    </section>
    <div class="turmas">
      <?php
      //while que imprime os card (logica do Home Aluno)
      $conteudo = $mysqli->query($sqlConteudoCard);
      if ($conteudo && $conteudo->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($conteudo)) { ?>
          <div class="card turma-card">
            <div class="infos">
              <p id="first_p">
                Turma:
                <?php echo $row['turma'];
                ?>
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
      } else {
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

  <section id="section_form" style="display: none;">
    <div class="form_cadastrarTurma">
      <div class="container">
        <div class="header">
          <div class="logo">
            <img width="200" class="logo-inicial" src="img/svg/logo_senai_vermelho.svg" />
          </div>
          <div class="icone">
            <img src="img/svg/icone_chapeu_academico.svg" alt="" />
          </div>

        </div>
        <form id="form_cria_sala" action="cadastro.php" method="POST">
          <input name="idProfessor" type="text" value="1" hidden>
          <div class="input_nome">
            <input type="text" placeholder="Nome da Turma" name="nomeSala" required />
          </div>
          <div class="checkboxes">
            <div class="titulo">
              <p>Máquinas que serão disponibilizadas:</p>
            </div>
            <?php
            $conteudo = $mysqli->query($sqlConteudoRadios);
            while ($radio = mysqli_fetch_assoc($conteudo)) {
              echo
              ' <label for="tipo_maquina" class="cyberpunk-checkbox-label">
                    <input  class="cyberpunk-checkbox" type="checkbox" id="' . $radio['tipo'] . '" name="maquinas[]" value="' . $radio['tipo'] . '">  
                    ' . $radio['tipo'] . '
                </label>';
            };
            ?>
          </div>
          <div class="bnts">
            <input disabled name="cadastrarSala" id="submitCadastrarSala" type="submit" value="Criar">
            <button id="cancelar_button">cancelar</button>

          </div>
        </form>
      </div>
    </div>
  </section>

</body>
<script src="js/reveal.js"></script>
<script>
  //TRECHO DO CODIGO QUE LIBERA A CRIAÇÃO DE SALA, HOME PROFESSOR
  document.addEventListener("DOMContentLoaded", function() {
    // aplicando os inputs qye precisam ser preechidos em variaveis
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const cadastrarSalaButton = document.querySelector('#submitCadastrarSala');

    //  ouvinte de eventos de mudança a todos os checkboxes
    checkboxes.forEach(function(checkbox) {
      checkbox.addEventListener('change', verificarFormulario);
    });


    // Funçao que verificar o formulario(para habiliyar ou na a criação)
    function verificarFormulario() {
      // Veridica se pelo menos um checkbox esta selecionado e o codigo esta preenchido
      const checkboxSelecionado = Array.from(checkboxes).some(checkbox => checkbox.checked);
      // Habilita ou desabilita a criaçao
      if (checkboxSelecionado) {
        cadastrarSalaButton.removeAttribute('disabled');
      } else {
        cadastrarSalaButton.setAttribute('disabled', 'disabled');
      }
    }
  });
  ////////////////////////////////////////////////// 
  //SCRIPTS DO HOME PROFESSOR//

  //front-end do botao de criar turma
  var from_criar_sala = document.getElementById("section_form");
  var mostrarBotao = document.getElementById("criar_turma");
  var ocultarBotao = document.getElementById("cancelar_button");
  mostrarBotao.addEventListener("click", function() {
    from_criar_sala.style.display = "block";
  });
  ocultarBotao.addEventListener("click", function() {
    from_criar_sala.style.display = "none";
  });
  ////////



    //JS DA PESQUISA
    document.addEventListener("DOMContentLoaded", function() {
        const campoPesquisa = document.getElementById("pesquisa-campo");
        const turmas = document.querySelectorAll(".turma-card");

        campoPesquisa.addEventListener("input", function() {
            const termoPesquisa = campoPesquisa.value.trim().toLowerCase();

            turmas.forEach(function(turma) {
                const turmaNome = turma.querySelector("#first_p").textContent.toLowerCase();

                if (turmaNome.includes(termoPesquisa)) {
                    turma.style.opacity = "1";
                } else {
					turma.style.opacity = "0";
                }
            });
        });
    });




  //JS PARA HABILITAR O BOTAO DE CRIAR A SALA NO FORMULARIO CRIAR SALA
  const checkboxes = form_cria_sala.querySelectorAll("input");
  const habilitarCriarSala = false;
  const CriarSalaButton = document.getElementById("submitCadastrarSala");

  checkboxes.foreach(function(input) {
    if (checkboxes.checked || !checkboxes.value == " ") {
      CriarSalaButton.removeAttribute('disabled')
    }
  });


</script>

<script src="js/script.js"></script>
</html>

<?php
if (filter_input(INPUT_GET, 'e')) {
  $mensagem_erro = filter_input(INPUT_GET, 'e');
  echo '<script>erroLogin(' . $mensagem_erro . ')</script>';
}
?>