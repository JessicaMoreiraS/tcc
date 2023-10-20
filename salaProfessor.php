<?php
session_start();
$id = $_SESSION['idAcesso'];
$sala = $_GET['sala'];

include("conexao.php");

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
$sqlTipos = "SELECT * FROM tipo_maquina LEFT JOIN lista_sala_tipo_maquina ON lista_sala_tipo_maquina.id_tipo_maquina = tipo_maquina.id";

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
    <title>Suas salas</title>
</head>

<body>

    <?php
    for ($i = 0; $i < count($tiposDaSalaId); $i++) {
        //print_r($tiposDaSalaId);
        //print_r($tiposDaSalaNome);
        $tipoId = $tiposDaSalaId[$i];
        $tipoNome = $tiposDaSalaNome[$i];
        $sqlBuscaMaquinas = "SELECT * FROM maquina WHERE maquina.id_tipo_maquina = $tipoId";
        ?>
        <div>
            <h3>
                <?php echo $tipoNome ?>
            </h3>
            <?php
            $conteudo = $mysqli->query($sqlBuscaMaquinas);
            while ($rowMaquina = mysqli_fetch_assoc($conteudo)) {
                ?>
                <a href="<?php echo "Checklist.php?id_maquina=" . $rowMaquina['id'] ?>">
                    <!--Card da maquina-->
                    <!-- <img src="<?php echo $rowMaquina['imagem'] ?>"> -->
                    <h2>
                        <?php echo $rowMaquina['modelo'] ?>
                    </h2>
                    <h3>
                        <?php echo $rowMaquina['id'] ?>
                    </h3>
                </a>
            </div>
            <?php
            }
    }
    ?>
</body>

</html>