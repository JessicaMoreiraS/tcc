<?php
include('conexao.php');

if (isset($_GET['id_sala'])) {
    $idSala = $_GET['id_sala'];

    $sql = "SELECT aluno.id, aluno.nome, aluno.email FROM lista_aluno_sala
            INNER JOIN aluno ON lista_aluno_sala.id_aluno = aluno.id
            WHERE lista_aluno_sala.id_sala = ?";


    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $idSala);
        $stmt->execute();
        $stmt->bind_result($idAluno, $nomeAluno, $emailAluno);
        $idSelecionado;

    }
    $deletarAlunoDaSala;
    echo "<table>";
    echo '<tr>';
    echo "<th>ID</th>";
    echo "<th>Nome</th>";
    echo "<th>Email</th>";
    echo '</tr>';
    while ($stmt->fetch()) {
        echo '<tr>';
        echo "<td>$idAluno</td>";
        $idSelecionado = $idAluno;
        echo "<td>$nomeAluno</td>";
        echo "<td>$emailAluno</td>";
       
        echo "<td></td>";
        ?>
        <td>
           
            <a href="<?php echo "delete.php?acao=deletarAluno&id_delecao=$idAluno" ?>">
                deletar aluno
            </a>
        </td>
        <td>
            <a href="<?php echo "delete.php?acao=deletarAlunoDaSala&id_delecao=$idAluno" ?>">
                deletar da sala
            </a>
        </td>
        <td>
        <td> <button>ver mais</button></td>
        </td>
        <?php
        echo '</tr>';
    }


    ?>

    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png"/>
        <title>Alunos</title>
    </head>

    <body>
        <div class="container">
            <?php


            $salasDoAluno = array();

            $sql = "SELECT id_sala FROM lista_aluno_sala WHERE id_aluno = $idAluno";

            $result = $mysqli->query($sql);

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $salasDoAluno[] = $row['id_sala'];
                }

                
                $result->close();
            
                echo "Erro na consulta: " . $mysqli->error;
            }
            echo "</table>";
} else {
    echo "Erro na consulta SQL.";
}

if (!empty($salasDoAluno)) {
    echo "<ul>";
    echo "<li>salas do aluno $nomeAluno:</li>";
    foreach ($salasDoAluno as $sala) {
        echo "<li>$sala</li>";
    }
    echo "</ul>";
} else {
    echo "O aluno não está matriculado em nenhuma sala.";
}
?>
    </div>
    <script src="js/script.js"></script>
</body>

</html>
<?php
    if (filter_input(INPUT_GET, 'e')) {
        $mensagem_erro = filter_input(INPUT_GET, 'e');
        echo '<script>erroLogin('.$mensagem_erro.')</script>';
    }
?>