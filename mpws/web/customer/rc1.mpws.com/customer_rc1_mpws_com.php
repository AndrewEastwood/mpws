<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class customer_rc1_mpws_com extends objectBaseWebCustomer {

    public function run ($command) { 
        parent::run($command);
        $ctx = contextMPWS::instance();
        
        //$ctx->contextCustomer->getDBO();
        
        // get plugin
        $plgToolbox = $ctx->contextToolbox->getPlugin('toolbox');
        
        
        echo '<br>|' . $plgToolbox->objectTemplatePath_widget_demo23;
        echo '<br>|' . $plgToolbox->objectTemplatePath_widget_demo2;
        echo '<br>|' . $plgToolbox->objectTemplatePath_widget_demo11;
        
        //echo $this->objectTemplatePath_widget_demo2;
        
        
        
       /* $ctx->pageModel->addWidget('toolboxmenu2',
            $this->objectTemplatePath_widget_demo2,
            $ctx->getCurrentContextName());
        
        $ctx->pageModel->setPageView(
            $this->objectTemplatePath_page_test,
            $ctx->getCurrentContextName());
        
        */
        
    }
    
}

?>
