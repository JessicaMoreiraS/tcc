<?php
include("conexao.php");

if (isset($_POST["codigo_recuperacao"]) && isset($_POST["nova_senha"]) && isset($_POST["confirmar_senha"])) {
    $codigoRecuperacao = $_POST["codigo_recuperacao"];
    $novaSenha = $_POST["nova_senha"];
    $confirmarSenha = $_POST["confirmar_senha"];

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
            echo json_encode(['success' => false]);
            exit;
        }
    } else {
        // Resposta para o cliente (JSON)
        echo json_encode(['success' => false]);
        exit;
    }
} else {
    // Resposta para o cliente (JSON)
    echo json_encode(['success' => false]);
    exit;
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de senha</title>
</head>
<body>
    <div>
        <form action="processa_recuperacao.php" method="POST">
            <input type="text" placeholder="Código de recuperação" name="codigo_recuperacao" required>
            <input type="submit" value="Confirmar" name="confirmar">
        </form>
    </div>
    <script>
    // Exemplo de código JavaScript para mostrar um pop-up e enviar a nova senha para o servidor
    document.addEventListener('DOMContentLoaded', function () {
        const codigoInput = document.querySelector('input[name="codigo"]');
        const confirmarBtn = document.querySelector('input[name="confirmar"]');

        confirmarBtn.addEventListener('click', function (event) {
            event.preventDefault();

            // Verifica se o código tem exatamente 4 dígitos
            if (codigoInput.value.length === 4) {
                // Mostra pop-up
                const novaSenhaPopup = prompt("Digite sua nova senha:");

                // Envia nova senha para o servidor
                fetch('recuperacaoSenha.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `codigo_recuperacao=${codigoInput.value}&nova_senha=${novaSenhaPopup}&confirmar_senha=${novaSenhaPopup}`,
                })
                .then(response => response.json())
                .then(data => {
                    // Lidar com a resposta do servidor
                    if (data.success) {
                        alert('Senha atualizada com sucesso. Você pode fazer login com a nova senha.');
                        // Redirecionar para a página de login
                        window.location.href = 'login.php';
                    } else {
                        alert('Ocorreu um erro ao atualizar a senha. Tente novamente.');
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição:', error);
                    alert('Ocorreu um erro na requisição. Tente novamente.');
                });
            } else {
                alert('O código de confirmação deve ter 4 dígitos.');
            }
        });
    });
</script>
</body>
</html>
