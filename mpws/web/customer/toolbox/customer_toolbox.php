<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class customer_toolbox extends objectBaseWebCustomer {

    public function run ($command) { 
        parent::run($command);
        $ctx = contextMPWS::instance();
        
        //$ctx->contextCustomer->getDBO();
        
        // get plugin
        $plgToolbox = $ctx->contextToolbox->getPlugin('toolbox');
        
        //echo '<br>|' . $this->objectTemplatePath_layout_page;
        //echo '<br>|' . $plgToolbox->objectTemplatePath_widget_demo23;
        //echo '<br>|' . $plgToolbox->objectTemplatePath_widget_demo2;
        //echo '<br>|' . $plgToolbox->objectTemplatePath_widget_demo11;
        
        //echo $this->objectTemplatePath_widget_demo2;
        
        $ctx->pageModel->setSiteObject($this)
            ->addWebObject($plgToolbox)
            ->setPageView($this->objectTemplatePath_layout_default);
        
        
        
    }
    
}

?>
