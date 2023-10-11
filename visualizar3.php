<?php
session_start();
if ($_SESSION['idAcesso'] != 'gestaoSenai') {
    header('Location: index.html');
    exit;
}
$tabelaExtra ="";
$linkCadastro="";
$camposExtras = [];
if (filter_input(INPUT_GET, 'view')) {
    //professor
    if ($_GET['view'] == 'professor') {
        $tabelaBusca = "professor";
        $campos = ['cpf', 'nome', 'email'];
        $linkCadastro="cadastrarProfessor.php";
    //maquinas
    } else if ($_GET['view'] == 'maquina') {
        $tabelaBusca = "maquina";
        $campos = ['id', 'modelo', 'fabricante'];
        $linkCadastro="cadastrarMaquina.php";
    //tipos
    } else if ($_GET['view'] == 'tipo') {
        $tabelaBusca = "tipo_maquina";
        $campos = ['id', 'tipo']; 
        //to do: adicionar busca de atributos
        $tabelaExtra = 'atributo_tipo';
        $camposExtras = ['atributos'];
        $linkCadastro="";
    //alunos
    } else if ($_GET['view'] == 'aluno') {
        $tabelaBusca = "aluno";
        $campos = ['nome', 'email']; 
        //adicionar turmas
        $tabelaExtra = 'lista_aluno_sala';
        $camposExtras = ['turmas'];
        $linkCadastro="";
    //turmas(salas)
    }else if($_GET['view'] == 'sala'){ 
        $tabelaBusca = "sala";
        $campos = ['turma', 'id_professor'];
        //buscar o nome do professor
        $tabelaExtra = 'professor';
        $camposExtras = ['nome'];
        $linkCadastro="";
    //alunos da sala
    }else if($_GET['view'] == 'alunosSala'){ 
        $tabelaBusca = "lista_aluno_sala";
        $campos = ['id_lista','id_aluno', 'id_sala'];   
    }

}

function trMaisTbody($campos, $tabContent, $tabelaBuscar, $camposQ, $tabelaExtraQ)
{
    include('conexao.php');
    //cria a tr com os campos segundo o array de campos?>
    <tr>
        <?php
        for ($i = 0; $i < count($campos); $i++) { ?>
            <th>
                <?php $campos[$i]; ?>
            </th>
            <?php   
        } 
        if($tabelaExtraQ != ""){
            for ($i = 0; $i < count($camposExtrasQ); $i++) { ?>
                <th>
                    <?php $camposExtrasQ[$i] ?>
                </th>
                <?php   
            } 
        }
        ?>
    </tr>

    <tbody>
        <?php
        //cria trs com as informacoes da consulta ao banco
        $conteudo = $mysqli->query($tabContent);
        while ($row = mysqli_fetch_assoc($conteudo)) { ?>
            <tr>
                <?php
                for ($i = 0; $i < count($campos); $i++) { 
                    $id_para_modificar = $row['id'];
                    $idExtra=$row['id'];
                    if($_GET['view'] =='sala'){
                        $idExtra = $row['id_professor']; // imprimir professor
                    }?>
                    <td>
                        <?php echo $row[$campos[$i]] ?>
                    </td>
                    <?php
                    if($camposExtrasQ && $camposExtrasQ[0] != "" && $tabelaExtra!=""){
                        $queryExtra = "SELECT * FROM $tabelaExtra WHERE id=$idExtra";
                        $conteudoExtra = $mysqli->query($tabContent);
                        while ($rowExtra = mysqli_fetch_assoc($conteudoEstra)) {
                            for ($x = 0; $x < count($camposExtrasQ); $x++) { ?>
                                <td>
                                    <?php echo $rowExtra[$camposExtrasQ[$x]] ?>
                                </td>
                        <?php
                            }
                        }
                    }
                } ?>
                <td>
                    <a href="<?php echo "delete.php?option=$tabelaBuscar&id_delecao=$id_para_modificar" ?>">
                        delete
                    </a>
                </td>
                <?php
                if ($_GET['view'] == 'maquina' || $_GET['view'] == 'tipo') {
                    echo '<td>
                        <a href="update.php?option=' . $tabelaBuscar . '&id_atualizacao=' . $id_para_modificar . '">
                         Atualizar
                        </a>
                         </td>';
                }else if($_GET['view'] == 'sala' ){
                    echo '<td>
                    <a href="gestaoAlunos.php?id_sala=' . $id_para_modificar . '">
                     entrar
                    </a>
                     </td>';
                }
                ?>

            </tr>
            <?php
        } ?>
    </tbody>
    <?php
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar</title>
</head>

<body>
    <?php
    include('conexao.php');
    if (in_array("modelo", $campos)) {
        $busca = "SELECT * FROM maquina INNER JOIN tipo_maquina ON tipo_maquina.id = maquina.id_tipo_maquina";
        //$buscar = "SELECT * FROM maquina";
        $conteudo = $mysqli->query($busca);
        while ($rowMaquina = mysqli_fetch_assoc($conteudo)) {


            ?>
            <table>
                <tr>
                    <th>
                        <?php $rowMaquina['id_tipo_maquina']; ?>
                    </th>
                </tr>
                <?php
                $idTipo = $rowMaquina['id_tipo_maquina'];
                $tabContent = "SELECT * FROM $tabelaBusca WHERE id_tipo_maquina = $idTipo";
                trMaisTbody($campos, $tabContent, $tabelaBusca, $camposExtras, $tabelaExtra) ?>
            </table>
            <?php
        }
    } else {
        ?>
        <table>
            <?php
            $tabContent = "SELECT * FROM $tabelaBusca";
            trMaisTbody($campos, $tabContent, $tabelaBusca, $camposExtras, $tabelaExtra) ?>
        </table>
        <?php
    }
    ?>
</body>

</html>
<?php
if (filter_input(INPUT_GET, 'e')) {
    $mensagem_erro = filter_input(INPUT_GET, 'e');
    echo '<script>erroLogin(' . $mensagem_erro . ')</script>';
}
?>