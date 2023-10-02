<?php
session_start();
if($_SESSION['idAcesso'] != 'gestaoSenai'){
    header('Location: index.html');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar professor</title>
</head>
<body>
    
    <form action="cadastros.php" method="POST">
        <input type="text" name="nome" placeholder="Nome" require>
        <input type="text" name="sobrenome" placeholder="Sobrenome" require>
        <input type="number" name="cpf" placeholder="CPF" require><!--Adicionar mascara para CPF-->
        <input type="email" name="email" placeholder="Email" require>

        <input type="submit" name="cadastrarProfessor" value="Cadastrar">
    </form>

</body>
</html>