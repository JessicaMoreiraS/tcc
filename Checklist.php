<?php
    session_start();
    if ($_SESSION['tipo'] != 'aluno' && $_SESSION['tipo'] != 'professor' && $_SESSION['tipo'] != 'gestor') {
        header('Location: index.html');
	}
    
    include('conexao.php');
    

    $idDaMaquina;
    if($_GET['id_maquina']){
        $idDaMaquina = $_GET['id_maquina'];
    }

    function buscarInfosMaquina($idMaquina){
        include('conexao.php');
        
        $sql = "SELECT * FROM esp32 INNER JOIN atributo_tipo ON esp32.id_atributos = atributo_tipo.id WHERE id_maquina = $idMaquina";
        $result = mysqli_query($mysqli, $sql);
        
        
        if (!$result) {
            die("Erro na consulta: " . mysqli_error($mysqli));
        }
        
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $array = [$row['atributo'], $row['valor']];
            $data[] = $array;
            foreach($data as $info){
               // echo $info[0]." - ". $info[1]."<br>";
                if($info[0] == "temperatura" || $info[0] == "velocidade" || $info[0] == "vibracao"){
                    // echo "console.log('".$info[0]."')";
                    echo '<script>';
                    echo 'graficoVelocimetro('.$info[1].', "'.$info[0].'");'; // Chama a função JavaScript
                    echo '</script>';
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
    <title>Checklist Form</title>
    <style>
        .grafico {
        width: 100%;
        height: 200px;
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
                <h2>Torno Mecanico</h2>
            </div>

            <div class="container-infoMaq">

            <div class="img">
                <img src="img/torno.png" width="300" alt="">
                <figcaption>Fabricante: Lorem ipsum dolor sit amet</figcaption><br>
            </div>

            <div class="subInfo">
                <div class="id">
                    <h3>ID:0000000</h3>
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

            <div>
                <div class="card-grafico">
                <div id="graficos"></div> 
                </div>
            </div>

            
            </section>
        </section>

        
        <section class="checklist" id="checklist">
            <h2>Checklist</h2>
            <div class="checkbox-wrapper-19">
                <input id="check1" type="checkbox" name="item_1">
                <label class="check-box" for="check1">Item</label>
            </div>
                <div class="checkbox-wrapper-19">
                    <input id="check2" type="checkbox" name="item_2">
                    <label class="check-box" for="check2">Item</label>
            </div>
            <div class="checkbox-wrapper-19">
                <input id="check3" type="checkbox" name="item_3">
                <label class="check-box" for="check3">Item</label>
            </div>

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
</body>
</html>
