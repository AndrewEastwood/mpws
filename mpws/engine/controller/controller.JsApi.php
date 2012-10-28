<?php

class controllerJsApi {

    public function processRequests() {
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
