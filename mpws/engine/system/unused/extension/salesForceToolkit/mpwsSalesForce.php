<?php

/**
 * define shorthand directory separator constant
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * set SFORCE_DIR to absolute path to salesForceToolkit files.
 * Sets SFORCE_DIR only if user application has not already defined it.
 */
if (!defined('SFORCE_DIR')) {
    define('SFORCE_DIR', dirname(__FILE__) . DS);
}

spl_autoload_register('sForceAutoload');

/**
 * Autoloader
 */
function sForceAutoload($class) {
    if (file_exists(SFORCE_DIR . $class . '.php'))
        include SFORCE_DIR . $class . '.php';
}

?>
