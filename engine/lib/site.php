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
use \engine\lib\email as Email;
use Exception;

class site {

    public function init () {
        session_start();
        $apiCustomer = API::getAPI('system:customers');
        $apiCustomer->loadActiveCustomer();
        // $mandrill = new Mandrill('YOUR_API_KEY');
        // echo 11111;
        // var_dump($mandrill);
    }

    // TODO: move most general params into customer api
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

        $customerSettings = $apiCustomer->getCustomerSettings();

        $layoutCustomer = Path::getWebStaticTemplateFilePath($displayCustomer, $layout, $app->isDebug());
        $layoutBodyCustomer = Path::getWebStaticTemplateFilePath($displayCustomer, $layoutBody, $app->isDebug());

        $build = $app->getBuildVersion();

        $initialJS = "{
            LOCALE: '" . $customerSettings->locale . "'
            ,BUILD: " . $build . "
            ,DEBUG: " . ($app->isDebug() ? 'true' : 'false') . "
            ,ISTOOLBOX: " . ($app->isToolbox() ? 'true' : 'false') . "
            ,PLUGINS: " . (count($customerSettings->plugins) ? "['" . implode("', '", $customerSettings->plugins) . "']" : '[]') . "
            ,CUSTOMER: '" . $displayCustomer . "'
            ,ACTIVEID: '" . $customer['ID'] . "'
            ,ACTIVEHOSTNAME: '" . $customer['HostName'] . "'
            ,URL_PUBLIC_HOMEPAGE: '" . $customerSettings->homepage . "'
            ,URL_PUBLIC_HOSTNAME: '" . $customerSettings->host . "'
            ,URL_PUBLIC_SCHEME: '" . $customerSettings->scheme . "'
            ,URL_PUBLIC_LOGO: '" . $customerSettings->logoUrl . "'
            ,TITLE: '" . ($app->isToolbox() ? $customer['AdminTitle'] : $customerSettings->title) . "'
            ,AUTHKEY: '" . API::getAPI('system:auth')->getAuthCookieKey() . "'
        }";
        // ,USER: " . API::getAPI('system:auth')->getAuthenticatedUserJSON() . "
        $initialJS = str_replace(array("\r","\n", '  '), '', $initialJS);

        $html = file_get_contents($layoutCustomer);
        $layoutBodyContent = file_get_contents($layoutBodyCustomer);

        // add system data
        $html = str_replace("{{BODY}}", $layoutBodyContent, $html);
        $html = str_replace("{{LANG}}", $customerSettings->lang, $html);
        $html = str_replace("{{SYSTEMJS}}", $initialJS, $html);
        $html = str_replace("{{MPWS_CUSTOMER}}", $displayCustomer, $html);
        $html = str_replace("{{PATH_STATIC}}", $app->getStaticPath(), $html);
        $html = str_replace("{{URL_STATIC_CUSTOMER}}", $customerSettings->staticPathCustomer, $html);
        $html = str_replace("{{URL_PUBLIC_LOGO}}", $customerSettings->logoUrl, $html);
        $html = str_replace("{{BUILD}}", $build, $html);

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
        $apiCustomer = API::getAPI('system:customers');
        $customer = $apiCustomer->getRuntimeCustomer();
        $ch = curl_init();
        $url = $customer['SnapshotURL'];
        if (empty($url)) {
            Response::setResponse('Empty SnapshotURL');
            return;
        }
        // var_dump($_GET);
        // echo $url . '/?_escaped_fragment_=' . urlencode($_GET['_escaped_fragment_']);
        // return;
        curl_setopt($ch, CURLOPT_URL, $url . '/?_escaped_fragment_=' . urlencode($_GET['_escaped_fragment_']));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($ch);
        curl_close($ch);
        Response::setResponse($resp);
    }

    public function runAsSITEMAP () {
        global $app;
        $apiCustomer = API::getAPI('system:customers');
        $customer = $apiCustomer->getRuntimeCustomer();
        $ch = curl_init();
        $url = $customer['SitemapURL'];
        if (empty($url)) {
            Response::setResponse('Empty SitemapURL');
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
        API::getAPI('system:auth')->updateSessionAuth();
    }

}

?>