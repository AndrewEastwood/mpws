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
        $this->customerInfo = $this->getCustomerInfo();
    }

    public function getCustomerID () {
        $info = $this->getCustomerInfo();
        return isset($info['ID']) ? $info['ID'] : null;
    }

    public function getCustomerInfo () {
        if (empty($this->customerInfo)) {
        //     if (glIsToolbox())
        //         $config = configurationCustomerDataSource::jsapiGetCustomer();
        //     // elseif (/* unmanaged session */)
        //     //     $config = configurationCustomerDataSource::jsapiGetCustomer("");
        //     else
            $config = configurationCustomerDataSource::jsapiGetCustomer();
            $this->customerInfo = $this->getDataBase()->getData($config);
        }
        return $this->customerInfo;
    }

    public function getDataBase () {
        return $this->dbo;
    }

    public function processData ($config) {
            // echo 123400;
        // echo 111111;
        // var_dump($config);
        $customerInfo = $this->getCustomerInfo();

        // var_dump($customerInfo);

        if (empty($config["condition"]["filter"])) {
            $config["condition"]["filter"] = "CustomerID (=) ?";
            $config["condition"]["values"] = array($customerInfo['ID']);
        } else {
            // if (count($config["condition"]["filter"]) > 1 || $config["condition"]["filter"][0] != "*") {
                $config["condition"]["filter"] = "CustomerID (=) ? + " . $config["condition"]["filter"];
                array_unshift($config["condition"]["values"], $customerInfo['ID']);
            // }
        }
        // var_dump($config);
        return $this->dbo->getData($config);
    }

    public function getAllPlugins () {
        return $this->plugins;
    }

    public function getPlugin($key) {
        return $this->plugins[$key];
    }

    public function getResponse () {

        $response = new libraryDataObject();
        $publicKey = libraryRequest::getValue('token');
        $source = libraryRequest::getValue('source');

        if (empty($source)) {
            $response->setError('WrongSource');
            return $response;
        }

        if (!isset($this->plugins[$source])) {
            $response->setError('UnknownSource');
            return $response;
        }

        // check page token
        if (empty($publicKey)) {
            $response->setError('WrongToken');
            return $response;
        }

        if (!libraryRequest::getOrValidatePageSecurityToken(configurationCustomerDisplay::$MasterJsApiKey, $publicKey)) {
            $response->setError('InvalidPublicTokenKey');
            return $response;
        }

        // if ($source == '*' && !configurationCustomerDatabase::$AllowWideJsApi)
        //     throw new Exception('objectCustomer => getResponse: wide api js request is not allowed');

        if ($source == '*')
            foreach ($this->plugins as $key => $plugin)
                $response->setData($key, $plugin->getResponse()->toNative());
        elseif ($this->hasPlugin($source)) {
            $plugin = $this->getPlugin($source);
            $response->setData($source, $plugin->getResponse()->toNative());
        }

        // this section must be located when all plugins are performed
        // becuse the toolbox plugin does user validation and authorizations
        if (!$this->isAdminActive()) {
            $response->setError('AccessDenied');
            // $response->setData('redirect', 'signin');
            return $response;
        }

        return $response;
    }

    public function hasPlugin ($pluginName) {
        return !empty($this->plugins[$pluginName]);
    }

    public function getPluginData ($source, $function, $params = null) {
        $plugin = $this->getPlugin($source);
        if (empty($plugin))
            return null;
        return $plugin->getPluginData($function, $params);
    }

    // Admin status (requires toolbox plugin)
    public function isAdminActive () {
        return $this->getPluginData('toolbox', 'isActive');
    }

    // Accounts
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
        $this->processData($config);
        return $this->getDataBase()->getLastInsertId();
    }

    public function getAccount ($login, $password, $encodePassword = true) {
        if ($encodePassword)
            $password = $this->getAccountPassword($password);
        $config = configurationCustomerDataSource::jsapiGetAccount($login, $password);
        // var_dump($config);
        $profile = $this->processData($config);
        if (isset($profile['ID']))
            $profile["addresses"] = $this->getAccountAddresses($profile['ID']);
        return $profile;
    }

    public function getAccountByID ($id) {
        $config = configurationCustomerDataSource::jsapiGetAccountByID($id);
        $profile = $this->processData($config);
        return $profile;
    }

    public function activateAccount ($ValidationString) {
        $config = configurationCustomerDataSource::jsapiActivateAccount($ValidationString);
        $this->processData($config);
    }

    public function removeAccount ($dataAccount) {
        $AccountID = $dataAccount['AccountID'];
        unset($dataAccount['AccountID']);
        $dataAccount['DateUpdated'] = date('Y:m:d H:i:s');
        $config = configurationCustomerDataSource::jsapiRemoveAccount($AccountID);
        $config['data'] = array(
            "fields" => array_keys($dataAccount),
            "values" => array_values($dataAccount)
        );
        $this->processData($config);
    }

    public function updateAccount ($dataAccount) {
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
        $this->processData($config);
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
        $this->processData($config);
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
        return $this->dbo->getData($config);
    }

    public function getAccountAddress ($AccountID, $AddressID) {
        $config = configurationCustomerDataSource::jsapiGetAccountAddress($AccountID, $AddressID);
        return $this->dbo->getData($config);
    }

    public function getAddress ($AddressID) {
        $config = configurationCustomerDataSource::jsapiGetAddress($AddressID);
        return $this->dbo->getData($config);
    }

    public function addAccountAddress ($address) {
        $address['DateCreated'] = date('Y:m:d H:i:s');
        $address['DateUpdated'] = date('Y:m:d H:i:s');
        $config = configurationCustomerDataSource::jsapiAddAccountAddress();
        $config['data'] = array(
            "fields" => array_keys($address),
            "values" => array_values($address)
        );
        // var_dump($config);
        $this->processData($config);
        return $this->getDataBase()->getLastInsertId();
    }

    public function updateAccountAddress ($address) {
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
        $this->processData($config);
    }

    public function removeAccountAddress ($AccountID, $AddressID) {
        $config = configurationCustomerDataSource::jsapiRemoveAccountAddress($AccountID, $AddressID);
        // var_dump($config);
        $this->processData($config);
    }

}

?>