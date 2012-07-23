<?php
    // create config object
    $config['TOOLBOX'] = array();
    $config['TOOLBOX']['HEADERS'] = array(
        'Content-type: text/html; charset=utf8'
    );
    $config['TOOLBOX']['TIMEZONE'] = 'GMT+2';
    $config['TOOLBOX']['SESSION_TIME'] = 30 * 60;
    $config['TOOLBOX']['ICONV-ENCODING'] = array(
        'internal_encoding' => 'UTF-8',
        'output_encoding' => 'ISO-8859-1'
    );
    $config['TOOLBOX']['STARTUP_PLUGIN'] = 'TOOLBOX';
?>
