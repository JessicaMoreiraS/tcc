<?php


$camposTema = [];
$camposBusca = [];
$aluno = false;
$checklist = false;
$tipo = false;
$tema = "";
$icone = "";

if (filter_input(INPUT_GET, 'view')) {
    if ($_GET['view'] == 'professor') {
        $tema = "Professores";
        $query = "SELECT * FROM professor";
        $camposBusca = ['nome', 'cpf', 'email'];
        $camposTema = ['Nome', 'CPF', 'Email'];


    } else if ($_GET['view'] == 'tipo') {
        $tema = "Categorias de Máquinas";
        $query = "SELECT * FROM tipo_maquina";
        $camposBusca = ['tipo'];
        $camposTema = ['Categoria', 'quantidade'];
        $tipo = true;

    } else if ($_GET['view'] == 'aluno') {
        $tema = "Alunos";
        $query = "SELECT * FROM aluno";
        $camposBusca = ['nome', 'email'];
        $aluno = true;
        $camposTema = ['Nome', 'Email', 'Turma(s)'];

    } else if ($_GET['view'] == 'maquina') {
        $tema = "Máquinas";
        $query = "SELECT * FROM maquina INNER JOIN tipo_maquina ON maquina.id_tipo_maquina = tipo_maquina.id";
        $camposBusca = ['id', 'modelo', 'fabricante', 'tipo'];
        $camposTema = ['ID', 'Modelo', 'Fabricante', 'Categoria'];

    } else if ($_GET['view'] == 'sala') {
        $tema = "Turmas";
        $query = "SELECT * FROM sala INNER JOIN professor ON sala.id_professor = professor.id";
        $camposBusca = ['turma', 'nome'];
        $camposTema = ['Turma', 'Professor'];

    } else if ($_GET['view'] == 'alunosSala') {
        if (isset($_GET['id_sala_view'])) {
            $tema = "Alunos da Turma";
            $idDaSala = $_GET['id_sala_view'];
            $query = "SELECT * FROM lista_aluno_sala INNER JOIN aluno ON lista_aluno_sala.id_aluno = aluno.id WHERE lista_aluno_sala.id_sala = " . $idDaSala . "";
            $camposBusca = ['nome', 'email'];
            $camposTema = ['Nome', 'Email'];
        }

    } else if ($_GET['view'] == 'checklist') {
        $tema = "Checklists de Segurança";
        $checklist = true;
        $query = "SELECT * FROM checklist INNER JOIN maquina ON checklist.id_maquina = maquina.id";
        $camposBusca = ['data', 'id', 'modelo'];
        $camposTema = ['data', 'Maquina', 'Modelo', 'Responsavel', 'Email'];
    } else {
        header('Location: homeGestor.php');
    }

}

function buscarDados($query, $camposBusca, $camposTema, $aluno, $checklist, $tipo)
{
    include("conexao.php");
    $conteudo = $mysqli->query($query);
    ?>

    <table class="table">
        <tr>
            <thead class="table__thead">
                <th class="table__th"></th>
                <?php for ($i = 0; $i < count($camposTema); $i++) { ?>
                    <th class="table__th">
                        <?php echo $camposTema[$i] ?>

                    </th>
                <?php } ?>
            </thead>
        </tr>
        <?php

        while ($row = mysqli_fetch_assoc($conteudo)) { ?>
            <tbody class="table__tbody">
                <?php
                if ($_GET['view'] == 'professor') {
                    echo '<tr class="table-row table-row--teacher table ">';
                } elseif ($_GET['view'] == 'aluno') {
                    echo '<tr class="table-row table-row--student table ">';
                } elseif ($_GET['view'] == 'sala') {
                    echo '<tr class="table-row table-row--class table ">';
                }elseif($_GET['view'] == 'checklist'){
                    echo '<tr class="table-row table-row--checklist table ">';
                }else{
                    echo '<tr class="table-row table-row--gears table ">';
                }
                ?>

                <td class="table-row__td">
                    <div class="table-row__img"></div>
                    <div class="table-row__info">
                    </div>
                </td>
                <?php for ($i = 0; $i < count($camposBusca); $i++) { ?>
                    <td class="table-row__td " data-column= "<?php echo $camposTema[$i]?>">
                        <div>
                            <p class="table-row__p">
                                <?php echo $row[$camposBusca[$i]]; ?>
                            </p>
                        </div>
                    </td>
                    <?php

                    if ($i == count($camposBusca) - 1) {
                        if ($aluno) {
                            $queryAluno = "SELECT * FROM lista_aluno_sala INNER JOIN sala ON lista_aluno_sala.id_sala = sala.id WHERE  " . $row['id'] . " = id_aluno";
                            $conteudoAluno = $mysqli->query($queryAluno); ?>
                            <td class="table-row__td">
                            <select class="custom-select">
                                <?php while ($rowSalsDoAluno = mysqli_fetch_assoc($conteudoAluno)) {

                                    echo "
                                    <option  class='link_option'>" .$rowSalsDoAluno['turma']."</option>
                                    ";
                                } ?>
                            </select>
                            </td>
                        <?php }
                        if ($tipo) {
                            $quantidade = 0;
                            $queryContarMaquinas = "SELECT * FROM tipo_maquina INNER JOIN maquina ON tipo_maquina.id = maquina.id_tipo_maquina";
                            $conteudoContagem = $mysqli->query($queryContarMaquinas);
                            while ($rowResponsavel = mysqli_fetch_assoc($conteudoContagem)) {
                                $quantidade = $quantidade + 1;
                            }
                            echo " <td class='table-row__td'>
                            <div>
                                <p class='table-row__p'>
                                   $quantidade
                                </p>
                            </div>
                        </td>";
                        }
                    }

                    if ($checklist && $camposBusca[$i] == "modelo") {
                        if ($row['id_aluno'] > 0) {
                            $id_responsavel = $row['id_aluno'];
                            $responsavel = "aluno";
                        } else {
                            $id_responsavel = $row['id_professor'];
                            $responsavel = "professor";
                        }
                        $queryResponsavel = "SELECT * FROM " . $responsavel . " WHERE id=" . $id_responsavel;
                        $conteudoResponsavel = $mysqli->query($queryResponsavel);
                        while ($rowResponsavel = mysqli_fetch_assoc($conteudoResponsavel)) {
                            echo "<td class='table-row__td'>" . $responsavel . " " . $rowResponsavel['nome'] . "</td><td class='table-row__td'>" . $rowResponsavel['email'] . "</td>";
                        }
                    }
                }
                ?>
                </tr>
            </tbody>
        <?php } ?>
    </table>
<?php }

?>

<!DOCTYPE html>
<html lang="pt-br" id="html_tables">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar</title>
    <script src="https://unpkg.com/scrollreveal"></script>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/mediaQuery.css" />
</head>

<body id="body_tables">
    <header class="topo-index" id="header_homeGestao">
        <img src="img/logo-senai-branco.png" alt="" />
    </header>
    <div class="container">
        <div class="row row--top-40">
            <div class="col-md-12">
                <div class="table-container">
                    <h2 class="row__title">
                        <?php echo '<b>' . $tema . '</b>' ?> do Sistema:
                    </h2>


                </div>
            </div>
        </div>

        <div class="row row--top-20">
            <div class="col-md-12">
                <div class="table-container">
                    <?php buscarDados($query, $camposBusca, $camposTema, $aluno, $checklist, $tipo); ?>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="js/reveal.js"></script>
</html>