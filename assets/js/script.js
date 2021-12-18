function toast(msg, fun = () => { }) {
    M.toast({
        html: msg,
        displayLength: 2000,
        classes: 'deep-orange',
        completeCallback: fun
    });
}

function progress(load = true) {
    if (load)
        $("#progress").show();
    else
        $("#progress").hide();
}

$(document).bind("ajaxSend", () => {
    progress();
}).bind("ajaxComplete", () => {
    progress(false);
});

function ajax(url, data = {}, func = (data) => { }, errFunc = () => { }) {
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        dataType: 'json',
        success: (res) => {
            console.log(res);
            if (res.ok === true)
                func(res.data);
            else {
                toast(res.err);
                errFunc();
            }
        },
        error: (qXHR, textStatus, error) => {
            console.error(error);
            toast(textStatus);
            errFunc();
        }
    });
}

function onlyNumberKey(evt) { 
          
    // Only ASCII charactar in that range allowed 
    var ASCIICode = (evt.which) ? evt.which : evt.keyCode 
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)) 
        return false; 
    return true; 
} 

if ('serviceWorker' in navigator) {
    navigator.serviceWorker
             .register('./script.js')
             .then(function() { console.log('Service Worker Registered'); });
}