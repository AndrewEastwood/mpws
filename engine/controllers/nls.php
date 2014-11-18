<?php
    namespace engine\controller;
    include $_SERVER['DOCUMENT_ROOT'] . '/app.php';

    use \app as App;
    use \engine\lib\request as Request;
    use \engine\lib\path as Path;
    // header('Content-Type: application/json; charset=utf-8');

    $app = new App('nls', 'Content-Type: application/json; charset=utf-8');
    // include $_SERVER['DOCUMENT_ROOT'] . '/engine/bootstrap.php';

    $customer = Request::pickFromGET('customer');
    $langFileName = Request::pickFromGET('lang');

    if ($app->isDebug())
        $langFilePath = Path::createPathWithRoot('web', 'customer', $customer, 'static', 'nls', $langFileName);
    else
        $langFilePath = Path::createPathWithRoot('web', 'build', 'customer', $customer, 'static', 'nls', $langFileName);

    if (file_exists($langFilePath))
        echo file_get_contents($langFilePath);
    else
        echo "{}";

?>