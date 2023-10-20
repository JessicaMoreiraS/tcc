function graficoVelocimetro(valorGrafico, nomeDiv){
    
    
    var elementoPai = document.getElementById("graficos")
    var novaDiv = document.createElement("div");
    novaDiv.id = nomeDiv;
    novaDiv.className = "grafico";
    // console.log(nomeDiv);
    elementoPai.appendChild(novaDiv);

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
        
        console.log(valorGrafico)
        setInterval(() => {
            axisDataItem.animate({
            key: 'value',
            to: valorGrafico,
            duration: 800,
            easing: am5.ease.out(am5.ease.cubic)
            });
        }, 2000);
    
    }); // end am5.ready()  

}

function temometro(valor){
    console.log(valor)
    var elementoPai = document.getElementById("graficos");
    var porcentagem = (valor/200)*100;
    
    console.log(porcentagem);
    var novaDiv = `<div id="temometro" onclick="mudarTemp()">
    <div id="valorTemometro" style="height:${porcentagem}px"></div>
    </div>`;
    elementoPai.innerHTML += novaDiv;

}