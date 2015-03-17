$(document).ready(function() {
    $('#pswd').keyup(function() {
	// set password variable
	var pswd = $(this).val();
        var correcto=0;
	//validate the length
        if ( pswd.length < 8 ) {
            $('#length').removeClass('valid').addClass('invalid');
        } else {
            $('#length').removeClass('invalid').addClass('valid');
            correcto=correcto+1;
        }

        //validate letter
        if ( pswd.match(/[A-z]/) ) {
            $('#letter').removeClass('invalid').addClass('valid');
            correcto=correcto+1;
        } else {
            $('#letter').removeClass('valid').addClass('invalid');
        }

        //validate capital letter
        if ( pswd.match(/[A-Z]/) ) {
            $('#capital').removeClass('invalid').addClass('valid');
            correcto=correcto+1;
        } else {
            $('#capital').removeClass('valid').addClass('invalid');
        }

        //validar caracter especial
        if ( pswd.match(/[^a-zA-Z0-9]/) ) {
                $('#especial').removeClass('invalid').addClass('valid');
                correcto=correcto+1;
        } else {
                $('#especial').removeClass('valid').addClass('invalid');
        }

        //validate number
        if ( pswd.match(/\d/) ) {
            $('#number').removeClass('invalid').addClass('valid');
            correcto=correcto+1;
        } else {
            $('#number').removeClass('valid').addClass('invalid');
        }
        
        if (correcto==5){
            $('#confirma').attr('disabled',false);
        }else{
            $('#confirma').attr('disabled',true);
        }

    }).focus(function() {
        $('#pswd_info').show();
    }).blur(function() {
        $('#pswd_info').hide();
    });
    
    $('#confirma').keyup(function(){
        var pass1 = $('#pswd').val();
        var pass2 = $('#confirma').val();
        if (pass1==pass2){
            $('#aceptar').attr('disabled',false);
        }else{
            $('#aceptar').attr('disabled',true);
        }
    });
});

$(document).on('ready', function() {
        $('#show-hide-passwd').on('click', function(e) {
        e.preventDefault();
        var current = $(this).attr('action');
            if (current == 'hide') {
                $(this).prev().attr('type','text');
                $(this).removeClass('glyphicon-eye-open').addClass('glyphicon-eye-close').attr('action','show');
            }
            if (current == 'show') {
                $(this).prev().attr('type','password');
                $(this).removeClass('glyphicon-eye-close').addClass('glyphicon-eye-open').attr('action','hide');
            }
        });
        $('#show-hide-passwd2').on('click', function(e) {
        e.preventDefault();
        var current = $(this).attr('action');
            if (current == 'hide') {
                $(this).prev().attr('type','text');
                $(this).removeClass('glyphicon-eye-open').addClass('glyphicon-eye-close').attr('action','show');
            }
            if (current == 'show') {
                $(this).prev().attr('type','password');
                $(this).removeClass('glyphicon-eye-close').addClass('glyphicon-eye-open').attr('action','hide');
            }
        });
    })