<?php
//inicisnd sessão 
session_start();
//capturando o id do professor
$idProfessor = $_SESSION['idAcesso'];

//incluindo o banco de dados
include("conexao.php");

//sql para busca do professor
$sqlBuscaProfessor = "SELECT * FROM professor WHERE id =".$idProfessor;

//busca do nome do professor
$professorNome = mysqli_fetch_assoc(($mysqli->query($sqlBuscaProfessor)))['nome'];

$sqlConteudoCard = "SELECT * FROM lista_aluno_sala LEFT JOIN sala ON sala.id = lista_aluno_sala.id_sala LEFT JOIN professor ON professor.id = sala.id_professor WHERE lista_aluno_sala.id_aluno = $idAluno";


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>