<?php
    namespace engine\controller;

    use \engine\bootstrap as App;

    header('Content-Type: application/json; charset=utf-8');
    // set request realm
    define ('MPWS_REQUEST', 'API');
    // start session
    session_start();
    // bootstrap
    include $_SERVER['DOCUMENT_ROOT'] . '/engine/bootstrap.php';

    $app = new App();
    $app->startApplication('api');
?>