<?php
session_start();
// if($_SESSION['idAcesso'] != 'gestaoSenai'){
//     header('Location: index.html');
// }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar professor</title>
</head>

<body>

    <form action="cadastro.php" method="POST">
        <input id="id" type="number" value="1" hidden name="nome" placeholder="Nome" required>
        <input id="nome" type="text" name="nome" placeholder="Nome" required>
        <input id="CPF" type="text" name="cpf" placeholder="CPF" required>
        <input id="email" type="email" name="email" placeholder="Email" required>
        <input id="senha" type="password" name="senha" placeholder="Senha" required>

        <input type="submit" name="cadastrarProfessor" value="Cadastrar">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>