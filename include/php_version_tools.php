<?php
/**
 * PHP Version Tools
 */

/**
 * Gets PHP version info
 * 
 * @return array(major, minor, release)
 */
function php_version_arr() {
    if (preg_match('/^(\d+)\.(\d+)\.(\d+)/', PHP_VERSION, $ver)) {
        array_shift($ver);
        return $ver;
    }
    return array();
}

/**
 * Test if PHP version matches 
 * 
 * @param  string gt|gte|lt|lte, stands for 'greater than', 'greater than or equal', 
 *          'less than', 'less than or equal' respectively
 * @param  int $major
 * @param  int $minor
 * @param  int $release
 * @return bool
 */
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