<?php
// PHP file to get LEDs state stored in the table/database.
  include 'database.php';
  
  //---------------------------------------- Condition to check that POST value is not empty.
  if (!empty($_POST)) {
        // keep track post values
        $id = $_POST['id_maquina'];
        
        $myObj = (object)array();
        //........................................ 
        $pdo = Database::connect();
        $sql = 'SELECT * FROM maquina WHERE id="' . $id . '"';
        foreach ($pdo->query($sql) as $row) {
        $myObj->id = $row['id'];
        //Led verde = LED 01.
        //Led amarelo = LED 02
        //Led vermelho = Led 03
        $myObj->led_vermelho = $row['led_vermelho'];
        $myObj->led_amarelo= $row['led_amarelo'];
        $myObj->led_verde = $row['led_verde'];

        /*if($row['led_verde'] == "ON"){
            $sqlVerificaTempo = "SELECT * FROM checklist WHERE id_maquina = $id";
            $preparaSqlVerificaTempo = $mysqli->query($sqlVerificaTempo);
            $tempo
            while($rowMaquina = mysqli_fetch_assoc($preparaSqlVerificaTempo)){
                $tempo = $rowMaquina['date_time'];
            }
            $tempoAtual = date("Y-m-d H:i:s");

            $diferencaTempo = $tempoAtual - $tempo;
            if($diferencaTempo > 00-00-0000 04:00:00){
                AcendeLuzVermelha($id);
            }

        }*/
        
        $myJSON = json_encode($myObj);
        
        echo $myJSON;
    }
    Database::disconnect();
  }
?>