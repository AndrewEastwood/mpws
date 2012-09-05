<?php
    
  
class pluginShop extends objectBaseContextualWebPlugin {  

    public function __construct ($context = false) {
        parent::__construct($context, 'shop');
        echo 'pluginShop CONSTRUCT';
    }
    
    final protected function displayTriggerOnCommonStart () {
        //echo 'SHOP displayTriggerOnCommonStart';
        //echo $this->dump();
        
        
        
    }
    final protected function displayTriggerOnActive () {
        echo '<br> IS ACTIVE <br>';
        parent::displayTriggerOnActive();
        switch (libraryRequest::getDisplay('home')) {
            case 'home' :
            default : {
                $this->_displayQueue();
                break;
            }
        }
    }
    
    /* PLUGIN SPEC METHODS */
    
    private function _displayQueue () {
        /*echo '_displayQueue';
        
        
        
        
        $this->store_storeSet('TEMPLATE.PATH', $this->res_getResource('page.queue.datatable'));
        $this->store_storeSet('TEMPLATE.NAME', 'page.queue.datatable');
        //$pModel = &$this->getModel();
        
        //var_dump($store);
        //$store['TEMPLATE'] = $this->getTemplate('page.queue.datatable');
        
        
        // menu component
        $menu = libraryView::getLinks($this->getConfiguration('GENERAL', 'MENU'));
        
        //var_dump($menu);
        
        $this->addComponent('MENU', $menu, 'menu_list');*/
        
    }
    
    
}
    
?>