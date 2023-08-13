<?php
include("conexao.php");
if(isset($_POST["enviar"])){
    $novaSenha = substr(password_hash(time(), PASSWORD_DEFAULT), 0, 6);
    $nscriptografa = password_hash($novaSenha, PASSWORD_DEFAULT);
    $erro = "";
    $subject = 'Recuperação de senha';
    $message =' 
    <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Redefinição de senha</title>
        </head>
        <body>
            <h3>Solicitação para troca de senha</h3>
            <p>Sua nova senha é: '.$novaSenha.'</p>
        </body>
    </html>
    ';
    
    $email = $mysqli->escape_string($_POST['email']); //scape_string evita ataques
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envio de emailcom PHP</title>
</head>
<body>
    <?php
       echo"<p>".$erro."</p>";
    ?>

    <form action="" method="POST">
        <input type="email" name="email" require>
        <input type="submit" value="enviar">
    </form>
</body>
</html>