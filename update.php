<?php
//iniciando sessão
session_start();

$id = $_SESSION['idAcesso'];


//incluindo conexao cm o banc
include('conexao.php');




if (isset($_GET['option']) && isset($_GET['id_atualizacao'])) {
    $editarTurma = false;
    $tabelaBuscar = $_GET['option'];
    $id_atualizacao = $_GET['id_atualizacao'];

    // sql que mostra todas as colunas da tabela em questao
    $sqlBuscarTodasColunas = "SHOW COLUMNS FROM $tabelaBuscar";

    //execuntando o sql e colocando este sql dentro de uma variavel
    $resultado = $mysqli->query($sqlBuscarTodasColunas);

    //aplicando campos como um array vazio (pq vai ser utilizado futuramente com uma função)
    $campos = array();


    //verifica se a url é sobre editar a turma
    if (isset($_GET['editarTurma'])) {
        $editarTurma = true;
    }

    while ($row = mysqli_fetch_assoc($resultado)) {

        //construindo a array campos, com os valores da funcao fetch
        $campos[] = $row['Field'];
    }
    //mysqli_fetch_assoc busca o resultado de uma consulta sql em linha, como array, entao o resultado de $sqlBuscarTodasColunas, é retornado nest função e aplicado na variavel $row



    // !!IMPORTANTE, redefinir senha ainda não é funcional pelo update.php

    //variaveis para não aparecerem no form de 
    $colunaExcluir = 'id_professor';
    $colunaExcluir1 = 'senha';
    $colunaExcluir3 = 'id';
    $colunaExcluir2 = 'redefinir_senha';
    $colunaExcluir4 = 'codigo_recuperacao';

    //substituindo os valores de campos(campos dos formuarios) por uma função que ira excluir s variavies criadas acima, da busca sql
    $campos = array_diff($campos, array($colunaExcluir, $colunaExcluir1, $colunaExcluir3, $colunaExcluir2, $colunaExcluir4));


    //verifica se o formuario é do metodo post
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        //sql da atualização de dados
        //tabelaBuscar = tabela em questao
        $sql = "UPDATE $tabelaBuscar SET ";

        //for que itera(percorre as linhas), na array campos (todos os nomes das tabelas que é possivel atualizar)
        foreach ($campos as $campo) {

            //array post (do formulario), esta sendo recebida por uma variavel
            $valor = $_POST[$campo];

            //concatenar o campo (nome da tabela), com o valor atualizado, puxado do POST
            $sql .= "$campo = '$valor', ";
        }


        //finaizaçao da consulta (informa o id)
        //rtrim($sql, ', ') é uma funçaõ de remocao de caracteres, onde aqui esta removendo as virgulas na consulta (aplicadas anteriormente)
        $sql = rtrim($sql, ', ') . " WHERE id = $id_atualizacao";

        // verifica se foi executado a consulta 
        if ($mysqli->query($sql)) {
          
        }
    }


    //buscando as informaçoes das colunas do id referido
    $sql = "SELECT * FROM $tabelaBuscar WHERE id = $id_atualizacao";
    //aplicando a execuçao do sql acima, em uma variavel
    $resultado = $mysqli->query($sql);

    //buscando a linha do objeto
    //nome da coluna = chave , valor da coluna = dados
    $dados = mysqli_fetch_assoc($resultado);

    if($editarTurma){
        $urlUpdate = "update.php?option=$tabelaBuscar&editarTurma&id_atualizacao=$id_atualizacao";
    }else{
        $urlUpdate = "update.php?option=$tabelaBuscar&id_atualizacao=$id_atualizacao";
    }
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css" />
        <script src="https://unpkg.com/scrollreveal"></script>
        <title>Perfil</title>
    </head>

    <body id="body_perfil">
        <header class="topo-inicial">
            <img width="140" class="logo-inicial" src="img/logo-senai-branco.png" alt="" />

            <div class="icons">
                <i class="fa fa-user-circle" style="color: rgb(255, 255, 255); cursor: pointer"></i>
                <input type="checkbox" role="button" aria-label="Display the menu" class="menu" />
            </div>
        </header>
        <main id="main_formPerfil">
            <div class="container_form">
                <section>
                    <div>
                        <img src="img/svg/user.svg" />
                    </div>
                    <div class="edit_Icon">
                        <img onclick="liberarEdicaoPerfil()" src="img/svg/Edit.svg" id="imgEditIcon" />
                    </div>
                </section>
                <form id="form_perfil" method="POST"
                    action="<?php echo $urlUpdate ?>">
                    <?php

                    //iteraçao pela array campos (array do fetch que armazena os dados da tabela)
                    foreach ($campos as $campo) {
                        // valor atual = dados do campo em especifico
                        $valorAtual = $dados[$campo];
                        ?>
                        <div class="input">
                            <input class="<?php echo $campo ?>" required readonly type="text" id="<?php echo $campo; ?>"
                                name="<?php echo $campo; ?>" value="<?php echo $valorAtual; ?>"><br>
                        </div>
                        <?php
                    }
                    if ($editarTurma) {
                        $sql_tipos_na_sala = "SELECT tipo_maquina.id, tipo_maquina.tipo
                        FROM tipo_maquina
                        INNER JOIN lista_sala_tipo_maquina ON tipo_maquina.id = lista_sala_tipo_maquina.id_tipo_maquina
                        WHERE lista_sala_tipo_maquina.id_sala = ?";
                
                        $stmt_tipos_na_sala = $mysqli->prepare($sql_tipos_na_sala);
                        $stmt_tipos_na_sala->bind_param("i", $id);
                        $stmt_tipos_na_sala->execute();
                        $stmt_tipos_na_sala->bind_result($tipo_maquina_id, $tipo_maquina_nome);
                
                        // Armazena os tipos de maquinas da sala 
                        $tipos_na_sala = array();
                        while ($stmt_tipos_na_sala->fetch()) {
                            $tipos_na_sala[] = $tipo_maquina_nome;
                        }
                
                        // Consulta para obter todos os tipos de maquinas
                        $sql_todos_tipos = "SELECT id, tipo FROM tipo_maquina";
                        $stmt_todos_tipos = $mysqli->query($sql_todos_tipos);
                
                        echo '<div class="checkboxes" id="checkboxesFormEditarTurma">';
                        echo '<div class="titulo">
                        <p>Máquinas  disponiveis:</p>
                      </div>';
                        while ($row = $stmt_todos_tipos->fetch_assoc()) {
                            $tipo = $row['tipo'];
                            $check = in_array($tipo, $tipos_na_sala) ? 'checked' : '';
                
                            echo '<label for="' . $tipo . '" class="cyberpunk-checkbox-label">';
                            echo '<input ' . $check . ' disabled  class="cyberpunk-checkbox" type="checkbox" id="' . $tipo . '" name="maquinas[]" value="' . $tipo . '" >';
                            echo $tipo;
                            echo '</label>';
                        }
                        echo '</div>';
                    } 
                                            
                      
                        ?>
                


                <div class="bnts">
                    <div class="inputs">
                        <input type="submit" value="Salvar">
                        <input type="button" value="Cancelar" id="botaoCancelar" onclick=" cancelarEdicaoPerfil()"
                            style="display: none;">
                    </div>
                    <?php
                    if (!$editarTurma) {
                        echo '<a id="mudarSenha" href="recuperacaoSenha.php">Mudar Senha</a>';
                    }


                    ?>
                </div>

                </form>
                </div>
            </main>

        </body>
        <script src="js/reveal.js"></script>

        <script>
            //script editar perfil
            const edit_button = document.getElementById("imgEditIcon");
            const cancel_button = document.getElementById("botaoCancelar");
            const inputs = form_perfil.querySelectorAll("input");
            function liberarEdicaoPerfil() {
                const form_perfil = document.getElementById("form_perfil");

                cancel_button.style.display = 'block';
                inputs.forEach(function (input) {
                    
                    if (input.name == 'nome' || input.name == 'turma') {
                        input.focus();
                    }
                    if (input.id !== "codigo_acesso") {
                        input.removeAttribute('readonly');
                    }
                    if (input.type == "checkbox"){
                        input.removeAttribute('disabled');
                    }
                });
            }


            function cancelarEdicaoPerfil() {
                location.reload()
            }

                                            ////
        </script>

        </html>
        <?php
                    }

