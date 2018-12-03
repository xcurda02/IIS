function is_valid_login(login){
    const re = /^[a-zA-Z0-9_-]{3,30}$/;
    return re.test(login);
}

function is_valid_password(password) {
    const re = /^[a-zA-Z0-9_-]{5,30}$/;
    return re.test(password);
}

function is_valid_email(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function is_valid_phone(phone){
    if (phone.length !== 9)
        return false;

    return !isNaN(phone);
}

function check_required(fields) {
    var status = true;
    for(var i = 0; i < fields.length; i++){
        if ($('#'+fields[i]).val() === ""){
            status = false;
            $('#'+fields[i]).css('background-color', '#f47070');
        }
    }
    return status
}

function set_white(fields) {
    for(var i = 0; i < fields.length; i++){
        $('#'+fields[i]).css('background-color', '#ffffff');
    }
}

function max_char_check(chars,fields) {
    var status = true;
    for (var i = 0; i < fields.length; i++) {
        if ($('#' + fields[i]).val().length > chars) {
            status = false;
            $('#' + fields[i]).css('background-color', '#f47070');
        }
    }
    return status;
}