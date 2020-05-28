function CreateData(time, depth, temperature){
    this.x = time;
    this.y = depth;
    this.temperature = temperature;
}

csvData.forEach(d => {
    let value = d.split(';');
    time = parseFloat(value[0]);//x
    depth = parseFloat(value[1]);//y
    temperature = parseFloat(value[2]) - 273.15;// from K to C°
    data.push(new CreateData(time, depth, temperature.toFixed(2)))
}),

Highcharts.chart('container', {
    title: {
        text: 'Graphique de la plongée'
    },
    subtitle: {
        text: 'vertical: profondeur, horizontal: temps'
    },
legend: {
    layout: 'vertical',
    align: 'right',
    verticalAlign: 'middle'
},
yAxis: {
    reversed: true,
    title: {
        enabled: true,
        text: 'Profondeur'
    },
    labels: {
        format: '{value} m'
    },
},
xAxis: {
    title: {
        enabled: true,
        text: 'Temps'
    },
    labels: {
        format: '{value} s'
    },
},

plotOptions: {
    series: {
        label: {
            connectorAllowed: false
        },
        pointStart: 0
    }
},

series: [{
    name: 'Plongée',
    data: data
}],

responsive: {
    rules: [{
        condition: {
            maxWidth: 500
        },
        chartOptions: {
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom'
            }
        }
    }]
},

tooltip: {
         formatter: function(){
             var s = 'Temp de plongée: ' + sec2time(this.point.x) + '<br/>';
             s += 'Profondeur: ' + this.point.y + ' m<br/>';
             s += 'Température: ' + this.point.temperature + '°C';
             return s;
         },
     },

});

function sec2time(timeInSeconds) {
    var pad = function(num, size) { return ('000' + num).slice(size * -1); },
    time = parseFloat(timeInSeconds).toFixed(3),
    hours = Math.floor(time / 60 / 60),
    minutes = Math.floor(time / 60) % 60,
    seconds = Math.floor(time - minutes * 60);

    return pad(hours, 2) + ':' + pad(minutes, 2) + ':' + pad(seconds, 2);
}