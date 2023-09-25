<?php
// PHP code to update DHT11 sensor data in the table/database.
  require 'database.php';
  
  //---------------------------------------- Condition to check that POST value is not empty.
  if (!empty($_POST)) {
    //........................................ keep track POST values
    $id = $_POST['id'];
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
    // $humidity = $_POST['humidity'];
    $status_read_sensor_dht11 = $_POST['status_read_sensor_dht11'];
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
    Database::disconnect();
  }
?>