<footer class="page-footer blue">
    <div class="container">
            © <script>document.write(new Date().getFullYear()); </script> BITSATHY Server
            <a class="grey-text text-lighten-4 right" href="http://sethilnasa.site/">Senthil Nasa</a>
    </div>
</footer>
<script src="<?php echo $GLOBALS['_path'] ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo $GLOBALS['_path'] ?>assets/js/materialize.min.js"></script>
    <script src="<?php echo $GLOBALS['_path'] ?>assets/js/script.js"></script>
    <script src="<?php echo $GLOBALS['_path'] ?>assets/js/chartjsutil.js"></script>
    <script src="<?php echo $GLOBALS['_path'] ?>assets/js/chartjs.js"></script>
    <script src="<?php echo $GLOBALS['_path'] ?>assets/js/chartjszoom.js"></script>
    <script src="<?php echo $GLOBALS['_path'] ?>assets/js/hammer.js"></script>
    
    <script>



        function strtotime(time) {

            var d = new Date();

            var ParsedTime = new RegExp('([+-][0-9]+) (\\w+)', 'i').exec(time);
            if(!ParsedTime) return 0;

            switch(ParsedTime[2]) {
            case 'second':
                d.setSeconds(d.getSeconds() + parseInt(ParsedTime[1], 10));
                break;
            case 'minute':
                d.setMinutes(d.getMinutes() + parseInt(ParsedTime[1], 10));
                break;
            case 'hour':
                d.setHours(d.getHours() + parseInt(ParsedTime[1], 10));
                break;
            case 'day':
                d.setDate(d.getDate() + parseInt(ParsedTime[1], 10));
                break;
            case 'month':
                d.setMonth(d.getMonth() + parseInt(ParsedTime[1], 10));
                break;
            case 'year':
                d.setFullYear(d.getFullYear() + parseInt(ParsedTime[1], 10));
                break;
            }

            return d.getTime();
            }
        
        let starttime=strtotime("-3 hour");
        let hourTime=strtotime("-1 hour");
        let dayTime=strtotime("-1 day");
        let weekTime=strtotime("-7 day");
        let weeksTime=strtotime("-14 day");
        let monthTime=strtotime("-1 month");
        let yearTime=strtotime("-1 year");
        let endtime=strtotime("+1 hour");
        let myDates=strtotime("+1 hour");

        $(document).ready(function() {
            var pathname ='../'+window.location.pathname.split("/admin/")[1];
            $("li > a").each(function() {
                if ($(this).attr("href") == pathname) {
                    $(this).parent().addClass("active");
                }
            });
        });
    </script>
    <script src="script.js"></script>

</body>

</html>