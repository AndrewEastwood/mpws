<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
_import ('extension@Smarty');

class extensionSmarty extends Smarty {
   function __construct()
   {

        // Class Constructor.
        // These automatically get set with each new instance.

        parent::__construct();
        
        //$this->setTemplateDir(DR . DS . 'data'. DS . 'templates' . DS);
        $this->setCompileDir(DR . DS . 'data'. DS . 'bin' . DS . 'templates_c' . DS);
        $this->setConfigDir(DR . DS . 'data'. DS . 'bin' . DS . 'configs' . DS);
        $this->setCacheDir(DR . DS . 'data'. DS . 'bin' . DS . 'cache' . DS);

        $this->caching = Smarty::CACHING_LIFETIME_CURRENT;
        $this->assign('app_name', 'myPhpWebSite');
        
   }
}

?>
