<?php
/*
 * Customer: PB.COM.UA
 */
class customerPbComUa extends objectBaseWebCustomer {

    protected function _displayTriggerAsCustomer () {
        $ctx = contextMPWS::instance();
        $ret = parent::_displayTriggerAsCustomer();

        $ctx->pageModel->setPageView($this->objectTemplatePath_layout_defaultLayout);
        return $ret;
    }
}

?>