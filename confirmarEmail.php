<?php
include("conexao.php");

//receber dados
if($_SERVER["REQUEST_METHOD"] == "POST"){ 
    if(isset($_POST["code"])){
        $senha = escape_string(filter_input(INPUT_POST, 'senhaCrip'));
    }else{
        $senha = escape_string(filter_input(INPUT_POST, 'senha'));
    }
    $nome = escape_string(filter_input(INPUT_POST, 'nome'));
    $email = escape_string(filter_input(INPUT_POST, 'email'));
    $enviarEmail = escape_string(filter_input(INPUT_POST, 'enviarEmail'));
    $senhaCrip = password_hash($senhas, PASSWORD_DEFAULT);
}


if(isset($_POST["enviar"])){
    $time = time();
    $codConfirmacao = substr(password_hash($time, PASSWORD_DEFAULT),6);
    $erro = "";

    if($enviarEmail){
        $subject = 'Confirme sua conta';
        $message =' 
        <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Confirmação de email</title>
            </head>
            <body>
                <h3>Confirmação de email</h3>
                <a href="confirmarEmail.php?enviarEmail=false;nome='.$nome.';email='.$email.';senhaCrip='.$senhaCrip.';code='.$codConfirmacao.';">Acesse o site</a>
            </body>
        </html>';
    }

    
    //$email = $mysqli->escape_string($_POST['email']); //scape_string evita ataques
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $erro .= "Email invalido ";
    }else{
        $dado = $sql_query->fetch_assoc();
        $total = $sql_query->num_rows;
        if($total == 0){
            $erro .= "O email não existe no banco de dados ";
        }else{
            if($total > 0){
                if(mail($email, $subject, $message, "Content-type: text/htmll")){
                    $sql_code= "UPDATE aluno SET senha = $nscriptografa WHERE email = email";
                    $sql_query = $mysqli->query($sql_code) or die($mysqli->error);
 
                    if($sql_query){
                        $erro .= "Senha alterada com sucesso ";
                    }else{
                        $erro .= "erro ao enviar email ";
                    }
                }
            }
        }
    }
}

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
        <p>Email confirmado com sucesso</p>
        <a href="login.php">Acessar sua conta</a>
        <input type="text" length="6">
    </div>
</body>
</html>