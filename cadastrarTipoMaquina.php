<?php
include('conexao.php');

// Verificar a conexão
if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}

$sqlAtributos = "SELECT * FROM atributo_tipo";
$preparaSqlAtributos = $mysqli->query($sqlAtributos);
$sqlPecas = "SELECT * FROM peca_tipo";
$preparaSqlPecas = $mysqli->query($sqlPecas);
$sqlItens = "SELECT * FROM item_checklist";
$preparaSqlItens = $mysqli->query($sqlItens);

$arrayIdAtributos = array();
$arrayNomeAtributosEsp = array();
$arrayNomeAtributos = array();
$arrayIdPecas = array();
$arrayCodPecas = array();
$arrayNomePecas = array();
$arrayIdItens = array();
$arrayNomeItens = array();
while ($dadosAtributo = mysqli_fetch_assoc($preparaSqlAtributos)) {
    $arrayIdAtributos[] = $dadosAtributo['id'];
    $arrayNomeAtributosEsp[] = $dadosAtributo['atributo_esp'];
    $arrayNomeAtributos[] = $dadosAtributo['atributo'];
}

while ($dadosPecas = mysqli_fetch_assoc($preparaSqlPecas)) {
    $arrayIdPecas[] = $dadosPecas['id'];
    $arrayCodPecas[] = $dadosPecas['codigo'];
    $arrayNomePecas[] = $dadosPecas['peca'];
}

while ($dadosItens = mysqli_fetch_assoc($preparaSqlItens)) {
    $arrayIdItens[] = $dadosItens['id'];
    $arrayNomeItens[] = $dadosItens['name_item'];
}

if (isset($_POST['categoria'])) {

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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['categoria'])) {
    $novaCategoria = $_POST['categoria'];
    $imagem = $_FILES["imagem"]["tmp_name"] ? file_get_contents($_FILES["imagem"]["tmp_name"]) : null;

    $stmt = $mysqli->prepare("INSERT INTO tipo_maquina(tipo, imagem_padrao) VALUES(?, ?)");
    $stmt->bind_param("sb", $novaCategoria, $imagem);
    $stmt->send_long_data(1, $imagem);  // Para dados BLOB (imagem)
    $stmt->execute();

    $idNovoTipo = $mysqli->insert_id;


    for ($x = 1; $x < 50; $x++) {
        if (isset($_POST['atributo' . $x]) && isset($_POST['vReferencia' . $x])) {
            echo "aquii";
            $arrayNovoAtributosNome[] = $_POST['atributo' . $x];
            $arrayNovoAtributosVR[] = $_POST['vReferencia' . $x];
        } else {
            break;
        }
    }
    for ($x = 1; $x < 50; $x++) {
        if (isset($_POST['peca' . $x]) && isset($_POST['codigo' . $x]) && isset($_POST['tempoTroca' . $x])) {
            $arrayNovoPecaNome[] = $_POST['peca' . $x];

            $arrayNovoPecaCod[] = $_POST['codigo' . $x];
            $arrayNovoPecaTempoTroca[] = $_POST['tempoTroca' . $x];
        } else {
            break;
        }
    }

    for ($x = 1; $x < 50; $x++) {
        if (isset($_POST['item' . $x])) {
            $arrayNovoItemNome[] = $_POST['item' . $x];
        } else {
            break;
        }
    }

    for ($x = 0; $x < count($arrayNovoAtributosNome); $x++) {
        $atributo = $arrayNovoAtributosNome[$x];
        // $atributoEsp  = str_replace(‘ ‘, ”, $atributo);
        // $atributoEsp  = preg_replace(‘/\s+/’, ”, $atributo);
        $atributoEsp = $atributo;
        $vr = $arrayNovoAtributosVR[$x];
        $sqlInsereAtributo = "INSERT INTO atributo_tipo (atributo, atributo_esp, valor_referencia) VALUES ('$atributo', '$atributoEsp', '$vr')";
        $mysqli->query($sqlInsereAtributo);
        $idAtributoAdd[] = mysqli_insert_id($mysqli);
    }
    for ($x = 0; $x < count($arrayNovoPecaNome); $x++) {
        $peca = $arrayNovoPecaNome[$x];
        $codigo = $arrayNovoPecaCod[$x];
        $tempoTroca = $arrayNovoPecaTempoTroca[$x];
        $sqlInserePeca = "INSERT INTO peca_tipo (codigo, peca, tempo_de_troca) VALUES ('$codigo', '$peca', '$tempoTroca')";
        $mysqli->query($sqlInserePeca);
        $idPecaAdd[] = mysqli_insert_id($mysqli);
    }
    for ($x = 0; $x < count($arrayNovoItemNome); $x++) {
        $item = $arrayNovoItemNome[$x];
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

    $linhasAtributo = mysqli_num_rows($executaSqlContarAtributo);
    $linhasPeca = mysqli_num_rows($executaSqlContarPeca);
    $linhasItem = mysqli_num_rows($executaSqlContarItem);

    for ($x = 1; $x <= $linhasAtributo; $x++) {
        if (isset($_POST['atributo-' . $x]) && $_POST['atributo-' . $x] == "on") {
            $idAtributoAdd[] = $x;
        }
    }
    for ($x = 1; $x <= $linhasPeca; $x++) {
        if (isset($_POST['peca-' . $x]) && $_POST['peca-' . $x] == "on") {
            $idPecaAdd[] = $x;
        }
    }
    for ($x = 1; $x <= $linhasItem; $x++) {
        if (isset($_POST['item-' . $x]) && $_POST['item-' . $x] == "on") {
            $idItemAdd[] = $x;
        }
    }

    foreach ($idAtributoAdd as $idAtributo) {
        $sqlListaAtributoTipo = "INSERT INTO lista_tipo_maquina_atributo (id_tipo_maquina, id_atributos) VALUES ($idNovoTipo, $idAtributo)";
        $mysqli->query($sqlListaAtributoTipo);
    }
    foreach ($idPecaAdd as $idPeca) {
        $sqlListaPecaTipo = "INSERT INTO lista_tipo_maquina_peca (id_tipo_maquina, id_peca) VALUES ($idNovoTipo, $idPeca)";

        $mysqli->query($sqlListaPecaTipo);
    }
    foreach ($idItemAdd as $idItem) {
        $sqlListaItemTipo = "INSERT INTO lista_tipo_maquina_item_checklist (id_tipo_maquina, id_item_checklist) VALUES ($idNovoTipo, $idItem)";
        $mysqli->query($sqlListaItemTipo);
    }


    header('location: cadastrarTipoMaquina.php?e=12');

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
    <link rel="icon" type="image/png" href="img/favicon/favicon-32x32.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png"/>
    <link rel="stylesheet" href="css/style.css" />
    <script src="https://unpkg.com/scrollreveal"></script>
    <title>Cadastrar Categoria de Máquina</title>
    <style>
        .setaVoltar{
            width: 50px;
            align-self: center;
            justify-self: start;
        }
    </style>
</head>

<body id="body_cadastrarCategoriaMaquina">

    <header class="topo-inicial">
        <a href="visualizar.php?view=tipo" class="setaVoltar">
            <img src="img/svg/setaVoltar.svg" alt="voltar">
        </a>
        <img width="200" class="logo-inicial" src="img/svg/logo_senai_vermelho.svg" />

        <div class="icons">
            <input type="checkbox" role="button" aria-label="Display the menu" class="menu" />
        </div>
    </header>

    <main id="main_cadastrarCategoriaMaquina">


        <div class="w3-modal-content">
            <div class="form_modal">

                <div class="container">
                    <div class="header">
                        <div>
                            <div class="icone">
                                <i class="fa fa-cogs fa-5x" style="font-size: 70px; color: rgb(255, 255, 255)"></i>
                            </div>
                        </div>
                    </div>
                    <form method="POST" id="form_tipo" enctype="multipart/form-data">
                        <div class="input_modal">
                            <input required type="text" placeholder="Nome da Categoria" name="categoria">
                        </div>

                        <section class="checkboxes_section">

                            <?php
                            echo "<div class='checkboxes'>";

                            echo "<div class='titulo' id='divAtt'>
                                <p>Atributos para medição pelo Microcontrolador</p>
                                <i
                                class='fa fa-info-circle'
                                title='Atributos de medição, pelo Microcontrolador.'
                                ></i>
                            </div>";

                            echo '<div class="container-checkboxes">';

                            for ($i = 0; $i < count($arrayIdAtributos); $i++) {
                                echo '<label  class="cyberpunk-checkbox-label"><input type="checkbox"  class="cyberpunk-checkbox" name="atributo-' . $arrayIdAtributos[$i] . '">' . $arrayNomeAtributos[$i] . '</label>';
                            }
                            echo ' </div>';
                            echo '<div id="novoAtributo"></div>';
                            echo "</div>";




                            echo "<div class='checkboxes'>";


                            echo "<div class='titulo' >
                                <p id='divItem'>Itens para o CheckList</p>
                                <i
                                class='fa fa-info-circle'
                                title='Estes itens farão fazer parte desta categoria de máquina. E poderão ser verificadas no momento do checklist.'
                                ></i>
                            </div>";
                            echo '<div class="container-checkboxes">';
                            for ($i = 0; $i < count($arrayIdItens); $i++) {
                                echo '<label class="cyberpunk-checkbox-label"><input type="checkbox"   class="cyberpunk-checkbox" name="item-' . $arrayIdItens[$i] . '">' . $arrayNomeItens[$i] . '</label>';
                            }

                            echo "</div>";

                            echo '<div id="novoItem"></div>';
                            echo "</div>";


                            echo "<div class='checkboxes'>";



                            echo "<div class='titulo'>
                                <p>Peças</p>
                                <i
                                class='fa fa-info-circle'
                                title='Peças que serão padrão desta Categoria.'
                                ></i>
                    </div>";
                            echo '<div class="container-checkboxes" id="divPecas">';
                            for ($i = 0; $i < count($arrayIdPecas); $i++) {
                                echo '<label class="cyberpunk-checkbox-label"><input type="checkbox" class="cyberpunk-checkbox" name="peca-' . $arrayIdPecas[$i] . '">' . $arrayCodPecas[$i] . "-" . $arrayNomePecas[$i] . '</label>';
                            }
                            echo "</div>";
                            echo '<div id="novoPeca"></div>';
                            echo "</div>";

                            ?>
                        </section>
                        <section id="section_input_img">
                            <div id="div_img_input">
                                <div class="form-upload">
                                    <label class="input-personalizado">
                                        <span class="botao-selecionar">Selecione uma imagem</span>
                                        <img class="imagem" />
                                        <input type="file" name="imagem" class="input-file" accept="image/*" required>
                                    </label>
                                </div>
                            </div>
                        </section>

                        <div id="containerSubmit">
                            <input id="submitTipo" type="submit" value="Cadastrar">
                        </div>
                    </form>

                </div>
            </div>
            <div class="bnts">
                <div>
                    <button onclick="criarInputs('Atributo')">Add Atributo</button>
                    <button onclick="criarInputs('Peca')">Add Peça</button>
                    <button onclick="criarInputs('Item')">Add Item Checklist</button>
                </div>
            </div>
        </div>




    </main>
</body>

</html>
<script>
        const $ = document.querySelector.bind(document);

        const previewImg = $('.imagem');
        const fileChooser = $('.input-file');
    
        fileChooser.onchange = e => {
            const fileToUpload = e.target.files.item(0);
            const reader = new FileReader();
            reader.onload = e => previewImg.src = e.target.result;
            reader.readAsDataURL(fileToUpload);
        };
    </script>
<script src="js/reveal.js"></script>
<script src="js/script.js"></script>

<?php
if (filter_input(INPUT_GET, 'e')) {
    $mensagem_erro = filter_input(INPUT_GET, 'e');
    echo '<script>erroLogin(' . $mensagem_erro . ')</script>';
}
?>