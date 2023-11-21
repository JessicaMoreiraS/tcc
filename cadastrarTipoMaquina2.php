<?php
$sqlAtributos = "SELECT * FROM atributo_tipo";
$preparaSqlAtributos = $mysqli->query($sqlAtributos);
$sqlPecas = "SELECT * FROM peca_tipo";
$preparaSqlPecas = $mysqli->query($sqlPecas);
$sqlItens = "SELECT * FROM item_checklist";
$preparaSqlItens = $mysqli->query($sqlItens);

$arrayIdAtributos = array();
$arrayNomeAtributos = array();
$arrayIdPecas = array();
$arrayCodPecas[] = array();
$arrayNomePecas = array();
$arrayIdItens = array();
$arrayNomeItens = array();

while ($dadosAtributo = mysqli_fetch_assoc($preparaSqlAtributos)){
    $arrayIdAtributos[] = $dadosAtributo['id'];
    $arraynomeAtributos[] = $dadosAtributo['atributo_esp'];
}

while ($dadosAtributo = mysqli_fetch_assoc($preparaSqlPecas)){
    $arrayIdPecas[] = $dadosAtributo['id'];
    $arrayCodPecas[] = $dadosAtributo['atributo_esp'];
    $arrayNomePecas[] = $dadosAtributo['atributo_esp'];
}

while ($dadosAtributo = mysqli_fetch_assoc($preparaSqlPecas)){
    $arrayIdItens[] = $dadosAtributo['id'];
    $arrayNomePecas[] = $dadosAtributo['name_item'];
}

if(isset($_POST['tipo'])){
    
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cateria de Maquina</title>
</head>
<body>
    <script src="js/script.js"></script>
</body>
</html>
<?php
    if (filter_input(INPUT_GET, 'e')) {
        $mensagem_erro = filter_input(INPUT_GET, 'e');
        echo '<script>erroLogin('.$mensagem_erro.')</script>';
    }
?>