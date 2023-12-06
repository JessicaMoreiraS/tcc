<?php
    session_start();
    if ($_SESSION['tipo'] != 'aluno' && $_SESSION['tipo'] != 'professor' && $_SESSION['tipo'] != 'gestor') {
        header('Location: index.html');
	}
    
    include('conexao.php');
    

    function buscarNoBanco($idMaquina, $sql){
        include('conexao.php');
        $result = mysqli_query($mysqli, $sql);
        
        if (!$result) {
            die("Erro na consulta: " . mysqli_error($mysqli));
        }
        return $result;
    }
    
    $idDaMaquina;
    $fabricante ="";
    $imagem = "";
    $tipo = "";
    $idTipo = "";
    $ledVermelho="";
    $ledVerde="";
    if($_GET['id_maquina']){
        $idDaMaquina = $_GET['id_maquina'];

        $sqlDadosMaquina = "SELECT * FROM maquina INNER JOIN tipo_maquina ON maquina.id = tipo_maquina.id WHERE maquina.id=$idDaMaquina";
        $result = buscarNoBanco($idDaMaquina, $sqlDadosMaquina);
        while ($row = mysqli_fetch_assoc($result)) {
            $fabricante= $row['fabricante'];
            $imagem= $row['imagem'];
            $tipo= $row['tipo'];
            $tipo = str_replace("Mecanico", "Mecânico", $tipo);
            $tipo = str_replace("mecanico", "Mecânico", $tipo);
            $tipo = ucfirst($tipo);
            $idTipo=$row['id_tipo_maquina'];
            $ledVermelho=$row['led_vermelho'];
            $ledVerde=$row['led_verde'];
        }

        if($imagem==null || $imagem==""){
            $sqlBuscaImgPadrao= "SELECT imagem_padrao FROM tipo_maquina WHERE id = $idTipo";
            $resultadoImgPadrao = $mysqli->query($sqlBuscaImgPadrao);
            $rowImgPadrao = $resultadoImgPadrao->fetch_assoc();
            $imagem = $rowImgPadrao['imagem_padrao'];
        }
        $imagem = base64_encode($imagem);
    }

    
    

    function buscarInfosMaquina($idMaquina){
        $sql = "SELECT * FROM esp32 INNER JOIN atributo_tipo ON esp32.id_atributos = atributo_tipo.id WHERE id_maquina = $idMaquina";
        $result = buscarNoBanco($idMaquina, $sql);
        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $array = [$row['atributo'], $row['valor']];
            
            if($row['atributo'] == "temperatura"){
                echo '<script>';
                echo 'temometro('.$row['valor'].',"'.$idMaquina.'",'.$row['valor_referencia'].');'; 
                echo '</script>';
            }
            if(strpos($row['atributo'], 'óleo') !== false || strpos($row['atributo'], 'oleo') !== false){
                echo '<script>';
                echo 'graficoFluidos('.$row['valor'].',"'.$row['atributo_esp'].'","'.$row['atributo'].'",'.$idMaquina.', '.$row['valor_referencia'].')';
                echo '</script>';
            }
            if(strpos($row['atributo'], 'viscosidade') !== false){
                echo '<script>';
                echo 'graficoViscodidadeFluidos('.$row['valor'].',"'.$row['atributo_esp'].'","'.$row['atributo'].'",'.$idMaquina.', '.$row['valor_referencia'].')';
                echo '</script>';
            }
            if($row['atributo'] == "velocidade" || $row['atributo'] == "vibracao"){
                echo '<script>';
                // echo "console.log('".$info[0]."');";
                echo 'graficoVelocimetro('.$row['valor'] .', "'.$row['atributo_esp'].'","'.$idMaquina.'");';
                echo '</script>';
                
            }
        }
    }

   

    function itensChecklist($idMaquina){
        $sql = "SELECT * FROM maquina INNER JOIN lista_tipo_maquina_item_checklist ON lista_tipo_maquina_item_checklist.id_tipo_maquina = maquina.id_tipo_maquina INNER JOIN item_checklist ON item_checklist.id = lista_tipo_maquina_item_checklist.id_item_checklist WHERE maquina.id = $idMaquina";
        $result = buscarNoBanco($idMaquina, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $info = $row['item'];
            $name = $row['name_item'];

            echo '
            <label class="material-checkbox">
                <input id="'.$name.'" type="checkbox" name="'.$name.'">
                <span class="checkmark"></span>
                '.$info.'
            </label>';
        } 
    }

    function atributosChecklist($idMaquina){
        $sql = "SELECT * FROM maquina INNER JOIN lista_tipo_maquina_atributo ON maquina.id_tipo_maquina = lista_tipo_maquina_atributo.id_tipo_maquina INNER JOIN atributo_tipo ON atributo_tipo.id = lista_tipo_maquina_atributo.id_atributos WHERE maquina.id = $idMaquina";
        $result = buscarNoBanco($idMaquina, $sql);
        
        
        while ($row = mysqli_fetch_assoc($result)) {
            $atributo = $row['atributo'];
            $name = $row['atributo_esp'];
            $idAtributo = $row['id'];

            $valorReferencia = $row['valor_referencia'];
            
            $sqlDadosEsp = "SELECT * FROM esp32 WHERE esp32.id_maquina = $idMaquina";
            $resultEsp = buscarNoBanco($idMaquina, $sqlDadosEsp);

            while ($rowEsp = mysqli_fetch_assoc($resultEsp)) {

                if($rowEsp['id_atributos'] == $idAtributo){
                    if($rowEsp['valor'] <= $valorReferencia){
                        echo '
                                <label class="material-checkbox">
                                    <input id="'.$name.'Check" type="checkbox" name="'.$name.'" checked="true">
                                    <span class="checkmark"></span>
                                    '.$atributo.'
                                </label>    
                            ';
                    }else{
                        echo '
                        <label class="material-checkbox">
                            <input id="'.$name.'Check" type="checkbox" name="'.$name.'">
                            <span class="checkmark botao-desabilitado" ></span>
                            '.$atributo.'
                        </label>';
                    }
                }
            }
        }
    }

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <!-- <script src="https://d3js.org/d3.v7.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://www.amcharts.com/lib/4/core.js"></script>
    <script src="https://www.amcharts.com/lib/4/charts.js"></script>
    <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
    <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script src="https://www.amcharts.com/lib/3/serial.js"></script>
    <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png"/>
    <script src="https://unpkg.com/scrollreveal"></script>
    <link rel="stylesheet" href="css/mediaQuery.css">


    <title>Checklist</title>
    <style>
        .grafico {
        width: 100%;
        height: 200px;
        }
        #temometro{
            /*background-color: rgb(194, 194, 194);
            width: 50px;
            height: 200px;
            border-radius: 50px;
            display: flex;
            justify-content: flex-end;
            align-items: flex-end;*/
            position: relative;
            width: 50px;
            height: 200px;
            background-color: #eee;
            border-radius: 20px;
            overflow: hidden;
        }
        #valorTemometro{
            /*background-color: red;
            width: 100%;
            border-radius: 0px 0px 50px 50px;*/
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: #ff5733;
            transition: height 0.5s ease;
        }
        
        .rotulos {
            display: flex;
            flex-direction: column; /* Ajusta a direção para coluna */
            align-items: center; /* Centraliza os rótulos verticalmente */
            /* justify-content: space-around; */
            justify-content: space-evenly;
            margin-top: 5px;
            height: 100%;
            margin-top: 0;
        }

        .rotulos span {
            height: 3px;
            background-color: #333;
            margin-top: 2px; /* Ajusta a margem entre os rótulos */
            z-index: 100;
        }
        .valorTemp{
            display: inline-block;
            font-size:18px;
            font-weight: bold;
        }

        .containerCirculo{
            position: relative;
            overflow: hidden;

            width: 200px;
            height:200px;
            background-color: #eee;
            border-radius: 100px;
        }
        .preenchimentoCirculo{
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: #800000;
            transition: height 0.5s ease;
            /*height: 50%;*/
        }
        
        .wrapper {
            position: relative;
            bottom:0;
            background-color: red;
            transition: margin-top 0.5s ease;
            /* margin-top:28%; */
        }
        
        .waves{
            position: absolute;
            left:0;
            right:0;
        }
        .wave1{
            animation: moveWave1 3s ease-in-out infinite alternate;
        }
        .wave2{
            animation: moveWave2 3s ease-in-out infinite alternate;
            opacity: 90%;
        }

        .setaVoltar{
            width: 50px;
            align-self: center;
            justify-self: start;
        }
  
  
    
    @keyframes moveWave1{
        from{
            transform: translateX(-30%);
        }
    }
    @keyframes moveWave2{
        from{
            transform: translateX(20%);
        }
    }

    .logo-inicial{
        padding-left: 0px;
    }

    </style>
</head>
<body id="visualizarMaquinas">

    <header class="topo-index">
        <?php
        if ($_SERVER['HTTP_REFERER']){
            if(!strpos($_SERVER['HTTP_REFERER'], "Checklist.php") && !strpos($_SERVER['HTTP_REFERER'], "processaChecklist.php") && !strpos($_SERVER['HTTP_REFERER'], "checklist.php")){
                echo '<a href='.$_SERVER['HTTP_REFERER'].' class="setaVoltar">';
            }else{
                if($_SESSION['tipo']){
                    switch($_SESSION['tipo']){
                        case 'aluno':
                            echo '<a href="homeAluno.php" class="setaVoltar">';
                            break;
                        case 'professor':
                            echo '<a href="homeProfessor.php" class="setaVoltar">';
                            break;
                        case 'gestor':
                                echo '<a href="homeGestao.php" class="setaVoltar">';
                                break;
                        case 'defalt':
                            echo '<a href="homeGestaoDefault.php" class="setaVoltar">';
                            break;
                        }
                    }
                }
            }else{
                echo '<a href="login.php" class="setaVoltar">';
            }
            ?>
            <!-- <a href="login.php" onClick="history.back()" class="setaVoltar"> -->
                <img src="img/svg/setaVoltar.svg" alt="voltar">
            </a>    

        <!-- <a href="homeAluno.php"> -->
        <img width="200" class="logo-inicial" src="img/svg/logo_senai_vermelho.svg" />
        <!-- </a> -->
    </header>
    <main id="main_visualizarMaquina">
        <section class="informacoes_maquina">
            
            <div class="nome_maquina">
                <h2><?php echo $tipo ?></h2>
            </div>

            <div class="container-infoMaq">

            <div class="img">
                <!-- <img src="img/torno.png" width="300" alt=""> -->
                <img src="data:image/jpeg;base64,<?php echo $imagem ?>" width="300" alt="">
                <figcaption>Fabricante: <?php echo $GLOBALS['fabricante']; ?></figcaption><br>
            </div>

            <div class="subInfo">
                <div class="id">
                    <h3>ID:<?php echo $idDaMaquina; ?></h3>
                </div>

                <?php
                if($ledVerde == "ON"){
                    echo '<div class="boxLedCheck">Status da máquina:<div class="boxLedCheck2"><div class="ledVerdeOn"></div><div class="ledOff"></div></div></div>';
                }else if($ledVermelho == "ON"){
                    echo '<div class="boxLedCheck">Status da máquina:<div class="boxLedCheck2"><div class="ledOff"></div><div class="ledVermelhoOn"></div></div></div>';
                }
                ?>
                
                <div class="btns">
                    <div class="btn">
                        <a href="">Manual</a>
                    </div>
                    <div class="btn">
                        <a href="#checklist">Checklist</a>
                    </div>
                    </div>
                </div>
            </div>
            </div>

        
            <!-- <section >  -->
                <div id="graficos" class="graficos"></div> 
                <div id="graficoCirculo"><div>
            <!-- </section> -->
        </section>

        
       
            <h2 class="tituloCheck">Checklist</h2>
            <p class="infoCheck">Realize o checklist da sua máquina</p>

            <div class="checklist" id="checklist">
                <div class="container-check">
                    <div class="testando">
                        <form action="processaChecklist.php?id_maquina=<?php echo $idDaMaquina; ?>" method="POST">
                                <input type="text" value="<?php echo $idDaMaquina; ?>" name="id_maquina" hidden>
                                <?php
                                itensChecklist($idDaMaquina);
                                atributosChecklist($idDaMaquina);
                                ?>
                                <div class="btns" >
                                    <div class="btn">
                                        <input type="submit" value="Enviar">
                                    </div>
                                </div>  
                                <!-- to do: verificar se todos os checkbox estao ok para habilitar o botao de envio -->
                        </form>
                    </div>
                </div>
            </div>

   
</main>

<script src="js/graficos.js"></script>

<?php buscarInfosMaquina($idDaMaquina) ?>



    <!-- <h1>Checklist Form</h1>
    <form action="processarForm.php" method="post">
        <label for="item_1">Item 1:</label>
        <input type="checkbox" name="item_1" value="1">
        <label for="item_2">Item 1:</label>
        <input type="checkbox" name="item_2" value="2">
        <label for="item_3">Item 1:</label>
        <input type="checkbox" name="item_3" value="3">
        <label for="item_4">Item 1:</label>
        <input type="checkbox" name="item_4" value="4">
        <label for="item_5">Item 1:</label>
        <input type="checkbox" name="item_5" value="5">
        <label for="item_6">Item 1:</label>
        <input type="checkbox" name="item_6" value="6">
        <label for="item_7">Item 1:</label>
        <input type="checkbox" name="item_7" value="7">
        <label for="item_8">Item 1:</label>
        <input type="checkbox" name="item_8" value="8">
        <label for="item_9">Item 1:</label>
        <input type="checkbox" name="item_9" value="9">
        <label for="item_10">Item 1:</label>
        <input type="checkbox" name="item_10" value="10">

        <input type="submit" value="Enviar Checklist">
    </form> -->

    <script>
        function fluidos(valorGrafico, nomeDiv, idMaquina){
            console.log("entrou")
            var elementoPai = document.getElementById("graficoCirculo");
            var containetCirculo = createElement('div');
            containetCirculo.className="containerCirculo";

            var preenchimentoCirculo = createElement("div");
            preenchimentoCirculo.className = "preenchimentoCirculo";
            preenchimentoCirculo.style = `height:${valorGrafico}%`;

            containetCirculo.appendChild(preenchimentoCirculo);
            elementoPai.appendChild(containetCirculo);
        }
    </script>
    <script src="js/script.js"></script>
    <script src="js/reveal.js"></script>
</body>
</html>
<?php
    if (filter_input(INPUT_GET, 'e')) {
        $mensagem_erro = filter_input(INPUT_GET, 'e');
        echo '<script>erroLogin('.$mensagem_erro.')</script>';
    }
?>
