<?php
$tabelasBusca = [];
$camposBusca = [[]];
$camposTema = [];

if (filter_input(INPUT_GET, 'view')) {
  if ($_GET['view'] == 'professor') {
    $tabelasBusca = ['professor'];
    $camposBusca = [['nome', 'cpf', 'email']];
    $camposTema = ['Nome', 'CPF', 'Email'];


  } else if ($_GET['view'] == 'tipo') {
    $tabelasBusca = ['tipo', 'pecas'];
    $camposBusca = [['id','tipo'], ['id_tipo_maquina','codigo','peca']];
    $camposTema = ['Tipo', 'Peça', 'Código'];


  } else if ($_GET['view'] == 'aluno') {
    $tabelasBusca = ['aluno', 'lista_aluno_sala', 'sala'];
    $camposBusca = [['id', 'nome', 'email'], ['id_aluno', 'id_sala'], ['id', 'turma']];
    $camposTema = ['Nome', 'Email', 'Turma(s)'];


  } else if ($_GET['view'] == 'maquina') {
    $tabelasBusca = ['maquina', 'tipo'];
    $camposBusca = [['id_tipo_maquina', 'modelo', 'fabricante'], ['id', 'tipo']];
    $camposTema = ['ID', 'Modelo', 'Fabricante','Tipo'];


  } else if ($_GET['view'] == 'sala') {
    $tabelasBusca = ['sala', 'professor'];
    $camposBusca = [['id_professor','turma'], ['id','nome']];
    $camposTema = ['Turma', 'Professor'];


  } else if ($_GET['view'] == 'alunosSala') {
    if (filter_input(INPUT_GET, 'id_sala_view')) {
      $idSala = 'id_sala_view';
      $tabelasBusca = ['lista_aluno_sala', 'aluno', 'sala', 'professor'];
      $camposTema = ['Nome', 'Email', 'Sala(s)'];
    }


  } else if ($_GET['view'] == 'checklist') {
    $tabelasBusca = ['checklist', 'aluno', 'professor', 'maquina', 'tipo_maquina'];
    $camposBusca = [['id_aluno', 'id_professor', 'id_maquina','data_hora'], ['nome', 'email'], ['nome', 'email']];
    $camposTema = ['Nome', 'Email', 'Sala(s)'];
  } else {
    header('Location: home.html');
  }
  buscarDados($tabelasBusca, $camposBusca, $camposTema);
}

function buscarDados($tabelaBusca, $camposBusca, $camposTema) {
  if (count($tabelaBusca) > 0) {
    $query = "SELECT * FROM " . $tabelaBusca[0];
    for ($i = 1; $i < count($tabelaBusca); $i++) {
      $query .= " INNER JOIN " . $tabelaBusca[$i] . " ON " . $camposBusca[$i-1][$i-1] . " = " . $camposBusca[$i][0];
    }
    echo $query;
  }
}
?>
