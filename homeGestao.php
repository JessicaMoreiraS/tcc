<?php
session_start();
if($_SESSION['idAcesso'] != 'gestaoSenai'){
    header('Location: index.html');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/mediaQuery.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <script src="https://unpkg.com/scrollreveal"></script>
    <title>Home - Gestão</title>
  </head>
  <body id="body_homeGestao">
    <header class="topo-index" id="header_homeGestao">
      <img src="img/logo-senai-branco.png" alt="" />
    </header>


    <main id="main_homeGestao">
      <div class="titulo">
        <h2>GES <b>TÃO</b></h2>
        <p>Bem-vindo(a) ao gerenciamento</p>
      </div>

      <section class="section_cards">
        <div class="card" >
          <div class="conteudo_card">
            <div class="img">
                <img src="img/professorICONE.png" alt="" />
              </div>
              <div class="textos">
                <div class="tituloCard">
                  <p>Gerenciamento de <b>Professores</b></p>
                </div>
                <div class="descricao">
                    Cadastrar, excluir , visualizar ou editar algum Professor de acordo com a necessidade. Com acesso a todas informações dos professores 
                </div>
              </div>
              <div class="btn">
                <a href="vizualizar.php?view=professor"><i class="fa fa-arrow-right"></i></a>
              </div>
          </div>
        </div>
        <div class="card">
          <div class="conteudo_card">
            <div class="img">
                <img src="img/escolaLivroICONE.png" alt="" />
              </div>
              <div class="textos">
                <div class="tituloCard">
                  <p>Gerenciamento de <b>Turmas</b></p>
                </div>
                <div class="descricao">
                    Excluir , visualizar ou editar alguma Turma de acordo com a necessidade. Com acesso aos alunos e professores de cada turma 
                </div>
              </div>
              <div class="btn">
                <a href="vizualizar.php?view=aluno"><i class="fa fa-arrow-right"></i></a>
              </div>
          </div>
        </div>
        <div class="card">
          <div class="conteudo_card">
            <div class="img">
                <img src="img/ferramentasICONE.png" alt="" />
              </div>
              <div class="textos">
                <div class="tituloCard">
                  <p>Gerenciamento de <b>Máquinas</b></p>
                </div>
                <div class="descricao">
                  Cadastrar, excluir , visualizar ou editar alguma máquina de acordo com a necessidade.
                </div>
              </div>
              <div class="btn">
                <a href="vizualizar.php?view=maquina"><i class="fa fa-arrow-right"></i></a>
              </div>
          </div>
        </div>
        <div class="card">
          <div class="conteudo_card">
            <div class="img">
                <img src="img/ferramentasICONE.png" alt="" />
              </div>
              <div class="textos">
                <div class="tituloCard">
                  <p>Gerenciamento de <b>Tipo de Máquinas</b></p>
                </div>
                <div class="descricao">
                 Gerenciamento do Tipo da máquina
                </div>
              </div>
              <div class="btn">
                <a href="vizualizar.php?view=tipo"><i class="fa fa-arrow-right"></i></a>
              </div>
          </div>
        </div>
      </section>
    </main>
  </body>
  <script src="js/reveal.js"></script>
</html>

<!-- Criar cadastros no database
   <div>
    <a href="cadastrar.php?option=professor">Cadastrar novo Professor</a>
    <a href="cadastrar.php?option=tipo">Cadastrar nova categoria de maquinário</a>
    <a href="cadastrar.php?option=maquina">Cadastrar nova maquina</a>
</div>
