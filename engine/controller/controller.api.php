<?php

    header('Content-Type: application/json; charset=utf-8');
    // set request realm
    define ('MPWS_REQUEST', 'API');
    // start session
    session_start();
    // bootstrap
    include $_SERVER['DOCUMENT_ROOT'] . '/engine/bootstrap.php';

    libraryCustomer::runCustomer();
    libraryResponse::sendResponse();

?>