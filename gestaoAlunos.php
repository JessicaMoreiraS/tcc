// visualizarAlunosSala.php

<?php
include('conexao.php');

if (isset($_GET['id_sala'])) {
    $idSala = $_GET['id_sala'];

    // Consulta para buscar os alunos dessa sala
    $sql = "SELECT aluno.id, aluno.nome, aluno.email FROM lista_aluno_sala
            INNER JOIN aluno ON lista_aluno_sala.id_aluno = aluno.id
            WHERE lista_aluno_sala.id_sala = ?";

    

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $idSala);
        $stmt->execute();
        $stmt->bind_result($idAluno,$nomeAluno,$emailAluno);

        } 

 
        echo "<table>";
        echo '<tr>';
            echo "<th>ID</th>";
            echo "<th>Nome</th>";
            echo "<th>Email</th>";
        echo '</tr>';
        while ($stmt->fetch()) {
            echo '<tr>';
            echo "<td>$idAluno</td>";
            echo "<td>$nomeAluno</td>";
            echo "<td>$emailAluno</td>";
            echo '</tr>';
        }

        echo "</table>";
    } else {
        echo "Erro na consulta SQL.";
    }
?>
