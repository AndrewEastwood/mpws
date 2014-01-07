<?php

class controllerJsApi {

    public function processRequests() {
        // all js api requests produce JSON data
        header('Content-Type: application/json');
        debug('controllerPublic => processRequests');
        $mpwsCtx = contextMPWS::instance();
        debug('controllerPublic => processRequests: adding command');
        $mpwsCtx->addCommand(array(MPWS_CUSTOMER.DOG.'jsapi'));
        debug('controllerPublic => processRequests: process commands with Customer');
        $mpwsCtx->processAll('Customer');
        debug('controllerPublic => processRequests: fetch html page');
        echo $mpwsCtx->pageModel->fetchStaticData();
    }
}

?>
