<?php

class contextToolbox extends objectContext  {

   /* protected $_customerObj;
    protected $_pluginsObj;
    protected $_databaseObj;
    protected $_model;*/
    
    /* We store plugins inside toolbox context
     * The Customer Context object has an access to current context
     */
    private $_pluginManager;

    function __construct () {
        
        // do not init older version at startup
        $this->_pluginManager = new libraryPluginManager(false);
        
        debug('contextToolbox __construct');
        
        /*$this->_customerObj = new libraryCustomerManager($customerName);
        $this->_pluginsObj = new libraryPluginManager($this);
        $this->_databaseObj = new libraryDataBaseChainQueryBuilder();
        $this->initManager();*/
    }
    
    final public function call ($command) {
        debug('contextToolbox => Running command: ' . $command[makeKey('method')]);
        $this->_pluginManager->runPluginAsync($command);
        //$runner->traceCommands();
    }
    
    /* init methods */
    protected function initManager () {
        /* init datatabse */
        /*$mdbc = $this->_customerObj->getCustomerConfiguration('MDBC');
        //var_dump($mdbc);
        $this->_databaseObj->connect($mdbc);
        $this->_model = array();
        $this->_model['PLUGINS'] = array();
        
        
        $this->_pluginsObj->setContext($this);*/
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
        //return $this->_customerObj;
    }
    public function getDatabaseObj() {
        //return $this->_databaseObj;
    }
    public function getPluginsObj() {
        //return $this->_pluginsObj;
    }
    public function &getModel() {
        //return $this->_model;
    }

    public function callMethod ($methodNames, $params = false) {
        //----global $config;
        // skip requested method name
        // otherwise simply make chnages according to it
        
        //echo $this->getDump();
        
        /*$results = array();
        foreach ($methodNames as $method)
            $results[] = $this->_pluginsObj->runPlugins($method, &$this);
        //var_dump($results);
        $model = $this->getModel();*/
        
        //echo  libraryStorage::cache('demo');
        //echo '<br>-----------------------------------------<br>';
        //var_dump(libraryStorage::storage('__all__'));
        
        //var_dump($this->getStorage());
        
        /*if (empty($model['HTML']['CONTENT']) && empty($model['html']['content']) && empty($gStore['HTML.CONTENT']))
            return 'The page you have requested cannot be found.';
        return implode('', $results);*/

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
        //return $this->_pluginsObj->getDump();
    }
    
    /* crossmode call */
    //static private $pluginsObj;
    //static public function callPluginMethod ($pluginName, $methodName, $params = false) {
        //echo 'callPluginMethod<br>';
        //echo '------ callPluginMethod 1<br>';
        /*if (!self::$pluginsObj)
            self::$pluginsObj = new libraryPluginManager(false);
        //echo 'callPluginMethod ' . $pluginName;
        $po = self::$pluginsObj->loadPlugin($pluginName);
        //echo '------ callPluginMethod 2<br>';
        //var_dump($po);
        $params['fn'] = $methodName;
        //echo '------ callPluginMethod 3<br>';
        //var_dump($params);
        $rez = $po->cross_method($params);
        //echo '------ callPluginMethod 4<br>';
        //unlink($po);
        //unlink($pluginsObj);
        return $rez;*/
    //}

}

?>
