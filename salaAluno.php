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
//$sql = 'SELECT codigo_acesso FROM sala WHERE id = ?';
//stmt do sql
// $stmt = $mysqli->prepare($sql);

// if ($stmt) {
//     //aplicando o id da sala ao  parametro da consulta
//     $stmt->bind_param("i", $sala);
//     //verificando se o sql executou
//     if ($stmt->execute()) {
//         //resganto o resultado
//         $stmt->bind_result($codigoTurma);
//         if ($stmt->fetch()) {
//             echo 'Código de acesso da turma: ' . $codigoTurma;
//         }
//         $stmt->close();
//     }
// } else {
//     echo $mysqli->error;
// }
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Máquinas de
        <?php echo $nomeTurma ?>
    </title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/mediaQuery.css" />
    <script src="https://unpkg.com/scrollreveal"></script>
    <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png"/>
    <style>
        .setaVoltar{
            width: 50px;
            align-self: center;
            justify-self: start;
        }
        .temaCardId{
            display:flex;
            justify-content: space-around;
        }
        .ledOff,
        .ledVerde,
        .ledVermelho{
            display: flex;
            align-self: center;
            margin-left:12px;
            width: 20px;
            height:20px;
            border-radius:100px;
            background-color: grey;
        }
        .ledVerde{
            background-color: rgb(98, 199, 98);
        }
        .ledVermelho{
            background-color: rgb(226, 96, 96);
        }
    </style>
</head>

<body>
    <header class="topo-inicial">
        <a href="homeAluno.php" class="setaVoltar">
            <img src="img/svg/setaVoltar.svg" alt="voltar">
        </a>
        <img width="200" class="logo-inicial" src="img/svg/logo_senai_vermelho.svg" />
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

                    $ledVerde = $rowMaquina['led_verde'];
                    $ledVermelho = $rowMaquina['led_vermelho'];
                    //utilizando a funcao string base64 do php para coverter a imagem, e aplicar ela no html

                    $imagem= $rowMaquina['imagem'];
                    
                    if($imagem==null){
                        $sqlBuscaImgPadrao= "SELECT imagem_padrao FROM tipo_maquina WHERE id = $tipoId";
                        $resultadoImgPadrao = $mysqli->query($sqlBuscaImgPadrao);
                        $rowImgPadrao = $resultadoImgPadrao->fetch_assoc();
                        $imagem = $rowImgPadrao['imagem_padrao'];
                    }
                    
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
                                <div class="temaCardId" id="ID-maquina">
                                    <?php echo 'ID: ' . $rowMaquina['id'];?>
                                    <?php 
                                        if($ledVerde == "ON"){
                                            echo '<div class="ledVerde"></div>';
                                        }else if($ledVermelho == "ON"){
                                            echo '<div class="ledVermelho"></div>';
                                        }else{
                                            echo '<div class="ledOff"></div>';
                                        }
                                    ?>
                                </div>

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
        
    </main>
</body>
<script>
    //JS DA PESQUISA
    document.addEventListener("DOMContentLoaded", function () {
        const campoPesquisa = document.getElementById("pesquisa-campo");
        const turmas = document.querySelectorAll(".card-maquina");
        const maquinaNotFound = document.querySelectorAll(".maquinaNotFound");

        campoPesquisa.addEventListener("input", function () {
            const termoPesquisa = campoPesquisa.value.trim().toLowerCase();

            turmas.forEach(function (turma) {
                const maquinaNome = turma.querySelector("#modelo-maquina").textContent.toLowerCase();
                const maquinaID = turma.querySelector("#ID-maquina").textContent.toLowerCase();

                if (maquinaNome.includes(termoPesquisa) || maquinaID.includes(termoPesquisa)) {
                    turma.style.opacity = "1";
                    maquinaNotFound.style.display = "none";
                } else {
                    turma.style.opacity = "0";
                    maquinaNotFound.style.display = "block";
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
