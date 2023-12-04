let l1;
let l2;
let historyShort;
$('#minute').val(hourTime);
$('#hour').val(dayTime);
$('#day').val(weekTime);
$('#day1').val(weeksTime);
$('#week').val(monthTime);
$('#month').val(yearTime);

var parts = window.location.search.substr(1).split("&");
var $_GET = {};
for (var i = 0; i < parts.length; i++) {
    var temp = parts[i].split("=");
    $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
}

function delServer(){
    if(confirm("Are you sure you want  delete the sever ?")){
        if(confirm("Once deleted  data can't retrived ?")){

            der();
        }
        else{

            toast('Delete canceled !')

        }
    }
    else{
        toast('Delete canceled !')
    }
}

function der(){
    let data = {
        'fun': 'server_delete',
        'sid': $_GET['id'],
    };
    let func = (data) => {
        if (data === true) {  
            toast('Updated Successfully');
            window.location.href = '../servers/';
        }
        else{
            toast('Please Try again');
        }
    }
    let err = () => {
        toast('Please Contact the admin!');
    }
    ajax('/api/data/', data, func, err);
}


function showmod(a,b){
    $('#show_err').modal();
    $('#show_err').modal('open'); 
    $('#mod_hea').html(a);
    $('#mod_bod').html(b);
}

$("#edit_form").submit(function (e) {
    $('#edit_server').modal('close');
    e.preventDefault();
    var data = $("#edit_form").serialize()+'&fun=server_edit'+'&sid='+$_GET['id'];
    let func = (data) => {
        if (data === true) {
            // serverListBody.fadeOut(50);
            toast('Updated Successfully');
            server_deatils();
            live_report();
        } else {
            toast('Please Try again');
        }
    }
    let err = () => {
        toast('Please Contact the admin!');
    }

    ajax('/api/data/', data, func, err);
});


$(document).ready(() => {
    $('.modal').modal();
    $('.sidenav').sidenav();
    $(':button').prop('disabled', true);
    $('.tooltipped').tooltip();
    server_deatils();
    live_report();
});


function set_page(list) {
    $('[name=server_name]').val(list.server_name);
    $('[name=url]').val(list.ip);
    $("input[name*='header_name']" ).val(list.header_name);
    $("input[name*='header_value']" ).val(list.header_value);
    $("input[name*='port']" ).val(list.port);
    $("input[name*='post_field']" ).val(list.post_field);
    $("input[name*='ssl']" ).val(list.ssl);
    $("input[name*='threshold']" ).val(list.threshold);
    $("input[name*='time_out']" ).val(list.time_out);
    $("input[name*='user_name']" ).val(list.user_name);
    $("input[name*='user_pass']" ).val(list.user_pass);
    
    $('select[name^="redirect_type"] option[value="'+list.redirect_type+'"]').attr("selected","selected").change();
    $('select[name^="method"] option[value="'+list.method+'"]').attr("selected","selected").change();
    $('select[name^="type"] option[value="'+list.type+'"]').attr("selected","selected").change();
    $('select[name^="state"] option[value="'+list.state+'"]').attr("selected","selected").change();
    $('select[name^="email"] option[value="'+list.email+'"]').attr("selected","selected").change();
    $('select[name^="telegram"] option[value="'+list.telegram+'"]').attr("selected","selected").change();

    $('select').formSelect();

    $('#server_title1').html('Details of ' + list.server_name);
    $('#server_title2').html(list.server_name);
    $('#server_url').attr("href", list.url);
    $('#server_url').html(list.url.substr(0, 12) + ' ..');
    $('#server_live').html('Offline');
    $('#server_state').html('<i class="material-icons">close</i>');
    $('#server_type').html(list.type);
    $('#ping_last_online').html(list.last_online);
    $('#ping_last_offline').html(list.last_offline);
    $('#ping_last_check').html(list.updated_at);
    $('#ping_check').html('Disabled');
    $('#server_type').html(list.type);
    $('#server_state').addClass('green');
    if (list.live) {
        $('#server_state').html('<i class="material-icons">check</i>');
        $('#server_live').html('Online');
    }
    if (list.state) {
        $('#ping_check').html('Enabled');
    }
    $('#email_not').html('<i class="material-icons">notification_important</i>');
    if (list.email) {
        $('#email_not').html('<i class="material-icons">campaign</i>');
    }
    $('#tel_not').html('<i class="material-icons">notification_important</i>');
    if (list.telegram) {
        $('#tel_not').html('<i class="material-icons">campaign</i>');
    }
    $('#server_lat').html(list.latency);

    $('#last_out').attr("href", "javascript:showmod('Last Response', `"+list.last_error+"`);");
    $('#last_e_out').attr("href", "javascript:showmod('Last Error Response', `"+list.last_output+"`);");
    $('#last_p_out').attr("href", "javascript:showmod('Last Posstive Response', `"+list.last_posstive+"`);");
    $('body').fadeIn(1000);
    server_chart_live1();
    $('#creat_button').removeAttr("disabled");
}


function server_deatils() {
    let data = {
        'fun': 'server_details',
        'sid': $_GET['id'],
    };
    let func = (data) => {
        list=data[0];
        if (list == null) {
            toast('Unable find the server Details');
        } else {
            console.log(list);
            set_page(list);
        }
    }
    let err = () => {
        toast('Unable find the server Details');
    }
    ajax('/api/data/', data, func, err);
}

function server_chart_live1() {
    let data = {
        'fun': 'server_off',
        'sid': $_GET['id'],
    };
    let func = (list) => {
        l1 = list;
        server_chart_live2();
    }
    let err = () => {
        toast('Unable find the server Details');
    }
    ajax('/api/data/', data, func, err);
}

function server_chart_live2() {
    let data = {
        'fun': 'server_on',
        'sid': $_GET['id'],
    };
    let func = (list) => {
        l2 = list;
        server_latency();
        live_chart();
    }
    let err = () => {
        toast('Unable find the server Details');
    }
    ajax('/api/data/', data, func, err);
}




function generate_pie(online, offline) {
    console.log(offline,online);
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
                text: 'Report of online & Offline'
            },
            responsive: true,
            maintainAspectRatio: true,
        }
    });


}

function live_report() {
    let func = (datas) => {
        data=datas[0];
        console.log()
        generate_pie(data.z, data.y);
    }
    let data = {
        'fun': 'server_report',
        'sid': $_GET['id'],
    };
    let err = () => {
        toast('try again later!');
    }
    ajax('/api/data/', data, func, err);
}
let a;

function server_latency() {
    let data = {
        'fun': 'server_latency',
        'sid': $_GET['id'],
    };
    let func = (list) => {
        console.log(list);
        latency_chart(list);
    }
    let err = () => {
        toast('Unable find the server Details');
    }
    ajax('/api/data/', data, func, err);
}

var colors = {
    latency_min: 'rgb(255, 99, 132)',
    latency_avg: 'rgb(54, 162, 235)',
    latency_max: 'rgb(255, 205, 86)'
}
var historyLong;
async function latency_chart(list) {

    historyLong = await new Chart(document.getElementById("history_long").getContext('2d'), {
        type: 'bar',
        data: {
            datasets: [{
                    data: list.x,
                    label: 'Latency (minimum)',
                    backgroundColor: colors['latency_min'],
                    borderColor: colors['latency_min'],
                },

                {
                    data: list.y,
                    label: 'Latency (maximum)',
                    backgroundColor: colors['latency_max'],
                    borderColor: colors['latency_max'],
                },
                {
                    data: list.z,
                    label: 'Latency (average)',
                    backgroundColor: colors['latency_avg'],
                    borderColor: colors['latency_avg'],
                },
            ]
        },
        options: {
            animation: {
                easing: 'easeInOutExpo',
            },
            tooltips: {
                mode: 'index'
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
                        unit: 'week',
                        minUnit: 'day',
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

    $('input[name=timeframe_short]').change(function() {
        updateScale(historyShort, parseInt($('input[name=timeframe_short]:checked').val()), $('input[name=timeframe_short]:checked')[0].id);
    });
    updateScale(historyLong, parseInt($('input[name=timeframe_long]:checked').val()), $('input[name=timeframe_long]:checked')[0].class);
    $('input[name=timeframe_long]').change(function() {
        updateScale(historyLong, parseInt($('input[name=timeframe_long]:checked').val()), $('input[name=timeframe_long]:checked')[0].class);
    });

}


async function live_chart() {
    historyShort = await new Chart(document.getElementById("history_short").getContext('2d'), {
        type: 'line',
        data: {
            datasets: [{
                    data: l1,
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
                    data: l2,
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
}

function updateScale(chart, min, unit) {
    chart.options.scales.xAxes[0].time.min = min;
    chart.options.scales.xAxes[0].time.unit = unit;
    chart.update(0);
}

//Edit

function ser_typ() {
    let typ = $("select[name=type]").val();
    console.log(typ);

    if (typ == 'ping') {

        return ping_type();

    }
    if (typ == 'service') {
        return service_type();

    }
    if (typ == 'website') {

        return website_type();
    }

}

function en(a) {
    var nodes = document.getElementById(a).getElementsByTagName('*');
    for (var i = 0; i < nodes.length; i++) {
        nodes[i].disabled = false;
    }
}

function di(a) {
    var nodes = document.getElementById(a).getElementsByTagName('*');
    for (var i = 0; i < nodes.length; i++) {
        nodes[i].disabled = true;
    }
}

function ping_type() {
    di('web1');
    di('port');
    $('#port').fadeOut(500);
    $('#web1').fadeOut(500);
}
ping_type();

function service_type() {
    en("port");
    di("web1");
    $('#web1').fadeOut(500);
    $('#port').fadeIn(500);
}


function website_type() {
    en("web1");
    di("port");
    $('#port').fadeOut(500);
    $('#web1').fadeIn(500);
}