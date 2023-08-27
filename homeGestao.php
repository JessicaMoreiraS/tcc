<?php
session_start();
if($_SESSION['idAcesso'] != 'gestaoSenai'){
    header('Location: index.html');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - gestão</title>
</head>
<body>

    <!-- Criar cadastros no database -->
    <div>
        <a href="cadastrar.php?option=professor">Cadastrar novo Professor</a>
        <a href="cadastrar.php?option=tipo">Cadastrar nova categoria de maquinário</a>
        <a href="cadastrar.php?option=maquina">Cadastrar nova maquina</a>
    </div>

    <div>
        <a href="vizualizar.php?view=professor">Vizualizar Professores</a>
        <a href="vizualizar.php?view=aluno">Vizualizar Alunos</a>
        <a href="vizualizar.php?view=tipo">Vizualizar tipos de maquinario</a>
        <a href="vizualizar.php?view=maquina">Vizualizar maquinas</a>
    </div>
</body>
</html>
