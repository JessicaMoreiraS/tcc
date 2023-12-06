<?php
include("conexao.php");

// Verificar a conexão
if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}
$mensagem = '';

// Se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_maquina = $_POST["id_maquina"];
    $id_tipo_maquina = $_POST["tipo_maquina"];
    $modelo = $_POST["modelo"];
    $fabricante = $_POST["fabricante"];
    $ledOff="OFF";
    $ledOn="ON";
    $imagem = $_FILES["imagem"]["tmp_name"] ? file_get_contents($_FILES["imagem"]["tmp_name"]) : null;

    // Verificar se o tipo de máquina já existe
    $stmt = $mysqli->prepare("SELECT id FROM tipo_maquina WHERE id = ?");
    $stmt->bind_param("s", $id_tipo_maquina);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Tipo de máquina existe
        $stmt = $mysqli->prepare("INSERT INTO maquina (id, id_tipo_maquina, modelo, fabricante, imagem, led_verde, led_amarelo, led_vermelho) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissbsss", $id_maquina, $id_tipo_maquina, $modelo, $fabricante, $imagem, $ledOff, $ledOff, $ledOn);
        $stmt->send_long_data(4, $imagem);  // Para dados BLOB (imagem)

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $mensagem = "
            <center>
                <h4 style= 'margin-top: 26px'>Máquina cadastrada com sucesso!</h4>
            </center>    
            ";
        } else {
            $mensagem = "Erro ao cadastrar a máquina. Verifique se todos os campos estão preenchidos corretamente.";
            $mensagem .= " Erro MySQL: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // Tipo de máquina não existe, redirecionar para cadastrar novo tipo
        header("Location: cadastrarmaquina.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png"/>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/mediaQuery.css">
    <script src="https://unpkg.com/scrollreveal"></script>
    <title>Cadastrar Máquinas</title>
    <style>
         .setaVoltar{
            width: 50px;
            align-self: center;
            justify-self: start;
        }
    </style>
</head>

<body  class="body-cadastrarMaquina">
    <header class="topo-inicial">
        <a href="visualizar.php?view=maquina" class="setaVoltar">
            <img src="img/svg/setaVoltar.svg" alt="voltar">
        </a>
        <img width="200" class="logo-inicial" src="img/svg/logo_senai_vermelho.svg" />

        <div class="icons">
          
            <input type="checkbox" role="button" aria-label="Display the menu" class="menu" />
        </div>
    </header>

    <main>

    <?php echo $mensagem; ?>
    <div class="form-cadastrarMaq">
        <form class="cadMaq" action="" method="post" enctype="multipart/form-data">
        <i class="fa fa-cogs" style="font-size:70px;color:rgb(255, 255, 255)"></i>


            <p class="categoria">Categoria da máquina:</p>

            <div class="teste">
                <div class="box">
                    <select name="tipo_maquina">
                        <?php
                            // Recuperar tipos de máquinas para exibir no formulário
                            $result = $mysqli->query("SELECT id, tipo FROM tipo_maquina");
                            $tipos_maquinas = $result->fetch_all(MYSQLI_ASSOC);

                            foreach ($tipos_maquinas as $tipo_maquina) {
                                echo '<option value="' . $tipo_maquina['id'] . '">' . $tipo_maquina['tipo'] . '</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="inputs-cadastrar-maquina">
                <div class="input_modal">
                    <input type="text" name="id_maquina"  placeholder="ID"  required>
                </div>
              
                <div class="input_modal">
                    <input type="text" name="modelo" placeholder="Modelo" required>
                </div>
                <div class="input_modal">   
                    <input type="text" name="fabricante" placeholder="Fabricante" required>
                </div>     
            </div>

            <section id="section_input_img">
                        <div id="div_img_input">
                            <div class="form-upload">
                                <label class="input-personalizado">
                                    <span class="botao-selecionar">Selecione uma imagem</span>
                                    <img class="imagem" />
                                    <input type="file" name="imagem" class="input-file" accept="image/*">
                                </label>
                            
                            </div>
                        </div>
            </section>
                    <div class="opcional">
                        <p>(Opcional)</p>
                    </div>
                    <div class="cadastrar-maquina">  
                        <input type="submit" value="Cadastrar">
                    </div>   
                
        </form>
    </div>   

    <div class="addCategoria">
       <div class="conteudo">
       
        <i class="fa fa-plus-circle" style="font-size:20px"></i> 
            <form action="cadastrarTipoMaquina.php" method="get">
                <input type="submit" value="Cadastrar nova Categoria">
            </form>
       </div>
       
    </div>
    </main>
    <script>
        const $ = document.querySelector.bind(document);

        const previewImg = $('.imagem');
        const fileChooser = $('.input-file');
    
        fileChooser.onchange = e => {
            const fileToUpload = e.target.files.item(0);
            const reader = new FileReader();
            reader.onload = e => previewImg.src = e.target.result;
            reader.readAsDataURL(fileToUpload);
        };
    </script>
    <script src="js/script.js"></script>
</body>
</html>
<?php
    if (filter_input(INPUT_GET, 'e')) {
        $mensagem_erro = filter_input(INPUT_GET, 'e');
        echo '<script>erroLogin('.$mensagem_erro.')</script>';
    }
?>