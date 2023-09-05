async function caixaSenhaGestao(){
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
function erroLogin(n){
    switch(n){
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
    }
}
