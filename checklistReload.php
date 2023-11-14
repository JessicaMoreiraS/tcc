<?php
    setInterval($idDaMaquina);
    function setInterval($idMaquina){
        $sqlAtualizacao = "SELECT * FROM esp32 INNER JOIN atributo_tipo ON esp32.id_atributos = atributo_tipo.id WHERE id_maquina = $idMaquina";
        $result = buscarNoBanco($idMaquina, $sqlAtualizacao);


        while ($row = mysqli_fetch_assoc($result)) {
            // if($row['atributo'] == "temperatura"){
                //     echo '<script>';
                //     echo 'temometro('.$row['valor'].');'; 
                //     echo '</script>';
                // }
            if($row['atributo'] == "velocidade" || $row['atributo'] == "vibracao"){
                // echo '<script>';
                // // echo "console.log('".$info[0]."');";
                // echo 'atualizaVelocimetro('.$row['valor'] .', "'.$row['atributo'].'");';
                // echo '</script>';
                $conteudo = $row['atributo']."-".$row['valor'];
                echo $conteudo;
            }
        }
        
    }
?>