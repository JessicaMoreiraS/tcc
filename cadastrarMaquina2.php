<?php
include('conexao.php');
if(isset($_POST['idmaquina']) && isset($_POST['modelo']) && isset($_POST['fabricante']) && isset($_POST['id_tipo_maquina'])){
    $id_maquina = $_POST['idmaquina'];
    $id_tipo_maquina = $_POST['id_tipo_maquina'];
    $modelo = $_POST['modelo'];
    $fabricante = $_POST['fabricante'];
    $imagem ="";
    if(isset($_POST['imagem'])){
        $imagem =$_POST['imagem'];
    }

    $existencia = existenciaMaquina($id_maquina);

    if(!$existencia){
        $sqlInsertMaquina = "INSERT INTO maquina (id, id_tipo_maquina, modelo, fabricante, imagem, led_verde, led_amarelo, led_vermelho) VALUES ('$id_maquina', '$id_tipo_maquina', '$modelo', '$fabricante', '$imagem', 'OFF', 'OFF', 'ON')";
        if ($mysqli->query($sqlInsertMaquina)){
            header('location: cadastrarMaquina2.php?e=12');
        } else {
            //header('location: cadastrarMaquina2.php?e=12');
            echo "Erro na atualização: ". $mysqli->error; //teste
        }
    }else{
        header('location: cadastrarMaquina2.php?e=11');
    }
}

function existenciaMaquina($idMaquina){
    include("conexao.php");
    $sqlExistenciaMaquina = "SELECT * FROM maquina WHERE id = $idMaquina"; /*Só ta aceitando id como numero ???? */
    $resultExistencia = $mysqli->query($sqlExistenciaMaquina);
    if ($resultExistencia->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Document</title>
</head>
<body>
    <form action="" method="POST">
        <input type="text" name="idmaquina" placeholder="ID">
        <input type="text" name="modelo" placeholder="Modelo">
        <input type="text" name="fabricante" placeholder="Fabricante">

        <select name="id_tipo_maquina" id="">
            <!-- <option value="">Escolha a categoria</option> -->
            <?php
                $sqlTipos = "SELECT * FROM tipo_maquina";
                $preparaSqlTipos = $mysqli->query($sqlTipos);

                while ($dadosAtributo = mysqli_fetch_assoc($preparaSqlTipos)){
                    ?>
                    <option value="<?php echo $dadosAtributo['id'];?>"><?php echo $dadosAtributo['tipo'];?></option>
                    <?php
                }
            ?>
        </select>

        <input type="file" name="imagem">
        <input type="submit" name="Cadastrar">

    </form>

    <script src="js/script.js"></script>
</body>
</html>
<?php
    if (isset($_GET['e'])) {
        $mensagem_erro = $_GET['e'];
        echo '<script>erroLogin('.$mensagem_erro.')</script>';
    }
?>
