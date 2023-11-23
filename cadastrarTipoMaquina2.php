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
            echo '<input type="text" name="nome'.$i.'" placeholder="Nome da peça">';
            echo '<input type="text" name="codigo'.$i.'" placeholder="Código da peça">';
            echo '<input type="text" name="tempoTroca'.$i.'" placeholder="Tempo de troca da peça">';
            echo '</div>';
        }
    }
    if($tipo == "novoItem"){
        for($i=1; $i<=$quantidade; $i++){
            echo '<div>';
            echo '<input type="text" name="nome'.$i.'" placeholder="Item para checklist manual">';
            echo '</div>';
        }
    }
}

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
    <form action="">
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