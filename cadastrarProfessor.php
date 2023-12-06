<?php
session_start();
// if($_SESSION['idAcesso'] != 'gestaoSenai'){
//     header('Location: index.html');
// }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/mediaQuery.css" />
    <script src="https://unpkg.com/scrollreveal"></script>
    <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png"/>
    <title>Cadastrar Professor</title>
</head>

<body id="body_cadastrarProfessor">
    <header class="topo-inicial">
        <img width="140" class="logo-inicial" src="img/logo-senai-branco.png" alt="" />

        <div class="icons">
            <i class="fa fa-user-circle" style="color: rgb(255, 255, 255); cursor: pointer"></i>
            <input type="checkbox" role="button" aria-label="Display the menu" class="menu" />
        </div>
    </header>
    <main id="main_cadastrarProfessor">


        <div class="w3-modal-content w3-animate-top">
            <input id="id" type="number" value="1" hidden name="id" required>
            <div class="form_modal">
                <div class="container">
                    <div class="header">
                        <div>
                            <div class="icone">
                            <img width="200" class="logo-inicial" src="img/svg/logo_senai_vermelho.svg" />
                            </div>
                        </div>
                    </div>
                    <form action="cadastro.php" method="POST">
                        <h2>Cadastrar Professor</h2>
                        <!-- <input name="idProfessor" type="text" value="1" hidden /> -->
                        <input name="redefinir_senha" type="text" value="0" hidden />
                        <div class="input_modal">
                            <input id="nome" type="text" name="nome" placeholder="Nome" required />
                        </div>
                        <p class="p_modal" id="verificacaoNomeCompleto" style="display: none">
                            Digite um nome completo
                        </p>
                        <div class="input_modal">
                            <input type="text" placeholder="CPF" name="cpf" oninput="mascara(this)" maxlength="11"
                                id="cpf" required />
                        </div>
                        <p id="verificacaoCPF_p" style="display: none">
                            Digite um CPF válido
                        </p>
                        <div class="input_modal">
                            <input id="email" type="email" name="email" placeholder="Email" required />
                        </div>
                        <div class="bnts">
                            <input type="submit" name="cadastrarProfessor" value="Cadastrar"
                                id="submitCadastrarProfessor">
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>
    <script>
        const botaoCriarConta = document.getElementById("submitCadastrarProfessor");
        const verificacaoNomeCompletoP = document.getElementById(
            "verificacaoNomeCompleto"
        );
        const inputNomeCompleto = document.getElementById("nome");


        inputNomeCompleto.addEventListener("input", validarCadastro);

        //verifca se as senhas conferem e se nao esta digitando apenas um nome
        function validarCadastro() {
            const inputCPF = document.getElementById("cpf");
            const verificacaoCPF_p = document.getElementById("verificacaoCPF_p");
            var comprimentoCPF = inputCPF.value.length;
            var cpfValido = false;

            inputCPF.addEventListener("input", function () {
                comprimentoCPF = inputCPF.value.length;
                verificacaoCPF_p.style.display = comprimentoCPF < 11 ? "block" : "none";
                if (comprimentoCPF == 14) {
                    botaoCriarConta.removeAttribute("disabled");
                } else {
                    botaoCriarConta.setAttribute("disabled", "true");
                }

            });


            const palavrasinputNomeCompleto = inputNomeCompleto.value
                .split(/\s+/)
                .filter(Boolean).length;


            verificacaoNomeCompletoP.style.display =
                palavrasinputNomeCompleto > 1 ? "none" : "block";


            if (
                palavrasinputNomeCompleto > 1 && cpfValido
            ) {
                botaoCriarConta.removeAttribute("disabled");
            } else {
                botaoCriarConta.setAttribute("disabled", "true");
            }
        }
///////////////////////////
    </script>
</body>
<script src="js/mascaraCPF.js"></script>
<script src="js/reveal.js"></script>
<script src="js/script.js"></script>

</html>

<?php
    if (filter_input(INPUT_GET, 'e')) {
        $mensagem_erro = filter_input(INPUT_GET, 'e');
        echo '<script>erroLogin('.$mensagem_erro.')</script>';
    }
?>