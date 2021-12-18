const serverListHead = $('#serverList > thead');
const serverListBody = $('#serverList > tbody');
let user_id;

$(document).ready(() => {
    $('body').fadeIn(1000);
    $('.sidenav').sidenav();
    $(':button').prop('disabled', true);
    $('.tooltipped').tooltip();
    users();
});



function setTable(fee, total) {
    serverListHead.fadeIn(750);
    let html = '';
    if (fee.length == 0 || total == 0) {
        html += '<tr><td colspan="9" class="center">No Sever Deatils found</td></tr>';
        $(':button').prop('disabled', true);
    } else {
        let s = 1;
        fee.forEach(f => {
            html += '<tr>';
            html += '<td>' + s + '</td>';
            html += '<td>' + f.name + '</td>';
            html += '<td>' + f.user_name + '</td>';
            html += '<td>' + f.telegram_id + '</td>';
            html += '<td>' + f.email + '</td>';
            html += '<td>' + f.created_at + '</td>';
            if (f.role) {
                html += '<td ><button onclick="edit(`' + f.name + '`,`' + f.email + '`,`' + f.telegram_id + '`,`' + f.user_id + '`)" class="waves-effect waves-light btn" ><i class="material-icons">create</i></button>&nbsp;<button class="waves-effect waves-light btn" style="background-color: red;" onclick="del(`' + f.name + '`,`' + f.user_id + '`)" ><i class="material-icons">delete_forever</i></button></td>';
            } else {
                html += '<td ><button onclick="edit(`' + f.name + '`,`' + f.email + '`,`' + f.telegram_id + '`,`' + f.user_id + '`)" class="waves-effect waves-light btn" ><i class="material-icons">create</i></button>&nbsp;<button class="waves-effect waves-light btn" disabled  ><i class="material-icons">https</i></button></td>';
            }
            html += '</tr>';
            s++;
        });
    }
    serverListBody.html(html);
    serverListBody.fadeIn(1000);
}

function users() {
    let func = (list) => {
        setTable(list);
    }
    let err = () => {
        toast('try again later!');
    }
    ajax('/api/data/', {
        "fun": "users_list"
    }, func, err);
}
function edit(name, mail, tel,uid) {
    $('#edit_email_inline').val(mail);
    $('#edit_tid').val(tel);
    $('#edit_name').val(name);
    $('#pass_div').hide();
    $('#edit_tit').html('Update user of '+name);
    $('#edit_modal').modal();
    $('#edit_modal').modal('open');
    $('#edit_button').removeAttr("disabled");
    user_id=uid;

}

function addUser(){
    $('#pass_div').show();

    $('#creat_modal').modal();
    $('#creat_modal').modal('open');
    $('#creat_button').removeAttr("disabled");
}
function del(a,b){
    var r = confirm("Delete user "+a+" !");
    if (r == true) {
        let data = {
            'fun': 'user_delete',
            'user_id': b
        };
        let func = (data) => {
            if (data === true) {  
                serverListBody.fadeOut(50);
                toast('Updated Successfully');
                users();
            }
            else{
                toast('Please Try again');
            }
        }
        let err = () => {
            toast('Please Contact the admin!');
        }

    ajax('/api/data/', data, func, err);
    } else {
    toast('Delete Request Rejected!!')
    }
}

$("#edit_form").submit(function(e) {
    e.preventDefault(); 
    $('#edit_modal').modal('close');
    let data = {
        'fun': 'user_update',
        'user_id': user_id,
        'mail': $('#edit_email_inline').val(),
        'name': $('#edit_name').val(),
        'tid': $('#edit_tid').val()
     };
    let func = (data) => {
        if (data === true) {  
            serverListBody.fadeOut(50);
            toast('Updated Successfully');
            users();
        }
        else{
            toast('Please Try again');
        }
    }
    let err = () => {
        toast('Please Contact the admin!');
    }

    ajax('/api/data/', data, func, err);
});

$("#creat_form").submit(function(e) {
    e.preventDefault(); 
    if($("#passwordConfirm").val().length<6){
        return toast('Password Sholud be atleast 7 digit !!');
    }

    if ($("#password").val() != $("#passwordConfirm").val()) {
        return toast('Both password sholud be same!!');
    }

    $('#creat_modal').modal('close');
    let data = {
        'fun': 'user_add',
        'mail': $('#creat_email_inline').val(),
        'name': $('#creat_name').val(),
        'uname': $('#creat_uname').val(),
        'tid': $('#creat_tid').val(),
        'pass': $('#password').val()

     };
    let func = (data) => {
        if (data === true) {  
            serverListBody.fadeOut(50);
            toast('Updated Successfully');
            users();
        }
        else{
            toast(data);
        }
    }
    let err = () => {
        toast('Please Contact the admin!');
    }

    ajax('/api/data/', data, func, err);
});

$("#password").on("keyup", function (e) {
    let a=$(this).val().length;
    if(a<6){
        console.log(a);
        $(this).removeClass("valid").addClass("invalid");
    }
    else{
        $(this).removeClass("invalid").addClass("valid");
    }


    if ($(this).val() != $("#passwordConfirm").val()) {
        $("#passwordConfirm").removeClass("valid").addClass("invalid");
    } else {
        $("#passwordConfirm").removeClass("invalid").addClass("valid");
    }
});

$("#passwordConfirm").on("keyup", function (e) {
    if ($("#password").val() != $(this).val()) {
        $(this).removeClass("valid").addClass("invalid");
    } else {
        $(this).removeClass("invalid").addClass("valid");
    }
});