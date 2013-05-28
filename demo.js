/**
 * Module "demo" - Handles UI of demo.html
 * 
 */
define("demo", ['jquery', 'exosite_websdk'], 
function($, sdk, undefined){
    var jc ;    // jquery object cache

    function _conf() {

        var conf = {
            'proxy'     : jc.proxy.val(),
            'server'    : jc.server.val(),
            'cik'       : jc.cik.val()
        }

        return conf;
    }

    function pull() {
        var attr = ['message', 'number'];

        sdk.pull(attr, _conf()).done(function(response){
            console.log(response);
        });

    }

    function push() {
        var data = {
            'message'   : jc.message.val(),
            'number'    : jc.number.val()
        }

        sdk.push(data, _conf()).done(function(response){
            console.log(response);
        })

    }

    function init() {
        // build cache
        jc = {};

        jc.proxy = $('#frm_proxy');
        jc.server = $('#frm_server');
        jc.cik = $('#frm_cik');
        jc.message = $('#frm_message');
        jc.number = $('#frm_number');

        // disable form submission by buttons
        $('form').off('submit').on('submit', function(){return false;})

        $('#frm_push').off('click', push).on('click', push)
        $('#frm_pull').off('click', pull).on('click', pull)

    }

    return {
        init: init
    }
});