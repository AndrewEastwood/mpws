<?php

    namespace engine\controller;

    include $_SERVER['DOCUMENT_ROOT'] . 'app.php';

    use \engine\app as App;

    $app = new App('api', 'Content-Type: application/json; charset=utf-8');

    $app->startApplication();

    echo $app->getJSONResponse();
?>