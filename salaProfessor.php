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
        //resganto o resultado
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
//////////

//buscar tipos de maquinas disponeis na turma
$sqlTipos = "SELECT * FROM tipo_maquina INNER JOIN lista_sala_tipo_maquina ON lista_sala_tipo_maquina.id_tipo_maquina = tipo_maquina.id";

$tiposDaSalaId = [];
$tiposDaSalaNome = [];
$conteudoTipo = $mysqli->query($sqlTipos);
while ($rowTipo = mysqli_fetch_assoc($conteudoTipo)) {
    $addId = $rowTipo['id'];
    $addTipo = $rowTipo['tipo'];
    array_push($tiposDaSalaId, $addId);
    array_push($tiposDaSalaNome, $addTipo);
}

//$sqlBuscaMaquinas = "SELECT * FROM tipo_maquina RIGHT JOIN lista_sala_tipo_maquina ON tipo_maquina.id = lista_sala_tipo_maquina.id_tipo_maquina WHERE ";

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
            <input style="font-size: 16px" placeholder="Pesquise por uma máquina" class="pesquisar" type="text" />
        </div>
        <div class="maquinas">
            <?php

            for ($i = 0; $i < count($tiposDaSalaId); $i++) {
                //print_r($tiposDaSalaId);
                //print_r($tiposDaSalaNome);
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
                    <div class="card">
                        <div class="imgBx" style="--clr:#009688;">
                            <img title="<?php $tipoNome ?>" src="data:image/jpeg;base64,<?php echo $imagem_base64; ?>">
                        </div>
                        <div class="content">
                            <h2>
                                <?php echo $rowMaquina['modelo'] ?>
                                <p>
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

</html>