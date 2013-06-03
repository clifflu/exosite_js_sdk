/**
 * Module "demo" - Handles UI of demo.html
 * 
 */
define("demo", ['jquery', 'exosite_websdk'], 
function($, sdk, undefined){
    var jc ;    // jquery object cache

    function log_http_code_curry(msg_hdr) {
        return function(xhr, textStatus){
            var msg = 'HTTP ' + xhr.status + ' [' + xhr.statusText + ']';
            if (xhr.responseText) {
                msg += (': ' + xhr.responseText);
            }
            console.log(xhr);
            appendmsg(msg, msg_hdr);
        }
    }

    function appendmsg(msg, padding) {
        var time, now;

        now = new Date();
        time = $('<time>').append(
            String.sprintf('%02d:%02d:%02d.%03d', now.getHours(), now.getMinutes(), now.getSeconds(), now.getMilliseconds())
        );

        if (padding == undefined) 
            padding = '';
        else
            padding = $('<span class="padding">').append(padding);

        jc.output.append($('<p>').append(time).append(padding).append($('<span>').text(msg)));
        jc.output.scrollTop(100);
    }

    function _conf() {
        var conf = {
            'proxy'     : jc.proxy.val(),
            'server'    : jc.server.val(),
            'cik'       : jc.cik.val()
        }

        return conf;
    }

    function _csrf() {
        return {
            'X-CSRF-Token'  : jc.csrf.token.val(),
            'X-CSRF-Seed'   : jc.csrf.seed.val()
        };
    }

    function pull() {
        var attr = ['message', 'number'],
            msg_hdr = '&lt;&lt;';

        appendmsg('Pulling from server', msg_hdr);

        sdk.pull(attr, _conf(), _csrf())
        .done(function(response){
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
            msg_hdr = '&gt;&gt;';

        appendmsg('Pushing to server', msg_hdr);

        sdk.push(data, _conf(), _csrf()).complete(log_http_code_curry(msg_hdr));
    }

    function init() {
        // build cache
        jc = {
            "cik"     : $('#frm_cik'),
            "csrf"    : {
                "seed"    : $('#frm_csrf_seed'),
                "token"   : $('#frm_csrf_token')
            },
            "message" : $('#frm_message'),
            "number"  : $('#frm_number'),
            "output"  : $('output'),
            "proxy"   : $('#frm_proxy'),
            "server"  : $('#frm_server')
        };

        // disable form submission by buttons
        $('form').off('submit').on('submit', function(){return false;})

        $('#frm_push').off('click', push).on('click', push)
        $('#frm_pull').off('click', pull).on('click', pull)

    }

    return {
        init: init
    }
});