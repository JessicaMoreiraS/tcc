<?php
// PHP code to update DHT11 sensor data in the table/database.
require 'database.php';
include("conexao.php");
$array = array();
date_default_timezone_set('America/Sao_Paulo');

function carregaArrayAtributos($id_maquina){
    include("conexao.php");
    $sqlVerificaAtributo = "SELECT * FROM esp32 WHERE id_maquina = $id_maquina";
    $tranformaSqlVerificaAtributo = $mysqli->query($sqlVerificaAtributo);
    while ($rowEsp = mysqli_fetch_assoc($tranformaSqlVerificaAtributo)){
        $id_espAtributo = $rowEsp['id_atributos'];
        $array[] = $id_espAtributo;
    }
    return $array;
}

//---------------------------------------- Condition to check that POST value is not empty.
if (!empty($_POST)) {
    //........................................ keep track POST values
    $id_esp = $_POST['id'];//esp
    $id_maquina = $_POST['id_maquina'];//maquina
    $status_read_sensor_dht11 = $_POST['status_read_sensor_dht11'];//ststus semsor
    $date_time= date('Y-m-d H:i:s');
    $id_tipo_maquina;

    $arrayAtributos = carregaArrayAtributos($id_maquina);

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
        }//to do: tratar erro caso o id da maquina enviado não exista na base de dados

        
        //receber atributos para o tipo de maquina
        $sqlBuscarAtt= "SELECT * FROM lista_tipo_maquina_atributo WHERE id_tipo_maquina = $id_tipo_maquina";
        $atributoConteudo = $mysqli->query($sqlBuscarAtt);
        
        while ($atributo = mysqli_fetch_assoc($atributoConteudo)){
            addNovosAtributos($id_tipo_maquina, $id_maquina, $id_esp , $date_time);
            
            echo $atributo['id_atributos'];
            $id_atributo= $atributo['id_atributos'];
            $sqlDadosAtributo = "SELECT *FROM atributo_tipo WHERE id = $id_atributo";

            $arrayAtributosAtualizado = verificaAtributosNovos($array, $id_esp, $id_atributo, $id_maquina, $date_time);

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

function addNovosAtributos($idTipoMaquina, $idMaquina, $esp, $time){
    include('conexao.php');
    $sqlAtributosMaquina = "SELECT * FROM lista_tipo_maquina_atributo WHERE id_tipo_maquina = $idTipoMaquina";
    $preparaSqlAtributosMaquina = $mysqli->query($sqlAtributosMaquina);
    $atributoLinha;

    $ultimoId;
    /*SELECT MAX(id) CODIGO FROM esp32*/
    $sqlUltimoId = "SELECT *from esp32 ORDER BY id DESC LIMIT 1";
    $preparaSqlUltimoId = $mysqli->query($sqlUltimoId);
    if($preparaSqlUltimoId->num_rows>0){
        $rowUltimoId = $preparaSqlUltimoId->fetch_assoc();
        $ultimoId = $rowUltimoId['id'];
    }

    while($AtributosMaquina = mysqli_fetch_assoc($preparaSqlAtributosMaquina)){
        $atributoLinha = $AtributosMaquina['id_atributos'];
        $sqlAtributosEsp = "SELECT * FROM esp32 WHERE id_maquina = $idMaquina";
        $preparaSqlAtributosEsp = $mysqli->query($sqlAtributosEsp);

        while($linhaAtributoEsp = mysqli_fetch_assoc($preparaSqlAtributosEsp)){
            $atributoEsp = $linhaAtributoEsp['id_atributos'];
            $idLinhaEsp = $linhaAtributoEsp['id'];
            if($atributoEsp != $atributoLinha){
                if($idLinhaEsp == $ultimoId){
                    $sqlAddAtributo = "INSERT INTO esp32 (esp, id_maquina, id_atributos, valor, date_time) VALUES ('$esp', '$idMaquina', '$atributoLinha', '0', '$time')";
                    if ($mysqli->query($sqlAddAtributo)) {
                        return;
                    }
                }
            }else{
                break;
            }
        }
    }
}


function verificaAtributosNovos($array, $id_esp, $id_atributo, $id_maquina, $date_time){
    for($i=0; $i<count($array); $i++){
        if($array[$i] == $id_atributo){
            if($i == count($array)-1){
                $novoArray = addAtributosNovos($id_esp, $id_atributo, $id_maquina, $date_time);
            }
        }else{
            return $array;
        }
    }
    // foreach ($array as $value) {
    //     if($value->date == $id_atributo){
    //         return $array;
    //     } else{
    //         return $novoArray;
    //     }
    // }
}

function addAtributosNovos($id_esp, $id_atributo, $id_maquina, $date_time){
    include("conexao.php");
    $sqlInsertAtributo = "INSERT INTO esp32 (esp, id_maquina, id_atributos, valor, date_time) VALUES ('$id_esp', '$id_maquina', '$id_atributo', '0', '$date_time')";
    if ($mysqli->query($sqlInsertAtributo)) {
        $recarregaArray = carregaArrayAtributos($id_maquina);
        return $recarregaArray; 
    } else {
        echo "erro";
        //addAtributosNovos($id_atributo, $id_maquina, $date_time);
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