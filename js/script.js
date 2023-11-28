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
            Swal.fire("Erro ao cadastrar");
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
        case 10:
            Swal.fire("As senhas não coincidem")
            break;
        case 11:
            Swal.fire("Essa maquina já está cadastrada")
            break;
        case 12:
            Swal.fire("Operação bem sucedida")
            break;
    }
}

//SCRIPTS DO HOME PROFESSOR//

//front-end do botao de criar turma
if(window.location.href.indexOf("homeProfessor") != -1){
    var from_criar_sala = document.getElementById("section_form");
    var mostrarBotao = document.getElementById("criar_turma");
    var ocultarBotao = document.getElementById("cancelar_button");
    mostrarBotao.addEventListener("click", function() {
        from_criar_sala.style.display = "block"; 
    });
    ocultarBotao.addEventListener("click", function() {
        from_criar_sala.style.display = "none"; 
    });
}
////////

//TRECHO DO CODIGO QUE LIBERA A CRIAÇÃO DE SALA, HOME PROFESSOR
document.addEventListener("DOMContentLoaded", function() {
    // aplicando os inputs qye precisam ser preechidos em variaveis
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const cadastrarSalaButton = document.querySelector('#submitCadastrarSala');

    //  ouvinte de eventos de mudança a todos os checkboxes
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', verificarFormulario);
    });


    // Funçao que verificar o formulario(para habiliyar ou na a criação)
    function verificarFormulario() {
        // Veridica se pelo menos um checkbox esta selecionado e o codigo esta preenchido
        const checkboxSelecionado = Array.from(checkboxes).some(checkbox => checkbox.checked);
        // Habilita ou desabilita a criaçao
        if (checkboxSelecionado) {
            cadastrarSalaButton.removeAttribute('disabled');
        } else {
            cadastrarSalaButton.setAttribute('disabled', 'disabled');
        }
    }
});
//////////////////////////////////////////////////

//script editar perfil
const edit_button = document.getElementById("imgEditIcon");
const cancel_button = document.getElementById("botaoCancelar");
function liberarEdicaoPerfil() {
  const form_perfil = document.getElementById("form_perfil");
  const inputs = form_perfil.querySelectorAll("input");
  cancel_button.style.display = 'block';  
  inputs.forEach(function (input) {
    input.removeAttribute('readonly');
    if(input.placeholder == 'Nome' || input.placeholder == 'Turma'){
        input.focus();
    }
  });
}
function cancelarEdicaoPerfil(){
    location.reload()
}

////

/*Cadastrar Novo Tipo de Maquina */
var contadorAtributo = 0;
var contadorPeca = 0;
var contadorItem = 0;

function criarInputs(tipo){
    var div = document.getElementById(`novo${tipo}`);
    var conteudo="";
    if(tipo=="Atributo" && contadorAtributo<5){
        contadorAtributo++;
        conteudo = `<div>
                        <input type="text" name="atributo${contadorAtributo}" placeholder="Atributo">
                        <input type="text" name="vReferencia${contadorAtributo}" placeholder="Valor de referência">
                    </div>`
    }
    if(tipo=="Peca" && contadorPeca<5){
        contadorPeca++;
        conteudo = `<div>
                        <div class="input_modal">
                            <input class="inputNomePeca" type="text" name="peca${contadorPeca}" placeholder="Nome da peça" >
                        </div> 
                          
                        <div class="input_modal">
                             <input  class="inputCodPeca" type="text" name="codigo${contadorPeca}" placeholder="Código da peça">
                        </div>
                          
                        <div class="input_modal">
                            <input  class="inputTrocaPeca" type="number" name="tempoTroca${contadorPeca}" placeholder="Tempo de troca em horas" min="1">
                        </div>    
                    </div>`
    }
    if(tipo=="Item" &&  contadorItem<5){
        contadorItem++;
        conteudo = `<div>
                        <input type="text" name="item${contadorItem}" placeholder="Item para checklist manual">
                    </div>`
    }

    div.innerHTML+=conteudo;
}

