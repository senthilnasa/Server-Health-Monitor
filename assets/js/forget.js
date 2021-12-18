function $_GET(param) {
	var vars = {};
	window.location.href.replace( location.hash, '' ).replace( 
		/[?&]+([^=&]+)=?([^&]*)?/gi,
		function( m, key, value ) { 
			vars[key] = value !== undefined ? value : '';
		}
	);

	if ( param ) {
		return vars[param] ? vars[param] : null;	
	}
	return vars;
}

const key = $_GET('key');
const email = $_GET('email');
const action = $_GET('action');
if(key==null||key==" "){
    window.location.replace("/");
}
if(email==null||email==" "){
    window.location.replace("/");
}
if(action==null||action==" " && action!="reset"){
    window.location.replace("/");
}



$('#php_aut').submit(function(e) {
    e.preventDefault();
    $('#loding').show();
    $('#form-ve').hide();
    let formData = $('#php_aut').serialize() ;
    $.ajax({
            type: 'POST',
            url: '../../api/auth/',
            data: formData,
        })
        .done(function(data) {
           
            if (data.ok) {
                alert('Password Reset Success');
                setTimeout(() => {
                    window.location.href = "../";
                }, 1000);
            } else {
                $('#form-ve').show();
                $('#loding').hide();
                err(data.err);
            }
        })
        .fail(function(data) {
            err(data);
        });
});

function err(a) {
    $('#err').html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + a);
    $('#suc').hide();
    $('#err').show();
}

function suc(a) {
    $('#suc').html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + a);
    $('#suc').show();
}

// window.history.pushState("object or string", "Reset Password Page", "setpass");
$( "input[name*='key']" ).val(key);
$( "input[name*='email']" ).val(email);
$( "input[name*='type']" ).val(action);
let pro=false;
$(document).ready(function() {
        $('#password').keyup(function() {
            a();
            
        });
        $('#confirm-password').keyup(function(){
            a();
        });
        function a(){

            var password = $('#password').val();
            if (checkStrength(password) == 'Strong') {
                if ($('#password').val() === $('#confirm-password').val()) {
                $('#popover-cpassword').addClass('hide');
                $('#reset-bt').prop('disabled', false);
                }
                else{
                    $('#popover-cpassword').removeClass('hide');
                    $('#reset-bt').prop('disabled', true);
                }
            }
            else{

                $('#reset-bt').prop('disabled', true);

            }
        }
        $('#confirm-password').blur(function() {
            if ($('#password').val() !== $('#confirm-password').val()) {
                $('#popover-cpassword').removeClass('hide');
                $('#reset-bt').prop('disabled', true);
            } else {
                $('#popover-cpassword').addClass('hide');
            }
        });

        function checkStrength(password) {
            var strength = 0;


            //If password contains both lower and uppercase characters, increase strength value.
            if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
                strength += 1;
                $('.low-upper-case').addClass('text-success');
                $('.low-upper-case i').removeClass('fa-file-text').addClass('fa-check');
                $('#popover-password-top').addClass('hide');


            } else {
                $('.low-upper-case').removeClass('text-success');
                $('.low-upper-case i').addClass('fa-file-text').removeClass('fa-check');
                $('#popover-password-top').removeClass('hide');
            }

            //If it has numbers and characters, increase strength value.
            if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) {
                strength += 1;
                $('.one-number').addClass('text-success');
                $('.one-number i').removeClass('fa-file-text').addClass('fa-check');
                $('#popover-password-top').addClass('hide');

            } else {
                $('.one-number').removeClass('text-success');
                $('.one-number i').addClass('fa-file-text').removeClass('fa-check');
                $('#popover-password-top').removeClass('hide');
            }

            if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
                strength += 1;
                $('.one-special-char').addClass('text-success');
                $('.one-special-char i').removeClass('fa-file-text').addClass('fa-check');
                $('#popover-password-top').addClass('hide');

            } else {
                $('.one-special-char').removeClass('text-success');
                $('.one-special-char i').addClass('fa-file-text').removeClass('fa-check');
                $('#popover-password-top').removeClass('hide');
            }

            if (password.length > 7) {
                strength += 1;
                $('.eight-character').addClass('text-success');
                $('.eight-character i').removeClass('fa-file-text').addClass('fa-check');
                $('#popover-password-top').addClass('hide');

            } else {
                $('.eight-character').removeClass('text-success');
                $('.eight-character i').addClass('fa-file-text').removeClass('fa-check');
                $('#popover-password-top').removeClass('hide');
            }




            // If value is less than 2

            if (strength < 2) {
                $('#result').removeClass()
                $('#password-strength').addClass('progress-bar-danger');

                $('#result').addClass('text-danger').text('Very Week');
                $('#password-strength').css('width', '10%');
            } else if (strength == 2) {
                $('#result').addClass('good');
                $('#password-strength').removeClass('progress-bar-danger');
                $('#password-strength').addClass('progress-bar-warning');
                $('#result').addClass('text-warning').text('Week')
                $('#password-strength').css('width', '60%');
                return 'Week'
            } else if (strength == 4) {
                $('#result').removeClass()
                $('#result').addClass('strong');
                $('#password-strength').removeClass('progress-bar-warning');
                $('#password-strength').addClass('progress-bar-success');
                $('#result').addClass('text-success').text('Strength');
                $('#password-strength').css('width', '100%');

                return 'Strong'
            }

        }

});