<?php
    include('conexao.php');
    //criar sala
    

    //Criar conta de Professor
    if(filter_input(INPUT_POST, 'cadastrarProfessor')){
        $nome = filter_input(INPUT_POST, 'nome');
        $email = filter_input(INPUT_POST, 'email');
        $cpf = filter_input(INPUT_POST, 'cpf');
        $primeiraSenhaCriptografada = password_hash($cpf, PASSWORD_DEFAULT);

        $sqlCriarContaProfessor = "INSERT INTO professor (nome, cpf, email, senha, redefinir_senha) VALUES ('$nome', '$cpf', '$email', '$primeiraSenhaCriptografada', 1)";

        if($mysqli->query($sqlCriarContaProfessor)){
            header('Location: criarConta.php?ProfessorSucess=true');
        }else{
            header('Location: criarConta.php?ProfessorSucess=false');
        }
    }

    //Cadastrar novo tipo de Maquina
    if(filter_input(INPUT_POST, 'cadastrarTipoMaquina')){
        $tipo = filter_input(INPUT_POST, 'categoria');

        $sqlCriarTipo = "INSERT INTO tipo_maquina (tipo) VALUES ('$tipo')";

        if($mysqli->query($sqlCriarTipo)){
            header('Location: criarConta.php?TipoSucess=true');
        }else{
            header('Location: criarConta.php?TipoSucess=false');
        }
    }

    //Criar nova Maquina
    if(filter_input(INPUT_POST, 'cadastrarMaquina')){
        $id = filter_input(INPUT_POST, 'codigo');
        $idTipo = filter_input(INPUT_POST, 'tipo');
        $modelo = filter_input(INPUT_POST, 'modelo');
        $fabricante = filter_input(INPUT_POST, 'fabricante');

        $sqlCriarMaquina = "INSERT INTO maquina (id, id_tipo_maquina, modelo, fabricante) VALUES ('$id', '$idTipo', '$modelo', '$fabricante')";

        if($mysqli->query($sqlCriarMaquina)){
            header('Location: criarConta.php?ProfessorSucess=true');
        }else{
            header('Location: criarConta.php?ProfessorSucess=false');
        }
    }
?>