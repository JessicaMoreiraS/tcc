<?php
session_start();
if ($_SESSION['idAcesso'] != 'gestaoSenai') {
    header('Location: index.html');
}
?>
<?php
//incluindo conexao cm o banco
include('conexao.php');



?>
