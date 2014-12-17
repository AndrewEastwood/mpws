<?php
    namespace engine\controller;

    include $_SERVER['DOCUMENT_ROOT'] . '/app.php';

    use \app as App;

    $app = new App(false, 'Access-Control-Allow-Origin: *');

    $app->startApplication();

    echo $app->getResponse();
?>