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

    //acende o led verde
    acendeVerde($id_maquina);

    if($status_read_sensor_dht11 == "SUCCEED"){
        
        //encontrar qual é o tipo de maquina
        include("conexao.php");
        $sqlBuscaAtributos = "SELECT * FROM maquina WHERE id = $id_maquina";
        $resultAtributos = mysqli_query($mysqli, $sqlBuscaAtributos);
        //$maquinaConteudo = $mysqli->query($sqlBuscaAtributos);
        
        while ($maquina = mysqli_fetch_assoc($resultAtributos)){
            $id_tipo_maquina = $maquina['id_tipo_maquina'];
        }
        
        //receber atributos para o tipo de maquina
        $sqlBuscarAtt= "SELECT * FROM lista_tipo_maquina_atributo WHERE id_tipo_maquina = $id_tipo_maquina";
        $atributoConteudo = $mysqli->query($sqlBuscarAtt);
        
        while ($atributo = mysqli_fetch_assoc($atributoConteudo)){
            echo $atributo['id_atributos'];
            $id_atributo= $atributo['id_atributos'];
            $sqlDadosAtributo = "SELECT *FROM atributo_tipo WHERE id = $id_atributo";
            $dadosAtributoConteudo = $mysqli->query($sqlDadosAtributo);

            while ($dadosAtributo = mysqli_fetch_assoc($dadosAtributoConteudo)){
                $atributoEsp = $dadosAtributo['atributo_esp'];
                $variavelEsp = $_POST[$atributoEsp];
    
                $sqlUpdate = "UPDATE esp32 SET esp = '$id_esp', valor = '$variavelEsp', date_time = '$date_time' WHERE id_maquina = '$id_maquina' AND id_atributos = '$id_atributo'";

                //to do: verificar valores com valores de referencia e alterar leds para vermelho
                verificaValorReferencia($id_atributo, $variavelEsp, $id_maquina);
                
                // Executar a consulta
                if ($mysqli->query($sqlUpdate)) {
                    echo "Sucesso.";
                } else {
                    echo "Erro na atualização: ". $mysqli->error;
                }
            }
        }
    }
}

function verificaValorReferencia($id, $valor, $id_maquina){
    include("conexao.php");
    $sqlValorRef = "SELECT * FROM atributo_tipo WHERE id= $id";
    $trataSql = $mysqli->query($sqlValorRef);
    $valorReferencia;

    while ($dadosAtributos = mysqli_fetch_assoc($trataSql)){
        $valorReferencia = $dadosAtributos['valor_referencia'];
    }

    if($valor > $valorReferencia){
        acendeVermelho($id_maquina);
    }
}


function acendeVermelho($id_maquina){
    include("conexao.php");
    $sqlVermelho = "UPDATE maquina SET led_verde = 'OFF', led_amarelo = 'OFF', led_vermelho = 'ON'";
    $mysqli->query($sqlVermelho);
}


function acendeVerde($id_maquina){
    include("conexao.php");
    $sqlVerde = "UPDATE maquina SET led_verde = 'ON', led_amarelo = 'OFF', led_vermelho = 'OFF'";
    $mysqli->query($sqlVerde);
}
?>