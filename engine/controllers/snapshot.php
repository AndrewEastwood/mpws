<?php
    namespace engine\controller;

    include $_SERVER['DOCUMENT_ROOT'] . '/app.php';

    use \app as App;

    $app = new App('snapshot', 'Access-Control-Allow-Origin: *');

    $app->startApplication();

    echo $app->getResponse();
?>