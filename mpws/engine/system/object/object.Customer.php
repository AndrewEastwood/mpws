<?php

class objectCustomer {

    private $dbo;
    private $plugins;
    private $customerInfo;

    function __construct() {

        // init dbo
        $this->dbo = new libraryDataBase(configurationCustomerDatabase::$DBOini);

        // init plugins
        $_pluginPath = glGetFullPath('web', 'plugin');
        foreach (configurationCustomerDisplay::$Plugins as $pluginName) {

            $pluginFileName = OBJECT_T_PLUGIN . DOT . $pluginName . EXT_SCRIPT;
            $pluginFilePath = $_pluginPath . DS . $pluginName . DS . $pluginFileName;

            debug('libraryPluginManager: getPluginWithContext plugin path: ' . $pluginFilePath);

            if (!file_exists($pluginFilePath))
                throw new Exception('MPWS PluginManager library: path does not exists: ' . $pluginFilePath);
            
            // load plugin
            include $pluginFilePath;
            $matches = null;
            preg_match('/^(\\w+).(\\w+).(\\w+)$/', $pluginFileName, $matches);
            $pluginObjectName = trim($matches[1]).trim($matches[2]);

            // save plugin instance
            $this->plugins[$pluginName] = new $pluginObjectName($this);
        }
    }

    public function getCustomerID () {
        $info = $this->getCustomerInfo();
        return isset($info['ID']) ? $info['ID'] : null;
    }

    public function getCustomerInfo () {
        if (empty($this->customerInfo)) {
            $config = configurationCustomerDataSource::jsapiGetCustomer();
            $this->customerInfo = $this->getDataBase()->getData($config);
        }
        return $this->customerInfo;
    }

    public function getDataBase () {
        return $this->dbo;
    }

    public function processData ($config) {
        $customerInfo = $this->getCustomerInfo();
        if (empty($config["condition"]["filter"])) {
            $config["condition"]["filter"] = "CustomerID (=) ?";
            $config["condition"]["values"] = array($customerInfo['ID']);
        } else {
            $config["condition"]["filter"] = "CustomerID (=) ? + " . $config["condition"]["filter"];
            array_unshift($config["condition"]["values"], $customerInfo['ID']);
        }
        // var_dump($config);
        return $this->dbo->getData($config);
    }

    public function getPlugins () {
        return $this->plugins;
    }

    public function getResponse () {

        // $response = array();
        $response = new libraryDataObject();

        // $p = libraryRequest::getApiParam();
        // $caller = libraryRequest::getApiCaller();
        $publicKey = libraryRequest::getValue('token');
        $source = libraryRequest::getValue('source');

        if (empty($source))
            throw new Exception('objectCustomer => getResponse: wrong source value', $source);

        if (!isset($this->plugins[$source]))
            throw new Exception('objectCustomer => getResponse: source is not allowed', $source);

        // check page token
        if (empty($publicKey))
            return $response;

        // // check request realm
        // if (empty($p['realm']))
        //     return $response;

        if (!libraryRequest::getOrValidatePageSecurityToken(configurationCustomerDisplay::$MasterJsApiKey, $publicKey)) {
            // if (md5(configurationCustomerDatabase::$MasterJsApiKey) !== $publicKey)
            $response->setError('Invalid public token key');
            return $response;
        }

        // if (libraryRequest::getValue('fn') === "configuration") {
        //     return 
        // }


        // perform request with plugins
        // if ($p['realm'] == OBJECT_T_PLUGIN) {
            if ($source == '*' && !configurationCustomerDatabase::$AllowWideJsApi)
                throw new Exception('objectCustomer => getResponse: wide api js request is not allowed');

            if ($source == '*')
                foreach ($this->plugins as $key => $plugin)
                    $response->setData($key, $plugin->getResponse()->toNative());
            elseif (isset($this->plugins[$source])) {
                $plugin = $this->plugins[$source];
                $response->setData($source, $plugin->getResponse()->toNative());
            }
        // }

        // if ($p['realm'] == OBJECT_T_CUSTOMER) {
        //     // otherwise proceed with customer
        //     // $response[OBJECT_T_CUSTOMER] = array();
        // }

        return $response;


        // $p = libraryRequest::getApiParam();
        // $caller = libraryRequest::getApiCaller();

        // if (empty($caller))
        //     throw new Exception('objectBaseWeb => _jsapiTriggerAsCustomer: wrong caller value', $caller);
        
        // // check page token
        // if (empty($publicKey))
        //     return;
        
        // if (!libraryRequest::getOrValidatePageSecurityToken($p['token'])) {
        //     // page token is wrong
        //     // try to verify master key
        //     // echo 'try to verify master key: ' . $this->objectConfiguration_customer_masterJsApiKey;
        //     //echo '<br> ' . md5($this->objectConfiguration_customer_masterJsApiKey);
        //     //echo '<br>token: ' . $p['token'];

        //     if (md5($this->objectConfiguration_customer_masterJsApiKey) !== $p['token'])
        //         return;
        // }

        // // echo '2 Caller is ' . $caller, $p['realm'];
        // //echo print_r($p, true);
        // // perform request with plugins
        // if (!empty($p['realm']) && $p['realm'] == OBJECT_T_PLUGIN) {
        //     // check if wide js api is allowed
        //     if ($p['realm'] == '*' && !$this->objectConfiguration_customer_allowWideJsApi)
        //         throw new Exception('objectBaseWeb => _jsapiTriggerAsCustomer: wide api js request is not allowed');
        //     // perform request with plugins
        //     // echo 'OLOLOLOLO = ' . $caller . DOG . 'jsapi:default', 'Toolbox';
        //     // return $ctx->directProcess($caller . DOG . 'jsapi:default', 'Toolbox');
        // } else {
        //     // echo 'ASDF';
        //     // otherwise proceed with customer
        // }
        // return false;
    }

    public function addAccount ($dataAccount) {

        $dataAccount["CustomerID"] = $this->getCustomerID();
        $dataAccount["ValidationString"] = md5(time());
        $dataAccount['Password'] = $this->getAccountPassword($dataAccount['Password']);
        $dataAccount['IsTemporary'] = 1;
        $dataAccount['DateCreated'] = date('Y:m:d H:i:s');
        $dataAccount['DateUpdated'] = date('Y:m:d H:i:s');
        $config = configurationCustomerDataSource::jsapiAddAccount();
        $config['data'] = array(
            "fields" => array_keys($dataAccount),
            "values" => array_values($dataAccount)
        );
        $this->getDataBase()->getData($config);
        return $this->getDataBase()->getLastInsertId();
    }

    public function getAccount ($login, $password, $encodePassword = true) {
        if ($encodePassword)
            $password = $this->getAccountPassword($password);
        $config = configurationCustomerDataSource::jsapiGetAccount($login, $password);
        // var_dump($config);
        $profile = $this->getDataBase()->getData($config);
        if (isset($profile['ID']))
            $profile["addresses"] = $this->getAccountAddresses($profile['ID']);
        return $profile;
    }

    public function activateAccount ($ValidationString) {
        $config = configurationCustomerDataSource::jsapiActivateAccount($ValidationString);
        $this->getDataBase()->getData($config);
    }

    public function removeAccount ($dataAccount) {
        // if (!$this->isAccountSignedIn())
        //     return false;
        $AccountID = $dataAccount['AccountID'];
        unset($dataAccount['AccountID']);
        $dataAccount['DateUpdated'] = date('Y:m:d H:i:s');
        $config = configurationCustomerDataSource::jsapiRemoveAccount($AccountID);
        $config['data'] = array(
            "fields" => array_keys($dataAccount),
            "values" => array_values($dataAccount)
        );
        $this->getDataBase()->getData($config);
    }

    public function updateAccount ($dataAccount) {
        // if (!$this->isAccountSignedIn())
        //     return false;
        $AccountID = $dataAccount['AccountID'];
        unset($dataAccount['AccountID']);
        if (isset($dataAccount['Password']))
            $dataAccount['Password'] = $this->getAccountPassword($dataAccount['Password']);
        $dataAccount['DateUpdated'] = date('Y:m:d H:i:s');
        $config = configurationCustomerDataSource::jsapiUpdateAccount($AccountID);
        $config['data'] = array(
            "fields" => array_keys($dataAccount),
            "values" => array_values($dataAccount)
        );
        $this->getDataBase()->getData($config);
    }

    public function updateAccountPassword ($dataAccount) {
        $AccountID = $dataAccount['AccountID'];
        unset($dataAccount['AccountID']);
        if (isset($dataAccount['Password']))
            $dataAccount['Password'] = $this->getAccountPassword($dataAccount['Password']);
        $dataAccount['DateUpdated'] = date('Y:m:d H:i:s');
        $config = configurationCustomerDataSource::jsapiUpdateAccount();
        $config['data'] = array(
            "fields" => array_keys($dataAccount),
            "values" => array_values($dataAccount)
        );
        $this->getDataBase()->getData($config);
        return $dataAccount['Password'];
    }

    public function getAccountPassword ($rawPassword) {
        $key = '!MPWSservice123';
        return md5($key . $rawPassword);
    }

    public function getAccountAddresses ($AccountID) {
        // if (!$this->isAccountSignedIn() && !$force)
        //     return false;
        $config = configurationCustomerDataSource::jsapiGetAccountAddresses($AccountID);
        return $this->getDataBase()->getData($config);
    }

    public function getAccountAddress ($AccountID, $AddressID) {
        $config = configurationCustomerDataSource::jsapiGetAccountAddress($AccountID, $AddressID);
        return $this->getDataBase()->getData($config);
    }

    public function addAccountAddress ($address) {
        // if (!$this->isAccountSignedIn() && !$force)
        //     return false;

        // if ($this->isAccountSignedIn())
        //     $address['AccountID'] = $this->getActiveProfileID();
        // elseif ($force && !isset($address['AccountID']))
        //     return false;


        // if ()
        // if (!$force)

        $address['DateCreated'] = date('Y:m:d H:i:s');
        $address['DateUpdated'] = date('Y:m:d H:i:s');
        $config = configurationCustomerDataSource::jsapiAddAccountAddress();
        $config['data'] = array(
            "fields" => array_keys($address),
            "values" => array_values($address)
        );
        // var_dump($config);
        $this->getDataBase()->getData($config);
        return $this->getDataBase()->getLastInsertId();
    }

    public function updateAccountAddress ($address) {
        // if (!$this->isAccountSignedIn())
        //     return false;
        $AccountID = $address['AccountID'];
        unset($address['AccountID']);
        $AddressID = $address['AddressID'];
        unset($address['AddressID']);
        $address['DateUpdated'] = date('Y:m:d H:i:s');
        // var_dump($address);
        $config = configurationCustomerDataSource::jsapiUpdateAccountAddress($AccountID, $AddressID, $address);
        $config['data'] = array(
            "fields" => array_keys($address),
            "values" => array_values($address)
        );
        // var_dump($config);
        $this->getDataBase()->getData($config);
    }

    public function removeAccountAddress ($AccountID, $AddressID) {
        // if (!$this->isAccountSignedIn())
        //     return false;
        $config = configurationCustomerDataSource::jsapiRemoveAccountAddress($AccountID, $AddressID);
        $this->getDataBase()->getData($config);
    }

    public function hasPlugin ($pluginName) {
        return !empty($this->plugins[$pluginName]);
    }

}

?>