<?php
//conexao com o DB
include('conexao.php');

// Ao cadastrar uma nova conta de aluno
$codigoConf = mt_rand(100000, 999999); // Gera um código de confirmação de seis dígitos

// Insere a nova conta na tabela de alunos
// $sqlCriaContaAluno = "INSERT INTO aluno (nome, email, senha, codigo_recuperacao) VALUES ('$nome', '$email', '$senha', '$codigoConf')";

// if ($mysqli->query($sqlCriaContaAluno)) {
//     // Envie o email com o código de confirmação para o usuário
//     // enviarCodigoConfirmacao($email, $codigoConf);

//     // Redireciona para a página de confirmação de email
//     header('Location: confirmar_email.php?emailconf=' . $email);
// } else {
//     // Erro na comunicação com o banco
//     header('Location: cadastro.php?e=7');
// }
session_start();
// Cadastrar SALA (recenbo o formulario)
if (filter_input(INPUT_POST, 'cadastrarSala')) {
    // Valores dos inputs aplicados em variáveis
    $nomeSala = filter_input(INPUT_POST, 'nomeSala');
    // $codigoSala = filter_input(INPUT_POST, 'codigoSala');
    $idProfessor = $_SESSION['idAcesso'];


    //funcao que gera um novo codigo de acesso
    function gerarCodigo()
    {
        $codigo = '';
        //caracteres possiveis no codigo
        $caracteres = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'w', 'z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];
        //for que roda 15 vezes (numero de carateres do codigo)
        for ($i = 0; $i < 15; $i++) {
            //gera um indice aleatorio
            $index_aleatorio = rand(0, count($caracteres) - 1);
            //aplica este indice em uma nova variavel e recupera uma caractere deste indice
            $caracter_aleatorio = $caracteres[$index_aleatorio];
            //adiciona este caractere ao codigo
            $codigo .= $caracter_aleatorio;
        }
        return $codigo;
    }

    // SQL para inserir dados na tabela sala, com três placeholders(atributos da tabela), que serão inseridos
    $sqlCriarSala = "INSERT INTO sala (turma, id_professor, codigo_acesso) VALUES (?, ?, ?)";

    //aplicando o sql em um prepare (preparando o sql)
    $preparacaoSeguraSQL = $mysqli->prepare($sqlCriarSala);

    // Verificando se o SQL foi bem-sucedido
    if ($preparacaoSeguraSQL) {
        $codigoAcesso = gerarCodigo();
        // Vinculando os valores aos placeholders(?,?,?)
        // 's' = string, 'i' = int , 's' = string
        $preparacaoSeguraSQL->bind_param("sis", $nomeSala, $idProfessor, $codigoAcesso);

        // Execução do POST (sql que ja passou pelo prepare)
        if ($preparacaoSeguraSQL->execute()) {
            // Captura o ID da sal
            $idSala = $mysqli->insert_id;
            echo 'Sala criada/ ID da sala: ' . $idSala;

            //fechando o sql
            $preparacaoSeguraSQL->close();

            // Verificando se o array das maquinas esta no post
            if (isset($_POST['maquinas']) && is_array($_POST['maquinas'])) {
                // Loop atraves das maquinas
                foreach ($_POST['maquinas'] as $maquina) {
                    // SQL que se baseia no nome da maquina, para encontrar o id
                    $sql = "SELECT id FROM tipo_maquina WHERE tipo = ?";

                    //utilizando nvment o prepare, agora na consulta anterior
                    $stmt = $mysqli->prepare($sql);

                    if ($stmt) { // verificando se a cnslt deu certo

                        //parametro que aplica valores ao parametro, do sql
                        $stmt->bind_param("s", $maquina);

                        $stmt->execute(); //executa a consulta
                        $stmt->bind_result($idTipoMaquina); //recuperando o id damaquina

                        //verificndo se ha mais resultados na consulta
                        if ($stmt->fetch()) {

                            //fechando a consulta
                            $stmt->close();

                            // sql para inserir registro na tabela de lista_sala_tipo_maquina 
                            $sqlInserirMaquinaSala = "INSERT INTO lista_sala_tipo_maquina (id_sala, id_tipo_maquina) VALUES (?, ?)";

                            //preparacao novmente da variavel
                            $preparacaoSeguraSQL2 = $mysqli->prepare($sqlInserirMaquinaSala);

                            //verificando se a preparcao funcionou
                            if ($preparacaoSeguraSQL2) {

                                //aplicando a parametro da insercao(sql)
                                $preparacaoSeguraSQL2->bind_param("ii", $idSala, $idTipoMaquina);

                                //verificando a execucao
                                if ($preparacaoSeguraSQL2->execute()) {
                                    // resultado de sucesso
                                    header('Location: homeProfessor.php');
                                    exit;
                                } else {
                                    // erroo
                                    echo "Erro ao aplicar a máquina: " . mysqli_error($mysqli);

                                }
                                //fechando a consulta
                                $preparacaoSeguraSQL2->close();
                            }
                        } else {
                            //caso nao encontre resultados, a maquina nao existe no banco
                            echo $maquina . " não encontrado<br>";
                        }
                    } else {
                        // caso a conulta n de certo(erro)
                        echo "erro em preparar a consulta ";
                    }
                }
            }
        } else {
            // caso o sql da criar a sala nao funfe
            echo 'erro ao criar sala';
        }
    }
}
///////////////////////////////////////////////////




//Criar conta de Professor
if (filter_input(INPUT_POST, 'cadastrarProfessor')) {
    $idGestor = 0;
    if(isset($_SESSION['idAcesso'])){
        $idGestor = $_SESSION['idAcesso'];
    }

    $nome = filter_input(INPUT_POST, 'nome');
    $email = filter_input(INPUT_POST, 'email');
    $cpf = filter_input(INPUT_POST, 'cpf');
    $primeiraSenhaCriptografada = password_hash($cpf, PASSWORD_DEFAULT);

    $sqlCriarContaProfessor = "INSERT INTO professor (nome, cpf, email, senha, id_gestor) VALUES ('$nome', '$cpf', '$email', '$primeiraSenhaCriptografada','$idGestor')";

    if ($mysqli->query($sqlCriarContaProfessor)) {
        header("location: visualizar.php?view=professor&e=12");
        //echo 'professor cadastrado';
    } else {
        header("location: visualizar.php?view=professor&e=3");
        echo 'erro em cadastrar professor'->mysql_error;
    }
}
?>