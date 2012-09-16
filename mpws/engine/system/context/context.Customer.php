<?php

class contextCustomer extends objectContext {

    private $_databaseManagers;
    private $_customerManager;

    function __construct () {
        debug('contextCustomer __construct');
        $this->_customerManager = new libraryCustomerManager();
    }
    
    final public function call ($command) {
        debug('contextCustomer => Running command: ' . $command[makeKey('method')]);
        $this->_customerManager->runCustomerAsync($command);
    }
    
    final public function getDBO($customerName = MPWS_CUSTOMER) {
        echo '<br> getting DataBaseObject for '.$customerName.'>>>>>>';
        // return existed dbo
        if (isset($this->_databaseManagers[$customerName]))
            return $this->_databaseManagers[$customerName];
        // get customer
        $customer = $this->_customerManager->getCustomer($customerName);
        // make connect config
        $this->_databaseManagers[$customerName] = new libraryDataBaseChainQueryBuilder($customer->getDBConnection());
        // return dbo
        return $this->_databaseManagers[$customerName];
    }

}


?>
