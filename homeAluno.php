<!-- CODIGO BACK -->

<?php
session_start();
$paginasPermitemAcesso = ["homeAluno.php","login.php", "confirmarEmail.php", "direcionamento.php", "confirmarEmail.php", "salaAluno.php", "Checklist.php", "checklist.php", "entrarTurmaNova.php", "update.php"];
foreach ($paginasPermitemAcesso as $pagina) {
  $len = count($paginasPermitemAcesso);
  if (!strpos($_SERVER['HTTP_REFERER'], $pagina) && $pagina == $paginasPermitemAcesso[$len-1] || $_SESSION['tipo'] != 'aluno') {
    header('Location: index.html');
  }else{
    if(strpos($_SERVER['HTTP_REFERER'], $pagina)){
      break;
    }
  }
}


$idAluno = $_SESSION['idAcesso'];

include("conexao.php");
$sqlAluno = "SELECT * FROM aluno WHERE id = " . $idAluno;
$alunoNome = mysqli_fetch_assoc(($mysqli->query($sqlAluno)))['nome'];

$sqlConteudoCard = "SELECT * FROM lista_aluno_sala LEFT JOIN sala ON sala.id = lista_aluno_sala.id_sala LEFT JOIN professor ON professor.id = sala.id_professor WHERE lista_aluno_sala.id_aluno = $idAluno";



//detela a ligacao entre o aluno e a sala
if (isset($_GET["salaSair"])) {
	$idLista = filter_input(INPUT_GET, 'salaSair');

	$sqlDetetaLigacaoAlunoSala = "DELETE FROM lista_aluno_sala WHERE id_lista='$idLista'";
	if ($mysqli->query($sqlDetetaLigacaoAlunoSala)) {
		header('location: homeAluno.php');
	} else {
		header('location: homeAluno.php?e=9');
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
	<title>Inicial - Aluno</title>
	<link rel="stylesheet" href="css/style.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
	<link rel="stylesheet" href="css/mediaQuery.css" />
	<script src="https://unpkg.com/scrollreveal"></script>
	<link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png"/>

	<style>
        .setaVoltar{
            width: 50px;
            align-self: center;
            justify-self: start;
        }
    </style>
</head>

<body id="body_homeAluno">
	<header class="topo-inicial">
		<a href="login.php" class="setaVoltar">
			<img src="img/svg/setaVoltar.svg" alt="voltar">
		</a>

		<img width="200" class="logo-inicial" src="img/svg/logo_senai_vermelho.svg" />

		<div class="icons">
			<?php
			echo '
      <a href="update.php?option=aluno&id_atualizacao=' . $idAluno . '">
         <i
           class="fa fa-user-circle"
           style="color: rgb(255, 255, 255); cursor: pointer"
         ></i>
      </a>';

			?>
			<input type="checkbox" role="button" aria-label="Display the menu" class="menu" />
		</div>
	</header>

	<main class="corpo-inicial">
		<div class="bem-vindo">
			<h2>Bem vindo(a),
				<?php echo $alunoNome ?>!
			</h2>
			<a href="entrarTurmaNova.php">
				<i class="fa fa-plus"></i> Acessar uma nova Turma
			</a>
		</div>
		<div class="pesquisa">
			<input id="pesquisa-campo" style="font-size: 16px" placeholder="Pesquise por uma turma ou professor" class="pesquisar" type="text" />
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





			$conteudo = $mysqli->query($sqlConteudoCard);
			if ($conteudo && $conteudo->num_rows > 0) {
				while ($row = mysqli_fetch_assoc($conteudo)) { ?>


					<div class="card turma-card">
						<div class="infos">
							<p id="first_p">
								<?php echo $row['turma']; ?>
							</p>
							<p id="second_p">
								<?php echo 'Professor(a): '. $row['nome']; ?>
							</p>

							<a id="a_sairSala" href="homeAluno.php?salaSair=<?php echo $row['id_lista'] ?>"> <img  src="img/svg/Logout.svg" width="30"> Sair da turma</a>
						</div>
						<a href="salaAluno.php?sala=<?php echo $row['id'] ?>">
							<div class="rodape">
								
									<img src="img/svg/seta.svg" alt="" />
							
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
		</div>
	</main>
</body>
<script src="js/reveal.js"></script>
<script>

//JS DA PESQUISA
document.addEventListener("DOMContentLoaded", function() {
        const campoPesquisa = document.getElementById("pesquisa-campo");
        const turmas = document.querySelectorAll(".turma-card");

        campoPesquisa.addEventListener("input", function() {
            const termoPesquisa = campoPesquisa.value.trim().toLowerCase();

            turmas.forEach(function(turma) {
                const turmaNome = turma.querySelector("#first_p").textContent.toLowerCase();
                const turmaProfessor = turma.querySelector("#second_p").textContent.toLowerCase();

                if (turmaNome.includes(termoPesquisa) || turmaProfessor.includes(termoPesquisa)) {
                    turma.style.opacity = "1";
                } else {
					turma.style.opacity = "0";
                }
            });
        });
    });
</script>
	<script src="js/script.js"></script>
</html>
<?php
    if (filter_input(INPUT_GET, 'e')) {
        $mensagem_erro = filter_input(INPUT_GET, 'e');
        echo '<script>erroLogin('.$mensagem_erro.')</script>';
    }
?>