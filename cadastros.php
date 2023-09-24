<?php
include('conexao.php');
//criar SALA

//filter_input que identifica o nome do form ('cadastrarSala)
if (filter_input(INPUT_POST, 'cadastrarSala')) {

    //values dos inputs aplicados em vaiaveis
    $nomeSala = filter_input(INPUT_POST, 'nomeSala');
    $codigoSala = filter_input(INPUT_POST, 'codigoSala');
    $idProfessor = filter_input(INPUT_POST, 'idProfessor');

    //sql para inserir dados na tabela sala, com tres placholder(atributos da tabela), que serão inseridos
    $sqlCriarSala = "INSERT INTO sala (turma, id_professor, codigo_acesso) VALUES (?, ?, ?)";


    $preparacaoSeguraSQL = $mysqli->prepare($sqlCriarSala);

    //verificando se o sql foi bem-sucedido
    if ($preparacaoSeguraSQL ) {

        //vinculando os valores aos placeholders(?,?,?)
        //s = string, i = int , s= string
        $preparacaoSeguraSQL ->bind_param("sis", $nomeSala, $idProfessor, $codigoSala);


        //execucao do POST
        if ($preparacaoSeguraSQL ->execute()) {
            echo 'Sala criada com sucesso.';
        } else {
            //exibição de erro se o POST nao funcionar
            echo 'Erro ao criar a sala: ' . $preparacaoSeguraSQL ->error;
        }

        $preparacaoSeguraSQL ->close();
    }
}



//Criar conta de Professor
if (filter_input(INPUT_POST, 'cadastrarProfessor')) {
    $nome = filter_input(INPUT_POST, 'nome');
    $email = filter_input(INPUT_POST, 'email');
    $cpf = filter_input(INPUT_POST, 'cpf');
    $primeiraSenhaCriptografada = password_hash($cpf, PASSWORD_DEFAULT);

    $sqlCriarContaProfessor = "INSERT INTO professor (nome, cpf, email, senha, redefinir_senha) VALUES ('$nome', '$cpf', '$email', '$primeiraSenhaCriptografada', 1)";

    if ($mysqli->query($sqlCriarContaProfessor)) {
        header('Location: criarConta.php?ProfessorSucess=true');
    } else {
        header('Location: criarConta.php?ProfessorSucess=false');
    }
}

//Cadastrar novo tipo de Maquina
if (filter_input(INPUT_POST, 'cadastrarTipoMaquina')) {
    $tipo = filter_input(INPUT_POST, 'categoria');

    $sqlCriarTipo = "INSERT INTO tipo_maquina (tipo) VALUES ('$tipo')";

    if ($mysqli->query($sqlCriarTipo)) {
        header('Location: criarConta.php?TipoSucess=true');
    } else {
        header('Location: criarConta.php?TipoSucess=false');
    }
}

//Criar nova Maquina
if (filter_input(INPUT_POST, 'cadastrarMaquina')) {
    $id = filter_input(INPUT_POST, 'codigo');
    $idTipo = filter_input(INPUT_POST, 'tipo');
    $modelo = filter_input(INPUT_POST, 'modelo');
    $fabricante = filter_input(INPUT_POST, 'fabricante');

    $sqlCriarMaquina = "INSERT INTO maquina (id, id_tipo_maquina, modelo, fabricante) VALUES ('$id', '$idTipo', '$modelo', '$fabricante')";

    if ($mysqli->query($sqlCriarMaquina)) {
        header('Location: criarConta.php?ProfessorSucess=true');
    } else {
        header('Location: criarConta.php?ProfessorSucess=false');
    }
}
?>