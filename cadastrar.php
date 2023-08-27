<?php
session_start();
if($_SESSION['idAcesso'] != 'gestaoSenai'){
    header('Location: index.html');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar novo professor</title>
</head>
<body>
    <?php
    if(filter_input(INPUT_GET, 'option')){
        $option = $_GET['option'];
        if($option == "professor"){
    ?>
    <form action="cadastros.php" method="POST">
        <input type="text" name="nome" placeholder="Nome" require>
        <input type="number" name="cpf" placeholder="CPF" require><!--Adicionar mascara para CPF-->
        <input type="email" name="email" placeholder="Email" require>

        <input type="submit" name="cadastrarProfessor" value="Cadastrar">
    </form>


    <?php 
     }else if($option == "tipo"){
    ?>
    <form action="cadastros.php" method="POST">
        <input type="text" name="categoria" placeholder="Categoria" require>

        <input type="submit" name="cadastrarTipoMaquina" value="Cadastrar">
    </form>

    
    <?php
    }else if($option == "maquina"){
    ?>
    <form action="cadastros.php" method="POST">
        <select name="tipo" id="tipo">
            <?php
                include('conexao.php');
                $tiposDisponiveis = "SELECT * FROM tipo_maquina";
                while($row = $mysqli->query($tiposDisponiveis)){
                    ?>
                    <option value="<?php $row['id']?>"><?php $row['tipo']?></option>
                    <?php
                }
            ?>
        </select>

        <input type="text" name="codigo" placeholder="Codigo (id)" require>
        <input type="number" name="modelo" placeholder="Modelo" require>
        <input type="email" name="fabricante" placeholder="Fabricante" require>

        <input type="submit" name="cadastrarMaquina" value="Cadastrar">
    </form>
    <?php
       }
    }
    ?>
</body>
</html>