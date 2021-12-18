
const serverListHead = $('#serverList > thead');
const serverListBody = $('#serverList > tbody');

  $(document).ready(() => {
        $('body').fadeIn(1000);
        $('.sidenav').sidenav();
        $(':button').prop('disabled', true);

        servers();
    });


    function setTable(fee, total) {
        serverListHead.fadeIn(750);
        let html = '';
        if (fee.length == 0 || total == 0) {
            html += '<tr><td colspan="9" class="center">No Notification Logs Found</td></tr>';
            $(':button').prop('disabled', true);
        }
        else {
            let s=1;
            fee.forEach(f => {
                var d = new Date(f.x);
                  
                html += '<tr>';
                html += '<td>' + s + '</td>';
                html += '<td>' + f.server_name + '</td>';
                html += '<td>' + d.toLocaleString() + '</td>';
                html += '<td>' + f.message + '</td>';
                html += '<td>' + f.type + '</td>';
                html += '</tr>';
                s++;
            });
        }
        serverListBody.html(html);
        $('.tooltipped').tooltip();
        serverListBody.fadeIn(1000);
    }

    function servers(){
        let func = (list) => {
            setTable(list);
        }
        let err = () => {
            toast('try again later!');
        }
        ajax('/api/data/', { "fun": "notification_log" }, func, err);
    }
