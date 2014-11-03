<?php
    namespace engine\controller;

    use \engine\app as App;

    $app = new App();

    $app->startApplication();

    echo $app->getResponse();
?>