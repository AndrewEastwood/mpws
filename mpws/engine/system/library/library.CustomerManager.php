<?php


class libraryCustomerManager {

    private $_customerName;
    private $_customerVersion;
    private $_customerPath;
    private $_defaultPath;

    /* stored data */
    private $_s_configs;
    private $_s_templates;
    private $_model;

    /* extended library */
    private $_e_library;
    
    /* objects */
    protected $_databaseObj;

    /* dump data info */
    //private $_dump_configPaths;

    function __construct ($customerName = '', $doInit = true) {
        if (empty($customerName))
            $this->_customerName = MPWS_CUSTOMER;
        else
            $this->_customerName = $customerName;

        //echo DR . '/web/customer/' . $this->_customerName;
        
        /* init customer paths */
        $this->_customerPath = DR . '/web/customer/' . $this->_customerName;
        /* init version */
        if (file_exists($this->_customerPath . '/VERSION.txt'))
            $this->_customerVersion = trim(file_get_contents($this->_customerPath . '/VERSION.txt'));
        if (empty($this->_customerVersion))
            $this->_customerVersion = MPWS_VERSION;
        /* default path */
        $this->_defaultPath = DR . '/web/default/' . MPWS_VERSION;
        /* extended library */
        //$nativeExLibraryName = trim(str_replace(range(0,9), '', md5($this->_customerName)));
        $nativeExLibraryName = 'customer';
        $this->_e_library = DR . '/web/customer/' . $this->_customerName . '/' . $nativeExLibraryName . '.php';

        if ($doInit)
            $this->initManager();
        
        

        //$this->_model['CONFIG'] = $this->_s_configs[$this->_customerName];
    }

    public function getDatabaseObj() {
        return $this->_databaseObj;
    }
    /* model object */
    public function &getModel () {
        /*$m = array();

        $m['customer'] = $this->_customerName;
        $m['config']['display'] = $this->getCustomerConfiguration('display');
        $m['config']['social'] = $this->getCustomerConfiguration('social');
        $m['config']['adverisement'] = $this->getCustomerConfiguration('adverisement');
        $m['config']['seo'] = $this->getCustomerConfiguration('seo');
        $m['config']['resources'] = $this->getCustomerConfiguration('resources');
        $m[$scope] = $content;

        return $m;*/
        return $this->_model;
    }

    /* request gateway */
    public function callMethod ($methodNames, $params = false) {
        if (!file_exists($this->_e_library))
            return false;
        
        $exLibName = basename($this->_e_library, '.php');
        $exLibObj = new $exLibName();
        
        $results = array();
        $p = array();
        if (is_array($params))
            $p = $params;
        else
            $p[] = $params;
        
        // connect to db
        $mdbc = $this->getCustomerConfiguration('MDBC');
        if (!empty($mdbc))
            $this->_databaseObj->connect($mdbc);
        
        //var_dump($methodNames);
        
        foreach ($methodNames as $method) {
            $results[] = $exLibObj->$method($this, $p);
        }

        //print_r($p);
        return implode('', $results);
    }

    /* init methods */
    private function initManager () {
        //echo 'initManager';
        //$this->getCustomerConfiguration();
        //$this->getCustomerTemplate();
        //$this->getCustomerProperty();

        $this->_databaseObj = new libraryDataBaseChainQueryBuilder();
        
        if (file_exists($this->_e_library))
            require_once $this->_e_library;

    }

    public function getCustomerConfiguration ($name) {
        $key = strtoupper($name);
        // check if exists
        if (empty($key))
            return false;
        // return configuration
        if (!empty($this->_s_configs[$this->_customerName][$key]))
            return $this->_s_configs[$this->_customerName][$key];
        // load requested config
        $_default = $this->_defaultPath . '/config/'.strtolower($name).'.php';
        $_customer = $this->_customerPath . '/config/'.strtolower($name).'.php';
        //echo '<br>|DefaultPath: ' . $this->_defaultPath . '/config/'.$_name.'.php';
        //echo '<br>|CustomerPath: ' . $this->_customerPath . '/config/'.$_name.'.php';
        // get default config
        if (file_exists($_default))
            eval(file_get_contents($_default));
        else
            $default = array();
        // get customer config
        if (file_exists($_customer))
            eval(file_get_contents($_customer));
        else
            $customer = array();
        $this->_s_configs[$this->_customerName][$key] = array_merge($default[$key], $customer[$key]);
        return $this->_s_configs[$this->_customerName][$key];
    }

    public function getCustomerTemplate ($name) {

        $name = strtolower($name);
        // return existed template
        if (!empty($this->_s_templates[$this->_customerName][$name]))
            return $this->_s_templates[$this->_customerName][$name];
        
        
        $_default = $this->_defaultPath . '/templates/' . str_replace(DOT, DS, $name) . '.html';
        $_customer = $this->_customerPath . '/templates/' . str_replace(DOT, DS, $name) . '.html';
        
        //echo '<br>| Customer Template file: ' . $_customer;
        //echo '<br>| Default Template file: ' . $_default;
        
        if (file_exists($_default))
            $this->_s_templates[$this->_customerName][$name] = $_default;
        if (file_exists($_customer))
            $this->_s_templates[$this->_customerName][$name] = $_customer;
        
        return $this->_s_templates[$this->_customerName][$name];
        
        /*
        $_default = $this->_defaultPath . '/templates/';
        $_customer = $this->_customerPath . '/templates/';

        $templatesC = libraryFileManager::getAllFilesFromDirectoryAsMap($_customer, '.html');
        $templates = libraryFileManager::getAllFilesFromDirectoryAsMap($_default, '.html');
        
        foreach ($templatesC as $key => $tpl)
            $templates[$key] = $tpl;
        
        // store loaded configuration files
        $this->_s_templates[$this->_customerName] = $templates;
        return false;

        */
     }

    public function getCustomerProperty ($name = '') { }

    /* properties */
    public function getCustomerName () {
        return $this->_customerName;
    }

    public function getCustomerVersion () {
        return $this->_customerVersion;
    }

    public function getCustomerPath () {
        return $this->_customerPath;
    }

    public function getDefaultPath () {
        return $this->_defaultPath;
    }

    /* other */
    public function getDump () {
        $dump = 'Customr Info:<br>';
        $dump .= '<br> Name: ' . $this->_customerName;
        $dump .= '<br> Ex.Library: ' . $this->_e_library;
        $dump .= '<br> Path: ' . $this->_customerPath;
        $dump .= '<br> Parent: ' . $this->_defaultPath;
        $dump .= '<br> Request GET: ';
        foreach ($_GET as $key => $val)
            $dump .= '<br>' . (str_pad('', 10, '.')) . '[' . str_pad($key, 25, ' ') . '] <=====> ' . $val;
        $dump .= '<br> Request POST: ';
        foreach ($_POST as $key => $val)
            $dump .= '<br>' . (str_pad('', 10, '.')) . '[' . str_pad($key, 25, ' ') . '] <=====> ' . $val;
        $dump .= '<hr size="2">';
        $dump .= '<br> Configurations:<br>';
        if (!empty($this->_s_configs[$this->_customerName]))
        foreach ($this->_s_configs[$this->_customerName] as $key => $val)
            $dump .= '<br> -- ' . $key . ' from ' . $val;
        $dump .= '<br> Templates:<br>';
        if (!empty($this->_s_templates[$this->_customerName]))
        foreach ($this->_s_templates[$this->_customerName] as $key => $val)
            $dump .= '<br> -- ' . $key . ' from ' . $val;

        return $dump;
    }

}


?>
