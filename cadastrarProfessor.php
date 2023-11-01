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
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/mediaQuery.css" />
    <script src="https://unpkg.com/scrollreveal"></script>
    <title>Cadastrar Professor</title>
</head>

<body id="body_cadastrarProfessor">
<header class="topo-inicial">
      <img
        width="140"
        class="logo-inicial"
        src="img/logo-senai-branco.png"
        alt=""
      />

      <div class="icons">
        <i
          class="fa fa-user-circle"
          style="color: rgb(255, 255, 255); cursor: pointer"
        ></i>
        <input
          type="checkbox"
          role="button"
          aria-label="Display the menu"
          class="menu"
        />
      </div>
    </header>
    <main id="main_cadastrarProfessor">

    <!-- <form action="cadastro.php" method="POST">
        <input id="id" type="number" value="1" hidden name="id" required>
        <input id="nome" type="text" name="nome" placeholder="Nome" required>
        <input id="CPF" type="text" name="cpf" placeholder="CPF" required>
        <input id="email" type="email" name="email" placeholder="Email" required>

        <input type="submit" name="cadastrarProfessor" value="Cadastrar">
    </form> -->

    <div class="w3-modal-content w3-animate-top">
    <input id="id" type="number" value="1" hidden name="id" required>
        <div class="form_modal">
          <div class="container">
            <div class="header">
              <div>
                <div class="icone">
                  <img src="img/svg/School Director-branco.svg" alt="" />
                </div>
              </div>
            </div>
            <form action="cadastro.php" method="POST">
              <input name="idProfessor" type="text" value="1" hidden />
              <div class="input_modal">
                <input
                id="nome" type="text" name="nome" placeholder="Nome" required
                />
              </div>
              <p
                class="p_modal"
                id="verificacaoNomeCompleto"
                style="display: none"
              >
                Digite um nome completo
              </p>
              <div class="input_modal">
                <input
                  type="text"
                  placeholder="CPF"
                  name="CPF"
                  oninput="mascara(this)"
                  maxlength="11"
                  id="CPF"
                  required
                />
              </div>
              <p id="verificacaoCPF_p" style="display: none">
                Digite um CPF válido
              </p>
              <div class="input_modal">
                <input
                id="email" type="email" name="email" placeholder="Email" required
                />
              </div>
              <div class="bnts">
              <input type="submit" name="cadastrarProfessor" value="Cadastrar">
              </div>
            </form>
          </div>
        </div>
      </div>

    </main>
    
</body>

</html>