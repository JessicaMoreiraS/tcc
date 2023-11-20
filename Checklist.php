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
    if($_GET['id_maquina']){
        $idDaMaquina = $_GET['id_maquina'];

        $sqlDadosMaquina = "SELECT * FROM maquina INNER JOIN tipo_maquina ON maquina.id = tipo_maquina.id WHERE maquina.id=$idDaMaquina";
        $result = buscarNoBanco($idDaMaquina, $sqlDadosMaquina);
        while ($row = mysqli_fetch_assoc($result)) {
            $fabricante= $row['fabricante'];
            $imagem= $row['imagem'];
            $tipo= $row['tipo'];
            $imagem = base64_encode($imagem);
        }
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
                //echo 'function graficoFluidos(valorGrafico, nomeDiv, idMaquina){ console.log("entrou"); var elementoPai = document.getElementById("graficoCirculo"); var containetCirculo = `<div class="containerCirculo"><div class="wrapper"><svg class="waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path class="wave1" fill="#800000" fill-opacity="1" d="M0,288L48,272C96,256,192,224,288,197.3C384,171,480,149,576,165.3C672,181,768,235,864,250.7C960,267,1056,245,1152,250.7C1248,256,1344,288,1392,304L1440,320L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>   <path class="wave2" fill="#800000" fill-opacity="1" d="M0,288L60,288C120,288,240,288,360,256C480,224,600,160,720,138.7C840,117,960,139,1080,176C1200,213,1320,267,1380,293.3L1440,320L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></fill=> </svg></div><div class="preenchimentoCirculo" height:"${valorGrafico}%"></div></div>`; elementoPai.innerHTML=containetCirculo; } graficoFluidos(50,"graficoCirculo",1)';
                echo 'graficoFluidos(50,"graficoCirculo",1)';
                echo '</script>';
            }
            if($row['atributo'] == "velocidade" || $row['atributo'] == "vibracao"){
                echo '<script>';
                // echo "console.log('".$info[0]."');";
                echo 'graficoVelocimetro('.$row['valor'] .', "'.$row['atributo'].'","'.$idMaquina.'");';
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

            echo '<div class="checkbox-wrapper-19">
                    <input id="'.$name.'" type="checkbox" name="'.$name.'">
                    <label class="check-box" for="'.$name.'" style="padding-left:30px">'.$info.'</label>
                </div>';
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
                        echo '<div class="checkbox-wrapper-19">
                                <input id="'.$name.'Check" type="checkbox" name="'.$name.'" checked="true">
                                <label class="check-box" for="'.$name.'Check" style="padding-left:30px">'.$atributo.'</label>
                            </div>';
                    }else{
                        echo '<div class="checkbox-wrapper-19">
                                <input id="'.$name.'Check" type="checkbox" name="'.$name.'" disabled class="boderRed">
                                <label class="check-box" for="'.$name.'Check" style="padding-left:30px">'.$atributo.'</label>
                            </div>';
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
    <title>Checklist Form</title>
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
            height: 50%;
        }
        
        .wrapper {
            position: relative;
            bottom:0;
            background-color: red;
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

    </style>
</head>
<body id="visualizarMaquinas">

    <header class="topo-index">
    <a href="inicialAluno.html"><img width="140" src="img/logo-senai-branco.png" alt="" /></a>
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

        
            <section class="graficos"> 
                <div id="graficos"></div> 
                <div id="graficoCirculo"><div>
            </section>
        </section>

        
        <section class="checklist" id="checklist">
            <h2>Checklist</h2>
            <form action="processaChecklist.php?id_maquina=<?php echo $idDaMaquina; ?>" method="POST">
                <input type="text" value="<?php echo $idDaMaquina; ?>" name="id_maquina" hidden>
                <?php
                itensChecklist($idDaMaquina);
                atributosChecklist($idDaMaquina);
                ?>

                <input type="submit" value="Enviar">
                <!-- to do: verificar se todos os checkbox estao ok para habilitar o botao de envio -->
            </form>

    </section>
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
</body>
</html>

