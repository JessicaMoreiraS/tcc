<?php
//envio do email com phpMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
include("conexao.php");
$conn = mysqli_connect("localhost", "root", "", "tcc"); 

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
    $sql="SELECT * FROM conta_pendente_aluno WHERE email = $email";
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
       
        if(buscarEmail('aluno', $email)){
            header('Location: login.php?e=4');
        }

        $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);
        $codConfirmacao = substr(password_hash(time(), PASSWORD_DEFAULT), -7, -1);
        
        
        $sqlCriaContaPendente = "INSERT INTO conta_pendente_aluno (`nome`, `email`, `senha`, `cod_confimacao`) VALUES ('$nome', '$email', '$senhaCriptografada', '$codConfirmacao')"; 
        
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
    $mail->Username   = 'profissionalmensagem@outlook.com';              
    $mail->Password   = 'CONTA#acesso963';                               
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
    $mail->Port       = 587;                                    

    $mail->SMTPSecure = 'tls'; 
    
    //Recipients
    $mail->setFrom('profissionalmensagem@outlook.com', 'Jessica M');
    $mail->addAddress($email, $nome); 
    $mail->addReplyTo('profissionalmensagem@outlook.com', 'Jessica M');

    //Content
    $mail->isHTML(true);                                  
    $mail->Subject = 'Email via Portifolio';
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