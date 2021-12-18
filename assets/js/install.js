        const configDiv = $('#configDiv');
        const loadTab = $('#Progress');
        const configFileDiv = $('#configFileDiv');
        const configExtensionDiv = $('#configExtensionDiv');
        const configAdminUserDiv = $('#configAdminUserDiv');
        let d1,d2,d3,d4,d5;
        let u1,u2,u3,u4;
        
        function changeConfigView() {
            configExtensionDiv.fadeOut();
            configDiv.fadeIn(500);
        }

        function verfiyDbConfig() {

            d1 = $('#d1').val();
            d2 = $('#d2').val();
            d3 = $('#d3').val();
            d4 = $('#d4').val();
            d5 = $('#d5').val();

            if (d1.length < 2) {
                return toast('Invalid .Database Host !');
            }

            if (d2.length < 2) {
                return toast('Invalid Database Port Number !');
            }

            if (d3.length < 1) {
                return toast('Invalid User name !');
            }

            if (d5.length < 1) {
                return toast('Invalid Db name !');
            }


            configDiv.hide();
            loadTab.fadeIn();

            let data = {
                'fun': 'verify_login',
                'd1': d1,
                'd2': d2,
                'd3': d3,
                'd4': d4,
                'd5': d5,
            };

            let func = (data) => {
                $('#config').val(data);
                // toast('Db Config check Success');
                configFileDiv.fadeIn();
                loadTab.hide();
            }
            let err = () => {
                configDiv.fadeIn();
                loadTab.hide();
            }
            ajax('../api/checkdb/', data, func, err);

        }
        function validateEmail(email) {
            const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }

        function configAdminUser() {
            u1 = $('#u1').val();
            u2 = $('#u2').val();
            u3 = $('#u3').val();
            u4 = $('#u4').val();
            if (u1.length < 3) {
                return toast('Invalid User Name ! \n set Minium 4 digit');
            }
            if (u2.length < 2) {
                return toast('Invalid Password ! \n set Minium 8 digit');
            }

            if (u3==u2) {
                return toast('Invalid Rep-Password !');
            }

            if (!validateEmail(u4)) {
                return toast('Invaliu ub name !');
            }
            


            configAdminUserDiv.hide();
            loadTab.fadeIn();

            let data = {
                'fun': 'verify_login',
                'u1': u1,
                'u2': u2,
                'u3': u4,
            };

            let func = () => {
                
               location.reload();
            }
            let err = () => {
                configDiv.fadeIn();
                loadTab.hide();
            }
            ajax('../api/checkdb/', data, func, err);

        }

        function gen_db() {

            loadTab.fadeIn();
            subTab.hide();

            let data = {
                'fun': 'db_add',
                'd1': d1,
                'd2': d2,
                'd3': d3,
                'd4': d4,
            };

            let func = (data) => {

                toast('Installed Success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
            let err = () => {
                toast(e);
            }

            ajax('../api/checkdb/', data, func, err);
        }
   