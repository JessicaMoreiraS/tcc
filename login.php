
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <!-- sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <title>Login</title>
</head>
<body>
    <div>
        <button onclick="caixaSenhaGestao()">Gestão</button>
    </div>

    <form action="direcionamentoLogin.php" method="GET">
        
        <div id="infosAcess">
            <input type="email" placeholder="email" name="email" required>
            <input type="password" placeholder="senha" name="senha" required>
            <a href="recuperacaoSenha.php">Esqueci minha senha</a>
        </div>
        
        <input type="submit" value="Entrar">
    </form>
    
    <a href="criarConta.html">Criar conta</a>

    <script src="js/script.js"></script>
</body>
</html>

<?php
    if(filter_input(INPUT_GET, 'e')){
        $e = filter_input(INPUT_GET, 'e');
        echo $e;
        echo '<script>erroLogin('.$e.')</script>';
    }
?>