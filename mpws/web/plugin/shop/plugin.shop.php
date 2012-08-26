<?php
    
  
class pluginShop extends objectPlugin {  

    function __construct ($context = false) {
        parent::__construct($context, 'shop');
        //echo 'pluginShop CONSTRUCT';
    }
    
    final function displayTriggerOnCommonStart () {
        //echo 'SHOP displayTriggerOnCommonStart';
        //echo $this->dump();
        
        
        //echo $this->getConfiguration('GENERAL', 'NAME');
    }
}
    
?>