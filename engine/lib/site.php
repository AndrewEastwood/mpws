<?php
namespace engine\lib;

use \engine\lib\utils as Utils;
use \engine\lib\path as Path;
use \engine\lib\request as Request;
use \engine\lib\response as Response;
use \engine\lib\uploadHandler as JqUploadLib;
use \engine\lib\validate as Validate;
use \engine\lib\secure as Secure;
use \engine\lib\api as API;
use Exception;

class site {

    public function init () {
        session_start();
        $apiCustomer = API::getAPI('system:customers');
        $apiCustomer->switchToDefaultCustomer();
    }

    public function getHtmlPage () {
        global $app;
        $apiCustomer = API::getAPI('system:customers');
        $customer = $apiCustomer->getRuntimeCustomer();

        // get customer name
        $displayCustomer = $app->displayCustomer();

        // system config
        $version = $app->getAppName();
        $layout = $app->getSettings('layout');
        $layoutBody = $app->getSettings('layoutBody');

        // TODO: get Plugins, Title, Locale, Lang and all other public customer's settings from DB
        // and expose in the template : >>>>>
        $lang = $customer['Settings']['lang'];
        $locale = $customer['Settings']['locale'];
        $plugins = explode(',', $customer['Settings']['plugins']);
        $Homepage = $customer['HomePage'];
        $Host = $customer['Settings']['host'];
        $Scheme = $customer['Settings']['scheme'];
        $Title = $customer['Settings']['title'];
        // <<< get from db according to display customer

        $layoutCustomer = Path::getWebStaticTemplateFilePath($displayCustomer, $version, $layout, $app->isDebug());
        $layoutBodyCustomer = Path::getWebStaticTemplateFilePath($displayCustomer, $version, $layoutBody, $app->isDebug());

        // var_dump($displayCustomer);
        // var_dump($version);
        // var_dump($layoutCustomer, 'layoutCustomer');
        // var_dump($layoutDefault, 'layoutDefault');

        $urls = $app->getSettings('urls');
        $staticPath = $urls['static'];
        $initialJS = "{
            LOCALE: '" . $locale . "',
            BUILD: " . ($app->isDebug() ? 'null' : $app->getBuildVersion()) . ",
            ISDEV: " . ($app->isDebug() ? 'true' : 'false') . ",
            ISTOOLBOX: " . ($app->isToolbox() ? 'true' : 'false') . ",
            PLUGINS: " . (count($plugins) ? "['" . implode("', '", $plugins) . "']" : '[]') . ",
            MPWS_VERSION: '" . $version . "',
            MPWS_CUSTOMER: '" . $displayCustomer . "',
            PATH_STATIC_BASE: '" . $staticPath . "',
            URL_PUBLIC_HOMEPAGE: '" . $Homepage . "',
            URL_PUBLIC_HOSTNAME: '" . $Host . "',
            URL_PUBLIC_SCHEME: '" . $Scheme . "',
            URL_PUBLIC_TITLE: '" . $Title . "',
            URL_API: '" . $urls['api'] . "',
            URL_UPLOAD: '" . $urls['upload'] . "',
            AUTHKEY: '" . API::getAPI('system:auth')->getAuthCookieKey() . "',
            USER: " . API::getAPI('system:auth')->getAuthenticatedUserJSON() . ",
            URL_STATIC_CUSTOMER: '" . Path::createPath(Path::getDirNameCustomer(), $displayCustomer, true) . "',
            URL_STATIC_WEBSITE: '" . Path::createPath(Path::getDirNameCustomer(), $displayCustomer, true) . "',
            URL_STATIC_PLUGIN: '" . Path::createPath('plugin', true) . "',
            URL_STATIC_DEFAULT: '" . Path::createPath('base', $version, true) . "',
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

    public function getRuntimeCustomerID () {
        $apiCustomer = API::getAPI('system:customers');
        return $apiCustomer->getRuntimeCustomerID();
    }

    public function hasPlugin ($pluginName) {
        $apiCustomer = API::getAPI('system:customers');
        $customer = $apiCustomer->getRuntimeCustomer();
        $plugins = $customer['Settings']['plugins'];
        if (empty($plugins)) {
            return false;
        }
        return in_array($pluginName, explode(',', $plugins));
        // return isset($plugins[$pluginName]);
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

    public function runAsAPI () {
        global $app;
        // refresh auth
        // $this->updateSessionAuth();
        API::getAPI('system:auth')->updateSessionAuth();
        API::execAPI();
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
        $urls = $app->getSettings('urls');
        $options = array(
            'script_url' => $urls['upload'],
            'download_via_php' => true,
            'upload_dir' => Path::rootPath() . Path::getUploadTemporaryDirectory(),
            'print_response' => Request::isGET()
        );
        $upload_handler = new JqUploadLib($options);
        Response::setResponse($upload_handler->get_response());
        // refresh auth
        API::getAPI('system:auth')->updateSessionAuth();
        // API::execAPI();
    }

}

?>