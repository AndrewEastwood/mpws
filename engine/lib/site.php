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
        $apiCustomer->loadActiveCustomer();
        // $mandrill = new Mandrill('YOUR_API_KEY');
        // echo 11111;
        // var_dump($mandrill);
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

        $build = $app->getBuildVersion();
        $urls = $app->getSettings('urls');
        $staticPath = $urls['static'];
        $staticPathCustomer = $staticPath . Path::createPath(Path::getDirNameCustomer(), $displayCustomer);
        $logoUrl = $staticPathCustomer . '/img/logo.png';
        if (!empty($customer['Logo'])) {
            $logoUrl = $customer['Logo']['normal'];
        }
        $initialJS = "{
            LOCALE: '" . $locale . "'
            ,BUILD: " . $build . "
            ,DEBUG: " . ($app->isDebug() ? 'true' : 'false') . "
            ,ISTOOLBOX: " . ($app->isToolbox() ? 'true' : 'false') . "
            ,PLUGINS: " . (count($plugins) ? "['" . implode("', '", $plugins) . "']" : '[]') . "
            ,CUSTOMER: '" . $displayCustomer . "'
            ,ACTIVEID: '" . $customer['ID'] . "'
            ,ACTIVEHOSTNAME: '" . $customer['HostName'] . "'
            ,URL_PUBLIC_HOMEPAGE: '" . $Homepage . "'
            ,URL_PUBLIC_HOSTNAME: '" . $Host . "'
            ,URL_PUBLIC_SCHEME: '" . $Scheme . "'
            ,URL_PUBLIC_LOGO: '" . $logoUrl . "'
            ,TITLE: '" . ($app->isToolbox() ? $customer['AdminTitle'] : $Title) . "'
            ,AUTHKEY: '" . API::getAPI('system:auth')->getAuthCookieKey() . "'
        }";
        // ,USER: " . API::getAPI('system:auth')->getAuthenticatedUserJSON() . "
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
        $html = str_replace("{{URL_PUBLIC_LOGO}}", $logoUrl, $html);
        $html = str_replace("{{BUILD}}", $build, $html);



        // $result = $app->getMail()->users->info();
        $template_name = 'demo4';
        $template_content = array(
            array(
                'name' => 'header',
                'content' => 'Вітаємо з покупкою!!!!'
            )
        );
        $message = array(
            'subject' => 'simple demo 4',
            'from_email' => 'no-reply@leogroup.com.ua',
            'from_name' => 'leogroup',
            'to' => array(
                array(
                    'email' => 'soulcor+test@gmail.com',
                    'name' => 'demo user',
                    'type' => 'to'
                )
            ),
            'global_merge_vars' => array(
                array('name' => 'param_email_static', 'content' => 'http://' . $app->getRawHost() . '/emails/' . 'default/simple/'),
                array('name' => 'param_customer_static', 'content' => 'http://' . $app->getRawHost() . '/' . $staticPathCustomer),
                array('name' => 'param_customer_title', 'content' => 'Нове замовлення'),
                array('name' => 'param_customer_logo2', 'content' => 'http://leogroup.com.ua/static_/customers/leogroup.com.ua/img/logo.png'),
                array('name' => 'param_greeting', 'content' => 'Вітаємо, DEMO USER3333!'),
                array('name' => 'param_content', 'content' => 'Congratulations! You have successfuly registered...'),
                array('name' => 'param_content2', 'content' => 'Please use your credentials...'),
                array('name' => 'param_content3', 'content' => 'Contact support any time...'),
                array('name' => 'param_show_buttons', 'content' => true),
                array('name' => 'param_button_href_1', 'content' => '//www.facebook.com/'),
                array('name' => 'param_button_title_1', 'content' => 'Share on Facebook'),
                array('name' => 'param_button_href_2', 'content' => ''),
                array('name' => 'param_button_title_2', 'content' => ''),
                array('name' => 'param_button_href_3', 'content' => ''),
                array('name' => 'param_button_title_3', 'content' => ''),
                array('name' => 'param_lead', 'content' => ''),
                array('name' => 'param_content_ending', 'content' => 'З повагою,<br/>Leogroup'),
                array('name' => 'param_cont_company', 'content' => 'Leogroup'),
                array('name' => 'param_cont_addr', 'content' => 'Polska, Jaroslaw ul.Wiegerska 1'),
                array('name' => 'param_cont_mailto', 'content' => 'sales@leo-trade.pl'),
                array('name' => 'param_homepage', 'content' => $Homepage),
                array('name' => 'text_unsub_link', 'content' => 'Відписатися'),
                array('name' => 'text_cont_prefix', 'content' => 'Від:'),
                array('name' => 'view_head_small', 'content' => true)
            ),
            "merge" => true,
            'headers' => array('Reply-To' => 'no-reply@leogroup.com.ua'),
            'tags' => array('test'),
        );
        $async = false;
        $ip_pool = 'Main Pool';
        $send_at = 'example send_at';
        $result = $app->getMail()->messages->sendTemplate($template_name, $template_content, $message);
        // print_r($message);
        print_r($result);

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