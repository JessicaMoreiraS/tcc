function graficoVelocimetro(valorGrafico, nomeDiv, idMaquina){
    window['idMaquina'] = idMaquina;
    var elementoPai = document.getElementById("graficos")
    
    var divCarGraficos = document.createElement("div");
    divCarGraficos.className = "card-graficos";
    var idCardGraficos = nomeDiv+"card";
    divCarGraficos.id = idCardGraficos;
    elementoPai.appendChild(divCarGraficos);
    
    var titulo = document.createElement("p");
    titulo.innerHTML = nomeDiv;
    document.getElementById(idCardGraficos).appendChild(titulo)
    // elementoPai.appendChild(titulo);


    var paiDoGrafico = document.getElementById(idCardGraficos)
    var novaDiv = document.createElement("div");
    novaDiv.id = nomeDiv;
    novaDiv.className = "grafico";
    // console.log(nomeDiv);
    paiDoGrafico.appendChild(novaDiv);

    am5.ready(function() {
    
        var root = am5.Root.new(nomeDiv);
        
        root.setThemes([
            am5themes_Animated.new(root)
        ]);
        
        var chart = root.container.children.push(
            am5radar.RadarChart.new(root, {
                panX: false,
                panY: false,
                startAngle: 180,
                endAngle: 360
            })
        );
        
        var axisRenderer = am5radar.AxisRendererCircular.new(root, {
            innerRadius: -10,
            strokeOpacity: 1,
            strokeWidth: 15,
            strokeGradient: am5.LinearGradient.new(root, {
                rotation: 0,
                stops: [
                    { color: am5.color(0x19d228) },
                    { color: am5.color(0xf4fb16) },
                    { color: am5.color(0xf6d32b) },
                    { color: am5.color(0xfb7116) }
                ]
            })
        });
        
        var xAxis = chart.xAxes.push(
            am5xy.ValueAxis.new(root, {
                maxDeviation: 0,
                min: 0,
                max: 100,
                strictMinMax: true,
                renderer: axisRenderer
            })
        );
                
        var axisDataItem = xAxis.makeDataItem({});
        axisDataItem.set("value", 0);
                
        var bullet = axisDataItem.set("bullet", am5xy.AxisBullet.new(root, {
            sprite: am5radar.ClockHand.new(root, {
                radius: am5.percent(99)
                })
        }));
                
        xAxis.createAxisRange(axisDataItem);
                
        axisDataItem.get("grid").set("visible", false);
                
        chart.appear(1000, 100);
        
        // console.log(valorGrafico)
        // setInterval(() => {
            axisDataItem.animate({
            key: 'value',
            to: valorGrafico,
            duration: 800,
            easing: am5.ease.out(am5.ease.cubic)
            });
        // }, 2000);
    
        window[nomeDiv + "_axisDataItem"] = axisDataItem;
    }); // end am5.ready()  

}

function temometro(valor, idMaquina, valorDeReferencia){
    window['idMaquina'] = idMaquina;
    var elementoPai = document.getElementById("graficos")
    
    var divCarGraficos = document.createElement("div");
    divCarGraficos.className = "card-graficos";
    var idCardGraficos = "temometrocard";
    divCarGraficos.id = idCardGraficos;
    elementoPai.appendChild(divCarGraficos);

    var titulo = document.createElement("p");
    titulo.innerHTML = "Temperatura";
    document.getElementById(idCardGraficos).appendChild(titulo);


    var paiDoGrafico = document.getElementById(idCardGraficos)
    paiDoGrafico.style = "display:flex; flex-direction:row; align-items: center; justify-content: space-around;"
    // var novaDiv = document.createElement("div");

    // console.log(valor)
    var elementoPai = document.getElementById("graficos");
    // var porcentagem = (valor/200)*100;
    var porcentagem = (valor*100)/valorDeReferencia;
    
    // console.log(porcentagem);
    var novaDiv = `<div id="temometro">
    <div id="valorTemometro" style="height:${porcentagem}%"></div>
    <div class="rotulos">
        <span>75%</span>
        <span>50%</span>
        <span>25%</span>
    </div>
    </div>
    <div class="valorTemp">
        <p id="valorTemp">${valor}°C</p>
    </div>`;

    paiDoGrafico.innerHTML += novaDiv;
}

function graficoFluidos(valorGrafico, nomeDiv, tema, idMaquina, valorReferencia){ 
    var iValorGrafico=parseFloat(valorGrafico);
    var iValorReferencia=parseFloat(valorReferencia);
    porcentagemValorGrafico = (iValorGrafico*100)/iValorReferencia;
    var topoFluido = (100-22)-porcentagemValorGrafico;

    var elementoPaiDosGraficos = document.getElementById("graficos")
    var divCarGraficos = document.createElement("div");
    divCarGraficos.className = "card-graficos";
    var idCardGraficos = nomeDiv+"card";
    divCarGraficos.id = idCardGraficos;
    elementoPaiDosGraficos.appendChild(divCarGraficos);

    var titulo = document.createElement("p");
    titulo.innerHTML = tema;
    document.getElementById(idCardGraficos).appendChild(titulo);

    var elementoPai = document.getElementById(idCardGraficos); 


    var containetCirculo = `<div class="containerCirculo">
                                <div class="wrapper" id="${nomeDiv}Wrapper" style="margin-top:${topoFluido}%;">
                                    <svg class="waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                                        <path class="wave1" fill="#e3a900" fill-opacity="1" d="M0,288L48,272C96,256,192,224,288,197.3C384,171,480,149,576,165.3C672,181,768,235,864,250.7C960,267,1056,245,1152,250.7C1248,256,1344,288,1392,304L1440,320L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                                        <path class="wave2" fill="#e3a900" fill-opacity="1" d="M0,288L60,288C120,288,240,288,360,256C480,224,600,160,720,138.7C840,117,960,139,1080,176C1200,213,1320,267,1380,293.3L1440,320L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path>
                                    </svg>
                                </div>
                                <div class="preenchimentoCirculo" id="${nomeDiv}" style="height:${porcentagemValorGrafico}%"></div>
                            </div>`; 
    var dadosConteudo=`<div>
                            <p>Nível de óleo em: ${parseInt(porcentagemValorGrafico)}%</p>
                        </div>`;
    
    elementoPai.innerHTML+=containetCirculo; 
    elementoPai.innerHTML+=dadosConteudo; 
}  


function graficoViscodidadeFluidos(valorGrafico, nomeDiv, tema, idMaquina, valorReferencia){
    var iValorGrafico = parseFloat(valorGrafico)
    var iValorReferencia = parseFloat(valorReferencia)
    var valorViscosidade = (iValorGrafico*100)/iValorReferencia;
    var dadosConteudo=`<div id=${nomeDiv}>
                            <p>Nível de viscosidade óleo em: ${parseInt(valorViscosidade)}%</p>
                        </div>`;
    
    var arrNomeEVisco = nomeDiv;
    var arrNomeVisco = arrNomeEVisco.split('_');
    var posicaoNome = arrNomeVisco.length-1;
    var nomeCard = "oleo_"+arrNomeVisco[posicaoNome]+"card";
    
    document.getElementById(nomeCard).innerHTML += dadosConteudo;
}


function cpuUsage(){
    var idMaquina = window['idMaquina'];
    if(idMaquina != undefined){
        
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var retorno = xhttp.responseText;
                atualizaGrafico(retorno);
                //document.getElementById("cpu_usage").innerHTML = xhttp.responseText;
            }
        };
        xhttp.open("GET", `checklistReload.php?id_maquina=${idMaquina}`, true);
        xhttp.send();
    }
    //Repetir após 5 segundos
    setTimeout(function(){ cpuUsage(); }, 5000);
}

function atualizaGrafico(retorno) {
    var arrRetorno = retorno.split('.');

    arrRetorno.forEach(nomeEValor => {
        var arrNomeEValor = nomeEValor;
        var arrNomeValor = arrNomeEValor.split('-');
        
        var nomeDiv = arrNomeValor[0];
        var novoValor = arrNomeValor[1];
        var nomeDivEsp = arrNomeValor[2];
        var idCheckbox = arrNomeValor[2]+"Check";
        var valorDeReferencia = arrNomeValor[3];

        verificarCheckbox(idCheckbox, novoValor, valorDeReferencia)
        // Recupere a referência para o objeto axisDataItem
        // console.log(nomeDiv);
        // console.log(novoValor);
        if(nomeDiv == "velocidade" || nomeDiv == "vibracao"){
            atualizaVelocimetro(nomeDivEsp, novoValor);
        }else if(nomeDiv == "temperatura"){
            atualizaTemometro(nomeDivEsp, novoValor, valorDeReferencia);
        }else if(nomeDivEsp != undefined){
            if(nomeDivEsp.indexOf('oleo') != -1){
                AtualizaFluido(nomeDivEsp, novoValor, valorDeReferencia)
            }else if(nomeDivEsp.indexOf('viscosidade') != -1){
                atualizaViscodidadeFluidos(nomeDivEsp, novoValor, valorDeReferencia)
            }
        }
    });
}

function atualizaVelocimetro(nomeDiv, novoValor){
    var axisDataItem = window[nomeDiv + "_axisDataItem"];
        
    // Verifique se a referência existe
    if (axisDataItem) {

        var valorAtual = axisDataItem.get("value");
        var i = valorAtual
        if(valorAtual< novoValor){
            while(i< novoValor){
                i++;
                axisDataItem.animate({
                    key: "value",
                    // from: i,
                    to: i,
                    duration: 800,
                    easing: am5.ease.out(am5.ease.cubic)
                });
            }
        }else if(valorAtual> novoValor){
            while(i> novoValor){
                i--;
                axisDataItem.animate({
                    key: "value",
                    // from: i,
                    to: i,
                    duration: 800,
                    easing: am5.ease.out(am5.ease.cubic)
                });
            }
        }

    } else {
        console.error("Referência para axisDataItem não encontrada.");
    }
}



function atualizaTemometro(nomeDiv, novoValor, valorDeReferencia){
    var porcentagem = (novoValor*100)/valorDeReferencia;
    var valorTemometro = document.getElementById("valorTemometro")
    document.getElementById("valorTemp").innerHTML = novoValor+"°C";
    valorTemometro.style.height = porcentagem+"%";
}

function AtualizaFluido(nomeDiv, novoValor, valorDeReferencia){
    var preenchimento= document.getElementById(nomeDiv);
    var ondas = document.getElementById(nomeDiv+"Wrapper");
    var porcentagem = (novoValor*100)/valorDeReferencia;
    preenchimento.style.height = porcentagem+"%";
    var topoOndas = (100-22)-porcentagem;
    ondas.style = `margin-top:${topoOndas}%`;
}
function atualizaViscodidadeFluidos(nomeDiv, novoValor, valorDeReferencia){
    var iValorGrafico = parseFloat(novoValor)
    var iValorReferencia = parseFloat(valorDeReferencia)
    var valorViscosidade = (iValorGrafico*100)/iValorReferencia;
    var dadosConteudo=`<p>Nível de viscosidade óleo em: ${parseInt(valorViscosidade)}%</p>`;

    var divVisco = document.getElementById(nomeDiv)
    divVisco.innerHTML = dadosConteudo;
}

function verificarCheckbox(nome, valor, valorReferencia){
    if(nome != "" && nome != "undefinedCheck"){
        //console.log(nome);
        var fValor = parseFloat(valor);
        var fValorReferencia = parseFloat(valorReferencia);
        if(fValor >= fValorReferencia){
            document.getElementById(nome).checked = false;
            document.getElementById(nome).disabled = true;
        }else{
            document.getElementById(nome).disabled =  false;
            document.getElementById(nome).checked = true;
        }
    }
}

cpuUsage();

// atualizarVelocimetro('nomeDaDiv', 50);






