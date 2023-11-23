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
if(isset($_GET['contadorAtributo']) && isset($_GET['contadorPeca']) && isset($_GET['contadorItem'])){
    $contadorAtributo=$_GET['contadorAtributo'];
    $contadorPeca=$_GET['contadorPeca'];
    $contadorItem=$_GET['contadorItem'];
}else{
    $contadorAtributo=0;
    $contadorPeca=0;
    $contadorItem=0;
}
if(isset($_GET['incrementa'])){
    $variavel = $_GET['incrementa'];
    $$variavel++;
}

function criarInput($quantidade, $tipo){
    if($tipo == "novoAtributo"){
        for($i=1; $i<=$quantidade; $i++){
            echo '<div>';
            echo '<input type="text" name="atributo'.$i.'" placeholder="Atributo">';
            echo '<input type="text" name="valorReferencia'.$i.'" placeholder="Valor de referencia Maxima">';
            echo '</div>';
        }
    }
    if($tipo == "novoPeca"){
        for($i=1; $i<=$quantidade; $i++){
            echo '<div>';
            echo '<input type="text" name="nomePeca'.$i.'" placeholder="Nome da peça">';
            echo '<input type="text" name="codigo'.$i.'" placeholder="Código da peça">';
            echo '<input type="time" name="tempoTroca'.$i.'" placeholder="Tempo de troca da peça">';
            echo '</div>';
        }
    }
    if($tipo == "novoItem"){
        for($i=1; $i<=$quantidade; $i++){
            echo '<div>';
            echo '<input type="text" name="nomeItem'.$i.'" placeholder="Item para checklist manual">';
            echo '</div>';
        }
    }
}

$arrayNovoAtributosNome = array();
$arrayNovoAtributosVR = array();
$arrayNovoPecaNome = array();
$arrayNovoPecaCod = array();
$arrayNovoPecaTempoTroca = array();
$arrayNovoItemNome = array();
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['categoria'])){
    $novaCategoria = $_POST['categoria'];

    for($x=0;$x<$contadorAtributo; $x++){
        if(isset($_POST['atributo'.$x]) && isset($_POST['valorReferencia'.$x])){
            $arrayNovoAtributosNome[]=$_POST['atributo'.$x];
            $arrayNovoAtributosVR[]=$_POST['valorReferencia'.$x];
        }
    }
    for($x=0;$x<$contadorPeca; $x++){
        if(isset($_POST['nome'.$x]) && isset($_POST['codigo'.$x]) && isset($_POST['tempoTroca'.$x])){
            $arrayNovoPecaNome[]=$_POST['nomePeca'.$x];
            $arrayNovoPecaCod[]=$_POST['codigo'.$x];
            $arrayNovoPecaTempoTroca[]=$_POST['tempoTroca'.$x];
        }
    }
    for($x=0;$x<$contadorItem; $x++){
        if(isset($_POST['nome'.$x])){
            $arrayNovoItemNome[]=$_POST['nomeItem'.$x];
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
    }
    for($x=0; $x<count($arrayNovoPecaNome); $x++){
        $peca = $arrayNovoPecaNome[$x];
        $codigo = $arrayNovoPecaCod[$x];
        $tempoTroca = $arrayNovoPecaTempoTroca[$x];
        $sqlInserePeca = "INSERT INTO atributo_tipo (codigo, peca, tempoTroca) VALUES ('$codigo', '$pea', '$tempoTroca')";
        $mysqli->query($sqlInserePeca);
    }
    for($x=0; $x<count($arrayNovoItemNome); $x++){
        $item= $arrayNovoItemNome[$x];
        // $nameItem = str_replace(‘ ‘, ”, $item);
        // $nameItem = preg_replace(‘/\s+/’, ”, $item);
        $nameItem = $item;
        $sqlInsereItem = "INSERT INTO item_checklist (item, name_item) VALUES ('$item', '$nameItem')";
        $mysqli->query($sqlInsereItem);
    }

    //to do: buscar esses atributos, pecas e itens e, juntos dos itens checkbox, adicionar na lista linkada com o tipo_maquina

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
            echo '<input type="checkbox" name="'.$arrayNomeAtributos[$i].'-'.$arrayIdAtributos[$i].'"><label>'.$arrayNomeAtributos[$i].'</label>';
        }
        echo '<div class="novoAtributo">'.criarInput($contadorAtributo, 'novoAtributo').'</div>';
        echo "<p>Peças</p>";
        for($i=0; $i<count($arrayIdPecas); $i++){
            echo '<input type="checkbox" name="'.$arrayNomePecas[$i].'-'.$arrayIdPecas[$i].'"><label>'.$arrayCodPecas[$i]."-".$arrayNomePecas[$i].'</label>';
        }
        echo '<div class="novoPeca">'.criarInput($contadorPeca, 'novoPeca').'</div>';
        echo "<p>Itens Para o Checklist</p>";
        for($i=0; $i<count($arrayIdItens); $i++){
            echo '<input type="checkbox" name="'.$arrayNomeItens[$i].'-'.$arrayIdItens[$i].'"><label>'.$arrayNomeItens[$i].'</label>';
        }
        echo '<div class="novoItem">'.criarInput($contadorItem, 'novoItem').'</div>';
        ?>
        <input type="submit" value="Salvar">
    </form>


    <?php echo '<a href="cadastrarTipoMaquina2.php?contadorAtributo='.$contadorAtributo.'&contadorPeca='.$contadorPeca.'&contadorItem='.$contadorItem.'&incrementa=contadorAtributo">';?>
        <button>Novo Atributo paraEsp</button>
    </a>
    <?php echo '<a href="cadastrarTipoMaquina2.php?contadorAtributo='.$contadorAtributo.'&contadorPeca='.$contadorPeca.'&contadorItem='.$contadorItem.'&incrementa=contadorPeca">';?>
            <button>Nova Peça</button>
        </a>
    <?php echo '<a href="cadastrarTipoMaquina2.php?contadorAtributo='.$contadorAtributo.'&contadorPeca='.$contadorPeca.'&contadorItem='.$contadorItem.'&incrementa=contadorItem">';?>
        <button>Novo Item pra Checklist</button>
    </a>


    <script src="js/script.js"></script>
</body>
</html>
<?php
    if (filter_input(INPUT_GET, 'e')) {
        $mensagem_erro = filter_input(INPUT_GET, 'e');
        echo '<script>erroLogin('.$mensagem_erro.')</script>';
    }
?>