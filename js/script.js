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


//alertas de erro no login
function erroLogin(n) {
    switch (n) {
        case 1:
            Swal.fire("Senha invalida");
            break;
        case 2:
            Swal.fire("A conta não está apta para verificação");
            break;
        case 3:
            Swal.fire("Erro ao cadastrar conta");
            break;
        case 4:
            Swal.fire("Esse email já está cadastrado");
            break;
        case 5:
            Swal.fire("usuario ou senha invalidos")
            break;
        case 6:
            Swal.fire("Email invalido")
            break;
        case 7:
            Swal.fire("Erro ao autenticar código")
            break;
        case 8:
            Swal.fire("Código incorreto")
            break;
        case 9:
            Swal.fire("Erro ao deletar")
            break;
    }
}

//SCRIPTS DO HOME PROFESSOR//

//função que gera um codigo de acesso para uma turma
function gerarCodigoAcesso(length) {
    const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let codigo = '';
    //for que percorre o tamanho do definido apra o codigo
    for (let i = 0; i < length; i++) {
        //funcao que transforma em inteiro, o indice gerado aleatoriamente. para escolher 1 caractere
        const randomIndex = Math.floor(Math.random() * caracteres.length);
        //adicionando os valores dos indices gerados a variavel codigo
        codigo += caracteres.charAt(randomIndex);
    }
    //aplicando o codigo gerado no input
    const inputCodigo = document.getElementById('codigoTurma').value = codigo
}


//exibir o formulario de criar sala
function exibirFormCriarSala() {
    document.querySelector('#form_cria_sala').style.opacity = '1';
}
function naoExibirFormCriarSala() {
    document.querySelector('#form_cria_sala').style.opacity = '0';
}


//TRECHO DO CODIGO QUE LIBERA A CRIAÇÃO DE SALA, HOME PROFESSOR
document.addEventListener("DOMContentLoaded", function() {
    // aplicando os inputs qye precisam ser preechidos em variaveis
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const codigoTurmaInput = document.querySelector('#codigoTurma');
    const cadastrarSalaButton = document.querySelector('#submitCadastrarSala');

    //  ouvinte de eventos de mudança a todos os checkboxes
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', verificarFormulario);
    });

    // ouvinte ao input  de codigo da turma
    codigoTurmaInput.addEventListener('input', verificarFormulario);

    // Funçao que verificar o formulario(para habiliyar ou na a criação)
    function verificarFormulario() {
        // Veridica se pelo menos um checkbox esta selecionado e o codigo esta preenchido
        const checkboxSelecionado = Array.from(checkboxes).some(checkbox => checkbox.checked);
        const codigoTurmaPreenchido = codigoTurmaInput.value.trim() !== '';

        // Habilita ou desabilita a criaçao
        if (checkboxSelecionado && codigoTurmaPreenchido) {
            cadastrarSalaButton.removeAttribute('disabled');
        } else {
            cadastrarSalaButton.setAttribute('disabled', 'disabled');
        }
    }
});
//////////////////////////////////////////////////




