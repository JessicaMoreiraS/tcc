<?php
// PHP file to get LEDs state stored in the table/database.
  include 'database.php';
  include('conexao.php');
  date_default_timezone_set('America/Sao_Paulo');
  
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
        $id = $row['id'];
        //Led verde = LED 01.
        //Led amarelo = LED 02
        //Led vermelho = Led 03
        $myObj->led_vermelho = $row['led_vermelho'];
        $myObj->led_amarelo= $row['led_amarelo'];
        $myObj->led_verde = $row['led_verde'];

        /*if($row['led_verde'] == "ON"){
            $sqlVerificaTempo = "SELECT * FROM checklist WHERE id_maquina = $id";
            $preparaSqlVerificaTempo = $mysqli->query($sqlVerificaTempo);
            $tempo;
            while($rowMaquina = mysqli_fetch_assoc($preparaSqlVerificaTempo)){
                $tempo = $rowMaquina['date_time'];
            }
            $tempoAtual = date("Y-m-d H:i:s");

            $diferencaTempo = (int)$tempoAtual > (int)$tempo;
            if($diferencaTempo > date('00-00-0000 04:00:00')){
                acendeLuzVermelha($id);

                $myObj->led_vermelho = $row['led_vermelho'];
                $myObj->led_amarelo= $row['led_amarelo'];
                $myObj->led_verde = $row['led_verde'];
            }

        }*/
        
        $myJSON = json_encode($myObj);
        
        echo $myJSON;
    }
    Database::disconnect();
}

function acendeLuzVermelha($idDaMaquina){
    // include('conexao.php');
    //$sqlLuzVermelha = "UPDATE maquina SET led_verde='OFF', led_amarelo='OFF', led_vermelho='ON' WHERE id = $idDaMaquina";
    //$mysqli->query($sqlLuzVermelha)
}
?>