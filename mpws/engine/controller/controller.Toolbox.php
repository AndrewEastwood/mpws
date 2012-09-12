<?php

class controllerToolbox {

    public function processRequests() {
        //global $config;

        
        debug('controllerToolbox => processRequests');
        
        $content = 'default';
        
        
        
        $mpwsCtx = contextMPWS::instance();
        //$tbx = $mpwsCtx->contextToolbox;
        //var_dump($tbx);
        //$tbx = $mpwsCtx->getContext('Toolbox');
        //var_dump($tbx);
        
        //$mpwsCtx->addBatchCommands('main', 'render', 'layout');
        //$mpwsCtx->modifyCommand('main', array('olololo'));
        
        $mpwsCtx->addCommand(array('main'));
        
        //$mpwsCtx->traceCommands();
        
        $mpwsCtx->processAll('Toolbox');
        
        //echo libraryMetaPage::getComponent('test');
        //$tbx->call(false, false);

        echo $mpwsCtx->pageModel->fetchHtmlPage();

        /*$toolbox = new contextToolbox();
        //echo 'Requested page : ' . $page;
        $content = 'default';
        /* get requested page * /
        switch (strtolower($_GET['page'])) {
            /* put override here for page request * /
            default : {
                $methodsToRun = array('main');
                if (strtolower($_GET['action']) == 'api') {
                    // we set scope for requested plugin only
                    $methodsToRun[] = $_GET['page'] . '@api';
                } else {
                    $methodsToRun[] = 'render';
                    // call method 'layout' of startup plugin only
                    $methodsToRun[] = $config['TOOLBOX']['STARTUP_PLUGIN'] . '@layout';
                }
                
                $content = $toolbox->callMethod($methodsToRun);
                break;
            }
        }*/
        
        
        /* perform requested post action */
        switch (strtolower($_GET['action'])) {
            /* put override here for action request */
            case 'api':
                //echo 'action API';
                break;
            case 'default':
            default : {
                break;
            }
        }
        
        echo $content;
        
        //debug($toolbox->getDump());
    }

}

?>
