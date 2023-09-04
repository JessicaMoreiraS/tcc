<?php
session_start();
if($_SESSION['idAcesso'] != 'gestaoSenai'){
    header('Location: index.html');
}
if(filter_input(INPUT_GET, 'view')){
    if($_GET['view'] == 'professor'){
        $tabelaBusca = "professor";
        $campos = ['cpf', 'nome', 'email'];
    }else if($_GET['view'] == 'tipo'){
        $tabelaBusca = "tipo_maquina";
        $campos = ['tipo'];
    } else if($_GET['view'] == 'professor'){
        $tabelaBusca = "maquina";
        $campos = ['id', 'modelo', 'fabricante'];
    } else if($_GET['view'] == 'aluno'){
        $tabelaBusca = "aluno";
        $campos = ['nome', 'email'];
    }
}

function trMaisTbody($campos, $tabContent){
    include('conexao.php');
    //cria a tr com os campos segundo o array de campos?>
    <tr>
        <?php
            for($i=0; $i< count($campos); $i++){?>
            <th><?php $campos[$i]?></th>
        <?php
        }?>
    </tr>
    
    <tbody>
        <?php
        $conteudo = $mysqli -> query($tabContent);
        while ($row = mysqli_fetch_assoc($conteudo)){?>
        <tr>
            <?php
            for($i=0; $i<count($campos); $i++){?>
                <td><?php echo $row[$campos[$i]]?></td>
            <?php
            }?>
            </tr>
            <?php
        }?>
    </tbody>
    <?php
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vizualizar</title>
</head>
<body>
        <?php
            include('conexao.php');
            if(in_array("modelo", $campos)){
                $busca = "SELECT * FROM maquina INNER JOIN tipo_maquina ON tipo_maquina.id = maquina.id_tipo_maquina";
                while($rowTipo = $mysqli->query($busca)){

                ?>
                    <table>
                        <tr>
                            <th><?php $rowTipo['tipo']; ?></th>
                        </tr>
                        <?php 
                        $idTipo = $rowTipo['id'];
                        $tabContent = "SELECT * FROM $tabelaBusca WHERE id_tipo_maquina = $idTipo";
                        trMaisTbody($campos, $tabContent) ?>
                    </table>
                    <?php
                }
            }else{
                ?>
                <table>
                    <?php 
                    $tabContent = "SELECT * FROM $tabelaBusca";
                    trMaisTbody($campos, $tabContent) ?>
                </table>
                <?php
            }
        ?>
</body>
</html>