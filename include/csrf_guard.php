<?php
/**
 * CSRF Guard
 *
 * A CSRF protection method relying on a pre-defined secret.
 */
class Csrf_Guard {

    private $config = array();

    private function __construct($config = null) {
        $this->config = array_merge(static::defaults(), is_array($config) ? $config : array()) ;
    }

    // ==============
    //  Token
    // ==============
    function get_token($seed) {
        return $this->_encrypt($seed, $this->config['secret']);
    }

    function verify_token($seed, $token) {
        return $this->_encrypt($seed, $this->config['secret']) === $token ;
    }

    private function _encrypt($seed, $secret) {
        $md5_msg = md5($secret . $seed . $secret);
        
        if ($this->config['hex_token']) {
            return $md5_msg;
        }

        return preg_replace('/=+$/','',base64_encode(pack('H*',$md5_msg)));
    }


    // ==============
    //  Secret
    // ==============
    
    function set_secret($secret) {
        $this->config['secret'] = (string) $secret;
    }

    // ==============
    //  Static
    // ==============
    
    /**
     * Initializes a Csrf_Guard object
     * 
     * @param  Array $config [description]
     * @return Csrf_Guard
     */
    static function forge($config = null) {
        return new static($config);
    }

    static function defaults () {
        return array(
            'secret'    => "MyL1ㄒt73$3CReㄒ",
            'hex_token' => false,
        );
    }
}

