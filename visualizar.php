<?php


$camposTema = [];
$camposBusca = [];
$aluno= false;
$checklist= false;
$tipo = false;
$tema= "";
$icone="";

if (filter_input(INPUT_GET, 'view')) {
    if ($_GET['view'] == 'professor') {
        $tema= "Professores";
        $query= "SELECT * FROM professor";
        $camposBusca = ['nome', 'cpf', 'email'];
        $camposTema = ['Nome', 'CPF', 'Email'];


  } else if ($_GET['view'] == 'tipo') {
        $tema= "Categorias de Máquinas";
        $query= "SELECT * FROM tipo_maquina";
        $camposBusca = ['tipo'];
        $camposTema = ['Categoria', 'quantidade'];
        $tipo = true;

  } else if ($_GET['view'] == 'aluno') {
        $tema= "Alunos";
        $query= "SELECT * FROM aluno";
        $camposBusca = ['nome', 'email'];
        $aluno = true;
        $camposTema = ['Nome', 'Email', 'Turma(s)'];

  } else if ($_GET['view'] == 'maquina') {
        $tema= "Máquinas";
        $query= "SELECT * FROM maquina INNER JOIN tipo_maquina ON maquina.id_tipo_maquina = tipo_maquina.id";
        $camposBusca = ['id', 'modelo', 'fabricante', 'tipo'];
        $camposTema = ['ID', 'Modelo', 'Fabricante','Categoria'];

  } else if ($_GET['view'] == 'sala') {
        $tema= "Turmas";
        $query= "SELECT * FROM sala INNER JOIN professor ON sala.id_professor = professor.id";
        $camposBusca = ['turma', 'nome'];
        $camposTema = ['Turma', 'Professor'];

  } else if ($_GET['view'] == 'alunosSala') {
        if (isset($_GET['id_sala_view'])) {
            $tema= "Alunos da Turma";
            $idDaSala =$_GET['id_sala_view'];
            $query= "SELECT * FROM lista_aluno_sala INNER JOIN aluno ON lista_aluno_sala.id_aluno = aluno.id WHERE lista_aluno_sala.id_sala = ".$idDaSala."";
            $camposBusca = ['nome', 'email'];
            $camposTema = ['Nome', 'Email'];
        }

  } else if ($_GET['view'] == 'checklist') {
        $tema= "Checklists de Segurança";
        $checklist= true;
        $query= "SELECT * FROM checklist INNER JOIN maquina ON checklist.id_maquina = maquina.id";
        $camposBusca = ['data', 'id', 'modelo'];
        $camposTema = ['data', 'Maquina', 'Modelo', 'Responsavel','Email'];
  } else {
        header('Location: homeGestor.php');
  }
  
}

function buscarDados($query, $camposBusca, $camposTema, $aluno, $checklist,$tipo) {
    include("conexao.php");
    $conteudo = $mysqli->query($query);
    ?>
    <table>
        <tr>
            <?php for($i=0; $i<count($camposTema); $i++){ ?>
                <td><?php echo $camposTema[$i]?></td>
            <?php } ?>
        </tr>
    <?php

    while ($row = mysqli_fetch_assoc($conteudo)) { ?>
        <tr>
        <?php for($i=0; $i<count($camposBusca); $i++){?>
            <td>
                <?php echo $row[$camposBusca[$i]]; ?>
            </td>
        <?php   
                   
                if($i == count($camposBusca)-1){
                    if($aluno){
                        $queryAluno = "SELECT * FROM lista_aluno_sala INNER JOIN sala ON lista_aluno_sala.id_sala = sala.id WHERE  ".$row['id']." = id_aluno";
                        $conteudoAluno = $mysqli->query($queryAluno);?>
                        <td>
                            <?php while ($rowSalsDoAluno = mysqli_fetch_assoc($conteudoAluno)) { 
                                echo  $rowSalsDoAluno['turma']." ";
                            }?>
                        </td>
                    <?php } 
                    if($tipo){
                        $quantidade = 0;
                        $queryContarMaquinas = "SELECT * FROM tipo_maquina INNER JOIN maquina ON tipo_maquina.id = maquina.id_tipo_maquina";
                        $conteudoContagem = $mysqli->query($queryContarMaquinas);
                        while ($rowResponsavel = mysqli_fetch_assoc($conteudoContagem)) { 
                            $quantidade = $quantidade+1;
                        }
                        echo "<td>".$quantidade."</td>";
                    }
                } 

                if($checklist && $camposBusca[$i] == "modelo"){
                    if($row['id_aluno'] >0){
                        $id_responsavel=$row['id_aluno'];
                        $responsavel = "aluno";
                    }else{
                        $id_responsavel=$row['id_professor'];
                        $responsavel = "professor";
                    }
                    $queryResponsavel = "SELECT * FROM ".$responsavel." WHERE id=".$id_responsavel;
                    $conteudoResponsavel = $mysqli->query($queryResponsavel);
                    while ($rowResponsavel = mysqli_fetch_assoc($conteudoResponsavel)) { 
                        echo "<td>".$responsavel. " ". $rowResponsavel['nome']."</td><td>". $rowResponsavel['email']."</td>";
                    }
                }
            }    
        ?>
        </tr>
    <?php } ?>
    </table>
<?php }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar</title>
</head>
<body>
    <h2><?php echo $tema?></h2>

    <div class=containerTable>
        <?php buscarDados($query, $camposBusca, $camposTema, $aluno, $checklist, $tipo);?>
    </div>
</body>
</html>
