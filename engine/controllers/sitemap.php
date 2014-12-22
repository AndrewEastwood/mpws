<?php
    namespace engine\controller;

    include $_SERVER['DOCUMENT_ROOT'] . '/app.php';

    use \app as App;

    $app = new App('sitemap', 'Content-Type: application/xml; charset=utf-8');

    $app->startApplication();

    echo $app->getResponse();
?>