<?php

    namespace engine\controller;

    include $_SERVER['DOCUMENT_ROOT'] . '/app.php';

    use \app as App;

    $app = new App('api', array('Content-Type: application/json; charset=utf-8', 'Access-Control-Allow-Origin: *'));

    $app->startApplication();

    echo $app->getJSONResponse();
?>