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
            Swal.fire("Erro ao reincontrar conta");
            break;
        case 3:
            Swal.fire("Erro ao criar conta");
            break;
        case 4:
            Swal.fire("Esse email já está cadastrado");
            break;
        case 5:
            Swal.fire("usuario ou senha invalidos")
            break;
    }
}
