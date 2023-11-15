<?php
    if(isset($_GET['id_maquina'])){
        $idDaMaquina = $_GET['id_maquina'];

        function buscarNoBanco($idMaquina, $sql){
            include('conexao.php');
            $result = mysqli_query($mysqli, $sql);
            
            if (!$result) {
                die("Erro na consulta: " . mysqli_error($mysqli));
            }
            return $result;
        }

        function reloadGrafico($idMaquina){
            include('conexao.php');
            $sqlAtualizacao = "SELECT * FROM esp32 INNER JOIN atributo_tipo ON esp32.id_atributos = atributo_tipo.id WHERE id_maquina = $idMaquina";
            $result = buscarNoBanco($idMaquina, $sqlAtualizacao);
            $conteudo = "";
    
            while ($row = mysqli_fetch_assoc($result)) {
                if($row['atributo'] == "velocidade" || $row['atributo'] == "temperatura" || $row['atributo'] == "vibracao"){
                    $conteudo .= $row['atributo']."-".$row['valor']."-".$row['atributo_esp']."-".$row['valor_referencia']." ";
                
                }
            }
            $conteudo = rtrim($conteudo, " ");
            echo $conteudo;
        }
        reloadGrafico($idDaMaquina);
    }
?>