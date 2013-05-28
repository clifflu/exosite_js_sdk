/**
 * Module "demo" - Handles UI of demo.html
 * 
 */
define("demo", ['jquery', 'exosite_websdk'], 
function($, sdk, undefined){
    var jc ;    // jquery object cache

    function log_http_code_curry(msg_hdr) {
        return function(xhr, textStatus){
            appendmsg('HTTP ' + xhr.status + ' [' + xhr.statusText + ']', msg_hdr);
        }
    }

    function appendmsg(msg, prefix) {
        var time, now;

        if (prefix == undefined) 
            prefix = '';

        now = new Date();
        time = $('<time>').append(
            String.sprintf('%2d:%2d:%2d.%3d', now.getHours(), now.getMinutes(), now.getSeconds(), now.getMilliseconds())
        );
        jc.output.append($('<p>').append(time).append(prefix + msg));
    }

    function _conf() {
        var conf = {
            'proxy'     : jc.proxy.val(),
            'server'    : jc.server.val(),
            'cik'       : jc.cik.val()
        }

        return conf;
    }

    function pull() {
        var attr = ['message', 'number'],
            msg_hdr = '&lt;&lt; ';

        appendmsg('Pulling from server', msg_hdr);

        sdk.pull(attr, _conf())
        .done(function(response){
            appendmsg("Got raw output: " + response, msg_hdr);
            response = JSON.parse(response);

            jc.message.val(response.message);
            jc.number.val(response.number);
        }).complete(log_http_code_curry(msg_hdr));
    }

    function push() {
        var data = {
                'message'   : jc.message.val(),
                'number'    : jc.number.val()
            },
            msg_hdr = '&gt;&gt; ';

        appendmsg('Pushing to server', msg_hdr);

        sdk.push(data, _conf()).complete(log_http_code_curry(msg_hdr));
    }

    function init() {
        // build cache
        jc = {};

        jc.proxy = $('#frm_proxy');
        jc.server = $('#frm_server');
        jc.cik = $('#frm_cik');
        jc.message = $('#frm_message');
        jc.number = $('#frm_number');
        jc.output = $('output');

        // disable form submission by buttons
        $('form').off('submit').on('submit', function(){return false;})

        $('#frm_push').off('click', push).on('click', push)
        $('#frm_pull').off('click', pull).on('click', pull)

    }

    return {
        init: init
    }
});