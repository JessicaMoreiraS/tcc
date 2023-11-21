<?php
include("conexao.php");

if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}

$mensagem = '';
$nova_peca_array = [];
$novo_atributo_array = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["id_maquina"])) {
        $id_maquina = $_POST["id_maquina"];
    } else {
        die("ID da máquina não fornecido.");
    }

    $modelo = $_POST["modelo"];
    $fabricante = $_POST["fabricante"];
    
    if ($_FILES["imagem"]["error"] == UPLOAD_ERR_OK) {
        $imagem = file_get_contents($_FILES["imagem"]["tmp_name"]);
    } else {
        $imagem = null;
    }

    // Inserir novo tipo de máquina na tabela maquina
    $stmt_maquina = $mysqli->prepare("INSERT INTO maquina (id_tipo_maquina, modelo, fabricante, imagem) VALUES (?, ?, ?, ?)");

    if (!$stmt_maquina) {
        die('Erro na preparação da query: ' . $mysqli->error);
    }

    $bind_result = $stmt_maquina->bind_param("sssb", $id_maquina, $modelo, $fabricante, $imagem);

    if (!$bind_result) {
        die('Erro ao vincular os parâmetros: ' . $stmt_maquina->error);
    }

    $stmt_maquina->send_long_data(3, $imagem);

    $execute_result = $stmt_maquina->execute();

    if (!$execute_result) {
        die('Erro ao executar a query: ' . $stmt_maquina->error);
    }

    if ($stmt_maquina->affected_rows > 0) {
        $id_tipo_maquina = $id_maquina;
        $stmt_maquina->close();

        if (!empty($_POST['cadastrar_nova_peca'])) {
            $quantidade_pecas = (int)$_POST['quantidade_pecas'];
    
            for ($i = 1; $i <= $quantidade_pecas; $i++) {
                $nova_peca = $_POST['nova_peca'][$i];
    
                if (!empty($nova_peca['codigo']) && !empty($nova_peca['peca']) && !empty($nova_peca['tempo_de_troca'])) {
                    // Inserir novo tipo de peça na tabela peca_tipo
                    $stmt_nova_peca = $mysqli->prepare("INSERT INTO peca_tipo (codigo, peca, tempo_de_troca) VALUES (?, ?, ?)");
                    
                    if (!$stmt_nova_peca) {
                        die('Erro na preparação da query para nova peça: ' . $mysqli->error);
                    }
    
                    $bind_result_nova_peca = $stmt_nova_peca->bind_param("sss", $nova_peca['codigo'], $nova_peca['peca'], $nova_peca['tempo_de_troca']);
    
                    if (!$bind_result_nova_peca) {
                        die('Erro ao vincular os parâmetros para nova peça: ' . $stmt_nova_peca->error);
                    }
    
                    $stmt_nova_peca->execute();
                    $stmt_nova_peca->close();
                }
            }
        }
    
        // Verificar se há novos atributos sendo adicionados
        if (!empty($_POST['cadastrar_novo_atributo'])) {
            $quantidade_atributos = (int)$_POST['quantidade_atributos'];
    
            for ($i = 1; $i <= $quantidade_atributos; $i++) {
                $novo_atributo = $_POST['novo_atributo'][$i];
    
                if (!empty($novo_atributo['atributo']) && !empty($novo_atributo['atributo_esp'])) {
                    // Inserir novo tipo de atributo na tabela atributo_tipo
                    $stmt_novo_atributo = $mysqli->prepare("INSERT INTO atributo_tipo (atributo, atributo_esp) VALUES (?, ?)");
    
                    if (!$stmt_novo_atributo) {
                        die('Erro na preparação da query para novo atributo: ' . $mysqli->error);
                    }
    
                    $bind_result_novo_atributo = $stmt_novo_atributo->bind_param("ss", $novo_atributo['atributo'], $novo_atributo['atributo_esp']);
    
                    if (!$bind_result_novo_atributo) {
                        die('Erro ao vincular os parâmetros para novo atributo: ' . $stmt_novo_atributo->error);
                    }
    
                    $stmt_novo_atributo->execute();
                    $stmt_novo_atributo->close();
                }
            }
        }
    } else {
        $mensagem = "Erro ao cadastrar novo tipo de máquina. Verifique se todos os campos estão preenchidos corretamente.";
        $mensagem .= " Erro MySQL: " . $stmt_maquina->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Cadastrar Novo Tipo de Máquina</title>
</head>
<body>
    <h2>Cadastrar Novo Tipo de Máquina</h2>

    <?php echo $mensagem; ?>

    <form action="" method="post" enctype="multipart/form-data">
        ID da Máquina: <input type="text" name="id_maquina" required><br>
        Modelo: <input type="text" name="modelo" required><br>
        Fabricante: <input type="text" name="fabricante" required><br>
        Imagem: <input type="file" name="imagem" required><br>

        <h3>Selecionar Peças:</h3>
        <?php
            // Recuperar peças da tabela peca_tipo
            $result_peca = $mysqli->query("SELECT id, codigo, peca FROM peca_tipo");
            $pecas = $result_peca->fetch_all(MYSQLI_ASSOC);

            foreach ($pecas as $peca) {
                echo '<label><input type="checkbox" name="pecas[]" value="' . $peca['id'] . '"> ' . $peca['peca'] . '</label><br>';
            }
        ?>
        <label><input type="checkbox" name="cadastrar_nova_peca" id="cadastrar_nova_peca"> Cadastrar Nova Peça</label><br>

<div id="nova_peca_container" style="display:none;">
    Quantidade de Peças: <input type="number" name="quantidade_pecas" id="quantidade_pecas" min="1"><br>

    <?php for ($i = 1; $i <= 3; $i++) : ?>
        <h4>Nova Peça <?php echo $i; ?>:</h4>
        Código: <input type="text" name="nova_peca[<?php echo $i; ?>][codigo]">
        Peça: <input type="text" name="nova_peca[<?php echo $i; ?>][peca]">
        Tempo de Troca: <input type="text" name="nova_peca[<?php echo $i; ?>][tempo_de_troca]"><br>
    <?php endfor; ?>
</div>

        <h3>Selecionar Atributos:</h3>
        <?php
            // Recuperar atributos da tabela atributo_tipo
            $result_atributo = $mysqli->query("SELECT id, atributo FROM atributo_tipo");
            $atributos = $result_atributo->fetch_all(MYSQLI_ASSOC);

            foreach ($atributos as $atributo) {
                echo '<label><input type="checkbox" name="atributos[]" value="' . $atributo['id'] . '"> ' . $atributo['atributo'] . '</label><br>';
            }
        ?>
        
        <label><input type="checkbox" name="cadastrar_novo_atributo" id="cadastrar_novo_atributo"> Cadastrar Novo Atributo</label><br>

        <div id="novo_atributo_container" style="display:none;">
            Quantidade de Atributos: <input type="number" name="quantidade_atributos" id="quantidade_atributos" min="1"><br>

            <?php for ($i = 1; $i <= 3; $i++) : ?>
                <h4>Novo Atributo <?php echo $i; ?>:</h4>
                Atributo: <input type="text" name="novo_atributo[<?php echo $i; ?>][atributo]">
                Atributo Especial: <input type="text" name="novo_atributo[<?php echo $i; ?>][atributo_esp]"><br>
            <?php endfor; ?>
        </div>

        <input type="submit" value="Cadastrar Novo Tipo de Máquina">
    </form>

    <script>
        document.getElementById('cadastrar_nova_peca').addEventListener('change', function () {
            document.getElementById('nova_peca_container').style.display = this.checked ? 'block' : 'none';
        });

        document.getElementById('cadastrar_novo_atributo').addEventListener('change', function () {
            document.getElementById('novo_atributo_container').style.display = this.checked ? 'block' : 'none';
        });
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