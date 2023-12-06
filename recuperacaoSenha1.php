<?php
include("conexao.php");

// Criar a instância do MySQLi
//$mysqli = new mysqli($host, $usuario, $senha, $database);

// Verificar a conexão
if ($mysqli->connect_error) {
    die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
}

if (isset($_POST["codigo_recuperacao"]) && isset($_POST["nova_senha"]) && isset($_POST["confirmar_senha"])) {
    $codigoRecuperacao = $mysqli->real_escape_string($_POST["codigo_recuperacao"]);
    $novaSenha = $mysqli->real_escape_string($_POST["nova_senha"]);
    $confirmarSenha = $mysqli->real_escape_string($_POST["confirmar_senha"]);

    // Verificar se as senhas coincidem
    if ($novaSenha === $confirmarSenha) {
        // Hash da nova senha
        $senhaHash = password_hash($novaSenha, PASSWORD_BCRYPT);

        // Atualizar a senha na tabela aluno
        $sqlAtualizarSenha = "UPDATE aluno SET senha = '$senhaHash', codigo_recuperacao = NULL, redefinir_senha = 0 WHERE codigo_recuperacao = '$codigoRecuperacao'";

        if ($mysqli->query($sqlAtualizarSenha)) {
            // Resposta para o cliente (JSON)
            echo json_encode(['success' => true]);
            exit;
        } else {
            // Resposta para o cliente (JSON)
            echo json_encode(['success' => false, 'error' => 'Erro ao atualizar a senha']);
            exit;
        }
    } else {
        // Resposta para o cliente (JSON)
        echo json_encode(['success' => false, 'error' => 'As senhas não coincidem']);
        exit;
    }
} /*else {
    // Resposta para o cliente (JSON)
    echo json_encode(['success' => false, 'error' => 'Parametros ausentes']);
    exit;
}*/

// Fechar a conexão
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="css/style.css" />
    <title>Recuperação de senha</title>

    <!-- Adicione o Bootstrap e o jQuery -->
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    />
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/scrollreveal"></script>
    <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png"/>
</head>
<body id="body_recuperacaoSenha">
<header class="topo-index">
      <a href="inicialAluno.html"
        > <img width="200" class="logo-inicial" src="img/svg/logo_senai_vermelho.svg" /></a>
</header>
    <main class="conteudoConfirmarSenha">
 
        <form action="processa_recuperacao.php" method="POST">
        <div class="areaCod">
          <h2>Recuperação Senha</h2>
          <label
            >Para que possamos validar seu usuário, insira o código gerado pelo
            sistema e enviado em seu email cadastrado</label
          >
          <input
            type="text"
            placeholder="Insira o código aqui"
            name="codigo_recuperacao"
            id="codigo_recuperacao"
            required
          />
        </div>
        <div class="verificacaoSenha">
          <div class="botoesSenha">
          <button id="Btn_confirmar" type="button" class="btn btn-primary" data-toggle="modal" data-target="#senhaModal">
                Confirmar
            </button>
          </div>
        </div>
            
        </form>
   

    <!-- Modal para a nova senha -->
    <div class="modal fade" id="senhaModal" tabindex="-1" role="dialog" aria-labelledby="senhaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="senhaModalLabel">Nova Senha</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="password" placeholder="Nova senha" id="novaSenha" required>
                    <input type="password" placeholder="Confirme a senha" id="confirmarSenha" required>
                </div>
                <div class="modal-footer">
                    <button id="botaoCancelarRecuperacaoSenha" type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="enviarSenha">Salvar</button>
                </div>
            </div>
        </div>
    </div>
    </main>

    <script>
        // Exemplo de código JavaScript para mostrar um pop-up e enviar a nova senha para o servidor
        document.addEventListener('DOMContentLoaded', function () {
            const enviarSenhaBtn = document.getElementById('enviarSenha');
            const novaSenhaInput = document.getElementById('novaSenha');
            const confirmarSenhaInput = document.getElementById('confirmarSenha');

            enviarSenhaBtn.addEventListener('click', function () {
                // Verifica se as senhas coincidem
                if (novaSenhaInput.value === confirmarSenhaInput.value) {
                    // Envia nova senha para o servidor
                    fetch('processa_recuperacao.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `codigo_recuperacao=${codigoInput.value}&nova_senha=${novaSenhaInput.value}&confirmar_senha=${confirmarSenhaInput.value}`,
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Lidar com a resposta do servidor
                        if (data.success) {
                            alert('Senha atualizada com sucesso. Você pode fazer login com a nova senha.');
                            // Redirecionar para a página de login
                            window.location.href = 'login.php';
                        } else {
                            alert(`Ocorreu um erro ao atualizar a senha. ${data.error}`);
                        }
                    })
                    .catch(error => {
                        console.error('Erro na requisição:', error);
                        alert('Ocorreu um erro na requisição. Tente novamente.');
                    });
                } else {
                    alert('As senhas não coincidem.');
                }
            });
        });
    </script>
<script src="js/script.js"></script>
<script src="js/reveal.js"></script>
</body>
</html>

<?php
    if (filter_input(INPUT_GET, 'e')) {
        $mensagem_erro = filter_input(INPUT_GET, 'e');
        echo '<script>erroLogin('.$mensagem_erro.')</script>';
    }
?>