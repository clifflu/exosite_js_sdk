/**
 * Module exosite_websdk - Passes data to and from Exosite via php proxy
 *
 */
define("exosite_websdk", ["jquery"],
function($,undefined){
    var default_config = {
        'proxy'     : 'proxy.php',
        'server'    : 'https://m2.exosite.com/api:v1/stack/alias',
    };

    // 
    // Update data via Proxy
    //
    //  Param:
    //      - data: {DATA_ALIAS: DATA_VALUE, ...}
    //      - config: 
    //  
    //  Return:
    //      jqXHR (Promise) object
    //
    function push(data, config, headers){
        var my_config, 
            header;

        my_config = $.extend({}, default_config, config);

        headers = $.extend({}, headers, {
            'X-Exosite-CIK' : encodeURIComponent(my_config.cik),
            'X-Server-URL'  : encodeURIComponent(my_config.server),
            'Content-type'  : 'application/x-www-form-urlencoded; charset=utf-8'
        });

        return $.ajax(my_config.proxy, {
            "type"      : "POST",
            "headers"   : headers,
            "data"      : data
        });
    }

    // 
    // Gets latest data via Proxy
    //
    //  Param:
    //      - attr: [DATA_ALIAS, ...]
    //      - config: 
    //  
    //  Return:
    //      jqXHR (Promise) object, response body is JSON {DATA_ALIAS: DATA_VALUE, ...}
    //
    function pull(attr, config, headers){
        var my_config, 
            header, 
            tmp;

        if (Object.prototype.toString.call(attr) !== '[object Array]') {
            // attr is not an array
            // todo
        }

        for (tmp in attr) {
            if (!attr.hasOwnProperty(tmp))
                continue;

            attr[tmp] = encodeURIComponent(attr[tmp]);
        }

        my_config = $.extend({}, default_config, config);

        headers = $.extend({}, headers, {
            'X-Exosite-CIK' : encodeURIComponent(my_config.cik),
            'X-Server-URL'  : encodeURIComponent(my_config.server) + '?' + attr.join('&'),
            'Accept'        : 'application/x-www-form-urlencoded; charset=utf-8'
        });

        return $.ajax(my_config.proxy, {
            "type"      : "GET",
            "headers"   : headers
        })
    }

    function config(args){
        if (typeof args === "undefined") {
            // elegant way to clone
            return $.extend({}, default_config);
        }
        
        if (typeof args !== "object") {
            args = [args];
        }
        
        $.extend(default_config, args)
    }

    return window.sdk = {
        push: push,
        pull: pull,
        config: config
    };
})