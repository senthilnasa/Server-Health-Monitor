
const loginTab = $('#loginSubmit');
const loadTab = $('#loginProgress');

let login = $('#login_id');
let pass = $('#login-pass');

function verifyPass(){
    login = $('#login_id').val();
    pass = $('#login-pass').val();

    if(login.length<2){
        return toast('Invalid User Id !');
    }
    if(pass.length<2){
        return toast('Invalid Password !');
    }
    loginTab.hide();
    loadTab.fadeIn();

    let data = {
        'fun': 'verify_login',
        'login': login,
        'pass': pass
    };

    let func = (data) => {
        toast('Login Success');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
    }
    let err = () => {
        loginTab.fadeIn(1000);
        loadTab.hide();
    }

    ajax('/api/auth/', data, func, err);
}

function resetPass(){
    login = $('#login_id').val();

    if(login.length<2){
        return toast('Invalid User Id !');
    }
    loginTab.hide();
    loadTab.fadeIn();

    let data = {
        'fun': 'reset_pass',
        'login': login
    };

    let func = (data) => {
        toast(data);
            setTimeout(() => {
                window.history.go(-1);
            }, 5000);
    }

    let err = () => {
        loginTab.fadeIn(1000);
        loadTab.hide();
    }

    ajax('/api/auth/', data, func, err);
}