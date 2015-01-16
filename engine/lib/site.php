<?php
namespace engine\lib;

use \engine\lib\utils as Utils;
use \engine\lib\path as Path;
use \engine\lib\request as Request;
use \engine\lib\response as Response;
// use \engine\lib\database as DB;
use \engine\lib\uploadHandler as JqUploadLib;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use Exception;
// use app;
// use \engine\interfaces\ICustomer as ICustomer;
// use \engine\object\multiExtendable as MultiExtendable;

class site {

    // private $version = 'atlantis';
    private $customerInfo;
    private $htmlPage;
    private $permissions;
    private $apis;

    function __construct() {
        // $this->customerInfo = $this->getCustomerInfo();
    }

    public function getHtmlPage () {
        global $app;

        // get customer name
        $displayCustomer = $app->displayCustomer();

        // system config
        $version = $app->getAppName();
        $layout = $app->getSettings('layout');
        $layoutBody = $app->getSettings('layoutBody');

        // TODO: get Plugins, Title, Locale, Lang and all other public customer's settings from DB
        // and expose in the template : >>>>>
        $lang = 'en';
        $locale = '';
        $plugins = array();
        $Homepage = '';
        $Host = '';
        $Scheme = '';
        $Title = '';
        // <<< get from db according to display customer

        $layoutCustomer = Path::getWebStaticTemplateFilePath($displayCustomer, $version, $layout, $app->isDebug());
        $layoutBodyCustomer = Path::getWebStaticTemplateFilePath($displayCustomer, $version, $layoutBody, $app->isDebug());

        // var_dump($displayCustomer);
        // var_dump($version);
        // var_dump($layoutCustomer, 'layoutCustomer');
        // var_dump($layoutDefault, 'layoutDefault');

        $staticPath = 'static';
        $initialJS = "{
            LOCALE: '" . $locale . "',
            BUILD: " . ($app->isDebug() ? 'null' : $app->getBuildVersion()) . ",
            ISDEV: " . ($app->isDebug() ? 'true' : 'false') . ",
            ISTOOLBOX: " . ($app->isToolbox() ? 'true' : 'false') . ",
            PLUGINS: ['" . implode("', '", $plugins) . "'],
            MPWS_VERSION: '" . $version . "',
            MPWS_CUSTOMER: '" . $displayCustomer . "',
            PATH_STATIC_BASE: '/',
            URL_PUBLIC_HOMEPAGE: '" . $Homepage . "',
            URL_PUBLIC_HOSTNAME: '" . $Host . "',
            URL_PUBLIC_SCHEME: '" . $Scheme . "',
            URL_PUBLIC_TITLE: '" . $Title . "',
            URL_API: '/api.js',
            URL_AUTH: '/auth.js',
            URL_TASK: '/background/',
            URL_UPLOAD: '/upload.js',
            URL_STATIC_CUSTOMER: '/" . Path::createPath($staticPath, Path::getDirNameCustomer(), $displayCustomer, true) . "',
            URL_STATIC_WEBSITE: '/" . Path::createPath($staticPath, Path::getDirNameCustomer(), $displayCustomer, true) . "',
            URL_STATIC_PLUGIN: '/" . Path::createPath($staticPath, 'plugin', true) . "',
            URL_STATIC_DEFAULT: '/" . Path::createPath($staticPath, 'base', $version, true) . "',
            ROUTER: '" . join(Path::getDirectorySeparator(), array('customer', 'js', 'router')) . "'
        }";
        $initialJS = str_replace(array("\r","\n", '  '), '', $initialJS);

        $html = file_get_contents($layoutCustomer);
        $layoutBodyContent = file_get_contents($layoutBodyCustomer);

        // add system data
        $html = str_replace("{{BODY}}", $layoutBodyContent, $html);
        $html = str_replace("{{LANG}}", $lang, $html);
        $html = str_replace("{{SYSTEMJS}}", $initialJS, $html);
        $html = str_replace("{{MPWS_VERSION}}", $version, $html);
        $html = str_replace("{{MPWS_CUSTOMER}}", $displayCustomer, $html);
        $html = str_replace("{{PATH_STATIC}}", $staticPath, $html);

        return $html;
    }

    public function getCustomerID () {
        $info = $this->getCustomerInfo();
        return isset($info['ID']) ? intval($info['ID']) : null;
    }

    public function getCustomerInfo () {
        global $app;
        if (empty($this->customerInfo)) {
            $api = $this->getRuntimeAPIClass('system:customers');
            $this->customerInfo = $api->getRuntimeCustomer();
        }
        return $this->customerInfo;
    }

    public function fetch ($config, $skipCustomerID = false) {
        global $app;
        $customerInfo = $this->getCustomerInfo();
        $source = $config["source"];
        $key = $source . '.CustomerID';
        $addCustomerID = false;
        if (!$skipCustomerID) {
            if (isset($config["condition"]["CustomerID"])) {
                $config["condition"][$key] = $app->getDB()->createCondition($customerInfo['ID']);
                unset($config["condition"]["CustomerID"]);
            } else if (!isset($config["condition"][$key])) {
                $config["condition"][$key] = $app->getDB()->createCondition($customerInfo['ID']);
            }
        }
        return $app->getDB()->getData($config);
    }

    public function runAsDISPLAY () {
        Response::setResponse($this->getHtmlPage());
    }

    public function runAsSNAPSHOT () {
        global $app;
        $ch = curl_init();
        $url = $app->getSettings('SeoSnapshotURL') . $_GET['_escaped_fragment_'];
        if (empty($url)) {
            Response::setResponse('empty SeoSnapshotURL occured');
            return;
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($ch);
        curl_close($ch);
        Response::setResponse($resp);
    }

    public function runAsSITEMAP () {
        global $app;
        $ch = curl_init();
        $url = $app->getSettings('SeoSiteMapUrl');
        if (empty($url)) {
            Response::setResponse('empty SeoSiteMapUrl occured');
            return;
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($ch);
        curl_close($ch);
        Response::setResponse($resp);
    }

    public function getRuntimeAPIClass ($apiKey = false) {
        if (empty($apiKey)) {
            $_source = Request::pickFromGET('source');
            $_fn = Request::pickFromGET('fn');
            $apiKey = $_source . ':' . $_fn;
        }
        $api = null;
        if (isset($this->apis[$apiKey]))
            $api = $this->apis[$apiKey];
        else {
            $apiClass = Utils::getApiClassName($_fn, $_source);
            $this->apis[$apiKey] = new $apiClass();
            $api = $this->apis[$apiKey];
        }
        return $api;
    }

    public function runAsAPI () {
        global $app;
        // refresh auth
        $this->updateSessionAuth();
        // get api
        $api = $this->getRuntimeAPIClass();
        // invoke api request method
        $api->$_method(Response::$_RESPONSE, $_REQ);
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
        global $app;
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
            'script_url' => $app->getSettings('urls')->upload,
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

}

?>