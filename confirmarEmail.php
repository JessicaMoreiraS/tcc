<?php
include("conexao.php");
$pSenhaIncorreta = false;

$codigoConf;
//criar conta de aluno como pendente
if(isset($_POST["emailconf"]) && filter_input(INPUT_POST, 'emailconf')){
    $email = $_POST["emailconf"];
    $sqlBuscaConta = "SELECT * FROM conta_pendente_aluno WHERE email = '$email'";

    if($busca = $mysqli->query($sqlBuscaConta)){
        $codigoConf = $busca['cod_confirmacao'];

        //deleta do pendente e adiciona em aluno se codigo de confirmação estiver correta
        if(isset($_GET["codigo"])){
            if($_GET["codigo"] == $codigoConf){
                $id = $busca['id'];
                $nome = $busca['nome'];
                $senha = $busca['senha'];
                
                $sqlCriaContaAluno = "INSERT INTO aluno (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
                $sqlDeletaContaPendente = "DELETE FROM conta_pendente_aluno WHERE id = '$id'";
                if($mysqli->query($sqlCriaContaAluno) && $mysqli->query($sqlDeletaContaPendente)){
                    //sucesso - mudar para homeAluno
                    header('Location: login.html');
                }else{
                    //erro na comunicacao com o banco
                    header('Location: login.php?e=7');
                }
            }else{
                //senha incorreta
                $pSenhaIncorreta = true;
            }
        }else{
            //ainda não tentou por a senha
        }
    }else{
        header('Location: login.php?e=2');
    }  
}
    //$email = $mysqli->escape_string($_POST['email']); //scape_string evita ataques
    //$sql_query = $mysqli->query($sql_code) or die($mysqli->error);
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
        
        <form action="" method="GET">
            <input type="text" length="6" placeholder="Código de confirmação" name="codigo">
            <input type="submit" value="Confimar" name="confimar">
        </form>
    </div>

    <?php
        if(isset($nome) && isset($email) && isset($senha)){?>
            <form action="direcionamentoLogin.php?nome=<?php $nome?>/senha=<?php $senha?>/email=<?php $email?>" method='POST'>
                <input type="submit" value="Reenviar Email" name='enviar'>
            </form>
    <?php
        }
    ?>

    <script src="js/script.js"></script>
    <?php
        if($pSenhaIncorreta = true){
            echo '<script>erroLogin(8)</script>';
        }
    ?>
</body>
</html>