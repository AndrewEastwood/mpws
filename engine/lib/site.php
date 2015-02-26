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
        $websiteCustomer = $app->customerName();

        // system config
        $layout = $app->getSettings('layout');
        $layoutBody = $app->getSettings('layoutBody');

        if ($customer['isBlocked'] && !$app->isToolbox()) {
            $layout = 'blocked.hbs';
        }

        // TODO: get Plugins, Title, Locale, Lang and all other public customer's settings from DB
        // and expose in the template : >>>>>
        $lang = $customer['Lang'];
        $locale = $customer['Locale'];
        $plugins = $customer['Plugins'];
        $Homepage = $customer['HomePage'];
        $Host = $customer['HostName'];
        $Scheme = $customer['Protocol'];
        $Title = $customer['Title'];
        // <<< get from db according to display customer

        $layoutCustomer = Path::getWebStaticTemplateFilePath($displayCustomer, $layout, $app->isDebug());
        $layoutBodyCustomer = Path::getWebStaticTemplateFilePath($displayCustomer, $layoutBody, $app->isDebug());

        // var_dump($layoutCustomer);
        // var_dump($layoutBodyCustomer);
        // var_dump($app->isDebug());
        // var_dump($layoutBodyCustomer);
        // var_dump($version);
        // var_dump($layoutCustomer, 'layoutCustomer');
        // var_dump($layoutDefault, 'layoutDefault');

        $urls = $app->getSettings('urls');
        $staticPath = $urls['static'];
        $staticPathCustomer = $staticPath . Path::createPath(Path::getDirNameCustomer(), $displayCustomer);
        $logoUrl = $staticPathCustomer . '/img/logo.png';
        if (!empty($customer['Logo'])) {
            $logoUrl = $customer['Logo']['normal'];
        }
        $initialJS = "{
            LOCALE: '" . $locale . "',
            BUILD: " . ($app->isDebug() ? 'null' : $app->getBuildVersion()) . ",
            DEBUG: " . ($app->isDebug() ? 'true' : 'false') . ",
            ISTOOLBOX: " . ($app->isToolbox() ? 'true' : 'false') . ",
            PLUGINS: " . (count($plugins) ? "['" . implode("', '", $plugins) . "']" : '[]') . ",
            CUSTOMER: '" . $displayCustomer . "',
            URL_PUBLIC_HOMEPAGE: '" . $Homepage . "',
            URL_PUBLIC_HOSTNAME: '" . $Host . "',
            URL_PUBLIC_SCHEME: '" . $Scheme . "',
            URL_PUBLIC_LOGO: '" . $logoUrl . "',
            TITLE: '" . ($app->isToolbox() ? $customer['AdminTitle'] : $Title) . "',
            AUTHKEY: '" . API::getAPI('system:auth')->getAuthCookieKey() . "',
            USER: " . API::getAPI('system:auth')->getAuthenticatedUserJSON() . "
        }";
        $initialJS = str_replace(array("\r","\n", '  '), '', $initialJS);

        $html = file_get_contents($layoutCustomer);
        $layoutBodyContent = file_get_contents($layoutBodyCustomer);

        // add system data
        $html = str_replace("{{BODY}}", $layoutBodyContent, $html);
        $html = str_replace("{{LANG}}", $lang, $html);
        $html = str_replace("{{SYSTEMJS}}", $initialJS, $html);
        $html = str_replace("{{MPWS_CUSTOMER}}", $displayCustomer, $html);
        $html = str_replace("{{PATH_STATIC}}", $staticPath, $html);
        $html = str_replace("{{URL_STATIC_CUSTOMER}}", $staticPathCustomer, $html);

        return $html;
    }

    public function getRuntimeCustomerID () {
        $apiCustomer = API::getAPI('system:customers');
        return $apiCustomer->getRuntimeCustomerID();
    }

    public function hasPlugin ($pluginName) {
        $apiCustomer = API::getAPI('system:customers');
        $customer = $apiCustomer->getRuntimeCustomer();
        $plugins = $customer['Plugins'];
        if (empty($plugins)) {
            return false;
        }
        return in_array($pluginName, $plugins);
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