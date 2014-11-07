<?php
    namespace engine\controller;

    include $_SERVER['DOCUMENT_ROOT'] . 'app.php';

    use \engine\app as App;

    $app = new App('auth', 'Content-Type: application/json; charset=utf-8');

    $app->startApplication();

    echo $app->getJSONResponse();
?>