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
        $sql = 'SELECT * FROM maquina WHERE id="'. $id .'"';
            foreach ($pdo->query($sql) as $row) {
            $myObj->id = $row['id'];
            $id = $row['id'];
            //Led verde = LED 01.
            //Led amarelo = LED 02
            //Led vermelho = Led 03
            $myObj->led_vermelho = $row['led_vermelho'];
            $myObj->led_amarelo= $row['led_amarelo'];
            $myObj->led_verde = $row['led_verde'];
            $ledVerde = $row['led_verde'];
        }
        Database::disconnect();
        //........................................ 

        if($ledVerde == "ON"){
            $sqlVerificaTempo = "SELECT * FROM checklist WHERE id_maquina = $id";
            $preparaSqlVerificaTempo = $mysqli->query($sqlVerificaTempo);
            $tempoCheck=0;
            while($rowMaquina = mysqli_fetch_assoc($preparaSqlVerificaTempo)){
                $tempoCheck = $rowMaquina['date_time'];
            }
            $tempoAtual =  date_create();
            $tempoCheck = date_create($tempoCheck);

            $diff = date_diff($tempoAtual, $tempoCheck);

            // Acesse os componentes da diferença (dias, horas, minutos, etc.)
            $anoAtual = (int)$tempoAtual->format('Y');
            $mesAtual = (int)$tempoAtual->format('m');
            $diaAtual = (int)$tempoAtual->format('d');
            $horaAtual = (int)$tempoAtual->format('H');
            $anos = $diff->y;
            $meses = $diff->m;
            $dias = $diff->days;
            $horas = $diff->h;
            $minutos = $diff->i;
            $segundos = $diff->s;

            if($anos != 0 || $meses != 0 || $dias != 0 || $horas>=4){
                acendeLuzVermelha($id);
                //........................................ 
                $pdo = Database::connect();
                $sql = 'SELECT * FROM maquina WHERE id="'. $id .'"';
                    foreach ($pdo->query($sql) as $row) {
                    $myObj->id = $row['id'];
                    $id = $row['id'];
                    //Led verde = LED 01.
                    //Led amarelo = LED 02
                    //Led vermelho = Led 03
                    $myObj->led_vermelho = $row['led_vermelho'];
                    $myObj->led_amarelo= $row['led_amarelo'];
                    $myObj->led_verde = $row['led_verde'];
                    $ledVerde = $row['led_verde'];
                }
                Database::disconnect();
                //........................................ 
            }
            $myObj->mesAtual = $mesAtual;
            $myObj->mesesDeDiferenca = $meses;
            $myObj->diaAtual = $diaAtual;
            $myObj->diasDeDiferenca = $dias;
            $myObj->horaAtual = $horaAtual;
            $myObj->horasDeDiferenca = $horas;

        }
        
        $myJSON = json_encode($myObj);
        
        echo $myJSON;
    
    
}

function acendeLuzVermelha($idDaMaquina){
    include('conexao.php');
    $sqlLuzVermelha = "UPDATE maquina SET led_verde='OFF', led_amarelo='OFF', led_vermelho='ON' WHERE id = $idDaMaquina";
    $mysqli->query($sqlLuzVermelha);
}
?>