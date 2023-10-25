<?php
// PHP code to update DHT11 sensor data in the table/database.
  require 'database.php';
  include("conexao.php");
  
  //---------------------------------------- Condition to check that POST value is not empty.
  if (!empty($_POST)) {
    //........................................ keep track POST values
    $id_esp = $_POST['id'];//esp
    $id_maquina = $_POST['id_maquina'];//maquina
    $status_read_sensor_dht11 = $_POST['status_read_sensor_dht11'];//ststus semsor
    $date_time= date('Y-m-d H:i:s');
    $id_tipo_maquina;

    if($status_read_sensor_dht11 == "SUCESS"){
        console.log("dentro do sucess");
        
        //encontrar qual é o tipo de maquina
        include("conexao.php");
        $sqlBuscaAtributos = "SELECT * FROM maquina WHERE id = $id_maquina";
        $resultAtributos = mysqli_query($mysqli, $sql);
        //$maquinaConteudo = $mysqli->query($sqlBuscaAtributos);

        while ($maquina = mysqli_fetch_assoc($resultAtributos)){
            $id_tipo_maquina = $maquina['id_tipo_maquina'];
        }

        //receber atributos para o tipo de maquina
        $sqlBuscarAtributos = "SELECT * FROM lista_tipo_maquina_atributo WHERE id_tipo_maquina = $id_tipo_maquina";
        $atributoConteudo = $mysqli->query($sqlBuscaAtributos);

        while ($atributo = mysqli_fetch_assoc($atributoConteudo)){
            $id_atributo = $atributo['id_atributo'];
            $sqlDadosAtributo = "SELECT *FROM atributo_tipo WHERE id = $id_atributo";
            $dadosAtributoConteudo = $mysqli->query($sqlDadosAtributo);

            while ($dadosAtributo = mysqli_fetch_assoc($dadosAtributoConteudo)){
                $atributoEsp = $dadosAtributo['atributo_esp'];
                $variavelEsp = $_POST[$atributoEsp];
                $sqlUpdate = "UPDATE esp32 (esp, id_maquina, id_atributo, valor, date_time) SET ('$id_esp', '$id_maquina', '$id_atributo','$variavelEsp','$date_time') WHERE id_maquina = $id_maquina AND id_atributos = $id_atributo";
                //to do: verificar valores com valores de referencia e alterar leds para verde ou vermelho

                // Executar a consulta
                if ($mysqli->query($sqlUpdate)) {
                    echo "Sucesso.";
                } else {
                    echo "Erro na atualização: " . $mysqli->error;
                }
            }
        }

    }
    
    /*
    $temperatura = $_POST['temperatura'];
    $velocidade = $_POST['velocidade'];
    $oleo_caixaDeVelocidade = $_POST['oleo_caixaDeVelocidade'];
    $viscosidade_caixaDeVelocidade = $_POST['viscosidade_caixaDeVelocidade'];
    $oleo_caixaDeNorton = $_POST['oleo_caixaDeNorton'];
    $viscosidade_caixaDeNorton = $_POST['viscosidade_caixaDeNorton'];
    $oleo_aventalDoTorno = $_POST['oleo_aventalDoTorno'];
    $viscosidade_aventalDoTorno = $_POST['viscosidade_aventalDoTorno'];
    $vibracao = $_POST['vibracao'];
    $tempo_On = $_POST['tempo_On'];
    //........................................
    
    //........................................ Updating the data in the table.
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $sql = "UPDATE esp32 SET temperatura = ?, velocidade = ?, oleo_caixaDeVelocidade = ?, viscosidade_caixaDeVelocidade = ?, oleo_caixaDeNorton = ?, viscosidade_caixaDeNorton = ?, oleo_aventalDoTorno = ?, viscosidade_aventalDoTorno = ?, vibracao = ?, tempo_On = ?, status_read_sensor_dht11 = ? WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array(
        $temperatura, $velocidade, $oleo_caixaDeVelocidade, 
        $viscosidade_caixaDeVelocidade, $oleo_caixaDeNorton, 
        $viscosidade_caixaDeNorton, $oleo_aventalDoTorno, 
        $viscosidade_aventalDoTorno, $vibracao, $tempo_On, 
        $status_read_sensor_dht11, $id
    ));
/*
    $sql = "UPDATE esp32 SET temperatura = ?, velocidade = ?, oleo_caixaDeVelocidade = ?, viscosidade_caixaDeVelocidade = ?, oleo_caixaDeNorton = ?, viscosidade_caixaDeNorton = ?, oleo_aventalDoTorno = ?, vibracao = ?, tempo_On = ?, status_read_sensor_dht11 = ? WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($temperatura,$velocidade, $oleo_caixaDeVelocidade, $viscosidade_caixaDeVelocidade, $oleo_caixaDeNorton, $viscosidade_caixaDeNorton, $oleo_aventalDoTorno, $viscosidade_aventalDoTorno, $vibracao, $tempo_On, $status_read_sensor_dht11, $id));*/
    //Database::disconnect();
  }
?>