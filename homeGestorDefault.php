<?php
session_start();
include 'conexao.php';

// Verificando a permissão do usuário
if ($_SESSION['idAcesso'] != 'gestaoDefault' || $_SESSION['tipo'] != 'defalt') {
    header('Location: index.html');
    exit();
}

//Função pra excluir o Gestor
function excluirGestor($idGestor)
{
    global $mysqli;
    $stmt = $mysqli->prepare("DELETE FROM gestor WHERE id = ?");
    $stmt->bind_param("i", $idGestor);

    // Executar a query
    if ($stmt->execute()) {
        echo "Gestor excluído com sucesso!";
    } else {
        echo "Erro ao excluir gestor: " . $stmt->error;
    }

    $stmt->close();
}

// Consultar gestores no banco de dados
$gestores = [];
$result = $mysqli->query("SELECT * FROM gestor");

if ($result) {
    // Transformar o resultado em um array associativo
    while ($row = $result->fetch_assoc()) {
        $gestores[] = $row;
    }

    // Liberar o resultado da consulta
    $result->free();
} else {
    echo "Erro ao consultar gestores: " . $mysqli->error;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cadastrarGestor'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];

    // Validar e inserir gestor no banco de dados
    $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

    $stmt = $mysqli->prepare("INSERT INTO gestor (nome, cpf, email, senha) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $nome, $cpf, $email, $senhaHash);

    if ($stmt->execute()) {
        echo "Gestor cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar gestor: " . $stmt->error;
    }

    $stmt->close();
}

// Função para validar a senha do Gestor Default
function senhaGestorDefaultCorreta($senha)
{
    global $mysqli;

    $senhaGestorDefaultHashed = '$2y$10$P6KoVMxrnt0rlpLFdFv8LOCUEMzZEbASF940OS0tsQJpzuThkbG1C';

    // Verificar se a senha fornecida corresponde à senha do Gestor Default
    return password_verify($senha, $senhaGestorDefaultHashed);
}

// Verificar se foi excluído
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmarExclusao'])) {
    $idGestorExcluir = $_POST['idGestorExcluir'];
    $senhaGestorDefault = $_POST['senhaGestorDefault'];

    // Validar a senha do Gestor Default
    if (senhaGestorDefaultCorreta($senhaGestorDefault)) {
        excluirGestor($idGestorExcluir);
        // Redirecionar para a mesma página após a exclusão
        // echo '<script>location.reload();</script>';

        exit();
    } else {
        echo "Senha do Gestor Default incorreta!";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/mediaQuery.css" />
    <script src="https://unpkg.com/scrollreveal"></script>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css" />
    <link rel="stylesheet" href="css/style.css" />
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
    <title>Home - Gestor Default</title>
</head>

<body id="body_tables">
    <header class="topo-index" id="header_homeGestao">
        <img src="img/logo-senai-branco.png" alt="" />
    </header>
    <main id="gestor_default_main">

        <div class="container">
            <div class="row row--top-40">
                <div class="col-md-12">
                    <h2 class="row__title">Gerenciamento de <span>GESTORES</span></h2>
                </div>
            </div>
            <div class="row row--top-20">
                <div class="col-md-12">
                    <div class="table-container">
                        <table class="table">
                            <thead class="table__thead">
                                <tr>

                                    <th class="table__th">ID</th>
                                    <th class="table__th">CPF</th>
                                    <th class="table__th">Nome</th>
                                    <th class="table__th">Email</th>
                                    <th class="table__th"></th>
                                </tr>
                            </thead>
                            <tbody class="table__tbody">
                                <?php
                                foreach ($gestores as $gestor) {
                                    echo "<tr class='table-row table-row--gestor table'>
                                    <td class='table-row__td'>
                                    <div class='table-row__img'></div>
                                    <div class='table-row__info'>
                                      <p class='table-row__id'>{$gestor['id']}</p>
                                    </div>
                                  </td>
                     
                        <td class='table-row__td' data-column='CPF'>
                            <div >
                                <p class='table-row__p'></p>
                            </div>
                        {$gestor['cpf']}
                        </td>
                        <td class='table-row__td' data-column='Nome'>{$gestor['nome']}</td>
                        <td class='table-row__td' data-column='Email'>{$gestor['email']}</td>
                        <td>
                            <button onclick='confirmarExclusao({$gestor['id']})'>Excluir</button>
                        </td>
                      </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="bnts">
            <input onclick="document.getElementById('cadastroGestor').style.display='block'" type="button"
                value="Cadastrar Gestor" />
            <input type="button" value="Atualizar a Tabela" onclick=" location.reload()" />
        </div>
    </main>
    <div class="w3-container">
        <div id="confirmacaoExclusao" class="w3-modal">
            <div class="w3-modal-content w3-animate-top">
                <span onclick="document.getElementById('confirmacaoExclusao').style.display='none'"
                    class="w3-button w3-display-topright">&times;</span>

                <div class="form_modal">
                    <p class="p_modal">
                        Você tem certeza que deseja excluir este Gestor?
                    </p>
                    <form method="post">
                        <input type="hidden" name="idGestorExcluir" id="idGestorExcluir" />
                        <div class="input_modal">
                            <input class="senhaGestorDefault" placeholder="Senha Gestor Default" type="password"
                                name="senhaGestorDefault" required />
                        </div>

                        <div class="bnts">
                            <button class="btn" type="submit" name="confirmarExclusao">Excluir</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="w3-container">
        <div id="cadastroGestor" class="w3-modal">
            <div class="w3-modal-content w3-animate-top">
                <span onclick="document.getElementById('cadastroGestor').style.display='none'"
                    class="w3-button w3-display-topright">&times;</span>
                <div class="form_modal">
                    <div class="container">
                        <div class="header">
                            <div>
                                <div class="logo">
                                    <img src="img/svg/Senai _logoSVG.svg" />
                                </div>

                            </div>
                        </div>
                        <form method="post">

                            <div class="input_modal">
                                <input type="text" name="nome" id="nome" placeholder="Nome Completo" required>
                            </div>
                            <p class="p_modal" id="verificacaoNomeCompleto" style="display: none">
                                Digite um nome completo
                            </p>
                            <div class="input_modal">
                                <input type="email" placeholder="Email" name="email" id="email" required />
                            </div>
                            <div class="input_modal">
                                <input type="text" placeholder="CPF" name="cpf" oninput="mascara(this)" maxlength="11"
                                    id="cpf" required />
                            </div>
                            <p id="verificacaoCPF_p" style="display: none">
                                Digite um CPF válido
                            </p>
                            <div class="input_modal">
                                <input id="senhaCadastro" placeholder="Senha" type="password" name="senha" id="senha"
                                    required />
                            </div>
                            <div class="input_modal">
                                <input id="confirmarSenhaCadastro" placeholder="Confirmar Senha" type="password"
                                    name="senha" id="senha" required />
                            </div>
                            <div>
                                <p class="p_modal" id="comparacaoSenhas" style="display: none">
                                    As senhas não conferem
                                </p>
                            </div>
                            <div class="bnts">
                                <button disabled type="submit" name="cadastrarGestor" id="submitCadastrarGestor">
                                    Cadastrar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
       function confirmarExclusao(idGestor) {
            document.getElementById('idGestorExcluir').value = idGestor;
            document.getElementById('confirmacaoExclusao').style.display = 'block';
        }
        //mascara CPF
        function mascara(i) {
            var v = i.value;
            if (isNaN(v[v.length - 1])) {
                // impede entrar outro caractere que não seja número
                i.value = v.substring(0, v.length - 1);
                return;
            }

            i.setAttribute("maxlength", "14");
            if (v.length == 3 || v.length == 7) i.value += ".";
            if (v.length == 11) i.value += "-";
        }

        ///////

        const botaoCriarConta = document.getElementById("submitCadastrarGestor");

        const inputSenha = document.getElementById("senhaCadastro");
        const inputConfirmarSenha = document.getElementById(
            "confirmarSenhaCadastro"
        );

        const comparacaoSenhasP = document.getElementById("comparacaoSenhas");
        const verificacaoNomeCompletoP = document.getElementById(
            "verificacaoNomeCompleto"
        );

        const inputNomeCompleto = document.getElementById("nome");

        inputSenha.addEventListener("input", validarCadastro);
        inputConfirmarSenha.addEventListener("input", validarCadastro);
        inputNomeCompleto.addEventListener("input", validarCadastro);

        //verifca se as senhas conferem e se nao esta digitando apenas um nome
        function validarCadastro() {
            const inputCPF = document.getElementById("cpf");
            const verificacaoCPF_p = document.getElementById("verificacaoCPF_p");
            var comprimentoCPF = inputCPF.value.length;

            inputCPF.addEventListener("input", function () {
                 comprimentoCPF = inputCPF.value.length;
                verificacaoCPF_p.style.display = comprimentoCPF < 11 ? "block" : "none";
                
            });

            const palavrasinputNomeCompleto = inputNomeCompleto.value
                .split(/\s+/)
                .filter(Boolean).length;

            comparacaoSenhasP.style.display =
                inputSenha.value === inputConfirmarSenha.value ? "none" : "block";

            verificacaoNomeCompletoP.style.display =
                palavrasinputNomeCompleto > 1 ? "none" : "block";


            if (
                inputSenha.value === inputConfirmarSenha.value &&
                palavrasinputNomeCompleto > 1
            ) {
                botaoCriarConta.removeAttribute("disabled");
            } else {
                botaoCriarConta.setAttribute("disabled", "true");
            }
        }
        ///////////////////////////
    </script>
</body>

</html>