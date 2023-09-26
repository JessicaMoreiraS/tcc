<?php
include('conexao.php');

// Criar ou cadastrar SALA
if (filter_input(INPUT_POST, 'cadastrarSala')) {
    // Valores dos inputs aplicados em variáveis
    $nomeSala = filter_input(INPUT_POST, 'nomeSala');
    $codigoSala = filter_input(INPUT_POST, 'codigoSala');
    $idProfessor = filter_input(INPUT_POST, 'idProfessor');

    // SQL para inserir dados na tabela sala, com três placeholders(atributos da tabela), que serão inseridos
    $sqlCriarSala = "INSERT INTO sala (turma, id_professor, codigo_acesso) VALUES (?, ?, ?)";

    $preparacaoSeguraSQL = $mysqli->prepare($sqlCriarSala);

    // Verificando se o SQL foi bem-sucedido
    if ($preparacaoSeguraSQL) {
        // Vinculando os valores aos placeholders(?,?,?)
        // 's' = string, 'i' = int , 's' = string
        $preparacaoSeguraSQL->bind_param("sis", $nomeSala, $idProfessor, $codigoSala);

        // Execução do POST
        if ($preparacaoSeguraSQL->execute()) {
            $idSala = $mysqli->insert_id; // Captura o ID da sala recém-inserida
            echo 'Sala criada com sucesso. ID da sala: ' . $idSala;

            $preparacaoSeguraSQL->close();

            // Agora, verifique se o array maquinas está definido no POST e se é um array
            if (isset($_POST['maquinas']) && is_array($_POST['maquinas'])) {
                // Loop através das máquinas
                foreach ($_POST['maquinas'] as $maquina) {
                    // Consulta SQL para obter o ID do tipo de máquina com base no nome
                    $sql = "SELECT id FROM tipo_maquina WHERE tipo = ?";
                    $stmt = $mysqli->prepare($sql);

                    if ($stmt) {
                        $stmt->bind_param("s", $maquina);
                        $stmt->execute();
                        $stmt->bind_result($idTipoMaquina);

                        if ($stmt->fetch()) {
                            // O ID do tipo de máquina foi encontrado
                            $stmt->close();

                            // Insira o ID da sala e o ID do tipo de máquina na tabela lista_sala_tipo_maquina
                            $sqlInserirMaquinaSala = "INSERT INTO lista_sala_tipo_maquina (id_sala, id_tipo_maquina) VALUES (?, ?)";
                            $preparacaoSeguraSQL2 = $mysqli->prepare($sqlInserirMaquinaSala);

                            if ($preparacaoSeguraSQL2) {
                                $preparacaoSeguraSQL2->bind_param("ii", $idSala, $idTipoMaquina);
                                if ($preparacaoSeguraSQL2->execute()) {
                                    // Inserção bem-sucedida
                                    echo "Máquina '$maquina' associada à sala com sucesso.";
                                } else {
                                    // Erro ao inserir a máquina na sala
                                    echo "Erro ao associar a máquina '$maquina' à sala: " . $preparacaoSeguraSQL2->error;
                                }
                                $preparacaoSeguraSQL2->close();
                            }
                        } else {
                            // O nome da máquina não corresponde a um tipo de máquina no banco de dados
                            echo "Máquina selecionada: $maquina - Tipo de Máquina não encontrado<br>";
                        }
                    } else {
                        // Erro na preparação da consulta
                        echo "Erro na preparação da consulta: " . $mysqli->error;
                    }
                }
            } else {
                // Nenhuma máquina selecionada
                echo "Nenhuma máquina foi selecionada.";
            }
        } else {
            // Exibição de erro se o POST não funcionar
            echo 'Erro ao criar a sala: ' . $preparacaoSeguraSQL->error;
        }
    } else {
        // Erro na preparação do SQL
        echo 'Erro na preparação do SQL: ' . $mysqli->error;
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