<!-- CODIGO BACK -->

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
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inicial - Aluno</title>
    <link rel="stylesheet" href="css/style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link rel="stylesheet" href="css/mediaQuery.css" />
    <script src="https://unpkg.com/scrollreveal"></script>
  </head>
  <body>
    <header class="topo-inicial">
      <img
        width="140"
        class="logo-inicial"
        src="img/logo-senai-branco.png"
        alt=""
      />

      <div class="icons">
        <i
          class="fa fa-user-circle"
          style="color: rgb(255, 255, 255); cursor: pointer"
        ></i>
        <input
          type="checkbox"
          role="button"
          aria-label="Display the menu"
          class="menu"
        />
      </div>
    </header>

    <main class="corpo-inicial">
      <div class="bem-vindo">
        <h2>Bem vindo(a), Thalita!</h2>
        <a href="entrarTurmaNova.html"
          ><i class="fa fa-plus"></i> Acessar uma nova Turma</a
        >
      </div>
      <div class="pesquisa">
        <input
          style="font-size: 16px"
          placeholder="Pesquise por uma turma"
          class="pesquisar"
          type="text"
        />
      </div>

      <div class="aviso" style="display: none">
        <h3>Ops!</h3>
        <p>
          Você ainda não está cadastrado em nenhuma turma.
          <a href="entrarTurmaNova.php">Clique aqui</a> para entrar em uma, ou
          acesse o menu.
        </p>
      </div>
      <div class="turmas">
        <?php
    $conteudo = $mysqli ->
        query($sqlConteudoCard); while ($row = mysqli_fetch_assoc($conteudo)){?>
        <div class="card">
          <a href="salaAluno.php?sala=<?php echo $row['id']?>">
            <div class="infos">
              <p><?php echo $row['turma']?></p>
              <p><?php echo $row['nome']?></p>
            </div>
            <div class="rodape">
              <i>
                <img src="img/svg/seta.svg" alt="" />
              </i>
            </div>
          </a>
        </div>
        <?php
    }?>
      </div>
    </main>
  </body>
  <script src="js/reveal.js"></script>
</html>
