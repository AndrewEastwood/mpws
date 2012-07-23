<?php
    // create config object
    $config['WEB'] = array();
    $config['WEB']['HEADERS'] = array(
        'Content-type: text/html; charset=utf8'
    );
    $config['WEB']['TIMEZONE'] = 'GMT+2';
    $config['WEB']['SESSION_TIME'] = 30 * 60;
    $config['WEB']['ICONV-ENCODING'] = array(
        'internal_encoding' => 'UTF-8',
        'output_encoding' => 'ISO-8859-1'
    );
?>
