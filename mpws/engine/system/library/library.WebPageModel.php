<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class libraryWebPageModel {

    private $_widgets;
    private $_page;
    private static $_instance;
    
    private function __construct() { }
    
    public static function instance () {
        if (empty(self::$_instance))
            self::$_instance = new libraryWebPageModel();
        return self::$_instance;
    }

    public function setPageView ($template, $data, $contextName) {
        $this->_page = array (
            'DATA' => $data,
            'CONTEXT' => $contextName,
            'TEMPLATE' => $template,
            'HTML' => ''
        );
    }
    
    public function addWidget($name, $template, $contextName, $data = array()) {
        $this->_widgets[makeKey($name, true)] = array (
            'NAME' => makeKey($name, true),
            'DATA' => $data,
            'CONTEXT' => $contextName,
            'TEMPLATE' => $template,
            'HTML' => ''
        );
    }
    
    public function fetchHtmlPage () {
        // get context
        $ctx = contextMPWS::instance();
        // fetch wigets
        foreach ($this->_widgets as $key => $value) {
            $this->_widgets[$key]['HTML'] = $this->fetchTemplate($value);
        }
        // set root block
        
        // fetch page
        return $this->fetchTemplate($this->_page);
    }
    
    public function fetchTemplate ($wgt) {
        $smarty = new extensionSmarty();
        
        $data = array(
            'H' => 'HELLO',
            'W' => array(
                'TITLE' => 'WORLD'
            )
        );

        $smarty->assign($data);
        
        echo $smarty->fetch($wgt);

        return 'DEMO';
    }
    
    public function dump() {
        return debug($this->_widgets, 'libraryWebPageModel widgets');
    }
    
    
}

?>
