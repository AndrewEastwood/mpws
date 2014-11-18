<?php
    // namespace engine\controller;
    // header('Content-Type: application/json; charset=utf-8');
    // // set request realm
    // define ('MPWS_REQUEST', 'UPLOAD');
    // // start session
    // session_start();
    // // bootstrap
    // include $_SERVER['DOCUMENT_ROOT'] . '/engine/bootstrap.php';

    // startApplication();

    namespace engine\controller;

    include $_SERVER['DOCUMENT_ROOT'] . '/app.php';

    use \app as App;

    $app = new App('upload', 'Content-Type: application/json; charset=utf-8');

    $app->startApplication();

    echo $app->getJSONResponse();
?>