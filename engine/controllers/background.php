<?php

    namespace engine\controller;

    include $_SERVER['DOCUMENT_ROOT'] . '/app.php';

    use \app as App;

    $app = new App('background', 'Content-Type: application/json; charset=utf-8');

    $app->startApplication();

    echo $app->getJSONResponse();
?>