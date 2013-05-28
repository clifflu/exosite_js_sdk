<?php
/**
 * json_encode emulating JSON_UNESCAPED_UNICODE for PHP < 5.4
 *
 * Ref: http://tw.php.net/manual/en/function.json-encode.php
 * 
 * @param  [type]  $arr
 * @param  integer $options
 * @param  integer $depth
 * 
 * @return string JSON-encoded string
 */

require_once("include/php_version_tools.php");

function json_encode_unescaped_unicode($arr, $options = 0)
{
    if (php_version_is('gte', 5, 4)) {
        return json_encode($arr, $options | JSON_UNESCAPED_UNICODE);
    }
    
    if (!is_array($arr)) {
        if (is_string($arr))
            return '"' . addslashes($arr) . '"';
        return $arr;
    }

    //convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). 
    // So such characters are being "hidden" from normal json_encoding
    array_walk_recursive($arr, function (&$item, $key) { 
        if (is_string($item)) 
            $item = mb_encode_numericentity($item, array (0x80, 0xffff, 0, 0xffff), 'UTF-8'); 
    });

    return mb_decode_numericentity(
        json_encode($arr, $options), 
        array (0x80, 0xffff, 0, 0xffff), 
        'UTF-8'
    );
}