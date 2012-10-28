<?php

class controllerPublic {

    public function processRequests() {
        debug('controllerPublic => processRequests');
        $mpwsCtx = contextMPWS::instance();
        debug('controllerPublic => processRequests: adding command');
        $mpwsCtx->addCommand(array(MPWS_CUSTOMER.DOG.'main'));
        debug('controllerPublic => processRequests: process commands with Customer');
        $mpwsCtx->processAll('Customer');
        debug('controllerPublic => processRequests: fetch html page');
        echo $mpwsCtx->pageModel->fetchHtmlPage();
    }
}

?>
