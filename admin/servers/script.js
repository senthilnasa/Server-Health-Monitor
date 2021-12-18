const serverListHead = $('#serverList > thead');
const serverListBody = $('#serverList > tbody');

$(document).ready(() => {
    $('.modal').modal();
    $('body').fadeIn(1000);
    $('.sidenav').sidenav();
    $('select').formSelect();
    $(':button').prop('disabled', true);
    $('.tooltipped').tooltip();
    servers();
});


function setTable(data) {
    $('#creat_button').removeAttr("disabled");
    serverListHead.fadeIn(750);
    let html = '';
    if (data.length == 0) {
        html += '<tr><td colspan="9" class="center">No Sever Deatils found</td></tr>';
        $(':button').prop('disabled', true);
    } else {
        let s = 1;
        data.forEach(f => {
            html += '<tr>';
            html += '<td>' + s + '</td>';
            html += '<td>' + f.server_name + '</td>';
            html += '<td><a href="' + f.ip + '" target="_blank">' + f.ip + '</a></td>';
            html += '<td>' + f.type + '</td>';
            html += '<td>' + f.last_offline + '</td>';
            html += '<td>' + f.last_online + '</td>';
            html += '<td>' + f.latency + '</td>';
            if (f.state) {
                html += '<td>Enabled</td>';
            } else {
                html += '<td>Disabled</td>';
            }
            html += '<td ><a class="waves-effect waves-light btn" href="../server/?id=' + f.server_id + '"><i class="material-icons right">settings</i>More</a></td>';
            html += '</tr>';
            s++;
        });
    }
    serverListBody.html(html);
    serverListBody.fadeIn(1000);
}

function servers() {
    let func = (list) => {
        setTable(list);
    }
    let err = () => {
        toast('try again later!');
    }
    ajax('/api/data/', {
        "fun": "server_list"
    }, func, err);
}

$("#edit_form").submit(function (e) {
    $('#edit_server').modal('close');
    e.preventDefault();
    var data = $("#edit_form").serialize()+'&fun=server_add';
    let func = (data) => {
        if (data === true) {
            serverListBody.fadeOut(50);
            toast('Updated Successfully');
            servers();
        } else {
            toast('Please Try again');
        }
    }
    let err = () => {
        toast('Please Contact the admin!');
    }

    ajax('/api/data/', data, func, err);
});

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