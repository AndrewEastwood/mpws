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
        /*
        $customer = new libraryCustomerManager();
        //echo 'Requested page : ' . $page;
        $content = '';
        /* get requested page * /
        switch (strtolower($_GET['page'])) {
            /* put override here for page request * /
            default : {
                $methodsToRun = array('main');
                if (strtolower($_GET['action']) == 'api')
                    $methodsToRun[] = 'api';
                else {
                    $methodsToRun[] = 'render';
                    // call method 'layout' to combine all html results
                    $methodsToRun[] = 'layout';
                }
                $content = $customer->callMethod($methodsToRun);
                break;
            }
        }*/
        
        /* perform requested action */
        /*switch (strtolower($_GET['action'])) {
            / * put override here for action request * /
            case 'default':
            default : {
                break;
            }
        }*/
        //$dump = $customer->getDump();
        //echo '<div style="margin:10px;padding:10px;border:1px solid #333"><pre>' . print_r($model, true) . '</pre></div>';
        //echo '<div style="margin:10px;padding:10px;border:1px solid #333;background:#aaa;color:#333;"><pre>' . $dump . '</pre></div>';
        //echo $content;
    }

}

?>
