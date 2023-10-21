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

    function buscarNoBanco($idMaquina, $sql){
        include('conexao.php');
        $result = mysqli_query($mysqli, $sql);
        
        if (!$result) {
            die("Erro na consulta: " . mysqli_error($mysqli));
        }
        return $result;
    }

    function buscarInfosMaquina($idMaquina){
        $sql = "SELECT * FROM esp32 INNER JOIN atributo_tipo ON esp32.id_atributos = atributo_tipo.id WHERE id_maquina = $idMaquina";
        $result = buscarNoBanco($idMaquina, $sql);
        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $array = [$row['atributo'], $row['valor']];
            
            if($row['atributo'] == "temperatura"){
                echo '<script>';
                echo 'temometro('.$row['valor'].');'; 
                echo '</script>';
            }
            if($row['atributo'] == "velocidade" || $row['atributo'] == "vibracao"){
                echo '<script>';
                // echo "console.log('".$info[0]."');";
                echo 'graficoVelocimetro('.$row['valor'] .', "'.$row['atributo'].'");';
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
        $sql = "SELECT * FROM maquina INNER JOIN lista_tipo_maquina_atributo ON maquina.id_tipo_maquina = lista_tipo_maquina_atributo.id_tipo_maquina INNER JOIN atributo_tipo ON atributo_tipo.id = lista_tipo_maquina_atributo.id_atributo WHERE maquina.id = $idMaquina";
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
                                <input id="'.$name.'" type="checkbox" name="'.$name.'" checked="true">
                                <label class="check-box" for="'.$name.'" style="padding-left:30px">'.$atributo.'</label>
                            </div>';
                    }else{
                        echo '<div class="checkbox-wrapper-19">
                                <input id="'.$name.'" type="checkbox" name="'.$name.'" disabled class="boderRed">
                                <label class="check-box" for="'.$name.'" style="padding-left:30px">'.$atributo.'</label>
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
    <title>Checklist Form</title>
    <style>
        .grafico {
        width: 100%;
        height: 200px;
        }
        #temometro{
            background-color: rgb(194, 194, 194);
            width: 50px;
            height: 200px;
            border-radius: 50px;
            display: flex;
            justify-content: flex-end;
            align-items: flex-end;
        }
        #valorTemometro{
            background-color: red;
            width: 100%;
            border-radius: 0px 0px 50px 50px;
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
            <form action="processaChecklist.php?id_maquina=<?php echo $idDaMaquina; ?>">

                <?php
                itensChecklist($idDaMaquina);
                atributosChecklist($idDaMaquina);
                ?>
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
</body>
</html>
