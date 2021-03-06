<?php 
require('include/csrf_guard.php');
$ts = microtime(true);
$cg = Csrf_Guard::forge();
?>
<!doctype html>
<html lang="zh_TW">
    <head>
        <link rel="stylesheet" href="css/demo.css" type="text/css">
        <link href='http://fonts.googleapis.com/css?family=Bubblegum+Sans|Libre+Baskerville|Sancreek' rel='stylesheet' type='text/css'>

        <script src="//cdnjs.cloudflare.com/ajax/libs/require.js/2.1.5/require.min.js"></script>
        <script> "undefined" === typeof require && document.write("\x3Cscript src=\"js/vendor/require-2.1.6.js\">\x3C/script>");</script>
    </head>
    <body>
        <div id="wrapper">
            <header>
                <h1>Demo for Exosite web sdk</h1>
            </header>

            <input type="hidden" id="frm_csrf_token" name="csrf_token" value="<?php echo $cg->get_token($ts);?>">
            <input type="hidden" id="frm_csrf_seed" name="csrf_seed" value="<?php echo $ts;?>">
            
            <div class="input clearfix">
                <label for="frm_proxy">Proxy: </label>
                <input type="text" id="frm_proxy" name="proxy" value="proxy.php">
            </div>
            <div class="input clearfix">
                <label for="frm_server">Server: </label>
                <input type="text" id="frm_server" name="server" value="https://m2.exosite.com/api:v1/stack/alias">
            </div>
            <div class="input clearfix">
                <label for="frm_cik">CIK: </label>
                <input type="text" id="frm_cik" name="cik" placeholder="ENTER CIK HERE">
            </div>
            <div class="input clearfix">
                <label for="frm_message"> message: </label>
                <input type="text" id="frm_message" name="message">
            </div>
            <div class="input clearfix">
                <label for="frm_number">number: </label>
                <input type="number" id="frm_number" name="number" step="any">
            </div>
            <div class="actions clearfix">
                <button class="pull-left" id="frm_push">Push</button>
                <button class="pull-right" id="frm_pull">Pull</button>
            </div>

            <output class="clearfix"></output>

            <footer class="pull-right">
                Project hosted <a href="https://github.com/clifflu/exosite_js_sdk">@GitHub</a>.
            </footer>
            <script>
        
                require.config({
                    urlArgs: "bust=" +  (new Date()).getTime(),
                    baseUrl: "js",
                    paths:{
                        'jquery': [
                            '//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min',
                            'js/vendor/jquery-2.0.1'
                        ]
                    },
                    shim: {
                        'demo': {
                            'deps': ['jquery', 'exosite_websdk', 'extends']
                        }
                    }
                });
        
                require(['demo'], function(obj){obj.init()});
            </script>
        </div>
    </body>
</html>
