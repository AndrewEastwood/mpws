<?php
namespace engine\object;

class api implements \engine\interface\IApi {

    private $customer;
    private $plugin;

    function __construct ($customer, $plugin, $pluginName) {
        $this->customer = $customer;
        $this->plugin = $plugin;
    }

    public function getCustomer() {
        return $this->customer;
    }
    public function getCustomerDataBase () {
        return $this->customer->getDataBase();
    }
    public function getPlugin () {
        return $this->plugin;
    }
    public function getAnotherPlugin ($pluginName) {
        $anotherPlugin = $this->getCustomer()->getPlugin($pluginName);
        return $anotherPlugin;
    }

    // public function getList() {}
    // public function getItem() {}
    // public function saveItem() {}
    // public function updateItem() {}
    // public function deleteItem() {}
}

?>