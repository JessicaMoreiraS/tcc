<?php
include('conexao.php');
//criar SALA

//filter_input que identifica o nome do form ('cadastrarSala)
if (filter_input(INPUT_POST, 'cadastrarSala')) {
    //values dos inputs aplicados em variaveis
    $nomeSala = filter_input(INPUT_POST, 'nomeSala');
    $codigoSala = filter_input(INPUT_POST, 'codigoSala');
    $idProfessor = filter_input(INPUT_POST, 'idProfessor');

    //sql para inserir dados na tabela sala, com tres placholder(atributos da tabela), que serão inseridos
    $sqlCriarSala = "INSERT INTO sala (turma, id_professor, codigo_acesso) VALUES (?, ?, ?)";
    if (isset($_POST['cadastrarSala'])) {
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

     // Verifica se o array maquinas esta definido no POST e se é um array
        if (isset($_POST['maquinas']) && is_array($_POST['maquinas'])) {

            // Consulta SQL para obter o ID do tipo de máquina com base no nome
            $sql = "SELECT id FROM tipo_maquina WHERE tipo = ?";
            $stmt = $mysqli->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("s", $maquina);
                $stmt->execute();
                $stmt->bind_result($idTipoMaquina);

                if ($stmt->fetch()) {
                    // O ID do tipo de máquina foi encontrado
                    echo 'oxe';
                } else {
                    // O nome da máquina não corresponde a um tipo de máquina no banco de dados
                    echo "Máquina selecionada: $maquina - Tipo de Máquina não encontrado<br>";
                }

                $stmt->close();
            } else {
                // Erro na preparação da consulta
                echo "Erro na preparação da consulta: " . $mysqli->error;
            }

            // Loop através das  máquinas
            foreach ($_POST['maquinas'] as $maquina) {
                // Faça o que for necessário com os valores das máquinas selecionadas
                echo "Máquina selecionada: $maquina, ID do Tipo de Máquina: $idTipoMaquina<br>";
            }
        } else {
            // Nenhuma máquina selecionada
            echo "Nenhuma máquina foi selecionada.";
        }
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