let historyShort;

$('#minute').val(hourTime);
$('#hour').val(dayTime);
$('#day').val(weekTime);
async function generate_chart(suc, fail) {
    console.log(fail);
    console.log(suc);

    historyShort = await new Chart(document.getElementById("history_short").getContext('2d'), {
        type: 'line',
        data: {
            datasets: [{
                    data: fail,
                    label: 'offline',
                    backgroundColor: '#dc3545',
                    borderColor: '#dc3545',
                    borderWidth: 2,
                    radius: 5,
                    pointStyle: 'crossRot',
                    fill: true,
                    spanGaps: false,
                },
                {
                    data: suc,
                    label: 'online',
                    fill: false,
                    spanGaps: false,
                    backgroundColor: '#28a745',
                    borderColor: '#28a745',
                    lineTension: 0,
                    steppedLine: true
                },
            ]
        },
        options: {
            title: {
                display: true,
                text: 'Report'
            },
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0.0
                    }
                }],
                xAxes: [{
                    type: 'time',
                    time: {
                        unit: 'minute',
                        minUnit: 'hour',
                        min: starttime,
                        max: endtime,
                    },
                    distribution: 'linear',
                    ticks: {
                        source: 'auto',
                    }
                }]
            },
            plugins: {
                zoom: {
                    pan: {
                        enabled: true,
                        mode: 'x',
                        rangeMax: {
                            x: new Date,
                        },
                    },
                    zoom: {
                        enabled: true,
                        mode: 'x',
                        rangeMax: {
                            x: new Date,
                        },
                        speed: 0.05,
                    }
                }
            }
        }
    });
    $('input[name=timeframe_short]').change(function () {
        updateScale(historyShort, parseInt($('input[name=timeframe_short]:checked').val()), $('input[name=timeframe_short]:checked')[0].id);
    });
}

function updateScale(chart, min, unit) {
    chart.options.scales.xAxes[0].time.min = min;
    chart.options.scales.xAxes[0].time.unit = unit;
    chart.update(0);
}
let ctx;

function generate_pie(online, offline) {
    ctx = document.getElementById("online_report").getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ["Online", "Offline"],
            datasets: [{
                data: [online, offline],
                borderColor: ['#2196f38c', '#f443368c'],
                backgroundColor: ['#2196f38c', '#f443368c'],
                borderWidth: 1
            }]
        },
        options: {
            title: {
                display: true,
                text: 'Live Report'
            },
            responsive: true,
            maintainAspectRatio: true,
        }
    });


}


function dashboard() {
    let func = (datas) => {
        data=datas[0];
        $('#d1').html(data.d1);
        $('#d2').html(data.d2);
        $('#d3').html(data.d3);
        $('#d4').html(data.d4);
        generate_pie(data.d2, data.d3);
    }
    let err = () => {
        toast('try again later!');
    }
    ajax('/api/data/', {
        "fun": "dashboard_data"
    }, func, err);
    chart1();
}
let online1;
let offine1;

function chart1() {
    let func = (data) => {
        online1 = data;
        chart2();
    }
    let err = () => {
        toast('try again later!');
    }
    ajax('/api/data/', {
        "fun": "dashboard_chart_online"
    }, func, err);
}
async function chart2() {
    let func = (data) => {
        offine1 = data;
        generate_chart(online1, offine1);
    }
    let err = () => {
        toast('try again later!');
    }
    ajax('/api/data/', {
        "fun": "dashboard_chart_offline"
    }, func, err);

}

$(document).ready(() => {
    dashboard();
    $('.container').hide();
    $('body').fadeIn(1000);
    $('#progress').fadeOut(1000);
    $('.sidenav').sidenav();
    $('.tooltipped').tooltip();
    $('.container').fadeIn(1000);
    $('.modal').modal();
});

function set_server_data(a) {
    $('#server_title').html('Offline server details');

    if (a == 1) {
        $('#server_title').html('Online server details');
    }

    let func = (data) => {
        let htm = '';

        // console.log(data.length);
        if (data.length == 0) {
            htm = '<tr><td colspan="4" class="center">No Offline Server found</td></tr>';
            
            if (a == 1) {
                 htm = '<tr><td colspan="4" class="center">No Online Sever  found</td></tr>';
            }
        
        } else {
            data.forEach(f => {
                let t = new Date(parseInt(f.tim));
                htm += '<tr>';
                htm += '<td>' + f.server_name + '</td>';
                htm += '<td>' + t.toLocaleString() + '</td>';
                htm += '<td><a class="waves-effect waves-light btn" href="../server/?id=' + f.server_id + '"><i class="material-icons right">settings</i>More</a></td>';
                htm += '</tr>';
            });
            dashboard();
        }
        $('#server_body').html(htm);
        $('#server_modal').modal('open');
    }
    let err = () => {
        toast('try again later!');
    }
    ajax('/api/data/', {"fun": "dashboard_live","typ":a}, func, err);
}