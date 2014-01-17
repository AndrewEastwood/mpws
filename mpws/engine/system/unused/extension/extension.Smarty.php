<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
_import ('extension@Smarty');

class extensionSmarty extends Smarty {

    //private static $_instance;

    function __construct() {

        // Class Constructor.
        // These automatically get set with each new instance.

        //echo '1';
        parent::__construct();

        //$this->setTemplateDir(DR . DS . 'data'. DS . 'templates' . DS);
        $this->setCompileDir(DR . DS . 'data'. DS . 'bin' . DS . 'templates_c' . DS);
        $this->setConfigDir(DR . DS . 'data'. DS . 'bin' . DS . 'configs' . DS);
        $this->setCacheDir(DR . DS . 'data'. DS . 'bin' . DS . 'cache' . DS);

        $this->caching = Smarty::CACHING_LIFETIME_CURRENT;
        $this->assign('app_name', 'myPhpWebSite');
    }
    
    /*public static function instance () {
        echo '1';
        if (empty(self::$_instance))
            self::$_instance = new extensionSmarty();
        return self::$_instance;
    }*/
    
}

?>
