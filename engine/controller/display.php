<?php

    namespace engine\controller;

    include $_SERVER['DOCUMENT_ROOT'] . 'app.php';

    use \engine\app as App;

    $app = new App();

    // var_dump($app);

    $app->startApplication();

    // echo $app->getResponse();
?>