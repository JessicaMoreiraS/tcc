<?php
$conn = mysqli_connect("localhost", "root", "", "tcc"); 

if($_SERVER["REQUEST_METHOD"] == "POST"){ 
    $nome = filter_input(INPUT_POST, 'nome');
    $email = filter_input(INPUT_POST, 'email');
    $cpf = filter_input(INPUT_POST, 'cpf');
    $senha = filter_input(INPUT_POST, 'senha');
    $codSala = filter_input(INPUT_POST, 'codSala');

    if(!buscarEmail($conn, 'aluno', $email)){
        $sqlCriaConta = "INSERT INTO aluno (nome, cpf, email, senha) VALUES ('$nome', '$cpf', '$email', '$senha')";
        

        if(mysqli_query($conn, $sqlCriaConta)){
            if(buscarEmailSenha($conn, 'aluno', $email, $senha)){
                header('Location: homeAluno.php');
            }else{
            echo ('<script>alert("Erro ao reincontrar conta")</script>');
            }
        }else{
            echo ('<script>alert("Erro ao criar conta")</script>');
        }
    }else{
        echo ('<script>alert("Esse email já está cadastrado")</script>');
    }
}

if($_SERVER["REQUEST_METHOD"] == "GET"){  
    $emailLogin = filter_input(INPUT_GET, 'email');
    $senhaLogin = filter_input(INPUT_GET, 'senha');

    if(buscarEmailSenha($conn, 'aluno', $emailLogin, $senhaLogin)){
        header('Location: homeAluno.php');
    }else{
        if(buscarEmailSenha($conn, 'professor', $emailLogin, $senhaLogin)){
            header('Location: homeProfessor.php');
        }else{
            echo ('<script>alert("usuario ou senha invalidos")</script>');
        }
    }
} 

function buscarEmailSenha($conn, $tabela, $email, $senha){
    $sqlBuscaConta = "SELECT * FROM $tabela WHERE email = '$email' AND senha = '$senha'";
    $resultado_busca = mysqli_query($conn, $sqlBuscaConta);
    if($busca = mysqli_fetch_assoc($resultado_busca)){
        session_start();
        $_SESSION['idAcesso'] = $busca['id'];
        return true;
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
// echo $dup;
/*
if($sou == "professor"){
//buscar no db professor
}else{
    if($sou == "aluno"){
    //buscar no db aluno
    }else{
        //error
    }
}*/
?>