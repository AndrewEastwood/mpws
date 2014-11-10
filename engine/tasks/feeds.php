<?php

    namespace engine\tasks;

    include $_SERVER['DOCUMENT_ROOT'] . 'app.php';

    use \engine\app as App;

    $app = new App('task', 'Content-Type: application/json; charset=utf-8');

    $app->startApplication();
?>