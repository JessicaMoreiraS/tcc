<?php
include("conexao.php");
$pSenhaIncorreta = false;

if (isset($_GET["codigo"]) && isset($_GET["emailconf"])) {
    $codigoInserido = $_GET["codigo"];
    $email = $_GET["emailconf"];

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
                // Sucesso - redirecionar para a página de login
                header('Location: login.php');
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
        <form action="confirmar_email.php" method="GET">
            <input type="text" length="6" placeholder="Código de confirmação" name="codigo" required>
            <input type="hidden" name="emailconf" value="<?php echo $email; ?>">
            <input type="submit" value="Confirmar" name="confirmar">
        </form>
    </div>

    <?php
    if (isset($nome) && isset($email) && isset($senha)) { ?>
        <form action="confirmar_email.php?nome=<?php echo $nome; ?>&senha=<?php echo $senha; ?>&email=<?php echo $email; ?>" method='POST'>
            <input type="submit" value="Reenviar Email" name='enviar'>
        </form>
    <?php } else { ?>
        <a href="cadastro.php">Reenviar Código ou Corrigir Email</a>
    <?php }
    ?>

    <script src="js/script.js"></script>
    <?php
    if ($pSenhaIncorreta) {
        echo '<script>erroLogin(8)</script>';
    }
    ?>
</body>
</html>
