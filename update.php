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


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if ($editarTurma) {
            $maquinasSelecionadas = isset($_POST['maquinas']) ? $_POST['maquinas'] : [];


            $sql_maquinas_sala = "SELECT id_tipo_maquina FROM lista_sala_tipo_maquina WHERE id_sala = ?";
            $stmt_maquinas_sala = $mysqli->prepare($sql_maquinas_sala);
            $stmt_maquinas_sala->bind_param("i", $id_atualizacao);
            $stmt_maquinas_sala->execute();
            $stmt_maquinas_sala->bind_result($id_tipo_maquina);

            $maquinasNoBanco = [];
            while ($stmt_maquinas_sala->fetch()) {
                $maquinasNoBanco[] = $id_tipo_maquina;
            }

            foreach ($maquinasNoBanco as $maquina) {
                if (!in_array($maquina, $maquinasSelecionadas)) {
                    $sql_remover_maquina = "DELETE FROM lista_sala_tipo_maquina WHERE id_sala = ? AND id_tipo_maquina = ?";
                    $stmt_remover_maquina = $mysqli->prepare($sql_remover_maquina);
                    $stmt_remover_maquina->bind_param("ii", $id_atualizacao, $maquina);
                    $stmt_remover_maquina->execute();
                }
            }

            foreach ($maquinasSelecionadas as $maquina) {
                if (!in_array($maquina, $maquinasNoBanco)) {
                    $sql_adicionar_maquina = "INSERT INTO lista_sala_tipo_maquina (id_sala, id_tipo_maquina) VALUES (?, ?)";
                    $stmt_adicionar_maquina = $mysqli->prepare($sql_adicionar_maquina);
                    $stmt_adicionar_maquina->bind_param("ii", $id_atualizacao, $maquina);
                    $stmt_adicionar_maquina->execute();

                }
            }
        }


        $sql = "UPDATE $tabelaBuscar SET ";


        foreach ($campos as $campo) {


            $valor = $_POST[$campo];


            $sql .= "$campo = '$valor', ";
        }



        $sql = rtrim($sql, ', ') . " WHERE id = $id_atualizacao";


        if ($mysqli->query($sql)) {

        }


        header("Location:update.php?option=$tabelaBuscar&editarTurma&id_atualizacao=$id_atualizacao");
        exit();
    }



    $sql = "SELECT * FROM $tabelaBuscar WHERE id = $id_atualizacao";

    $resultado = $mysqli->query($sql);

    $dados = mysqli_fetch_assoc($resultado);

    if ($editarTurma) {
        $urlUpdate = "update.php?option=$tabelaBuscar&editarTurma&id_atualizacao=$id_atualizacao";
    } else {
        $urlUpdate = "update.php?option=$tabelaBuscar&id_atualizacao=$id_atualizacao";
    }
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css" />
        <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png"/>
        <script src="https://unpkg.com/scrollreveal"></script>
        <title>
            <?php
            if ($editarTurma) {
                echo 'Editar Turma';
            } else {
                echo 'Editar Perfil';
            }
            ?>
        </title>
        <style>
            .setaVoltar{
                width: 50px;
                align-self: center;
                justify-self: start;
            }
            .logo-inicial{
                margin:0 auto;
                padding-left:0;
            }
        </style>
    </head>

    <body id="body_perfil">
        <header class="topo-inicial">
            <?php
            if ($_SERVER['HTTP_REFERER']){
                if(!strpos($_SERVER['HTTP_REFERER'], "update.php")){
                    echo '<a href='.$_SERVER['HTTP_REFERER'].' class="setaVoltar">';
                }else{
                    if(isset($_SESSION['tipo'])){
                        switch($_SESSION['tipo']){
                            case 'aluno':
                                echo '<a href="homeAluno.php" class="setaVoltar">';
                                break;
                            case 'professor':
                                echo '<a href="homeProfessor.php" class="setaVoltar">';
                                break;
                            case 'gestor':
                                echo '<a href="homeGestao.php" class="setaVoltar">';
                                break;
                            case 'defalt':
                                echo '<a href="homeGestaoDefault.php" class="setaVoltar">';
                                break;
                        }
                    }
                }
            }else{
                echo '<a href="login.php" class="setaVoltar">';
            }
            ?>
            <!-- <a href="login.php" onClick="history.back()" class="setaVoltar"> -->
                <img src="img/svg/setaVoltar.svg" alt="voltar">
            </a>
            <img width="200" class="logo-inicial" src="img/svg/logo_senai_vermelho.svg"  />

            <div class="icons">
                <i class="fa fa-user-circle" style="color: rgb(255, 255, 255); cursor: pointer"></i>
            </div>
        </header>
       
        <main id="main_formPerfil">
            <div class="container_form">
                <section>
                    <div>
                        <?php
                        if ($editarTurma) {
                            echo '<img src="img/svg/class.svg" >';
                        } else {
                            echo '<img src="img/svg/user.svg" width="70">';
                        }
                        ?>
                    </div>
                    <div class="edit_Icon" onclick="liberarEdicaoPerfil()">
                        <img  src="img/svg/Edit.svg" id="imgEditIcon" />
                    </div>
                </section>
                <form id="form_perfil" method="POST" action="<?php echo $urlUpdate ?>">
                    <?php


                    foreach ($campos as $campo) {

                        $valorAtual = $dados[$campo];
                        ?>
                        <div class="input">
                            <input class="<?php echo $campo ?>" required readonly type="text" id="<?php echo $campo; ?>"
                                name="<?php echo $campo; ?>" value="<?php echo $valorAtual; ?>"><br>
                                <?php 
                                    if($campo == "codigo_acesso"){
                                        echo '<p  id="p_update">O código de acesso não pode ser alterado.</p>';
                                    }
                                ?>
                        </div>
                        <?php
                    }
                    if ($editarTurma) {

                        $sql_tipos_na_sala = "SELECT tipo_maquina.id, tipo_maquina.tipo
                        FROM tipo_maquina
                        INNER JOIN lista_sala_tipo_maquina ON tipo_maquina.id = lista_sala_tipo_maquina.id_tipo_maquina
                        WHERE lista_sala_tipo_maquina.id_sala = ?";

                        $stmt_tipos_na_sala = $mysqli->prepare($sql_tipos_na_sala);
                        $stmt_tipos_na_sala->bind_param("i", $id_atualizacao);
                        $stmt_tipos_na_sala->execute();
                        $stmt_tipos_na_sala->bind_result($tipo_maquina_id, $tipo_maquina_nome);


                        $tipos_na_sala = array();
                        while ($stmt_tipos_na_sala->fetch()) {
                            $tipos_na_sala[] = $tipo_maquina_id;
                        }


                        $sql_todos_tipos = "SELECT id, tipo FROM tipo_maquina";
                        $stmt_todos_tipos = $mysqli->query($sql_todos_tipos);

                        echo '<div class="checkboxes" id="checkboxesFormEditarTurma">';
                        echo '<div class="titulo"><p>Máquinas disponíveis:</p></div>';

                        while ($row = $stmt_todos_tipos->fetch_assoc()) {
                            $tipo = $row['tipo'];
                            $Idtipo = $row['id'];
                            $check = in_array($Idtipo, $tipos_na_sala) ? 'checked' : '';

                            echo '<label for="' . $tipo . '" class="cyberpunk-checkbox-label">';
                            echo '<input disabled ' . $check . ' class="cyberpunk-checkbox" type="checkbox" id="' . $Idtipo . '" name="maquinas[]" value="' . $Idtipo . '" >';
                            echo $Idtipo;
                            echo '<span>';
                            echo $tipo;
                            echo '</span>';
                            echo '</label>';
                        }

                        echo '</div>';



                    }


                    ?>



                    <div class="bnts">
                        <div class="inputs">
                            <input  disabled  type="submit" value="Salvar" id="botaoSalvar">
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
        const save_button = document.getElementById("botaoSalvar");
        const inputs = form_perfil.querySelectorAll("input");

        const p = document.getElementById("p_update");

        function liberarEdicaoPerfil() {
            const form_perfil = document.getElementById("form_perfil");

            cancel_button.style.display = 'block';
           
            save_button.removeAttribute('disabled');
            inputs.forEach(function (input) {

                if (input.name == 'nome' || input.name == 'turma') {
                    input.focus();
                }
                if (input.id !== "codigo_acesso") {
                    input.removeAttribute('readonly');
                }
                if (input.type == "checkbox") {
                    input.removeAttribute('disabled','enabled');
                }
            });
        }


        function cancelarEdicaoPerfil() {
            location.reload()
        }


    </script>
    <script src="js/script.js"></script>
    </html>
    <?php
}

    if (filter_input(INPUT_GET, 'e')) {
        $mensagem_erro = filter_input(INPUT_GET, 'e');
        echo '<script>erroLogin('.$mensagem_erro.')</script>';
    }
?>
