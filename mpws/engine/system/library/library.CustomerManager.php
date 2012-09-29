<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of libraryCustomerManager
 *
 * @author admin1
 */
class libraryCustomerManager {
    protected $_customerPath;
    protected $_defaultPath;
    
    /* stored data */
    private $_s_customers;
    
    public function __construct () {
        $this->_customerPath = DR . '/web/customer';
        $this->_defaultPath = DR . '/web/default/' . MPWS_VERSION;
    }
    
    
    public function getCustomer ($name) {
        if (empty($name))
            throw new Exception('libraryCustomerManager: empty customer name');

        // return already loaded object
        if (!empty($this->_s_customers[makeKey($name, true)])) {
            debug('libraryCustomerManager: getCustomer Return Existed Customer Object: ' . $name);
            return $this->_s_customers[makeKey($name, true)];
        }
        
        $customerFileName = OBJECT_T_CUSTOMER . BS . str_replace(array('.', '-'), '_', $name) . EXT_SCRIPT;
        $customerFilePath = $this->_customerPath . DS . $name . DS . $customerFileName;
       
        debug('libraryCustomerManager: getCustomer path: ' . $customerFilePath);

        if (!file_exists($customerFilePath))
            throw new Exception('libraryCustomerManager: path does not exists: ' . $customerFilePath);

        // load customer
        include $customerFilePath;
        $customerObjectName = basename($customerFileName, EXT_SCRIPT);
        debug('libraryCustomerManager: getCustomer plugin name: ' . $customerObjectName);

        // store customer
        $this->_s_customers[makeKey($name, true)] = new $customerObjectName($name);
        
        // return customer
        return $this->_s_customers[makeKey($name, true)];
    }
    
    public function runCustomerAsync ($command) {
        debug($command, 'libraryCustomerManager: runCustomerAsync action:');
        // get specific caller (plugin)
        $_caller = $command->getCaller();
        // get plugin object
        $plugin = $this->getCustomer($_caller);
        // send message
        $plugin->run($command);
    }
    
    
}

?>
