<?php
//envio do email com phpMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
include("conexao.php");
$conn = mysqli_connect("localhost", "root", "", "iot"); 

//verifica acesso do gestor
if(filter_input(INPUT_GET, 'sg')){
    $sg = $_GET['sg'];
    if($sg == 'senai'){
        session_start();
        $_SESSION['idAcesso'] = 'gestaoDefault';
        $_SESSION['tipo'] = 'defalt';
        header('Location: homeGestorDefault.php');
        return;
    }else{
        header('Location: login.php?e=1');
    }
}

/*$sql_code= "UPDATE aluno SET senha = $nscriptografa WHERE email = email";
$sql_query = $mysqli->query($sql_code) or die($mysqli->error);*/


//reenvia email de confirmacao para criar conta
if(isset($_GET['reenviarCodigo']) && isset($_GET['email'])){
    $email = $_GET['email'];
    $sql="SELECT * FROM aluno WHERE email = '$email' AND conta_pendente = 1";
    $conteudoAlunoPendente = $mysqli->query($sql);
    $nome;
    $codigo;
    while($row = mysqli_fetch_assoc($conteudoAlunoPendente)){
        $nome = $row['nome'];
        $codigo = $row['cod_confimacao'];
    }
    $conteudo= 'Seu código de confirmação é: <b>'.$codigo.'</b>';

    if(isset($_GET['nome'])){
        if(enviaEmail($conteudo, $email, $nome)){
            header('Location: "confirmarEmail.php?emailconf='.$email); 
        }else{
            header('Location: login.php?e=3');
        }
    }else{
        header('Location: login.php?e=2');
    }
}



//criar conta de aluno como pendente
if(isset($_POST['criarConta'])){
    if(isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha'])&& isset($_POST['confimarSenha'])){
        $nome = filter_input(INPUT_POST, 'nome');
        $email = filter_input(INPUT_POST, 'email');
        $senha = filter_input(INPUT_POST, 'senha');
        $confimarSenha = filter_input(INPUT_POST, 'confimarSenha');

        
        if($senha != $confimarSenha){
            //senhas não coincidem
            header('Location: login.php?e=10');
        }

       
        if(buscarEmail('aluno', $email) || buscarEmail('professor', $email) || buscarEmail('gestor', $email)){
            header('Location: login.php?e=4');
            return;
        }

        $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);
        $codConfirmacao = substr(password_hash(time(), PASSWORD_DEFAULT), -7, -1);
        
        
        $sqlCriaContaPendente = "INSERT INTO aluno (`nome`, `email`, `senha`,`codigo_recuperacao`,`conta_pendente`, `cod_confimacao`) VALUES ('$nome', '$email', '$senhaCriptografada','','1', '$codConfirmacao')"; 
        
        $conteudo='Seu código de confirmação é: <b>'.$codConfirmacao.'</b>';

        if($mysqli->query($sqlCriaContaPendente) && enviaEmail($conteudo, $email, $nome)){
            header('Location: confirmarEmail.php?emailconf='.$email); 
        }else{
            header('Location: login.php?e=3');
        }
    }else{
        header('Location: login.php?e=3');
    }
}



//verificar acesso de aluno ou professo
if($_SERVER["REQUEST_METHOD"] == "GET" && !filter_input(INPUT_GET, 'sg') && isset($_GET['entrar'])){  
    $emailLogin = filter_input(INPUT_GET, 'email');
    $senhaLogin = filter_input(INPUT_GET, 'senha');

    if(buscarEmailSenha($conn, 'aluno', $emailLogin, $senhaLogin)){
        header('Location: homeAluno.php');
    }else{
        if(buscarEmailSenha($conn, 'professor', $emailLogin, $senhaLogin)){
            header('Location: homeProfessor.php');
        }else if(buscarEmailSenha($conn, 'gestor', $emailLogin, $senhaLogin)){
            header('Location: homeGestao.php');
        }else{
            header('Location: login.php?e=5');
        }
    }
} 

//redireciona aluno apos confirmacao de email;
if(isset($_GET['email']) && isset($_GET['senha'])){
    include('conexao.php');
    $emailAcesso = $_GET['email'];
    $senhaAcesso = $_GET['senha'];
    $sqlBuscaAcesso = "SELECT * FROM aluno WHERE email = '$emailAcesso'";
    $preparaBuscaAcesso = $mysqli->query($sqlBuscaAcesso);

    if ($buscaAcesso = mysqli_fetch_assoc($preparaBuscaAcesso)) {
        if($senhaAcesso == $buscaAcesso['senha']){
            session_start();
            $_SESSION['idAcesso'] = $buscaAcesso['id'];
            $_SESSION['tipo'] = 'aluno';
            header('Location: homeAluno.php');
        }else{
            //erro ao comparar senhas
        }

    }else{echo "nao";}
}


function buscarEmailSenha($conn, $tabela, $email, $senha){
    $sqlBuscaConta = "SELECT * FROM $tabela WHERE email = '$email'";
    $resultado_busca = mysqli_query($conn, $sqlBuscaConta);
    if($buscar = mysqli_fetch_assoc($resultado_busca)){
        if(password_verify($senha, $buscar['senha'])){
            session_start();
            $_SESSION['idAcesso'] = $buscar['id'];
            $_SESSION['tipo'] = $tabela;
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

//verifica exixtencia do email
function buscarEmail($tabela, $email){
    include("conexao.php");
    $sqlBuscaConta = "SELECT * FROM $tabela WHERE email = '$email'";
    $result = $mysqli->query($sqlBuscaConta);
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function enviaEmail($conteudo, $email, $nome){
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                    
    $mail->isSMTP();                                           
    $mail->Host       = 'smtp-mail.outlook.com';                    
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = 'EMAIL';               
    $mail->Password   = 'SENHA';                               
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
    $mail->Port       = 587;                                    

    $mail->SMTPSecure = 'tls'; 
    
    //Recipients
    $mail->setFrom('EMAIL', 'Monitoramento IOT');
    $mail->addAddress($email, $nome); 
    $mail->addReplyTo('EMAIL', 'Monitoramento IOT');

    //Content
    $mail->isHTML(true);                                  
    $mail->Subject = 'Confirmacao de Cadastro';
    $mail->Body    = ' <html>
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <title>Confirmação de email</title>
                        </head>
                        <body>
                            <h3>Confirmação de email</h3>
                            <p>'.$conteudo.'</p>
                            <a href="confirmarEmail.php?emailconf='.$email.'">Acesse o site</a>
                        </body>
                    </html>';
    $mail->AltBody = 'Confirme seu email';

    $mail->send();
        return true;
    } catch (Exception $e) {
       return false;
    }
}
?>
