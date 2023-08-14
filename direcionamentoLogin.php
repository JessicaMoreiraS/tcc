<?php
$conn = mysqli_connect("localhost", "root", "", "tcc"); 

//verifica acesso do gestor
if(filter_input(INPUT_GET, 'sg')){
    $sg = filter_input(INPUT_GET, 'sg');
    if($sg == 'senai'){
        $_SESSION['idAcesso'] = 'gestao';
        header('Location: homeGestao.php');
    }else{
        header('Location: login.php?e=1');
    }
}

//criar conta de aluno
if($_SERVER["REQUEST_METHOD"] == "POST"){ 
    $nome = filter_input(INPUT_POST, 'nome');
    $email = filter_input(INPUT_POST, 'email');
    $senha = filter_input(INPUT_POST, 'senha');

    if(!buscarEmail($conn, 'aluno', $email)){
        $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);
        $sqlCriaConta = "INSERT INTO aluno (nome, email, senha) VALUES ('$nome', '$email', '$senhaCriptografada')";     

        if(mysqli_query($conn, $sqlCriaConta)){
            if(buscarEmailSenha($conn, 'aluno', $email, $senha)){
                header('Location: homeAluno.php');
            }else{
                header('Location: login.php?e=2');  
            }
        }else{
            header('Location: login.php?e=3');
        }
    }else{
        header('Location: login.php?e=4');
    }
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


function buscarEmail($conn, $tabela, $email){
    $sqlBuscaConta = "SELECT * FROM $tabela WHERE email = '$email'";
    $resultado_busca = mysqli_query($conn, $sqlBuscaConta);
    if($busca = mysqli_fetch_assoc($resultado_busca)){
        return true;
    }else{
        return false;
    }
}

// $dup = var_dump($sou);

?>