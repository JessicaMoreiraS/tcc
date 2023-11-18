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
    elementoPai.appendChild(titulo);


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
    elementoPai.appendChild(titulo);


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

function fluido(valorGrafico, nomeDiv, idMaquina){
    // Configurações do gráfico
    var raio = 100;
    var dados = [25, 50, 75, 100];

    // Criação do gráfico
    var svg = d3.select("#graficoCirculo")
        .append("svg")
        .attr("width", raio * 2)
        .attr("height", raio * 2)
        .append("g")
        .attr("transform", "translate(" + raio + "," + raio + ")");

    // Criação do círculo
    var circulo = svg.selectAll("circle")
        .data(dados)
        .enter()
        .append("circle")
        .attr("r", function(d) { return d; })
        .attr("fill-opacity", 0.5);

    // Adiciona efeito de transição para o fluido
    circulo.transition()
        .duration(1000)
        .attr("fill-opacity", 1);
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
        var idCheckbox = arrNomeValor[2]+"Check";
        var valorDeReferencia = arrNomeValor[3];

        verificarCheckbox(idCheckbox, novoValor, valorDeReferencia)
        // Recupere a referência para o objeto axisDataItem
        // console.log(nomeDiv);
        // console.log(novoValor);
        if(nomeDiv == "velocidade" || nomeDiv == "vibracao"){
            atualizaVelocimetro(nomeDiv, novoValor);
        }else if(nomeDiv == "temperatura"){
            atualizaTemometro(nomeDiv, novoValor, valorDeReferencia);
        }
    });
}

function atualizaVelocimetro(nomeDiv, novoValor){
    var axisDataItem = window[nomeDiv + "_axisDataItem"];
        
    // Verifique se a referência existe
    if (axisDataItem) {
        // Atualize dinamicamente o valor do gráfico
        axisDataItem.animate({
            key: 'value',
            to: novoValor,
            duration: 800,
            easing: am5.ease.out(am5.ease.cubic)
        });
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






