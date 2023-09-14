
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <title>Login</title>
</head>
<body>
    <div>
        <button onclick="caixaSenhaGestao()" id="btnAcessoGestao">Gestão</button>
    </div>

    <!--Formulario para acessar a conta de aluno de professor-->
    <form action="direcionamentoLogin.php" method="GET">
        <div id="infosAcess">
            <input type="email" placeholder="email" name="email" required>
            <input type="password" placeholder="senha" name="senha" required>
            <a href="recuperacaoSenha.php">Esqueci minha senha</a>
        </div>
        
        <input type="submit" value="Entrar">
    </form>
    
    <!--Formulario para criar a conta de aluno-->
    <form action="direcionamentoLogin.php" method="POST">
        <div id="infosCreate">
            <input type="text" placeholder="Nome" name="nome" required>
            <input type="email" placeholder="email" name="email" required>
            <input type="password" placeholder="senha" name="senha" required>
            <input type="password" placeholder="confirme sua senha" required>
        </div>

        <input type="submit" value="Criar conta">
    </form>
    

    <script>
        //to do:Sistema para conferirir se as duas senhas do formulario de criação de conta coincidem antes do envio do formulario
        //to do: sistema para deixar visivel apenas um formulario por vez / mudar title da pagina
    </script>

    <script src="js/script.js"></script>
    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
</body>
</html>

<?php
    if(filter_input(INPUT_GET, 'e')){
        $mensagem_erro = filter_input(INPUT_GET, 'e');
        echo '<script>erroLogin('.$mensagem_erro.')</script>';
    }
?>