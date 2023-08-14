<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar conta</title>
</head>
<body>
    <form action="ConfirmarEmail.php?enviarEmail=true" method="POST">
        <div id="infosCreat">
            <input type="text" placeholder="Nome" name="nome" required>
            <input type="email" placeholder="email" name="email" required>
            <input type="password" placeholder="senha" name="senha" required>
            <input type="password" placeholder="confirme sua senha" required>
        </div>

        <input type="submit" value="Criar conta">

        <script>//to do:Sistema para conferirir se as duas senhas coincidem antes do envio do formulario</script>
    </form>
</body>
</html>