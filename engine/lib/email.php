<?php

namespace engine\lib;
use \engine\lib\api as API;
use \engine\lib\path as Path;
use \engine\lib\utils as Utils;

class email {

    private $template_name = '';//'demo4';
    private $subject = '';
    private $items = array();
    private $tags = array();
    private $params = array(
        'param_customer_title' => 'Нове замовлення',
        'param_greeting' => 'Вітаємо, DEMO USER3333!',
        'param_content' => 'Congratulations! You have successfuly registered...',
        'param_content2' => 'Please use your credentials...',
        'param_content3' => 'Contact support any time...',
        'param_lead' => '',
        'param_content_ending' => 'З повагою,<br/>Leogroup',
        'param_items_title' => '',
        // buttons
        'param_button_href_1' => '//www.facebook.com/',
        'param_button_title_1' => 'Share on Facebook',
        'param_button_href_2' => '',
        'param_button_title_2' => '',
        'param_button_href_3' => '',
        'param_button_title_3' => '',
        // contacts
        'param_cont_company' => 'Leogroup',
        'param_cont_addr' => 'Polska, Jaroslaw ul.Wiegerska 1',
        'param_cont_mailto' => 'sales@leo-trade.pl',
        // labels
        'text_unsub_link' => 'Відписатися',
        'text_cont_prefix' => 'Від:',
        // ui
        'param_show_buttons' => true,
        'view_head_small' => true
    );

    // TEXTS
    public function setTitle ($title) {
        $this->params['param_customer_title'] = $title;
        return $this;
    }

    public function setGreeting ($greeting) {
        $this->params['param_greeting'] = $greeting;
        return $this;
    }

    public function setContent1 ($text) {
        $this->setContent('', $text);
        return $this;
    }
    public function setContent2 ($text) {
        $this->setContent(2, $text);
        return $this;
    }
    public function setContent3 ($text) {
        $this->setContent(3, $text);
        return $this;
    }
    private function setContent ($n, $text) {
        $this->params['param_content' . $n] = $text;
        return $this;
    }
    public function setContentLead ($text) {
        $this->params['param_lead'] = $text;
        return $this;
    }
    public function setContentEnding ($text) {
        $this->params['param_content_ending'] = $text;
        return $this;
    }

    // ITEMS
    public function setItemsTitle ($text) {
        $this->params['param_items_title'] = $text;
        return $this;
    }
    public function addItem ($title, $text, $href = '', $image = '', $button_text = '') {
        $this->items[] = array(
            'image' => $image,
            'title' => $title,
            'text' => $text,
            'href' => $href,
            'button_text' => $button_text
        );
        return $this;
    }

    // ADDRESS
    public function setCompany ($text) {
        $this->params['param_cont_company'] = $text;
        return $this;
    }
    public function setAddress ($text) {
        $this->params['param_cont_addr'] = $text;
        return $this;
    }
    public function setMailto ($text) {
        $this->params['param_cont_mailto'] = $text;
        return $this;
    }

    // LABELS
    public function setTextUnsubLink ($text) {
        $this->params['text_unsub_link'] = $text;
        return $this;
    }
    public function setContactsPrefix ($text) {
        $this->params['text_cont_prefix'] = $text;
        return $this;
    }

    // UI CONFIG
    public function showButtons () {
        $this->params['param_show_buttons'] = true;
        return $this;
    }

    public function hideButtons () {
        $this->params['param_show_buttons'] = false;
        return $this;
    }

    public function smallHeader () {
        $this->params['view_head_small'] = true;
        return $this;
    }

    public function normalHeader () {
        $this->params['view_head_small'] = false;
        return $this;
    }

    // BUTTONS
    public function setButton1 ($href, $title) {
        $this->setButton(1, $href, $title);
        return $this;
    }
    public function setButton2 ($href, $title) {
        $this->setButton(2, $href, $title);
        return $this;
    }
    public function setButton3 ($href, $title) {
        $this->setButton(3, $href, $title);
        return $this;
    }
    private function setButton ($n, $href, $title) {
        $this->params['param_button_href_' . $n] = $href;
        $this->params['param_button_title_' . $n] = $title;
        return $this;
    }

    // ATTRIBUTES
    private $from_email = '';
    private $from_name = '';
    public function setSender ($email, $name = '') {
        $this->from_email = $email;
        $this->from_name = $name;
        return $this;
    }

    private $recepients = array();
    public function addRecepient ($email, $name = '') {
        $this->recepients[] = array(
            'email' => $email,//'soulcor+test2@gmail.com',
            'name' => $name,//'demo user',
            'type' => 'to'
        );
        return $this;
    }
    private $headers = array();
    public function addHeader ($key, $val) {
        $this->headers[$key] = $val;
        return $this;
    }

    public function setTemplate ($templateName) {
        $this->template_name = $templateName;
        return $this;
    }

    public function setSubject ($subject) {
        $this->subject = $subject;
        return $this;
    }
    public function addTags () {
        $this->tags += func_get_args();
        return $this;
    }

    public function setParamsIni ($iniParams) {
        $params = Utils::parse_ini_string_m($iniParams);
        $this->params = array_merge($this->params, $params);
    }

    // SEND EMAIL
    public function send () {
        global $app;

        if (empty($this->template_name)) {
            return 'EmptyTemplateName';
        }

        $apiCustomer = API::getAPI('system:customers');
        $customerSettings = $apiCustomer->getCustomerSettings();

        // Prepare params
        $global_merge_vars = array(
            array('name' => 'param_email_static', 'content' => 'http://' . $app->getRawHost() . '/emails/' . 'default/simple/'),
            array('name' => 'param_customer_static', 'content' => 'http://' . $app->getRawHost() . '/' . $customerSettings->staticPathCustomer),
            array('name' => 'param_customer_logo2', 'content' => 'http://leogroup.com.ua/static_/customers/leogroup.com.ua/img/logo.png'),
            array('name' => 'param_homepage', 'content' => $customerSettings->homepage)
        );
        foreach ($this->params as $key => $value) {
            $global_merge_vars[] = array('name' => $key, 'content' => $value);
        }

        // $template_name = 'demo4';
        $template_content = array(
            // array(
            //     'name' => 'header',
            //     'content' => 'Вітаємо з покупкою!!!!'
            // )
        );
        $message = array(
            'subject' => $this->subject,
            'from_email' => $this->from_email,//'no-reply@leogroup.com.ua',
            'from_name' => $this->from_name,//'leogroup',
            'to' => $this->recepients,
            'global_merge_vars' => $global_merge_vars,//array(
                
                // array('name' => 'param_customer_title', 'content' => 'Нове замовлення'),
                // array('name' => 'param_greeting', 'content' => 'Вітаємо, DEMO USER3333!'),
                // array('name' => 'param_content', 'content' => 'Congratulations! You have successfuly registered...'),
                // array('name' => 'param_content2', 'content' => 'Please use your credentials...'),
                // array('name' => 'param_content3', 'content' => 'Contact support any time...'),
                // array('name' => 'param_show_buttons', 'content' => true),
                // array('name' => 'param_button_href_1', 'content' => '//www.facebook.com/'),
                // array('name' => 'param_button_title_1', 'content' => 'Share on Facebook'),
                // array('name' => 'param_button_href_2', 'content' => ''),
                // array('name' => 'param_button_title_2', 'content' => ''),
                // array('name' => 'param_button_href_3', 'content' => ''),
                // array('name' => 'param_button_title_3', 'content' => ''),
                // array('name' => 'param_lead', 'content' => ''),
                // array('name' => 'param_content_ending', 'content' => 'З повагою,<br/>Leogroup'),
                // array('name' => 'param_cont_company', 'content' => 'Leogroup'),
                // array('name' => 'param_cont_addr', 'content' => 'Polska, Jaroslaw ul.Wiegerska 1'),
                // array('name' => 'param_cont_mailto', 'content' => 'sales@leo-trade.pl'),
                // array('name' => 'text_unsub_link', 'content' => 'Відписатися'),
                // array('name' => 'text_cont_prefix', 'content' => 'Від:'),
                // array('name' => 'view_head_small', 'content' => true),
                // // items
                // array('name' => 'param_items_title', 'content' => 'hot offers')
                // array('name' => 'param_items', 'content' => array(
                //     array('image' => 'http://www.tongfang-global.com/userfiles/image1.jpeg', 'title' => 'aaaa',
                //         'text' => 'aaa', 'href' => '', 'button_text' => ''),
                //     array('image' => 'http://www.d4products.com/wp-content/uploads/2013/11/sample_item3.png', 'title' => 'fsdf', 'text' => 'b', 'href' => '', 'button_text' => ''),
                //     array('image' => 'http://ic.tweakimg.net/ext/i/1277370767.jpeg', 'title' => 'fsdf', 'text' => 'gfdg', 'href' => '', 'button_text' => ''),
                //     array('image' => 'http://www.bandag.eu/Assets/Images/Content/product%20range%20pic.JPG', 'title' => 'fsdf', 'text' => 'fsdfsdfsdf', 'href' => '', 'button_text' => ''),
                //     array('image' => 'http://intl.welchallyn.com/images/products/fullsize/Defibrillation/AEDs/AED10case_900421_product2_MC.jpg', 'title' => 'fsfsdfdsf', 'text' => 'fsdfsdfsdfsdfsd', 'href' => '', 'button_text' => '')
                // )),

            // ),
            "merge" => true,
            'headers' => $this->headers,//array('Reply-To' => 'no-reply@leogroup.com.ua'),
            'tags' => $this->tags
        );
        // $async = false;
        // $ip_pool = 'Main Pool';
        // $send_at = 'example send_at';
        // $result = $app->getMail()->users->info();

        $result = $app->getMail()->messages->sendTemplate($this->template_name, $template_content, $message);
        // print_r($message);
        // print_r($result);
        return $result;
    }
}

?>
