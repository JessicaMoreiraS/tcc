<?php
//iniciando sessão
session_start();



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
            echo '<script>';
            echo "alert('Atualizado')";
            echo '</script>';
        }
    }


    //buscando as informaçoes das colunas do id referido
    $sql = "SELECT * FROM $tabelaBuscar WHERE id = $id_atualizacao";
    //aplicando a execuçao do sql acima, em uma variavel
    $resultado = $mysqli->query($sql);

    //buscando a linha do objeto
    //nome da coluna = chave , valor da coluna = dados
    $dados = mysqli_fetch_assoc($resultado);


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
                    action="<?php echo "update.php?option=$tabelaBuscar&id_atualizacao=$id_atualizacao" ?>">
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
                        $sala = $id_atualizacao;

                        $sql = "SELECT turma FROM sala WHERE id=?";
                        $stmt = $mysqli->prepare($sql);


                        if ($stmt) {
                            //aplicando o id da sala ao  parametro da consulta
                            $stmt->bind_param("i", $sala);
                            //verificando se o sql executou
                            if ($stmt->execute()) {
                                //resganto o resultado
                                $stmt->bind_result($nomeTurma);
                                $stmt->fetch();
                                $stmt->close();
                            }
                        } else {
                            echo $mysqli->error;
                        }

                        //buscar tipos de máquinas disponíveis na turma
                        $sqlTipos = "SELECT DISTINCT tipo_maquina.id, tipo_maquina.tipo FROM tipo_maquina
                        INNER JOIN lista_sala_tipo_maquina ON lista_sala_tipo_maquina.id_tipo_maquina = tipo_maquina.id
                        WHERE lista_sala_tipo_maquina.id_sala = ?";

                        $tiposDaSalaId = [];
                        $tiposDaSalaNome = [];
                        $stmt = $mysqli->prepare($sqlTipos);
                        if ($stmt) {
                            $stmt->bind_param("i", $sala);
                            if ($stmt->execute()) {
                                $stmt->bind_result($tipoId, $tipoNome);
                                while ($stmt->fetch()) {
                                    $tiposDaSalaId[] = $tipoId;
                                    $tiposDaSalaNome[] = $tipoNome;
                                }
                                $stmt->close();
                            }
                        } else {
                            echo $mysqli->error;
                        }

                        echo ' <div class="checkboxes">
                       <div class="titulo">
                         <p>Máquinas Disponíveis:</p>
                       </div>';

                        for ($i = 0; $i < count($tiposDaSalaId); $i++) {
                            $tipoId = $tiposDaSalaId[$i];
                            $tipoNome = $tiposDaSalaNome[$i];
                            $sqlBuscaMaquinas = "SELECT * FROM maquina WHERE maquina.id_tipo_maquina = $tipoId";
                            $conteudo = $mysqli->query($sqlBuscaMaquinas);
                            while ($radio = mysqli_fetch_assoc($conteudo)) {
                                echo '<label for="tipo_maquina" class="cyberpunk-checkbox-label">
                                <input  class="cyberpunk-checkbox" type="checkbox" id="' . $radio['tipo'] . '" name="maquinas[]" value="' . $radio['tipo'] . '">  
                                ' . $radio['tipo'] . '
                            </label>';
                            }
                        }
                        echo ' </div>';
                        ?>
                </div>


                <div class="bnts">
                    <div class="inputs">
                        <input type="submit" value="Salvar">
                        <input type="button" value="Cancelar" id="botaoCancelar" onclick=" cancelarEdicaoPerfil()"
                            style="display: none;">
                    </div>
                    <?php
                    if (!$editarTurma) {
                        echo '<a id="mudarSenha" href="#">Mudar Senha</a>';
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
                    input.removeAttribute('readonly');
                    if (input.placeholder == 'Nome' || input.placeholder == 'Turma') {
                        input.focus();
                    }
                    if (input.id = "codigo_acesso") {
                        input.setAttribute(readonly, true)
                    }
                });
            }

            setTimeout(function () {
                inputs.forEach(function (input) {
                    input.style.width = 'auto';
                });
            }, 100);

            function cancelarEdicaoPerfil() {
                location.reload()
            }

                                            ////
        </script>

        </html>
        <?php
                    }
}
