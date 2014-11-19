<?php
namespace engine\objects;

use \engine\lib\utils as Utils;
use \engine\lib\path as Path;
use \engine\lib\request as Request;
use \engine\lib\response as Response;
use \engine\lib\database as DB;
use \engine\lib\uploadHandler as JqUploadLib;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
// use \engine\interfaces\ICustomer as ICustomer;
// use \engine\object\multiExtendable as MultiExtendable;

class customer {

    private $version = 'atlantis';
    private $app;
    private $dbo;
    private $plugins;
    private $configuration;
    // private $extensions;
    private $customerInfo;
    private $htmlPage;
    private $permissions;

    function __construct($app) {

        $this->app = $app;

        // init configuration
        $configuration = array();
        $defaultConfigs = Path::getDefaultConfigNames($this->getVersion());
        $customerConfigs = Path::getCustomerConfigNames($this->getApp()->customerName());
        // var_dump($defaultConfigs);
        // var_dump($customerConfigs);
        foreach ($defaultConfigs as $configName) {
            if (in_array($configName, $customerConfigs)) {
                $configClass = Utils::getCustomerConfigClassName($this->customerName(), $configName);
            } else {
                $configClass = Utils::getDefaultConfigClassName($this->getVersion(), $configName);
            }
            $configuration[$configName] = new $configClass();
        }
        $this->configuration = (object)$configuration;

        // var_dump($this->configuration);

        // init dbo
        $this->dbo = new DB($this->getConfiguration()->db->getConnectionParams($this->getApp()->isDebug()));

        // init extensions
        // $this->addExtension(new \engine\extension\auth($this)); // move to middleware
        // $this->addExtension(new \engine\extension\dataInterface($this)); // thinnk to optmize

        // init plugins
        $_pluginPath = Path::createPathWithRoot('web', 'plugin');
        foreach ($this->getConfiguration()->display->Plugins as $pluginName) {
            // load plugin
            $pluginClass = Utils::getPluginClassName($pluginName);// '\\web\\plugin\\' . $pluginName . '\\plugin';
            // save plugin instance
            $this->plugins[$pluginName] = new $pluginClass($this, $pluginName, $app);
        }

        $this->customerInfo = $this->getCustomerInfo();
    }

    public function getApp () {
        return $this->app;
    }

    public function customerName () {
        return $this->getApp()->customerName();
    }

    public function getConfiguration () {
        return $this->configuration;
    }

    public function getVersion () {
        return $this->version;
    }

    public function getHtmlPage () {
        $displayCustomer = $this->getApp()->displayCustomer();
        $layout = $this->getConfiguration()->display->Layout;
        $layoutBody = $this->getConfiguration()->display->LayoutBody;

        $layoutCustomer = Path::getWebStaticTemplateFilePath($displayCustomer, $this->getVersion(), $layout, $this->getApp()->isDebug());
        $layoutBodyCustomer = Path::getWebStaticTemplateFilePath($displayCustomer, $this->getVersion(), $layoutBody, $this->getApp()->isDebug());

        // var_dump($displayCustomer);
        // var_dump($this->getVersion());
        // var_dump($layoutCustomer, 'layoutCustomer');
        // var_dump($layoutDefault, 'layoutDefault');

        $staticPath = 'static';
        $initialJS = "{
            LOCALE: '" . $this->getConfiguration()->display->Locale . "',
            BUILD: " . ($this->getApp()->isDebug() ? 'null' : $this->getApp()->getBuildVersion()) . ",
            ISDEV: " . ($this->getApp()->isDebug() ? 'true' : 'false') . ",
            ISTOOLBOX: " . ($this->getApp()->isToolbox() ? 'true' : 'false') . ",
            PLUGINS: ['" . implode("', '", $this->getConfiguration()->display->Plugins) . "'],
            MPWS_VERSION: '" . $this->getVersion() . "',
            MPWS_CUSTOMER: '" . $displayCustomer . "',
            PATH_STATIC_BASE: '/',
            URL_PUBLIC_HOMEPAGE: '" . $this->getConfiguration()->display->Homepage . "',
            URL_PUBLIC_TITLE: '" . $this->getConfiguration()->display->Title . "',
            URL_API: '/api.js',
            URL_AUTH: '/auth.js',
            URL_TASK: '/background/',
            URL_UPLOAD: '/upload.js',
            URL_STATIC_CUSTOMER: '/" . Path::createPath($staticPath, Path::getDirNameCustomer(), $displayCustomer, true) . "',
            URL_STATIC_WEBSITE: '/" . Path::createPath($staticPath, Path::getDirNameCustomer(), $displayCustomer, true) . "',
            URL_STATIC_PLUGIN: '/" . Path::createPath($staticPath, 'plugin', true) . "',
            URL_STATIC_DEFAULT: '/" . Path::createPath($staticPath, 'base', $this->getVersion(), true) . "',
            ROUTER: '" . join(Path::getDirectorySeparator(), array('customer', 'js', 'router')) . "'
        }";
        $initialJS = str_replace(array("\r","\n", '  '), '', $initialJS);

        $html = file_get_contents($layoutCustomer);
        $layoutBodyContent = file_get_contents($layoutBodyCustomer);

        // add system data
        $html = str_replace("{{BODY}}", $layoutBodyContent, $html);
        $html = str_replace("{{LANG}}", $this->getConfiguration()->display->Lang, $html);
        $html = str_replace("{{SYSTEMJS}}", $initialJS, $html);
        $html = str_replace("{{MPWS_VERSION}}", $this->getVersion(), $html);
        $html = str_replace("{{MPWS_CUSTOMER}}", $displayCustomer, $html);
        $html = str_replace("{{PATH_STATIC}}", $staticPath, $html);

        return $html;
    }

    public function getCustomerID () {
        $info = $this->getCustomerInfo();
        return isset($info['ID']) ? intval($info['ID']) : null;
    }

    public function getCustomerInfo () {
        if (empty($this->customerInfo)) {
            $config = $this->getConfiguration()->data->jsapiGetCustomer($this->customerName());
            $this->customerInfo = $this->getDataBase()->getData($config);
        }
        return $this->customerInfo;
    }

    public function getDataBase () {
        return $this->dbo;
    }

    public function fetch ($config, $skipCustomerID = false) {
        $customerInfo = $this->getCustomerInfo();
        $source = $config["source"];
        $key = $source . '.CustomerID';
        $addCustomerID = false;
        if (!$skipCustomerID) {
            if (isset($config["condition"]["CustomerID"])) {
                $config["condition"][$key] = $this->getConfiguration()->data->jsapiCreateDataSourceCondition($customerInfo['ID']);
                unset($config["condition"]["CustomerID"]);
            } else if (!isset($config["condition"][$key])) {
                $config["condition"][$key] = $this->getConfiguration()->data->jsapiCreateDataSourceCondition($customerInfo['ID']);
            }
        }
        return $this->dbo->getData($config);
    }

    public function getAllPlugins () {
        return $this->plugins;
    }

    public function getPlugin ($key) {
        return $this->plugins[$key] ?: null;
    }

    public function hasPlugin ($pluginName) {
        return !empty($this->plugins[$pluginName]);
    }

    public function runAsDISPLAY () {
        Response::setResponse($this->getHtmlPage());
    }

    public function runAsAPI () {

        // if (glIsToolbox()) {
        //     $publicKey = "";
        //     if (Request::hasInGet('token'))
        //         $publicKey = Request::pickFromGET('token');

        // // // check page token
        // // if (empty($publicKey)) {
        // //     \engine\lib\response::setError('EmptyToken', "HTTP/1.0 500 EmptyToken");
        // //     return;
        // // }

        // // if (!Request::getOrValidatePageSecurityToken($this->getConfiguration()->display->MasterJsApiKey, $publicKey)) {
        // //     \engine\lib\response::setError('InvalidTokenKey', "HTTP/1.0 500 InvalidTokenKey");
        // //     return;
        // // }

        // // if (MPWS_IS_TOOLBOX && !$this->getConfiguration()->display->IsManaged) {
        // //     \engine\lib\response::setError('AccessDenied', "HTTP/1.0 500 AccessDenied");
        // //     return;
        // // }
        // }

        // \engine\lib\response::$_RESPONSE['authenticated'] = $this->getPlugin('account')->isAuthenticated();
        // \engine\lib\response::$_RESPONSE['script'] = Request::getScriptName();

        // refresh auth
        $this->updateSessionAuth();
        foreach ($this->plugins as $plugin)
            $plugin->run();
    }

    public function runAsAUTH () {
        // Request::processRequest($this);
        $_REQ = Request::getRequestData();
        $_source = Request::pickFromGET('source');
        $_fn = Request::pickFromGET('fn');
        $_method = strtolower($_SERVER['REQUEST_METHOD']);
        $requestFnElements = array($_method);
        if (Request::hasInGet('source'))
            $requestFnElements[] = $_source;
        if (Request::hasInGet('fn'))
            $requestFnElements[] = $_fn;
        $fn = join("_", $requestFnElements);
        $this->$fn(Response::$_RESPONSE, $_REQ);
    }
    public function runAsUPLOAD () {
        /*
         * jQuery File Upload Plugin PHP Example 5.14
         * https://github.com/blueimp/jQuery-File-Upload
         *
         * Copyright 2010, Sebastian Tschan
         * https://blueimp.net
         *
         * Licensed under the MIT license:
         * http://www.opensource.org/licenses/MIT
         */
        $options = array(
            'script_url' => $this->getConfiguration()->urls->upload,
            'download_via_php' => true,
            'upload_dir' => Path::rootPath() . Path::getUploadTemporaryDirectory(),
            'print_response' => Request::isGET()
        );
        $upload_handler = new JqUploadLib($options);
        Response::setResponse($upload_handler->get_response());
        // refresh auth
        $this->updateSessionAuth();
        // bypass response to all plugins
        foreach ($this->plugins as $plugin)
            $plugin->run();
    }
    // public function runAsBACKGROUND () {
    //     foreach ($this->plugins as $plugin) {
    //         $plugin->task();
    //     }
    // }

    public function getDataList ($dsConfig, array $options = array(), array $callbacks = array()) {
        $limit = $dsConfig['limit'];
        $page = 1;
        $items = array();

        if ($dsConfig['action'] !== "select")
            throw new Exception("ErrorProcessingDataListMethod", 1);

        // grab other fields
        foreach ($options as $key => $value) {
            $matches = array();
            if (preg_match("/^_f(\w+)$/", $key, $matches)) {
                // $matches
                $field = $matches[1];
                // parse value
                $parsedValue = array();
                preg_match("/([0-9A-Za-z%\,_-]+)\:(.*)$/", $value, $parsedValue);
                // var_dump($field);
                // var_dump($value);
                $count = count($parsedValue);
                // var_dump($parsedValue);
                // var_dump($count);
                if ($count === 0)
                    $dsConfig['condition'][$field] = $this->getConfiguration()->data->jsapiCreateDataSourceCondition($value);
                elseif ($count === 3) {
                    $value = $parsedValue[1];
                    $comparator = $parsedValue[2];
                    if (strtolower($comparator) === 'in')
                        $value = explode(',', $parsedValue[1]);
                    $dsConfig['condition'][$field] = $this->getConfiguration()->data->jsapiCreateDataSourceCondition($value, $comparator);
                }
            }
        }

        // var_dump($dsConfig['condition']);
        // get data total records
        $configCount = $this->getConfiguration()->data->jsapiUtil_GetTableRecordsCount($dsConfig['source'], $dsConfig['condition']);
        
        $countData = $this->fetch($configCount);
        $count = intval($countData["ItemsCount"]);

        if (!empty($options)) {
            if (isset($options['sort']))
                $dsConfig['order']['field'] = $options['sort'];
            if (isset($options['order']))
                $dsConfig['order']['ordering'] = $options['order'];

            if (isset($options['page']))
                $page = intval($options['page']);
            if (isset($options['limit']))
                $limit = intval($options['limit']);

            if ($count > 0) {
                if ($limit >= 1) {
                    $dsConfig['limit'] = $limit;
                }
                if ($limit === 0) {
                    unset($dsConfig['limit']);
                }
                if ($page >= 1 && $limit >= 1) {
                    if ($page > round($count / $limit + 0.49)) {
                        $page = round($count / $limit + 0.49);
                    }
                    $dsConfig['offset'] = ($page - 1) * $limit;
                } elseif ($page === 0)
                    $page = 1;
            }
        }

        // var_dump($dsConfig);
        // get data
        $items = $this->fetch($dsConfig) ?: array();
        // var_dump($items);

        if (isset($callbacks['parse']) && is_callable($callbacks['parse'])) {
            $parseFn = $callbacks['parse'];
            $items = $parseFn($items) ?: array();
        }

        $rez = array();

        $listInfo = array(
            "page" => $page,
            "limit" => $limit,
            "total_pages" => empty($limit) ? 1 : round($count / $limit + 0.49),
            "total_entries" => $count
        );

        if (isset($dsConfig['order']['field'])) {
            $listInfo["order_by"] = $dsConfig['order']['field'];
        }

        if (isset($dsConfig['order']['ordering'])) {
            $listInfo["order"] = $dsConfig['order']['ordering'];
        }

        $rez["info"] = $listInfo;
        $rez["items"] = $items ?: array();

        return $rez;
    }

    private function _setPermissions ($perms) {
        $listOfDOs = array();
        // adjust permission values
        foreach ($perms as $field => $value) {
            if (preg_match("/^Can/", $field) === 1)
                $listOfDOs[$field] = intval($value) === 1;
        }
        $this->permissions = $listOfDOs;
    }

    public function getPermissions () {
        return $this->permissions;
    }

    public function ifYouCan ($action) {
        $permissions = $this->getPermissions();
        if (!isset($permissions['Can' . $action]))
            return false;
        return $this->permissions['Can' . $action];
    }

    public function getAuthID () {
        if (!isset($_SESSION['AccountID']))
            $_SESSION['AccountID'] = null;
        if (isset($_SESSION['AccountID'])) {
            $configPermissions = $this->getConfiguration()->data->jsapiGetPermissions($_SESSION['AccountID']);
            $permissions = $this->fetch($configPermissions, true) ?: array();
            $this->_setPermissions($permissions);
            if ($this->getApp()->isToolbox() && !$this->ifYouCan('Admin')) {
                return $this->clearAuthID();
            }
        }
        return $_SESSION['AccountID'];
    }

    public function updateSessionAuth () {
        $authID = $this->getAuthID();
        setcookie('auth_id', $authID, time() + 3600, '/');
    }

    public function clearAuthID () {
        if (!empty($_SESSION['AccountID'])) {
            $configOffline = $this->getConfiguration()->data->jsapiSetOnlineAccount($_SESSION['AccountID']);
            $this->fetch($configOffline);
        }
        $_SESSION['AccountID'] = null;
        $this->_setPermissions(array());
        return null;
    }

    public function get_status (&$resp) {
        $resp['auth_id'] = $this->getAuthID();
        $this->updateSessionAuth();
    }

    public function post_signin (&$resp, $req) {

        $password = $req->post['password'];
        $email = $req->post['email'];
        $remember = $req->post['remember'];

        if (empty($email) || empty($password)) {
            $resp['error'] = 'WrongCredentials';
            return;
        }

        $password = Secure::EncodeAccountPassword($password);

        $config = $this->getConfiguration()->data->jsapiGetAccountByCredentials($email, $password);
        // avoid removed account
        $config["fields"] = array("ID");
        $config["condition"]["Status"] = $this->getConfiguration()->data->jsapiCreateDataSourceCondition('REMOVED', '!=');
        $account = $this->fetch($config);
        $AccountID = null;
        // var_dump($config);
        if (empty($account))
            $resp['error'] = 'WrongCredentials';
        else {
            $AccountID = intval($account['ID']);
            $_SESSION['AccountID'] = $AccountID;
            // set online state for account
            $configOnline = $this->getConfiguration()->data->jsapiSetOnlineAccount($AccountID);
            $this->fetch($configOnline);
        }
        $resp['auth_id'] = $this->getAuthID();
        $this->updateSessionAuth();
    }

    public function post_signout (&$resp) {
        $resp['auth_id'] = $this->clearAuthID();
        $this->updateSessionAuth();
    }

    public function addTask ($group, $name, $params) {
        $result = array();
        $success = false;
        $errors = array();
        // echo 1111;
        $config = $this->getConfiguration()->data->jsapiAddTask(array(
            'CustomerID' => $this->getCustomerID(),
            'Group' => $group,
            'Name' => $name,
            'Params' => $params
        ));
        // var_dump($config);
        try {
            $this->getDataBase()->beginTransaction();
            $this->fetch($config);
            $this->getDataBase()->commit();
            $success = true;
        } catch (Exception $e) {
            $this->getDataBase()->rollBack();
            $errors[] = $e->getMessage();
        }
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function startTask ($group, $name, $params) {
        $result = array();
        $success = false;
        $errors = array();
        $config = $this->getConfiguration()->data->jsapiStartTask(md5($group.$name.$params));
        try {
            $this->getDataBase()->beginTransaction();
            $this->fetch($config);
            $this->getDataBase()->commit();
            $success = true;
        } catch (Exception $e) {
            $this->getDataBase()->rollBack();
            $errors[] = $e->getMessage();
        }
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function scheduleTask ($group, $name, $params) {
        $result = array();
        $success = false;
        $errors = array();
        $config = $this->getConfiguration()->data->jsapiScheduleTask(md5($group.$name.$params));
        try {
            $this->getDataBase()->beginTransaction();
            $this->fetch($config);
            $this->getDataBase()->commit();
            $success = true;
        } catch (Exception $e) {
            $this->getDataBase()->rollBack();
            $errors[] = $e->getMessage();
        }
        $result['errors'] = $errors;
        $result['success'] = $success;

        return $result;
    }

    public function cancelTask ($id) {
        $result = array();
        $success = false;
        $errors = array();
        $config = $this->getConfiguration()->data->jsapiStopTask($id);
        try {
            $this->getDataBase()->beginTransaction();
            $this->fetch($config);
            $this->getDataBase()->commit();
            $success = true;
        } catch (Exception $e) {
            $this->getDataBase()->rollBack();
            $errors[] = $e->getMessage();
        }
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    public function setTaskResult ($id, $taskResult) {
        $result = array();
        $success = false;
        $errors = array();
        $config = $this->getConfiguration()->data->jsapiSetTaskResult($id, $taskResult);
        try {
            $this->getDataBase()->beginTransaction();
            $this->fetch($config);
            $this->getDataBase()->commit();
            $success = true;
        } catch (Exception $e) {
            $this->getDataBase()->rollBack();
            $errors[] = $e->getMessage();
        }
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    public function isTaskAdded ($group, $name, $params) {
        $result = array();
        $config = $this->getConfiguration()->data->jsapiGetTaskByHash(md5($group . $name . $params));
        $result = $this->fetch($config);
        $this->__adjustTask($result);
        return $result;
    }

    public function deleteTaskByParams ($group, $name, $params) {
        return $this->deleteTaskByHash(md5($group . $name . $params));
    }

    public function deleteTaskByHash ($hash) {
        $result = array();
        $success = false;
        $errors = array();
        $config = $this->getConfiguration()->data->jsapiDeleteTaskByHash($hash);
        try {
            $this->getDataBase()->beginTransaction();
            $result = $this->fetch($config);
            $this->getDataBase()->commit();
            $success = true;
        } catch (Exception $e) {
            $this->getDataBase()->rollBack();
            $errors[] = $e->getMessage();
        }
        $result['errors'] = $errors;
        $result['success'] = $success;
        return $result;
    }

    public function getActiveTasksByGroupName ($groupName) {
        $result = array();
        $config = $this->getConfiguration()->data->jsapiGetGroupTasks($groupName, true, false, false);
        $result = $this->fetch($config);
        if ($result) {
            foreach ($result as &$value) {
                $this->__adjustTask($value);
            }
        }
        return $result;
    }

    public function getCompletedTasksByGroupName ($groupName) {
        $result = array();
        $config = $this->getConfiguration()->data->jsapiGetGroupTasks($groupName, false, true, false);
        $result = $this->fetch($config);
        if ($result) {
            foreach ($result as &$value) {
                $this->__adjustTask($value);
            }
        }
        return $result;
    }

    public function getNewTasksByGroupName ($groupName) {
        $result = array();
        $config = $this->getConfiguration()->data->jsapiGetGroupTasks($groupName, false, false, false);
        $result = $this->fetch($config);
        if ($result) {
            foreach ($result as &$value) {
                $this->__adjustTask($value);
            }
        }
        return $result;
    }

    public function getCanceledTasksByGroupName ($groupName) {
        $result = array();
        $config = $this->getConfiguration()->data->jsapiGetGroupTasks($groupName, false, false, true);
        $result = $this->fetch($config);
        if ($result) {
            foreach ($result as &$value) {
                $this->__adjustTask($value);
            }
        }
        return $result;
    }

    public function getNextNewTaskToProcess ($group, $name) {
        $result = array();
        $config = $this->getConfiguration()->data->jsapiGetNextTaskToProcess($group, $name);
        $result = $this->fetch($config);
        if ($result) {
            foreach ($result as &$value) {
                $this->__adjustTask($value);
            }
        }
        return $result;
    }

    private function __adjustTask (&$task) {
        if (empty($task))
            return null;
        $task['ID'] = intval($task['ID']);
        $task['CustomerID'] = intval($task['CustomerID']);
        $task['IsRunning'] = intval($task['IsRunning']) === 1;
        $task['Complete'] = intval($task['Complete']) === 1;
        $task['ManualCancel'] = intval($task['ManualCancel']) === 1;
        $task['Scheduled'] = intval($task['Scheduled']) === 1;
    }
}

?>