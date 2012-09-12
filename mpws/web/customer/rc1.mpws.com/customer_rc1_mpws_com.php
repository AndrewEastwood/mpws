<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class customer_rc1_mpws_com extends objectBaseWebCustomer {
    
    protected function objectCustomSetup() {
        parent::objectCustomSetup();
        $this->setMeta('PATH_DEF', DR . '/web/default/' . $this->objectConfiguration_customer_version);
    }
    
    
    public function run ($command) { 
        parent::run($command);
        $ctx = contextMPWS::instance();
        
        $ctx->pageModel->addWidget('toolboxmenu2',
            $this->objectTemplatePath_widget_demo2,
            $ctx->getCurrentContextName());
        
        $ctx->pageModel->setPageView(
            $this->objectTemplatePath_page_test,
            $ctx->getCurrentContextName());
    }
    
}

?>
