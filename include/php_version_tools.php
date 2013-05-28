<?php


function php_version_arr() {
    if (preg_match('/^(\d+)\.(\d+)\.(\d+)/', PHP_VERSION, $ver)) {
        array_shift($ver);
        return $ver;
    }
    return array();
}

function php_version_is($verb, $major, $minor = null, $release = null) {
    $ver = php_version_arr();

    switch($verb) {
        case 'gt':
            return ($ver[0] > $major) || 
                    ($ver[0] == $major && $ver[1] > $minor) ||
                    ($ver[0] == $major && $ver[1] == $minor && $ver[2] > $release);
        break;
        case 'gte':
            return ($ver[0] > $major) || 
                    ($ver[0] == $major && (null == $minor || $ver[1] > $minor)) ||
                    ($ver[0] == $major && $ver[1] == $minor && (null == $release || $ver[2] >= $release));
        break;
        case 'lt':
            return !php_version_is('gte', $major, $minor, $release);
        break;
        case 'lte':
            return !php_version_is('gt', $major, $minor, $release);
        break;

        return false;
    }
}