<?php
session_start();
$paginasPermitemAcesso = ["login.php", "homeGestao.php", "cadastrarMaquina.php", "gestaoAlunos.php", "cadastrarProfessor.php", "visualizar.php"];
foreach ($paginasPermitemAcesso as $pagina) {
  $len = count($paginasPermitemAcesso);
  if (!strpos($_SERVER['HTTP_REFERER'], $pagina) && $pagina == $paginasPermitemAcesso[$len-1] || $_SESSION['tipo'] != 'gestor') {
    header('Location: index.html');
  }else{
    if(strpos($_SERVER['HTTP_REFERER'], $pagina)){
      break;
    }
  }
}
$permitido = false;

// for($i = 0; $i < count($paginasPermitemAcesso); $i++){
//     // echo $paginasPermitemAcesso[$i];
//     if(!strpos($_SERVER['HTTP_REFERER'], $paginasPermitemAcesso[$i])){
//         if(($i == count($paginasPermitemAcesso)-1 && !$permitido) || $_SESSION['tipo'] != 'gestor'){
//             // echo $paginasPermitemAcesso[$i];
//             header('Location: index.html');
//         }
//     }else{
//         $permitido=true;
//     }
// }
$idGestor = $_SESSION['idAcesso'];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/mediaQuery.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png"/>
    <script src="https://unpkg.com/scrollreveal"></script>
    <title>Inicial - Gestão</title>
    <style>
        .setaVoltar{
            width: 50px;
            align-self: center;
            justify-self: start;
        }
        .logo-inicial{
                margin:0 auto;
                padding-left:0;
        }
    </style>
</head>

<body id="body_homeGestao">

    <header class="topo-inicial">
        <a href="login.php" class="setaVoltar">
            <img src="img/svg/setaVoltar.svg" alt="voltar">
        </a>
        <img  class="logo-inicial" width="200" src="img/svg/logo_senai_vermelho.svg"  class="logoCenter" />

        <div class="icons">
            <?php
            echo '
      <a href="update.php?option=gestor&id_atualizacao=' . $idGestor . '">
         <i
           class="fa fa-user-circle"
           style="color: rgb(255, 255, 255); cursor: pointer"
         ></i>
      </a>';

            ?>
        </div>
    </header>



    <main id="main_homeGestao">
        <div class="titulo">
            <h2>GES <b>TÃO</b></h2>
            <p>Bem-vindo(a) ao gerenciamento</p>
        </div>

        <section class="section_cards">
            <div class="card">
                <div class="conteudo_card">
                    <div class="img">
                        <img src="img/professorICONE.png" alt="" />
                    </div>
                    <div class="textos">
                        <div class="tituloCard">
                            <p>Gerenciamento de <b>Professores</b></p>
                        </div>
                        <div class="descricao">
                            Cadastrar, excluir ou visualizar algum Professor de acordo com a necessidade. Com acesso a
                            todas informações dos professores
                        </div>
                    </div>
                    <div class="btn">
                        <a href="visualizar.php?view=professor"><i class="fa fa-arrow-right"></i></a>
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
                            Excluir , visualizar ou editar alguma Turma de acordo com a necessidade. Com acesso aos
                            alunos de cada turma
                        </div>
                    </div>
                    <div class="btn">
                        <a href="visualizar.php?view=sala"><i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="conteudo_card">
                    <div class="img">
                        <img src="img/svg/Student_white.svg">
                    </div>
                    <div class="textos">
                        <div class="tituloCard">
                            <p>Gerenciamento de <b>Alunos</b></p>
                        </div>
                        <div class="descricao">
                            Excluir ou visualizar algum aluno cadastrado, de acordo com a necessidade.
                        </div>
                    </div>
                    <div class="btn">
                        <a href="visualizar.php?view=aluno"><i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="conteudo_card">
                    <div class="img">
                        <img src="img/svg/checklist_white.svg">
                    </div>
                    <div class="textos">
                        <div class="tituloCard">
                            <p>Gerenciamento de <b>Checklists</b></p>
                        </div>
                        <div class="descricao">
                            Visualizar checklists feitas por professores ou alunos.
                        </div>
                    </div>
                    <div class="btn">
                        <a href="visualizar.php?view=checklist"><i class="fa fa-arrow-right"></i></a>
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
                        <a href="visualizar.php?view=maquina"><i class="fa fa-arrow-right"></i></a>
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
                        <a href="visualizar.php?view=tipo"><i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="js/script.js"></script>
</body>
<script src="js/reveal.js"></script>

</html>
<?php
    if (filter_input(INPUT_GET, 'e')) {
        $mensagem_erro = filter_input(INPUT_GET, 'e');
        echo '<script>erroLogin('.$mensagem_erro.')</script>';
    }
?>