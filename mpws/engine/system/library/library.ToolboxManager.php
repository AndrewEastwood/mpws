<?php

class libraryToolboxManager {

    protected $_customerObj;
    protected $_pluginsObj;
    protected $_databaseObj;
    protected $_model;

    function __construct ($customerName = '') {
        $this->_customerObj = new libraryCustomerManager($customerName);
        $this->_pluginsObj = new libraryPluginManager();
        $this->_databaseObj = new libraryDataBaseChainQueryBuilder();
        $this->initManager();
    }
    
    /* init methods */
    protected function initManager () {
        /* init datatabse */
        $mdbc = $this->_customerObj->getCustomerConfiguration('MDBC');
        //var_dump($mdbc);
        $this->_databaseObj->connect($mdbc);
        $this->_model = array();
        $this->_model['PLUGINS'] = array();
    }

    /*protected function getManagerObject() {
        return array(
            'CUSTOMER' => $this->_customerObj,
            'PLUGINS' => $this->_pluginsObj,
            'DATABASE' => $this->_databaseObj,
            'MODEL' => &$this->_model
        );
    }*/

    public function getCustomerObj() {
        return $this->_customerObj;
    }
    public function getDatabaseObj() {
        return $this->_databaseObj;
    }
    public function getPluginsObj() {
        return $this->_pluginsObj;
    }
    public function &getModel() {
        return $this->_model;
    }

    public function callMethod ($methodNames, $params = false) {
        //----global $config;
        // skip requested method name
        // otherwise simply make chnages according to it
        $results = array();
        foreach ($methodNames as $method)
            $results[] = $this->_pluginsObj->runPlugins($method, &$this);
        //var_dump($results);
        return implode('', $results);

        //$allPlugins = $this->_pluginsObj->getAllPlugins();
        //foreach ($allPlugins as $_name => $_plugin)
        //    echo $_plugin['obj']->performPlugin($this, $_plugin);

        //$allPlugins[$config['TOOLBOX']['STARTUP_PLUGIN']]['obj']

        /* standalone implementation */
        // get startup plugin
        //$startupPlugin = $this->_pluginsObj->getPlugin($config['TOOLBOX']['STARTUP_PLUGIN']);
        //var_dump($startupPlugin);
        // model has all data reterived from plugins
        // so let's combine it with templates
        // we'll use defined plugins as startup in the configration
        //--------$startupPlugin = $this->_pluginsObj->getPlugin($config['TOOLBOX']['STARTUP_PLUGIN']);
        // check if the plugin has template file to render
        //echo 'ololo';
        //var_dump($startupPluginObj);
        //------if (!empty($startupPlugin))
        //------    return $startupPlugin['obj']->layout($this, $startupPlugin);


        //var_dump($this->getModel());

        //return 'Problems with rendering page';// $startupPlugin['obj']->performPlugin($this, $startupPlugin);
    }

    public function getDump() {
        return $this->_pluginsObj->getDump();
    }

}

?>
