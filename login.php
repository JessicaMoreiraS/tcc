    <?php
    session_start();
    session_destroy();
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="css/style.css" />
        <link rel="stylesheet" href="css/mediaQuery.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png"/>
        <script src="https://unpkg.com/scrollreveal"></script>
        <style>
            .topo-index{
                justify-content: center;
            }
        </style>
        <title>Login</title>
    </head>

    <body id="body_login">
        <header class="topo-index" id="header_login">
            <img src="img/svg/logo_senai_vermelho.svg" width="200" />
        </header>
        <main id="main_Login">
            <div class="form-box">
                <div class="botoes_box">
                    <div>
                        <button class="ativo" id="btnLogin" onclick="login()">Login</button>
                    </div>
                    <div>
                        <button onclick="register()" id="btnRegister">Cadastre-se</button>
                    </div>
                </div>
            

                <!--Formulario para acessar a conta de aluno de professor-->
                <form action="direcionamentoLogin.php" method="GET"  class="form_inputs" id="login">
                    <div id="infosAcess">
                        <div class="input">
                        <input type="email" placeholder="Email" name="email" required  />
                        </div>
                        <div class="input">
                        <input
                            type="password"
                            placeholder="Senha"
                            name="senha"
                            required
                            id="inputSenha"
                        />
                        </div>

                    <a href="recuperacaoSenha.php">Esqueci minha senha</a>
                    </div>

                    <div class="footer-form">
                        <div class="input_entrar">
                        <input type="submit" value="Entrar" name="entrar" />
                        </div>

                        <div class="gestao_link">
                        <a onclick="caixaSenhaGestao()">Gestão</a>
                        </div>
                    </div>
                </form>

                <!--Formulario para criar a conta de aluno-->
            <form action="direcionamentoLogin.php" method="POST" class="form_inputs" id="register">
                <div id="infosCreate">
                    <div class="input">
                    <input
                        id="nomeCompleto"
                        type="text"
                        placeholder="Nome Completo"
                        name="nome"
                        required
                    />
                    <span> <p id="verificacaoNomeCompleto" style="display: none;">Digite um nome completo</p></span>
                    </div>
                
                    <div class="input">
                    <input type="email" placeholder="Email" name="email" required  />
                    </div>
                    <div class="input">
                    <input
                        type="password"
                        placeholder="Senha"
                        name="senha"
                        id="senhaCadastro"
                        required
                        min="6"
                    />
                    </div>
                    <div class="input">
                    <input
                        name="confimarSenha"
                        id="confirmarSenhaCadastro"
                        type="password"
                        placeholder="Confirme sua senha"
                        required
                    />
                    <p id="comparacaoSenhas" style="display: none;">Senhas não conferem</p>
                    </div>
                
                </div>

                <div class="input_entrar">
                    <input type="submit" value="Criar conta" id="criarConta" disabled name="criarConta"/>

                </div>
            </form>
            </div>
            <div class="eclipses">
                <div class="img">
                    <img src="img/Login-amico 1.png" />
                </div>
                <div class="eclipse-1">
                    <svg width="1789" height="1140" viewBox="0 0 1789 1140" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="994.5" cy="570" rx="894.5" ry="570" fill="#256687" fill-opacity="0.7" />
                    </svg>
                </div>
                <div class="eclipse-2">
                    <svg width="1789" height="1140" viewBox="0 0 1789 1140" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="894.5" cy="570" rx="894.5" ry="570" fill="#256687" fill-opacity="0.7" />
                    </svg>
                </div>
                <div class="eclipse-3">
                    <svg width="1789" height="1140" viewBox="0 0 1789 1140" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="894.5" cy="570" rx="894.5" ry="570" fill="#256687" fill-opacity="0.7" />
                    </svg>
                </div>
            </div>
        </main>

        <footer>
            <div class="escola">
                <img width="150" height="40" src="img/svg/logo_senai_vermelho.svg" alt="" />

                <div class="escola-info">
                    <p>Escola Senai "Nami Jafet"</p>
                    <br />
                    <p>Mogi das Cruzes/SP</p>
                </div>
            </div>
            <div class="contatos">
                <div class="icones">
                    <i class="fa fa-facebook-square" style=" font-size: 30px;"></i>
                    <i class="fa fa-envelope" style=" font-size: 30px;"></i>
                </div>
                <p>Telefone:(11)4728-3900</p>
            </div>
        </footer>

    </body>
    <script>
        //Sistema de troca do formulario (login e criar conta)
        const form_login = document.getElementById("login");
        const form_register = document.getElementById("register");
        const btnLogin = document.getElementById("btnLogin");
        const btnRegister = document.getElementById("btnRegister");

        function login() {
            btnLogin.classList.add("ativo");
            btnRegister.classList.remove("ativo");

            form_login.style.left = "5%";
            form_register.style.left = "100%";
        }

        function register() {
            btnLogin.classList.remove("ativo");
            btnRegister.classList.add("ativo");
            form_login.style.left = "100%";
            form_register.style.left = "5%";
        }

        const botaoCriarConta = document.getElementById("criarConta");

const inputSenha = document.getElementById("senhaCadastro");
const inputConfirmarSenha = document.getElementById("confirmarSenhaCadastro");
const comparacaoSenhasP = document.getElementById("comparacaoSenhas");
const verificacaoNomeCompletoP = document.getElementById(
  "verificacaoNomeCompleto"
);
const inputNomeCompleto = document.getElementById("nomeCompleto");
//confirmar se as senhas conferem

inputSenha.addEventListener("input", validarCadastro);
inputConfirmarSenha.addEventListener("input", validarCadastro);
inputNomeCompleto.addEventListener("input", validarCadastro);

function validarCadastro() {
  //capturar a qauntidades de palavras no nome input
  const palavrasinputNomeCompleto = inputNomeCompleto.value
    .split(/\s+/)
    .filter(Boolean).length;

  //verificar se há no minimo duas palavras em nome
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

async function caixaSenhaGestao() {
    const { value: password } = await Swal.fire({
        title: 'Preencha sua senha',
        input: 'password', 
        inputLabel: 'Senha',
        inputPlaceholder: 'Sua senha',
        inputAttributes: {
            maxlength: 10,
            autocapitalize: 'off',
            autocorrect: 'off'
        }
    })

    if (password) {
        location.assign(`direcionamentoLogin.php?sg=${password}`)
        //Swal.fire(`Entered password: ${password}`)
    }
}
        </script>
    <script src="js/reveal.js"></script>
    <script src="js/script.js"></script>
   
    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </html>

    <?php
        if (filter_input(INPUT_GET, 'e')) {
            $mensagem_erro = filter_input(INPUT_GET, 'e');
            echo '<script>erroLogin('.$mensagem_erro.')</script>';
        }
    ?>
