<?php

include('conexao');
$fase = 1;
if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['email'])){

    $email = $_GET['email'];
    $codigoRec = substr(password_hash(time(), PASSWORD_DEFAULT), -7, -1);

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                    
    $mail->isSMTP();                                           
    $mail->Host       = 'smtp-mail.outlook.com';                    
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = 'profissionalmensagem@outlook.com';  //'profissionalmensagem@outlook.com';              
    $mail->Password   = 'CONTA#acesso963';  //'CONTA#acesso963';                               
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
    $mail->Port       = 587;                                    

    $mail->SMTPSecure = 'tls'; 
    
    //Recipients
    $mail->setFrom('profissionalmensagem@outlook.com', 'Jessica M');
    $mail->addAddress($email, $nome); 
    $mail->addReplyTo('profissionalmensagem@outlook.com', 'Jessica M');

    //Content
    $mail->isHTML(true);                                  
    $mail->Subject = 'Recuperacao de Senha';
    $mail->Body    = ' <html>
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <title>Confirmação de email</title>
                        </head>
                        <body>
                            <h3>Recuperação de senha</h3>
                            <p>Seu código para recuperação de senha é: '.$codigoRec.'</p>
                            <p>Atenção: não compartilhe o código acima com ninguém</p>
                            <a href="recuperacaoSenha.php?emailRec='.$email.'">Acesse o site</a>
                        </body>
                    </html>';
    $mail->AltBody = 'Confirme seu email';

    $mail->send();
        $fase = 2;
    } catch (Exception $e) {
        $fase = 1;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Conta</title>
</head>
<body>
    <?php
        if($fase = 1){
    ?>
        <form method="GET">
            <input type="mail" name="email">
            <input type="submit" value="Enviar Código">
        </form>
    <?php }else{ ?>
        <form method="GET">
            <input type="text" name="codigo">
            <input type="text" name="senha">
            <input type="text" name="verificaSenha">
            <input type="submit" value="Cadastrar Senha">
        </form>
    <?php } ?>
</body>
</html>