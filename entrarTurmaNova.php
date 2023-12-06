<?php
    session_start();
    $idAluno = $_SESSION['idAcesso'];
    include("conexao.php");

    $salaNaoEncontrada = false;
    $erroInsert = false;

    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET ["codigoTurma"])){
        $codigoTentado = filter_input(INPUT_GET, 'codigoTurma');

        $encontrarSala = "SELECT * FROM sala WHERE sala.codigo_acesso = '$codigoTentado'";
        if($sala = mysqli_fetch_assoc($mysqli->query($encontrarSala))){
            $salaEncontrada = $sala['id'];
            $adicionaAlunoEmSala = "INSERT INTO lista_aluno_sala (`id_aluno`, `id_sala`) VALUES ('$idAluno', '$salaEncontrada')";
            if($mysqli->query($adicionaAlunoEmSala)){
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
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Nova Turma</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="css/mediaQuery.css" />
    <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png"/>
  </head>
  <body>

  <header class="topo-index">
    <a href="inicialAluno.html"><img width="200" class="logo-inicial" src="img/svg/logo_senai_vermelho.svg" /></a>
  </header>

  <main class="conteudoTurmaNova">
    <form action="" method="GET">
     
    <div class="areaCod">
      <h2>Código da Turma</h2>
      <label for="">Digite o código compartilhado pelo professor</label>
      <input type="text" name="codigoTurma" placeholder="Código da nova turma">
    </div>

    <div class="botoesTurmaNova">
      <input type="submit" value="Entrar">
      <input onclick="retornar()" type="button" value="Cancelar">
    </div>
    
    
  </form>
  </main>

     <?php
        if($erroInsert){
            echo "<p>Ops, não foi possível entrar nessa sala</p>";
        }
        if($salaNaoEncontrada){
            echo "<p>Ops, sala não encontrada</p>";
        }
    ?> 

    <script>
      function retornar(){
          window.location.href = "homeAluno.php";
      }
  </script>
    
    <script src="js/script.js"></script>
</body>
</html>

<?php
    if (filter_input(INPUT_GET, 'e')) {
        $mensagem_erro = filter_input(INPUT_GET, 'e');
        echo '<script>erroLogin('.$mensagem_erro.')</script>';
    }
?>