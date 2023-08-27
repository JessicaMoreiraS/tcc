<?php
include("conexao.php");
$conn = mysqli_connect("localhost", "root", "", "tcc"); 

//verifica acesso do gestor
if(filter_input(INPUT_GET, 'sg')){
    $sg = filter_input(INPUT_GET, 'sg');
    if($sg == 'senai'){
        $_SESSION['idAcesso'] = 'gestaoSenai';
        header('Location: homeGestao.php');
    }else{
        header('Location: login.php?e=1');
    }
}

/*$sql_code= "UPDATE aluno SET senha = $nscriptografa WHERE email = email";
$sql_query = $mysqli->query($sql_code) or die($mysqli->error);*/

//criar conta de aluno como pendente
if($_SERVER["REQUEST_METHOD"] == "POST"){ 
    if(isset($_GET['nome'])){
        $nome = filter_input(INPUT_POST, 'nome');
        $email = filter_input(INPUT_POST, 'email');
        $senha = filter_input(INPUT_POST, 'senha');
        $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);
        $codConfirmacao = substr(password_hash(time(), PASSWORD_DEFAULT),6);
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            header('Location: login.php?e=6'); 
        }
        if(buscarEmail('aluno', $email)){
            header('Location: login.php?e=4');
        }
        
        $sqlCriaContaPendente = "INSERT INTO conta_pendente_aluno (nome, email, senha, cod_confimacao) VALUES ('$nome', '$email', '$senhaCriptografada', '$codConfirmacao')";     

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
                    <p>Seu código de confirmação é: <b>'.$codConfirmacao.'</b></p>
                    <a href="confirmarEmail.php?emailconf='.$email.'">Acesse o site</a>
                </body>
            </html>';

        if($mysqli->query($sqlCriaConta) && mail($email, $subject, $message, "Content-type: text/htmll")){
            header('Location: "confirmarEmail.php?emailconf='.$email); 
        }else{
            header('Location: login.php?e=3');
        }
    }else{
        header('Location: login.php?e=3');
    }
}else{
    header('Location: login.php?e=3');
}

//verificar acesso de aluno ou professo
if($_SERVER["REQUEST_METHOD"] == "GET" && !filter_input(INPUT_GET, 'sg')){  
    $emailLogin = filter_input(INPUT_GET, 'email');
    $senhaLogin = filter_input(INPUT_GET, 'senha');

    if(buscarEmailSenha($conn, 'aluno', $emailLogin, $senhaLogin)){
        header('Location: homeAluno.php');
    }else{
        if(buscarEmailSenha($conn, 'professor', $emailLogin, $senhaLogin)){
            header('Location: homeProfessor.php');
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
    if($mysqli->query($sqlBuscaConta)){
        return true;
    }else{
        return false;
    }
}
?>