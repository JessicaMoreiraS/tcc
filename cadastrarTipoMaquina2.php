<?php
include('conexao.php');
$sqlAtributos = "SELECT * FROM atributo_tipo";
$preparaSqlAtributos = $mysqli->query($sqlAtributos);
$sqlPecas = "SELECT * FROM peca_tipo";
$preparaSqlPecas = $mysqli->query($sqlPecas);
$sqlItens = "SELECT * FROM item_checklist";
$preparaSqlItens = $mysqli->query($sqlItens);

$arrayIdAtributos = array();
$arrayNomeAtributos = array();
$arrayIdPecas = array();
$arrayCodPecas = array();
$arrayNomePecas = array();
$arrayIdItens = array();
$arrayNomeItens = array();
while ($dadosAtributo = mysqli_fetch_assoc($preparaSqlAtributos)){
    $arrayIdAtributos[] = $dadosAtributo['id'];
    $arrayNomeAtributos[] = $dadosAtributo['atributo_esp'];
}

while ($dadosPecas = mysqli_fetch_assoc($preparaSqlPecas)){
    $arrayIdPecas[] = $dadosPecas['id'];
    $arrayCodPecas[] = $dadosPecas['codigo'];
    $arrayNomePecas[] = $dadosPecas['peca'];
}

while ($dadosItens = mysqli_fetch_assoc($preparaSqlItens)){
    $arrayIdItens[] = $dadosItens['id'];
    $arrayNomeItens[] = $dadosItens['name_item'];
}

if(isset($_POST['categoria'])){
    
}



$arrayNovoAtributosNome = array();
$arrayNovoAtributosVR = array();
$arrayNovoPecaNome = array();
$arrayNovoPecaCod = array();
$arrayNovoPecaTempoTroca = array();
$arrayNovoItemNome = array();

$idAtributoAdd = array();
$idPecaAdd = array();
$idItemAdd = array();
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['categoria'])){
    $novaCategoria = $_POST['categoria'];
    $sqlInsereCategoria = "INSERT INTO tipo_maquina(tipo) VALUES('$novaCategoria')";
    $executaCategoria = $mysqli->query($sqlInsereCategoria);
    $idNovoTipo = mysqli_insert_id($mysqli);


    for($x=1;$x<50; $x++){
        if(isset($_POST['atributo'.$x]) && isset($_POST['vReferencia'.$x])){
            echo "aquii";
            $arrayNovoAtributosNome[]=$_POST['atributo'.$x];
            $arrayNovoAtributosVR[]=$_POST['vReferencia'.$x];
        }else{
            break;
        }
    }
    for($x=1;$x<50; $x++){
        if(isset($_POST['nome'.$x]) && isset($_POST['codigo'.$x]) && isset($_POST['tempoTroca'.$x])){
            $arrayNovoPecaNome[]=$_POST['peca'.$x];
            $arrayNovoPecaCod[]=$_POST['codigo'.$x];
            $arrayNovoPecaTempoTroca[]=$_POST['tempoTroca'.$x];
        }else{
            break;
        }
    }
    for($x=1;$x<50; $x++){
        if(isset($_POST['nome'.$x])){
            $arrayNovoItemNome[]=$_POST['item'.$x];
        }else{
            break;
        }
    }

    for($x=0; $x<count($arrayNovoAtributosNome); $x++){
        $atributo = $arrayNovoAtributosNome[$x];
        // $atributoEsp  = str_replace(‘ ‘, ”, $atributo);
        // $atributoEsp  = preg_replace(‘/\s+/’, ”, $atributo);
        $atributoEsp = $atributo;
        $vr = $arrayNovoAtributosVR[$x];
        $sqlInsereAtributo="INSERT INTO atributo_tipo (atributo, atributo_esp, valor_referencia) VALUE ('$atributo', '$atributoEsp', $vr)";
        $mysqli->query($sqlInsereAtributo);
        $idAtributoAdd[] = mysqli_insert_id($mysqli);
    }
    for($x=0; $x<count($arrayNovoPecaNome); $x++){
        $peca = $arrayNovoPecaNome[$x];
        $codigo = $arrayNovoPecaCod[$x];
        $tempoTroca = $arrayNovoPecaTempoTroca[$x];
        $sqlInserePeca = "INSERT INTO peca_tipo (codigo, peca, tempoTroca) VALUES ('$codigo', '$pea', '$tempoTroca')";
        $mysqli->query($sqlInserePeca);
        $idPecaAdd[] = mysqli_insert_id($mysqli);
    }
    for($x=0; $x<count($arrayNovoItemNome); $x++){
        $item= $arrayNovoItemNome[$x];
        // $nameItem = str_replace(‘ ‘, ”, $item);
        // $nameItem = preg_replace(‘/\s+/’, ”, $item);
        $nameItem = $item;
        $sqlInsereItem = "INSERT INTO item_checklist (item, name_item) VALUES ('$item', '$nameItem')";
        $mysqli->query($sqlInsereItem);
        $idItemAdd[] = mysqli_insert_id($mysqli);
    }
    //to do: buscar esses atributos, pecas e itens e, juntos dos itens checkbox, adicionar na lista linkada com o tipo_maquina
    $sqlContarAtributo = "SELECT * FROM atributo_tipo";
    $sqlContarPeca = "SELECT * FROM peca_tipo";
    $sqlContarItem = "SELECT * FROM item_checklist";

    $executaSqlContarAtributo = $mysqli->query($sqlContarAtributo);
    $executaSqlContarPeca = $mysqli->query($sqlContarPeca);
    $executaSqlContarItem = $mysqli->query($sqlContarItem);
    
    $linhasAtributo =  mysqli_num_rows($executaSqlContarAtributo);
    $linhasPeca =  mysqli_num_rows($executaSqlContarPeca);
    $linhasItem =  mysqli_num_rows($executaSqlContarItem);

    for($x=1; $x<$linhasAtributo; $x++){
        if(isset($_POST['atributo-'.$x]) && $_POST['atributo-'.$x] == "on"){
            $idAtributoAdd[] = $x;
        }
    }
    for($x=1; $x<$linhasPeca; $x++){
        if(isset($_POST['peca-'.$x]) && $_POST['peca-'.$x] == "on"){
            $idPecaAddAdd[] = $x;
        }
    }
    for($x=1; $x<$linhasItem; $x++){
        if(isset($_POST['item-'.$x]) && $_POST['item-'.$x] == "on"){
            $idItemAddAdd[] = $x;
        }
    }

    foreach($idAtributoAdd as $idAtributo){
        $sqlListaAtributoTipo = "INSERT INTO lista_tipo_maquina_atributo (id_tipo_maquina, id_atributos) VALUES ($idNovoTipo, $idAtributo)";
        $mysqli->query($sqlListaAtributoTipo);
    }
    foreach($idPecaAdd as $idPeca){
        $sqlListaPecaTipo = "INSERT INTO lista_tipo_maquina_peca (id_tipo_maquina, id_atributos) VALUES ($idNovoTipo, $idPeca)";
        $mysqli->query($sqlListaPecaTipo);
    }
    foreach($idItemAdd as $idItem){
        $sqlListaItemTipo = "INSERT INTO lista_tipo_maquina_item_checklist (id_tipo_maquina, id_atributos) VALUES ($idNovoTipo, $idItem)";
        $mysqli->query($sqlListaItemTipo);
    }
}

/*
function tirarAcentos($string){
    return preg_replace(array(“/(á|à|ã|â|ä)/”,”/(Á|À|Ã|Â|Ä)/”,”/(é|è|ê|ë)/”,”/(É|È|Ê|Ë)/”,”/(í|ì|î|ï)/”,”/(Í|Ì|Î|Ï)/”,”/(ó|ò|õ|ô|ö)/”,”/(Ó|Ò|Õ|Ô|Ö)/”,”/(ú|ù|û|ü)/”,”/(Ú|Ù|Û|Ü)/”,”/(ñ)/”,”/(Ñ)/”),explode(” “,”a A e E i I o O u U n N”),$string);
}*/
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Categoria de Máquina</title>
</head>
<body>
    <form action="" method="POST">
        <input type="text" placeholder="Categoria" name="categoria">

        <?php
        echo $arrayNomeAtributos[0];
        echo "<p>Atributos para medição pelo Microcontrolador</p>";
        for($i=0; $i<count($arrayIdAtributos); $i++){
            echo '<input type="checkbox" name="atributo-'.$arrayIdAtributos[$i].'"><label>'.$arrayNomeAtributos[$i].'</label>';
        }
        echo '<div id="novoAtributo"></div>';
        echo "<p>Peças</p>";
        for($i=0; $i<count($arrayIdPecas); $i++){
            echo '<input type="checkbox" name="peca-'.$arrayIdPecas[$i].'"><label>'.$arrayCodPecas[$i]."-".$arrayNomePecas[$i].'</label>';
        }
        echo '<div id="novoPeca"></div>';
        echo "<p>Itens Para o Checklist</p>";
        for($i=0; $i<count($arrayIdItens); $i++){
            echo '<input type="checkbox" name="item-'.$arrayIdItens[$i].'"><label>'.$arrayNomeItens[$i].'</label>';
        }
        echo '<div id="novoItem"></div>';
        ?>
        <input type="submit" value="Salvar">
    </form>

    <button onclick="criarInputs('Atributo')">Novo Atributo paraEsp</button>
    <button onclick="criarInputs('Peca')">Nova Peça</button>
    <button onclick="criarInputs('Item')">Novo Item pra Checklist</button>


    <script src="js/script.js"></script>
</body>
</html>
<?php
    if (filter_input(INPUT_GET, 'e')) {
        $mensagem_erro = filter_input(INPUT_GET, 'e');
        echo '<script>erroLogin('.$mensagem_erro.')</script>';
    }
?>