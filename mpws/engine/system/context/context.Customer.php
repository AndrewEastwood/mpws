<?php


class contextCustomer extends objectContext {

    private $_databaseManager;
    private $_customerManager;
    
    
    function __construct () {
        debug('contextCustomer __construct');
        $this->_databaseManager = new libraryDataBaseChainQueryBuilder();
        $this->_customerManager = new libraryCustomerManager();
    }
    
    final public function call ($command) {
        debug('contextCustomer => Running command: ' . $command[makeKey('method')]);
        $this->_customerManager->runCustomerAsync($command);
    }


}


?>
