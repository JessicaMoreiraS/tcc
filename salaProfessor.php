<?php
include("conexao.php");
session_start();
$id = $_SESSION['idAcesso'];
$sala = $_GET['sala'];

$sql = "SELECT turma FROM sala WHERE id=?";
$stmt = $mysqli->prepare($sql);


if ($stmt) {
    //aplicando o id da sala ao  parametro da consulta
    $stmt->bind_param("i", $sala);
    //verificando se o sql executou
    if ($stmt->execute()) {
        //resgantando o resultado
        $stmt->bind_result($nomeTurma);
        $stmt->fetch();
        $stmt->close();
    }
} else {
    echo $mysqli->error;
}

//buscar o codigo acesso da turma
$sql = 'SELECT codigo_acesso FROM sala WHERE id = ?';
//stmt do sql
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    //aplicando o id da sala ao  parametro da consulta
    $stmt->bind_param("i", $sala);
    //verificando se o sql executou
    if ($stmt->execute()) {
        //resganto o resultado
        $stmt->bind_result($codigoTurma);
        if ($stmt->fetch()) {
            echo 'Código de acesso da turma: ' . $codigoTurma;
        }
        $stmt->close();
    }
} else {
    echo $mysqli->error;
}
echo '<a href="update.php?option=sala&editarTurma&id_atualizacao=' . $sala . '">Editar turma</a>';
echo '<br>';
echo '<a href="delete.php?option=sala&id_delecao= '.$sala.'" >Deletar Turma</a>';
//////////

//buscar tipos de máquinas disponíveis na turma
$sqlTipos = "SELECT DISTINCT tipo_maquina.id, tipo_maquina.tipo FROM tipo_maquina
             INNER JOIN lista_sala_tipo_maquina ON lista_sala_tipo_maquina.id_tipo_maquina = tipo_maquina.id
             WHERE lista_sala_tipo_maquina.id_sala = ?";

$tiposDaSalaId = [];
$tiposDaSalaNome = [];
$stmt = $mysqli->prepare($sqlTipos);
if ($stmt) {
    $stmt->bind_param("i", $sala);
    if ($stmt->execute()) {
        $stmt->bind_result($tipoId, $tipoNome);
        while ($stmt->fetch()) {
            $tiposDaSalaId[] = $tipoId;
            $tiposDaSalaNome[] = $tipoNome;
        }
        $stmt->close();
    }
} else {
    echo $mysqli->error;
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Máquinas de
        <?php echo $nomeTurma ?>
    </title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/mediaQuery.css" />
    <script src="https://unpkg.com/scrollreveal"></script>
</head>

<body>
    <header class="topo-inicial">
        <?php  echo '
      <a href="visualizar.php?view=alunosSala&id_sala_view=' . $sala . '">' ?>
            <img src="" alt="botao para ver os alunos">
        </a>
        <img width="140" class="logo-inicial" src="img/logo-senai-branco.png" alt="" />
        <div class="icons">
            <i class="fa fa-user-circle" style="color: rgb(255, 255, 255); cursor: pointer"></i>
            <input type="checkbox" role="button" aria-label="Display the menu" class="menu" />
        </div>
    </header>
    <main class="corpo-inicial" id="main_maquinasDaTurmas">
        <div class="bem-vindo">
            <h2>Máquinas disponíveis em
                <?php echo $nomeTurma ?>
            </h2>
        </div>
        <div class="pesquisa">
            <input id="pesquisa-campo" style="font-size: 16px" placeholder="Pesquise por um modelo ou ID"
                class="pesquisar" type="text" />
        </div>
        <div class="maquinas">
            <?php
            for ($i = 0; $i < count($tiposDaSalaId); $i++) {
                $tipoId = $tiposDaSalaId[$i];
                $tipoNome = $tiposDaSalaNome[$i];
                $sqlBuscaMaquinas = "SELECT * FROM maquina WHERE maquina.id_tipo_maquina = $tipoId";
                ?>

                <?php
                $conteudo = $mysqli->query($sqlBuscaMaquinas);
                while ($rowMaquina = mysqli_fetch_assoc($conteudo)) {

                    //utilizando a funcao string base64 do php para coverter a imagem, e aplicar ela no html
                    $imagem = $rowMaquina['imagem'];
                    $imagem_base64 = base64_encode($imagem);

                    ?>
                    <!--Card da maquina-->
                    <div class="card card-maquina">
                        <div class="imgBx" style="--clr:#009688;">
                            <img title="<?php $tipoNome ?>" src="data:image/jpeg;base64,<?php echo $imagem_base64; ?>">
                        </div>
                        <div class="content">
                            <h2 id="modelo-maquina">
                                <?php echo $rowMaquina['modelo'] ?>
                                <p id="ID-maquina">
                                    <?php echo 'ID: ' . $rowMaquina['id'] ?>
                                </p>

                            </h2>
                            <p>
                                <?php echo 'Fabricante:' . $rowMaquina['fabricante'] ?>
                            </p>
                            <div>
                                <a href="<?php echo "checklist.php?id_maquina=" . $rowMaquina['id'] ?>">Acessar</a>
                            </div>
                        </div>
                    </div>

                    <?php
                }

            }

            ?>
        </div>
        </div>
    </main>
</body>
<script>
    //JS DA PESQUISA
    document.addEventListener("DOMContentLoaded", function () {
        const campoPesquisa = document.getElementById("pesquisa-campo");
        const turmas = document.querySelectorAll(".card-maquina");

        campoPesquisa.addEventListener("input", function () {
            const termoPesquisa = campoPesquisa.value.trim().toLowerCase();

            turmas.forEach(function (turma) {
                const maquinaNome = turma.querySelector("#modelo-maquina").textContent.toLowerCase();
                const maquinaID = turma.querySelector("#ID-maquina").textContent.toLowerCase();

                if (maquinaNome.includes(termoPesquisa) || maquinaID.includes(termoPesquisa)) {
                    turma.style.opacity = "1";


                } else {
                    turma.style.opacity = "0";
                }
            });
        });
    });
</script>
 <!-- sweetalert2 -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</html>