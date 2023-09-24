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

function gerarCodigo(tamanhoCodigo) {
    const caracteres = 'abcdefghijklmopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    let codigo;
    for (i = 0; i < tamanhoCodigo; i++) {
        var caractereAleatorio = Math.floor(Math.random() * caracteres.length);
        codigo += caracteres.charAt(caractereAleatorio);
    }
}

function gerarCodigoAcesso(length) {
    const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let codigo = '';

    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * caracteres.length);
        codigo += caracteres.charAt(randomIndex);
    }
    const inputCodigo = document.getElementById('codigoTurma').value = codigo
}

function exibirFormCriarSala() {
    document.querySelector('#form_cria_sala').style.opacity = '1';
}




