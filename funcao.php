<?php
//PAGINA DE FUNCOES, QUE SERAO CHAMADAS POR ALGUM ELEMENTO HTML




//fUNCAO DE GERAR O CODIGO DA TURMA
function gerarCodico($lenght){
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVabcdefghijklmnopqrstuv1234567890';
    $codigo;
    for($i=0; $i<$lenght;$i++){
      $index_aleatorio = rand(0, $length - 1); 
      $caracter_aleatorio = $caracteres[$index_aleatorio];
      $codigo .= $caracter_aleatorio;
    }
    echo $codigo;
}   
//FUNCAO DE GERAR O CODIGO DA TURMA - verificao para nao gerar um codigo ja existente no banco


?>