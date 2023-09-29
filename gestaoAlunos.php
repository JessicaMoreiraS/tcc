// visualizarAlunosSala.php

<?php
include('conexao.php');

if (isset($_GET['id_sala'])) {
    $idSala = $_GET['id_sala'];

    // Consulta para buscar os alunos dessa sala
    $sql = "SELECT aluno.nome FROM lista_aluno_sala
            INNER JOIN aluno ON lista_aluno_sala.id_aluno = aluno.id
            WHERE lista_aluno_sala.id_sala = ?";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $idSala);
        $stmt->execute();
        $stmt->bind_result($nomeAluno);

        echo "<h1>Alunos da Sala</h1>";
        echo "<ul>";

        while ($stmt->fetch()) {
            echo "<li>$nomeAluno</li>";
        }

        echo "</ul>";
    } else {
        echo "Erro na consulta SQL.";
    }
} else {
    echo "ID da sala não especificado.";
}
?>
