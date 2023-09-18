<!-- CODIGO BACK -->

<?php
		session_start();
		$idAluno = $_SESSION['idAcesso'];

		include("conexao.php");
		$sqlAluno = "SELECT * FROM aluno WHERE id = ".$idAluno;
		$alunoNome = mysqli_fetch_assoc(($mysqli->query($sqlAluno)))['nome'];

		$sqlConteudoCard = "SELECT * FROM lista_aluno_sala LEFT JOIN sala ON sala.id = lista_aluno_sala.id_sala LEFT JOIN professor ON professor.id = sala.id_professor WHERE lista_aluno_sala.id_aluno = $idAluno";


		//detela a ligacao entre o aluno e a sala
		if (isset($_GET["salaSair"]) ) {
			$idLista = filter_input(INPUT_GET, 'salaSair');

			$sqlDetetaLigacaoAlunoSala = "DELETE FROM lista_aluno_sala WHERE id_lista='$idLista'";
			if($mysqli->query($sqlDetetaLigacaoAlunoSala)){
					header('location: homeAluno.php');
			}else{
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
				<h2>Bem vindo(a), <?php echo $alunoNome?>!</h2>
				<a href="entrarTurmaNova.php">
					<i class="fa fa-plus"></i> Acessar uma nova Turma
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
				$conteudo = $mysqli -> query($sqlConteudoCard); 
				while ($row = mysqli_fetch_assoc($conteudo)){?>
				

				<div class="card">
					<a href="salaAluno.php?sala=<?php echo $row['id']?>">
						<div class="infos">
							<p id="first_p"><?php echo $row['turma'];?></p>
							<p id="second_p"><?php echo $row['nome'];?></p>
								
							<a href="homeAluno.php?salaSair=<?php echo $row['id_lista']?>">Sair da turma</a>
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
<?php
		if(filter_input(INPUT_GET, 'e')){
				$mensagem_erro = filter_input(INPUT_GET, 'e');
				echo '<script>erroLogin('.$mensagem_erro.')</script>';
		}
?>