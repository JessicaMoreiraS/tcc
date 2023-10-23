<?php
include("conexao.php");
$pSenhaIncorreta = false;
$email;
if(isset($_GET["emailconf"])){
    $email=$_GET["emailconf"];
}

if (isset($_GET["codigo"]) && isset($_GET["emailconf"])) {
    $codigoInserido = $_GET["codigo"];

    $sqlBuscaConta = "SELECT * FROM conta_pendente_aluno WHERE email = '$email'";

    if ($busca = $mysqli->query($sqlBuscaConta)) {
        $codigoConf = $busca['cod_confirmacao'];

        if ($codigoInserido == $codigoConf) {
            // Código correto, prosseguir com o cadastro na tabela de alunos
            $id = $busca['id'];
            $nome = $busca['nome'];
            $senha = $busca['senha'];

            $sqlCriaContaAluno = "INSERT INTO aluno (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
            $sqlDeletaContaPendente = "DELETE FROM conta_pendente_aluno WHERE id = '$id'";

            if ($mysqli->query($sqlCriaContaAluno) && $mysqli->query($sqlDeletaContaPendente)) {
                // Sucesso - redirecionar 
                header('Location: direcionamentoLogin.php?email='.$email.'&senha='.$senha);
            } else {
                // Erro na comunicação com o banco
                header('Location: login.php?e=7');
            }
        } else {
            // Código incorreto
            $pSenhaIncorreta = true;
        }
    } else {
        header('Location: login.php?e=2');
    }
};
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de email</title>
</head>
<body>
    <div>
        <form action="confirmarEmail.php?emailconf=<?php echo $email?>" method="GET">
            <input type="text" length="6" placeholder="Código de confirmação" name="codigo" required>
            <input type="submit" value="Confirmar" name="confirmar">
        </form>
    </div>

    <?php
    if (isset($email)) { ?>
        <form action="confirmarEmail.php?email=<?php echo $email; ?>" method='POST'>
            <input type="submit" value="Reenviar Email" name='enviar'>
        </form>
    <?php } ?>
    
    <a href="direcionamentoLogin.php?email=<?php echo $email ?>&reenviarCodigo=true">Reenviar Código</a>



    <script src="js/script.js"></script>
    <?php
    if ($pSenhaIncorreta) {
        echo '<script>erroLogin(8)</script>';
    }
    ?>
</body>
</html>
