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

function temometro(valor, idMaquina){
    window['idMaquina'] = idMaquina;
    var elementoPai = document.getElementById("graficos")
    
    var divCarGraficos = document.createElement("div");
    divCarGraficos.className = "card-graficos";
    var idCardGraficos = "temometrocard";
    divCarGraficos.id = idCardGraficos;
    elementoPai.appendChild(divCarGraficos);

    var titulo = document.createElement("p");
    titulo.innerHTML = "termometro";
    elementoPai.appendChild(titulo);


    var paiDoGrafico = document.getElementById(idCardGraficos)
    var novaDiv = document.createElement("div");

    // console.log(valor)
    var elementoPai = document.getElementById("graficos");
    var porcentagem = (valor/200)*100;
    
    // console.log(porcentagem);
    var novaDiv = `<div id="temometro">
    <div id="valorTemometro" style="height:${porcentagem}px"></div>
    <div class="rotulos">
        <span>25</span>
        <span>50</span>
        <span>75</span>
    </div>
    </div>`;

    paiDoGrafico.innerHTML += novaDiv;
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
            atualizaTemometro(nomeDiv, novoValor);
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

function atualizaTemometro(nomeDiv, novoValor){
    var porcentagem = (novoValor/200)*100;
    var valorTemometro = document.getElementById("valorTemometro")
    valorTemometro.style.height = porcentagem+"px";
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






