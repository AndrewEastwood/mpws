<?php

    header('Content-Type: application/json');
    // set request realm
    define ('MPWS_REQUEST', 'API');
    // start session
    session_start();
    // bootstrap
    include $_SERVER['DOCUMENT_ROOT'] . '/engine/bootstrap.php';

    // // all js api requests produce JSON data
    // debug('controllerPublic => processRequests');
    // $mpwsCtx = contextMPWS::instance();

    // debug('controllerPublic => processRequests: adding command');
    // $mpwsCtx->addCommand(array(MPWS_CUSTOMER.DOG.'jsapi'));

    // debug('controllerPublic => processRequests: process commands with Customer');
    // $mpwsCtx->processAll('Customer');

    // debug('controllerPublic => processRequests: fetch html page');
    // echo $mpwsCtx->pageModel->fetchStaticData();

    // $request = new
    // do not put $customer into global scope
    function response () {
        $customer = new libraryCustomer();
        return $customer->getResponse();
    }
    
    echo response();

?>
