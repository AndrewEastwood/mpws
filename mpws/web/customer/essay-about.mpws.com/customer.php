<?php


class customer {

    
    public function main ($customer) {
        //echo 'Customer main |';
        //var_dump($_GET);
        //var_dump($_POST);
        
        //var_dump($_SESSION['WEB_USER']);
        //echo '<br>--------------------------------------------<br>';
        $model = &$customer->getModel();
        $model['USER'] = $this->_userGetInfo($customer);
        $model['CUSTOMER']['MESSAGES'] = array();
        
        //var_dump($model['USER']);
        //echo '<br>--------------------------------------------<br>';
        
        
        switch(libraryRequest::getPage('index')){
            case 'make-order':{
                $this->_pageMakeOrder($customer);
                break;
            }
            case 'index':
                $this->_pageIndex($customer);
                break;
            case 'account':
                $this->_pageAccount($customer);
                break;
            case 'activate':
                $this->_pageActivate($customer);
                break;
            case 'purchase':
                $this->_pagePurchase($customer);
                break;
            default:{
                $this->_pageNotFound($customer);
                break;
            }
        }
        
        $this->_componentMenu($customer);
    }
    
    public function render ($customer) {
        //echo '***WRITER RENDER***';
        $model = &$customer->getModel();
        $libView = new libraryView();
        
        
        $model['HTML']['ACTION'] = libraryView::getFrendlyLabel(libraryRequest::getAction());
        
        //echo 'Customer render |';
        foreach ($model['CUSTOMER']['COMPONENT'] as $key => $component) {
            $_model = $model;
            $_model['COMPONENT_KEY'] = $key;
            $_model['COMPONENT_DATA'] = $component['DATA'];
            $model['HTML']['COMPONENT'][$key] = $libView->getTemplateResult($_model, $component['TEMPLATE']);
        }
        //$model['html']['menu'] .= $model['html']['writer']['com']['menu'];
        
        
        /* set html data */
        $model['HTML']['CONTENT'] = $libView->getTemplateResult($model, $model['CUSTOMER']['TEMPLATE']);
        
    }
    
    public function layout ($customer) {
        //echo 'Customer layout |';
        $libView = new libraryView();
        $model = &$customer->getModel();
        
        $layout = 'layout';
        
        if (!empty($model['LAYOUT']))
            $layout = $model['LAYOUT'];
       
        return $libView->getTemplateResult($model, $customer->getCustomerTemplate($layout)); 
    }
    
    public function api ($customer) {
        
        //echo 'Customer API |';
        
        /*$p = libraryRequest::getApiParam();

        if (empty($p['token']) || !libraryRequest::getOrValidatePageSecurityToken($p['token']))
            return;*/
        $result = false;
        switch (libraryRequest::getApiFn('none')) {
            case 'check_login':
                //debug(libraryRequest::getApiParam());
                $result = $this->api_check_login($customer);
                break;
            case 'mark_as_read':
                $result = $this->api_mark_as_read($customer);
                break;
        }
        return $result;
    }
    
    private function api_mark_as_read ($customer) {
        $model = &$customer->getModel();
        if (!$model['user']['ACTIVE'])
            return false;
        $p = libraryRequest::getApiParam();
        if (isset($p['checked']) && isset($p['oid']))
            $customer->getDatabaseObj()
                ->update('writer_messages')
                ->set(array('IsUnread' => $p['checked']))
                ->where('ID', '=', $p['oid'])
                ->query();
        else
            return false;
        return true;
    }
    private function api_check_login ($customer) {
        //$p = libraryRequest::getApiParam();
        
    }
    
    /* components */
    private function _componentMenu($customer) {
        $model = &$customer->getModel();
        
        // set all menus
        foreach ($model['CONFIG']['DISPLAY']['PUBLIC_MENU'] as $key => $entry) {
            $model['CUSTOMER']['COMPONENT']['MENU_' . $key]['DATA'] = $entry;
            $model['CUSTOMER']['COMPONENT']['MENU_' . $key]['TEMPLATE'] = $customer->getCustomerTemplate('component.menu');
        }
        
        // check for user
        if ($model['USER']['ACTIVE']) {
            $key = strtoupper($_SESSION['WEB_USER']['TYPE']);
            $model['CUSTOMER']['COMPONENT']['MENU_ACCOUNT']['DATA'] = $model['CONFIG']['DISPLAY']['ACCOUNT_MENU'][$key];
            $model['CUSTOMER']['COMPONENT']['MENU_ACCOUNT']['TEMPLATE'] = $customer->getCustomerTemplate('component.menu');
        }
        
        
        //var_dump($model['CUSTOMER']['COMPONENT']['MENU']);
    }
    
    
    /* pages */
    
    private function _pageNotFound ($customer) {
        $model = &$customer->getModel();
        $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.404');
    }
    private function _pageAccount ($customer) {
        $model = &$customer->getModel();

        //var_dump($model['USER']);
        
        // check for login
        if (!$model['USER']['ACTIVE']) {
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.accountdesk.login');
            return;
        }

        if ($model['USER']['TEMPORARY'])
            $model['CUSTOMER']['MESSAGES'][] = '
                Your account will be deleted in 73 hours.<br>
                To activate your account please click <a href="/page/activate.html?digest='.$model['USER']['TOKEN'].'" target="blank">here</a>';

        //$messages = array();
        $display = libraryRequest::getDisplay('orders');
        $action = libraryRequest::getAction('default');

        //echo '<br><br>ACTION SWITCHER ['.$action.']<br><br>';
        //if (libraryRequest::isPostFormAction('logout')) {}
        switch ($display) {
            /*
            case "active": {
                $this->_accountDeskCommonOrders($customer, 'IN PROGRESS');
                break;
            }
            case "rework": {
                $this->_accountDeskCommonOrders($customer, 'REWORK');
                break;
            }
            case "pending": {
                $this->_accountDeskCommonOrders($customer, 'PENDING');
                break;
            }
            case "completed": {
                $this->_accountDeskCommonOrders($customer, 'CLOSED');
                break;
            }*/
            case "settings": {
                $this->_accountDeskCommonSettings($customer);
                break;
            }
            case "history": {
                $this->_accountDeskCommonHistoryOrders($customer);
                break;
            }
            case "orders":
            default: {
                //$display = 'orders';
                switch ($action) {
                    case "details": {
                        $this->_accountDeskCommonOrders_details($customer);
                        break;
                    }
                    default: {
                        $this->_accountDeskCommonOrders_all($customer);
                        break;
                    }
                }
                break;
            }
        }

        // set template
        if (empty($model['CUSTOMER']['TEMPLATE'])) {
            // set default template name if custom is not specified
            if (empty($model['CUSTOMER']['TEMPLATE_NAME']))
                $templateName = $display.'_'.$action;
            else
                $templateName = $model['CUSTOMER']['TEMPLATE_NAME'];
            // prepend account type name
            $templateName = strtolower($_SESSION['WEB_USER']['TYPE'] . '_' . $templateName);
            echo '<br><br><br>USING TEMPLATE NAME:  =====> ' . $templateName;
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.accountdesk.' . $templateName);
        }
        $model['LAYOUT'] = 'accountdesk';
    }
    private function _pagePurchase ($customer) {
        $model = &$customer->getModel();
        
        //var_dump($_POST);
        
        // get merchant id
        $merchant_order_id_data = explode('-', libraryRequest::getValue('merchant_order_id'));
        $merchant_order_id = '';
        $user_login = '';
        
        if (!empty($merchant_order_id_data[0]))
            $merchant_order_id = $merchant_order_id_data[0];
        if (!empty($merchant_order_id_data[1]))
            $user_login = $merchant_order_id_data[1];
        
        //var_dump($_GET);
        //var_dump($merchant_order_id_data);
        //echo 'MID = ' . $merchant_order_id;
        //echo '<br>';
        //echo 'USER LOGIN = ' . $user_login;
        
        // check for double opening
        $invoiceInfo = $customer->getDatabaseObj()
                ->select('*')
                ->from('writer_invoices')
                ->where('merchant_order_id', '=', $merchant_order_id)
                ->fetchRow();

        // if second opening
        if (!empty($invoiceInfo)) {
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.index');
            return;
        }
        
        
        if (empty($merchant_order_id)) {
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.purchase_error');
            return;
        }

        // get order info
        $orderInfo = $customer->getDatabaseObj()
                ->select('*')
                ->from('writer_orders')
                ->where('OrderToken', '=', $merchant_order_id)
                ->fetchRow();
        
        // if no order
        if (empty($orderInfo)) {
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.purchase_error');
            return;
        }
        
        // save invoice
        $invoice = array();
        $invoice['inv_type'] = 'PAYMENT';
        $invoice['invoice_id'] = libraryRequest::getValue('invoice_id');
        $invoice['sid'] = libraryRequest::getValue('sid');
        $invoice['key'] = libraryRequest::getValue('key');
        $invoice['order_number'] = libraryRequest::getValue('order_number');
        $invoice['total'] = libraryRequest::getValue('total');
        $invoice['credit_card_processed'] = libraryRequest::getValue('credit_card_processed');
        $invoice['merchant_order_id'] = $merchant_order_id;
        
        libraryUtils::wrapArrayKeys($invoice, '`');
        
        $customer->getDatabaseObj()
                ->reset()
                ->insertInto('writer_invoices')
                ->fields(array_keys($invoice))
                ->values(array_values($invoice))
                ->query();
        
        if (!empty($user_login)) {
            $user_billing_info = array(
                'Billing_FirstName' => libraryRequest::getValue('card_holder_name'),
                'Billing_LastName' => '',
                'Billing_Email' => libraryRequest::getValue('email'),
                'Billing_Phone' => libraryRequest::getValue('phone'),
                'Billing_Address' => libraryRequest::getValue('street_address') . libraryRequest::getValue('street_address2'),
                'Billing_City' => libraryRequest::getValue('city'),
                'Billing_State' => libraryRequest::getValue('state'),
                'Billing_PostalCode' => libraryRequest::getValue('zip'),
                'Billing_Country' => libraryRequest::getValue('country'),
                'Name' => libraryRequest::getValue('card_holder_name')
            );
            $customer->getDatabaseObj()
                    ->reset()
                    ->update('writer_students')
                    ->set($user_billing_info)
                    ->where('Login', '=', $user_login)
                    ->query();
        }
            
        
        
        $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.purchase');
    }
    private function _pageActivate ($customer) {
        
        $model = &$customer->getModel();
        $digest = libraryRequest::getValue('digest');
        if (empty($digest) || strlen($digest) != 32) {
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.activate_error');
            return;
        }

        // get user info
        $studentInfo = $customer->getDatabaseObj()
                ->select('*')
                ->from('writer_students')
                ->where('UserToken', '=', $digest)
                ->fetchRow();
        
        //var_dump($studentInfo);
        
        if (empty($studentInfo)) {
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.activate_error');
            return;
        }
        
        // enable user
        $customer->getDatabaseObj()
                ->reset()
                ->update('writer_students')
                ->set(array('IsTemporary' => 0))
                ->where('UserToken', '=', $digest)
                ->fetchRow();
        
        $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.activate_ok');
        
    }
    private function _pageIndex ($customer) {
        //echo 'ololo';
        //echo $customer->getDump();
        $model = &$customer->getModel();
        $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.index');
    }
    private function _pageMakeOrder ($customer) {
        //echo 'ololo';
        
        $model = &$customer->getModel();
        $validator = $customer->getCustomerConfiguration('VALIDATOR');
        $messages = array();
        // session key
        $_sessionKey = md5(mt_rand(1, 1000));
        $model['CUSTOMER']['DATA_SESSION'] = $_sessionKey;
        // form data
        $data = libraryRequest::getPostMapContainer($validator['DATAMAP']['ORDER']);
        $model['CUSTOMER']['DATA'] = $data;
        // preview or checkout actions
        $isPreviewOrSave = libraryRequest::isPostFormActionMatchAny('proceed', 'checkout');
        // user object
        $user = array();
        // add condition to avoid new profile when user is loggined
        if ($model['USER']['ACTIVE']) {
            $user = $customer->getDatabaseObj()
                ->select('*')
                ->from('writer_students')
                ->where('ID', '=', $model['USER']['ID'])
                ->fetchRow();
            $model['CUSTOMER']['DATA_USER'] = $user;
        }
        
        // validate data when preview or save
        if ($isPreviewOrSave) {
            /* validate fileds */
            libraryValidator::validateData($data, $validator['FILTER']['ORDER'], $messages);
            $model['CUSTOMER']['MESSAGES'] = $messages;
        }
        
        
        // preview or save data
        // if action detected and no error messages
        if ($_SESSION['MPWS_ORDER_SESSION'] == libraryRequest::getPostValue('session_key') && empty($messages)) {
            
            // set new session key
            $_SESSION['MPWS_ORDER_SESSION'] = $_sessionKey;
 
            // get price info
            $priceInfo = $customer->getDatabaseObj()
                    ->select('*')
                    ->from('writer_prices')
                    ->where('ID', '=', $data['PriceID'])
                    ->fetchRow();
            
            // preview
            if (libraryRequest::isPostFormAction('proceed')) {


                $docInfo = $customer->getDatabaseObj()
                        ->select('*')
                        ->from('writer_documents')
                        ->where('ID', '=', $data['DocumentID'])
                        ->fetchRow();
                $subjInfo = $customer->getDatabaseObj()
                        ->select('*')
                        ->from('writer_subjects')
                        ->where('ID', '=', $data['SubjectID'])
                        ->fetchRow();


                $model['CUSTOMER']['DATA_PRICE'] = $priceInfo;
                $model['CUSTOMER']['DATA_DOC'] = $docInfo;
                $model['CUSTOMER']['DATA_SUBJECT'] = $subjInfo;
                //$model['CUSTOMER']['DATA_DEADLINE'] = date($mdbc['DB_DATE_FORMAT'], $_deadlineTime);
                $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.make_order_preview');
            } // -end of preview
            // save order
            if (libraryRequest::isPostFormAction('checkout')) {

                $mdbc = $customer->getCustomerConfiguration('MDBC');
                $customer_config_mail = $customer->GetCustomerConfiguration('MAIL');

                // user token
                //$_student = array();
                //$_userToken = false;
                
                // make new user
                if (!$model['USER']['ACTIVE']) {
                    // make temp user
                    $_userName = 'eu_' . libraryUtils::genRandomString(10);
                    $_userPwd = 'eu_' . libraryUtils::generatePassword(9, 8);
                    // make new user token
                    $_userToken = md5($_userPwd . $_userName . $data['Email'] . date($mdbc['DB_DATE_FORMAT']));
                    $user = array(
                        'Name' => $_userName,
                        'Login' => $_userName,
                        'Password' => md5($_userPwd),
                        'Email' => $data['Email'],
                        'Active' => 0,
                        'IsTemporary' => 1,
                        'UserToken' => $_userToken,
                        'DateCreated' => date($mdbc['DB_DATE_FORMAT']),
                        'DateLastAccess' => date($mdbc['DB_DATE_FORMAT'])
                    );
                    // save new user
                    $customer->getDatabaseObj()
                        ->reset()
                        ->insertInto('writer_students')
                        ->fields(array_keys($user))
                        ->values(array_values($user))
                        ->query();
                    // get new student's id
                    //$_student = $student;
                    $user['ID'] = $customer->getDatabaseObj()->getNewID();
                    
                }
                
                // make order token
                $_o_token = md5($user['UserToken'] . mktime());

                // save sources
                if (!empty($data['SourceLinks'])) {
                    foreach ($data['SourceLinks'] as $link)
                        $customer->getDatabaseObj()
                            ->reset()
                            ->insertInto('writer_sources')
                            ->fields(array('OrderToken', 'SourceURL', 'DateCreated'))
                            ->values(array($_o_token, $link, date($mdbc['DB_DATE_FORMAT'])))
                            ->query();
                }

                // remove non-accepted fileds
                unset($data['Email']);
                unset($data['Sources']);
                unset($data['SourceLinks']);

                // append additional information
                $_saveTime = mktime();
                $_deadlineTime = $_saveTime + (60 * 60 * ($priceInfo['Hours'] + (24 * 7 * $priceInfo['Weeks'])));

                // fill order information
                $data['StudentID'] = $user['ID'];
                $data['Price'] = $data['Pages'] * $priceInfo['Price'];
                $data['Discount'] = 0;
                $data['Credits'] = round($data['Price'] / 4, 0);
                $data['DateCreated'] = date($mdbc['DB_DATE_FORMAT'], $_saveTime);
                $data['DateDeadline'] = date($mdbc['DB_DATE_FORMAT'], $_deadlineTime);
                $data['RefundToken'] = '';
                $data['OrderToken'] = $_o_token;

                // save new order
                $customer->getDatabaseObj()
                    ->reset()
                    ->insertInto('writer_orders')
                    ->fields(array_keys($data))
                    ->values(array_values($data))
                    ->query();

                
                $libView = new libraryView();
                
                /* NOTIFY BUYER IF NEW */
                if (!$model['USER']['ACTIVE']) {
                    // form email object
                    $recipient = $customer_config_mail['NOTIFY'];
                    $recipient['TO'] = $user['Email'];
                    $recipient['NAME'] = $user['Name'];
                    $recipient['SUBJECT'] = 'Your account has been created';
                    $recipient['DATA'] = array(
                        'Login' => $user['Login'],
                        'Name' => $user['Name'],
                        'Password' => $_userPwd, // raw user password
                        'TargetUrl' => $customer_config_mail['URLS']['ACTIVATE'] . $user['UserToken'],
                        'SupportEmail' => $customer_config_mail['SUPPORT']['EMAIL']
                    );
                    //var_dump($recipient);
                    // get html message
                    $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.student.new'));
                    // send email message to new user
                    libraryMailer::sendEMail($recipient);
                }

                /* SYSTEM NOTIFY */
                $recipient = $customer_config_mail['ACTION_TRIGGERS']['ON_NEW_ORDER'];
                $recipient['TO'] = 'soulcor@gmail.com';
                $recipient['SUBJECT'] = 'New order has been created';
                $recipient['DATA'] = array(
                    'Name' => $user['Name'],
                    'TargetUrl' => $customer_config_mail['URLS']['TOOLBOX_ORDER_LINK'] . $_o_token
                );
                $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.order_created'));
                // send email message to system
                libraryMailer::sendEMail($recipient);

                // set public data
                //$model['CUSTOMER']['DATA_EMAIL'] = $student['Email'];
                //$model['CUSTOMER']['DATA_TOKEN'] = $_o_token;

                // general information
                $_order = array();
                $_order['sid'] = '1799160';
                $_order['quantity'] = '1';
                $_order['product_id'] = '1';
                $_order['merchant_order_id'] = $_o_token . '-' . $user['Login'];
                $_order['email'] = $user['Email'];
                $_order['submit'] = 'Buy from 2CO';
                // billing information if user is loggined
                if ($model['USER']['ACTIVE']) {
                    $_order['card_holder_name'] = $user['Billing_FirstName'];
                    $_order['street_address'] = $user['Billing_Address'];
                    $_order['city'] = $user['Billing_City'];
                    $_order['state'] = $user['Billing_State'];
                    $_order['zip'] = $user['Billing_PostalCode'];
                    $_order['country'] = $user['Billing_Country'];
                    $_order['email'] = $user['Billing_Email'];
                    $_order['phone'] = $user['Billing_Phone'];
                    $_order['pay_method'] = $user['CC'];
                }

                libraryRequest::locationRedirect($_order, 'https://www.2checkout.com/checkout/purchase');
                exit;
            }
            
        }
            
        // edit mode
        
        // set new session key
        $_SESSION['MPWS_ORDER_SESSION'] = $_sessionKey;
        
        // get order fields
        $dataFields['Level'] = $customer->getDatabaseObj()->getEnumValues('writer_orders', 'Level');
        $dataFields['Format'] = $customer->getDatabaseObj()->getEnumValues('writer_orders', 'Format');
        
        // get prices
        $prices = $customer->getDatabaseObj()
                ->select('*')
                ->from('writer_prices')
                ->fetchData();
        $documents = $customer->getDatabaseObj()
                ->select('*')
                ->from('writer_documents')
                ->fetchData();
        $subjects = $customer->getDatabaseObj()
                ->select('*')
                ->from('writer_subjects')
                ->fetchData();

        // set data
        $model['CUSTOMER']['DATA'] = $data;
        $model['CUSTOMER']['DATA_FIELDS'] = $dataFields;
        $model['CUSTOMER']['DATA_PRICES'] = $prices;
        $model['CUSTOMER']['DATA_DOCS'] = $documents;
        $model['CUSTOMER']['DATA_SUBJECTS'] = $subjects;
        
        // populate user fields
        if (!$isPreviewOrSave) {
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.make_order');
            if ($model['USER']['ACTIVE'])
                $model['CUSTOMER']['DATA']['Email'] = $user['Email'];
        }
    }

    public function _accountDeskCommonOrders_details ($customer) {
        $model = &$customer->getModel();
        $oid = libraryRequest::getOID();
        $messages = array();
        
        // check for empty oid
        if(empty($oid)) {
            $model['CUSTOMER']['TEMPLATE_NAME'] = 'orders_error';
            return;
        }
        
        // get order record
        $data_order = $customer->getDatabaseObj()
            ->select('*')
            ->from('writer_orders')
            ->where('ID', '=', $oid)
            ->fetchRow();
        
        // on wrong order id
        if(empty($data_order)) {
            $model['CUSTOMER']['TEMPLATE_NAME'] = 'orders_error';
            return;
        }
        
        // check if order created current user
        if ($model['USER']['ID'] != $data_order['StudentID']) {
            $model['CUSTOMER']['TEMPLATE_NAME'] = 'orders_error';
            return;
        }
        
        /* order actions */
        // send to rework
        if (libraryRequest::isPostFormAction('send to rework')) {
            
            if ($data_order['ReworkCount'] == 3)
                $messages[] = 'You can not send to rework more then 3 times.';
            else {
                $order_status = array(
                    'PublicStatus' => 'REWORK',
                    'InternalStatus' => 'OPEN',
                    'ReworkCount' => $data_order['ReworkCount'] + 1
                );
                $customer->getDatabaseObj()
                    ->reset()
                    ->update('writer_orders')
                    ->set($order_status)
                    ->where('ID', '=', $oid)
                    ->query();
                /* save internal message */
                $mdbc = $customer->getCustomerConfiguration('MDBC');
                $message['Subject'] = 'Buyer Wants To Rework';
                $message['Message'] = 'Ask owner for more details to rework.';
                $message['StudentID'] = $model['USER']['ID'];
                $message['OrderID'] = $oid;
                $message['Owner'] = 'SYSTEM';
                $message['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
                $data_messages = $customer->getDatabaseObj()
                    ->reset()
                    ->insertInto('writer_messages')
                    ->fields(array_keys($message))
                    ->values(array_values($message))
                    ->query();
                /* alter already selected order info */
                $data_order['PublicStatus'] = 'REWORK';
                $data_order['InternalStatus'] = 'OPEN';
                $data_order['ReworkCount']++;
            }
        }
        // want refund
        if (libraryRequest::isPostFormAction('want refund')) {
            $order_status = array(
                'PublicStatus' => 'TO REFUND',
                'InternalStatus' => 'OPEN'
            );
            $customer->getDatabaseObj()
                ->reset()
                ->update('writer_orders')
                ->set($order_status)
                ->where('ID', '=', $oid)
                ->query();
            /* save internal message */
            $mdbc = $customer->getCustomerConfiguration('MDBC');
            $message['Subject'] = 'Buyer Wants Refund';
            $message['Message'] = 'You must clarify the reason of refund action.';
            $message['StudentID'] = $model['USER']['ID'];
            $message['OrderID'] = $oid;
            $message['Owner'] = 'SYSTEM';
            $message['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
            $data_messages = $customer->getDatabaseObj()
                ->reset()
                ->insertInto('writer_messages')
                ->fields(array_keys($message))
                ->values(array_values($message))
                ->query();
            /* alter already selected order info */
            $data_order['PublicStatus'] = 'TO REFUND';
            $data_order['InternalStatus'] = 'OPEN';
        }
        if (libraryRequest::isPostFormAction('reopen order')) {
            $order_status = array(
                'PublicStatus' => 'REOPEN',
                'InternalStatus' => 'OPEN'
            );
            $customer->getDatabaseObj()
                ->reset()
                ->update('writer_orders')
                ->set($order_status)
                ->where('ID', '=', $oid)
                ->query();
            /* save internal message */
            $mdbc = $customer->getCustomerConfiguration('MDBC');
            $message['Subject'] = 'Order Is Reopened';
            $message['Message'] = 'Owner has reopened this order.';
            $message['StudentID'] = $model['USER']['ID'];
            $message['OrderID'] = $oid;
            $message['Owner'] = 'SYSTEM';
            $message['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
            $data_messages = $customer->getDatabaseObj()
                ->reset()
                ->insertInto('writer_messages')
                ->fields(array_keys($message))
                ->values(array_values($message))
                ->query();
            /* alter already selected order info */
            $data_order['PublicStatus'] = 'REOPEN';
            $data_order['InternalStatus'] = 'OPEN';
        }
        // close order
        if (libraryRequest::isPostFormAction('close order')) {
            $order_status = array(
                'PublicStatus' => 'CLOSED',
                'InternalStatus' => 'CLOSED'
            );
            $customer->getDatabaseObj()
                ->reset()
                ->update('writer_orders')
                ->set($order_status)
                ->where('ID', '=', $oid)
                ->query();
            /* save internal message */
            $mdbc = $customer->getCustomerConfiguration('MDBC');
            $message['Subject'] = 'Order Is Closed';
            $message['Message'] = 'Owner has closed this order.';
            $message['StudentID'] = $model['USER']['ID'];
            $message['OrderID'] = $oid;
            $message['Owner'] = 'SYSTEM';
            $message['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
            $data_messages = $customer->getDatabaseObj()
                ->reset()
                ->insertInto('writer_messages')
                ->fields(array_keys($message))
                ->values(array_values($message))
                ->query();
            /* alter already selected order info */
            $data_order['PublicStatus'] = 'CLOSED';
            $data_order['InternalStatus'] = 'CLOSED';
        }
        // posn new message
        if (libraryRequest::isPostFormAction('post message')) {
            $validator = $customer->getCustomerConfiguration('VALIDATOR');
            $mdbc = $customer->getCustomerConfiguration('MDBC');
            $message = libraryRequest::getPostMapContainer($validator['DATAMAP']['MESSAGES']);
            libraryValidator::validateData($message, $validator['FILTER']['MESSAGES'], $messages);
            // check for errors
            if (!count($messages)) {
                $message['StudentID'] = $model['USER']['ID'];
                $message['OrderID'] = $oid;
                $message['Owner'] = strtoupper($model['USER']['TYPE']);
                $message['IsPublic'] = 1;
                $message['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
                $data_messages = $customer->getDatabaseObj()
                    ->reset()
                    ->insertInto('writer_messages')
                    ->fields(array_keys($message))
                    ->values(array_values($message))
                    ->query();
            
                /* notify writer with new message */
            }
        }
        if (libraryRequest::isPostFormAction('save changes')) {
            $mdbc = $customer->getCustomerConfiguration('MDBC');
            
            $deadline = libraryRequest::getPostValue('order_deadline');
            
            // check if deadline is changed
            if ($deadline !== $data_order['DateDeadline']) {
                // save new deadline
                if ($deadline > date($mdbc['DB_DATE_FORMAT'])) {
                    $customer->getDatabaseObj()
                        ->reset()
                        ->update('writer_orders')
                        ->set(array('DateDeadline' => $deadline))
                        ->where('ID', '=', $oid)
                        ->query();

                    /* save internal message */
                    $message['Subject'] = 'Date Deadline is changed';
                    $message['Message'] = $data_order['DateDeadline'] . ' => ' . $deadline;
                    $message['StudentID'] = $model['USER']['ID'];
                    $message['OrderID'] = $oid;
                    $message['Owner'] = 'SYSTEM';
                    $message['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
                    $data_messages = $customer->getDatabaseObj()
                        ->reset()
                        ->insertInto('writer_messages')
                        ->fields(array_keys($message))
                        ->values(array_values($message))
                        ->query();

                    /* notify writer with new deadline */


                    /* alter already selected order info */
                    $data_order['DateDeadline'] = $deadline;
                }
            }
            
            /* save new sources */

            // get current sources
            $currentSources = $customer->getDatabaseObj()
                ->reset()
                ->select('SourceURL')
                ->from('writer_sources')
                ->where('OrderToken', '=', $data_order['OrderToken'])
                ->fetchData();
            $_c_sources = array();
            foreach ($currentSources as $key => $val)
                $_c_sources[] = $val['SourceURL'];
            // get new sources
            $_sources = libraryRequest::getPostValue('order_source_links');
            if (empty($_sources))
                $_sources = array();
            // compare arrays
            $srcDiff = array_diff_assoc($_c_sources, $_sources);
            
            //echo '<pre>'.print_r($_c_sources, true).'</pre>';
            //echo '<pre>'.print_r($_sources, true).'</pre>';
            //echo '<pre>'.print_r($srcDiff, true).'</pre>';
            
            // proceed if sources were changed
            if (count($srcDiff)) {
                // remove previous links
                $customer->getDatabaseObj()
                    ->deleteFrom('writer_sources')
                    ->where('OrderToken', '=', $data_order['OrderToken'])
                    ->query();
                // add new source links
                foreach ($_sources as $link) {
                    if (empty($link))
                        continue;
                    $customer->getDatabaseObj()
                        ->reset()
                        ->insertInto('writer_sources')
                        ->fields(array('OrderToken', 'SourceURL', 'DateCreated'))
                        ->values(array($data_order['OrderToken'], $link, date($mdbc['DB_DATE_FORMAT'])))
                        ->query();
                }

                /* save internal message */
                $message['Subject'] = 'Sources were changed.';
                if (empty($_sources) && count($currentSources) != 0)
                    $message['Message'] = 'All sources were removed.';
                else
                    $message['Message'] = implode('<br>', $_sources);
                $message['StudentID'] = $model['USER']['ID'];
                $message['OrderID'] = $oid;
                $message['Owner'] = 'SYSTEM';
                $message['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
                $data_messages = $customer->getDatabaseObj()
                    ->reset()
                    ->insertInto('writer_messages')
                    ->fields(array_keys($message))
                    ->values(array_values($message))
                    ->query();
            }
        }
        
        // collect order data and related items
        $data_messages = $customer->getDatabaseObj()
            ->select('*')
            ->from('writer_messages')
            ->where('OrderID', '=', $oid)
            ->andWhere('IsPublic', '=', 1)
            ->orderBy('DateCreated')
            ->order('DESC')
            ->fetchData();
        
        /*$data_student = $customer->getDatabaseObj()
            ->select('*')
            ->from('writer_students')
            ->where('ID', '=', $data_order['StudentID'])
            ->fetchRow();*/
        
        $data_price = $customer->getDatabaseObj()
            ->select('*')
            ->from('writer_prices')
            ->where('ID', '=', $data_order['PriceID'])
            ->fetchRow();
        
        $data_sources = $customer->getDatabaseObj()
            ->select('SourceURL')
            ->from('writer_sources')
            ->where('OrderToken', '=', $data_order['OrderToken'])
            ->fetchData();
        
        $data_document = $customer->getDatabaseObj()
            ->select('Name')
            ->from('writer_documents')
            ->where('ID', '=', $data_order['DocumentID'])
            ->fetchRow();
        
        $data_subject = $customer->getDatabaseObj()
            ->select('Name')
            ->from('writer_subjects')
            ->where('ID', '=', $data_order['SubjectID'])
            ->fetchRow();
        
        $data_invoice_order = array();
        if (!empty($data_order['OrderToken']))
            $data_invoice_order = $customer->getDatabaseObj()
                ->select('*')
                ->from('writer_invoices')
                ->where('merchant_order_id', '=', $data_order['OrderToken'])
                ->fetchRow();
        
        $data_invoice_refund = array();
        if (!empty($data_order['RefundToken']))
            $data_invoice_refund = $customer->getDatabaseObj()
                ->select('*')
                ->from('writer_invoices')
                ->where('merchant_order_id', '=', $data_order['RefundToken'])
                ->fetchRow();
        
        $model['CUSTOMER']['DATA'] = $data_order;
        $model['CUSTOMER']['DATA_DOCUMENT'] = $data_document;
        $model['CUSTOMER']['DATA_SUBJECT'] = $data_subject;
        $model['CUSTOMER']['DATA_MESSAGES'] = $data_messages;
        //$model['CUSTOMER']['DATA_STUDENT'] = $data_student;
        //$model['CUSTOMER']['DATA_WRITER'] = $data_writer;
        $model['CUSTOMER']['DATA_PRICE'] = $data_price;
        $model['CUSTOMER']['DATA_INVOICE_ORDER'] = $data_invoice_order;
        $model['CUSTOMER']['DATA_INVOICE_REFUND'] = $data_invoice_refund;
        $model['CUSTOMER']['DATA_SOURCES'] = $data_sources;
        $model['CUSTOMER']['MESSAGES'] = $messages;
        //$model['CUSTOMER']['template'] = $plugin['templates']['page.orders.details'];
    }
    public function _accountDeskCommonOrders_all ($customer) {
        $model = &$customer->getModel();
        // get new tasks
        echo $_SESSION['WEB_USER']['TYPE'].'ID'. '='. $_SESSION['WEB_USER']['ID'];
        $data = $customer->getDatabaseObj()
                ->reset()
                ->select('ID, Title, Price, Pages, Format, DateCreated, DateDeadline, PublicStatus, ReworkCount')
                ->from('writer_orders')
                ->where($_SESSION['WEB_USER']['TYPE'].'ID', '=', $_SESSION['WEB_USER']['ID'])
                ->andWhere('PublicStatus', '<>', 'CLOSED')
                ->fetchData();
        $model['CUSTOMER']['DATA'] = libraryUtils::groupArrayRowsByField($data, 'PublicStatus');
    }

    private function _accountDeskCommonHistoryOrders ($customer) {
        $model = &$customer->getModel();
        $data_orders = array();
        $data_suma = 0;
        if (libraryRequest::isPostFormAction('show orders')) {
            $data_orders = $customer->getDatabaseObj()
                ->select('*')
                ->from('writer_orders')
                ->where($_SESSION['WEB_USER']['TYPE'].'ID', '=', $_SESSION['WEB_USER']['ID'])
                ->andWhere('Status', '=', 'CLOSED')
                ->andWhere('DateCreated', '>', libraryRequest::getPostValue('start_date'))
                ->andWhere('DateCreated', '<', libraryRequest::getPostValue('end_date'))
                ->fetchData();


            foreach ($data_orders as $entry) {
                if ($_SESSION['WEB_USER']['TYPE'] == 'Student')
                    $data_suma += $entry['Price'];
                else
                    $data_suma += $entry['Credits'];
            }
            
            
            $model['CUSTOMER']['DATA_DATE_START'] = libraryRequest::getPostValue('start_date');
            $model['CUSTOMER']['DATA_DATE_END'] = libraryRequest::getPostValue('end_date');
        }
        $model['CUSTOMER']['DATA'] = $data_orders;
        $model['CUSTOMER']['DATA_SUMA'] = $data_suma;
    }

    private function _accountDeskCommonSettings ($customer) {
        $model = &$customer->getModel();
        $messages = array();
        $data = array();
        
        
        if (libraryRequest::isPostFormAction('change password')) {
            $validator = $customer->getCustomerConfiguration('VALIDATOR');
            $data = libraryRequest::getPostMapContainer($validator['DATAMAP']['ACCOUNT_PWD_UPDATE']);
            libraryValidator::validateData($data, $validator['FILTER']['ACCOUNT_PWD_UPDATE'], $messages);
            // get current password
            $currentUser = $customer->getDatabaseObj()
                ->reset()
                ->select('Password')
                ->from($model['USER']['REALM'])
                ->where('ID', '=', $model['USER']['ID'])
                ->fetchRow();
            
            if ($currentUser['Password'] !== md5($data['CurrentPassword']))
                $messages[] = 'Wrong current password.';
            
            var_dump($currentUser);
            
            if (empty($messages)) {
                /*
                $customer->getDatabaseObj()
                    ->update($model['USER']['REALM'])
                    ->set($data)
                    ->where('ID', '=', $model['USER']['ID'])
                    ->query();
                $messages[] = 'Your password was changed.';*/
            }
            
            
        } elseif (libraryRequest::isPostFormAction('update')) {
            $validator = $customer->getCustomerConfiguration('VALIDATOR');
            $data = libraryRequest::getPostMapContainer($validator['DATAMAP']['ACCOUNT_UPDATE']);
            libraryValidator::validateData($data, $validator['FILTER']['ACCOUNT_UPDATE'], $messages);
            // check for double login
            $doubleUser = $customer->getDatabaseObj()
                ->reset()
                ->select('Login')
                ->from($model['USER']['REALM'])
                ->where('Login', '=', $data['Login'])
                ->andWhere('ID', '<>', $model['USER']['ID'])
                ->fetchRow();
            if (!empty($doubleUser))
                $messages[] = 'This login is already used.';

            //var_dump($data);
            //var_dump(array_keys($data));
            //echo '<br><br><br>';
            //var_dump(array_values($data));
            
            if (empty($messages)) {
                $customer->getDatabaseObj()
                    ->update($model['USER']['REALM'])
                    ->set($data)
                    ->where('ID', '=', $model['USER']['ID'])
                    ->query();
                $messages[] = 'Your account information was updated successfully.';
            }
        } else {
            // get user information
            $data = $customer->getDatabaseObj()
                ->reset()
                ->select('Name, Login, Email, Phone,
                    Billing_FirstName, Billing_LastName,
                    Billing_Email, Billing_Phone, Billing_Address,
                    Billing_City, Billing_State, Billing_PostalCode,
                    Billing_Country')
                ->from($model['USER']['REALM'])
                ->where('ID', '=', $model['USER']['ID'])
                ->fetchRow();
        }
        
        $model['CUSTOMER']['DATA'] = $data;
        $model['CUSTOMER']['MESSAGES'] = $messages;
        //$data['Password'] = false;
        
        
        /*if (empty($user))
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.accountdesk.settings_empty');
        else
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.accountdesk.settings_list');*/
    }
    
    private function _userGetInfo($customer) {
        //debug('_userGetInfo');
        //echo '<br>Current User: ' . $_SESSION['USER']['NAME'];
        //echo '<br>Logined since: ' . $_SESSION['USER']['SINCE'];
        //echo '<br>Last access ' . $_SESSION['USER']['LAST_ACCESS'];
        $state = $this->_userVerifySession($customer);
        $user = isset($_SESSION['WEB_USER'])?$_SESSION['WEB_USER']:array();
        $user['STATE'] = $state;
        $user['ACTIVE'] = ($user['STATE'] == 'USER_AUTHORIZED' || $user['STATE'] == 'USER_ALIVE');
        //$model['USER'] = $user;
        //echo '<br>_userGetInfo<br>';
        //var_dump($user);
        return $user;
    }
    private function _userVerifySession ($customer) {
        global $config;
    
        // check credentials
        $login = libraryRequest::getPostValue('user_login');
        $pwd = libraryRequest::getPostValue('user_pwd');

        $_accountRealm = '';
        $_userType = '';

        // logout user
        if (libraryRequest::isPostFormAction('logout')) {
            //echo 'olololo';
            echo '--------------------------- logout';
            if(!empty($_SESSION['WEB_USER'])) {
                // put user offline
                if (!empty($_SESSION['WEB_USER']['ID']))
                    $customer->getDatabaseObj()
                            ->update($_SESSION['WEB_USER']['REALM'])
                            ->set(array('IsOnline' => 0))
                            ->where('ID', '=', $_SESSION['USER']['ID'])
                            ->query();
                $_SESSION['WEB_USER'] = false;
                return 'USER_FORCE_LOGOUT';
            }
            return 'USER_ALREADY_LOGOUT';
        }
        
        
        //echo 'trololo<br><br>';
        //var_dump($config['WEB']);
        
        // do login
        if (empty($_SESSION['WEB_USER'])) {
            
            //echo '<br>EMPTY SESSION WEB_USER<br>';
            
            if (libraryRequest::isPostFormAction('log in')) {

                $pwd = libraryRequest::getPostValue('user_pwd');
                $login = libraryRequest::getPostValue('user_login');

                //echo 'olololo';
                if (empty($pwd) || empty($login))
                    return 'USER_EMPTY_CREDENTIALS';

                // attempt to login user: student
                $userStud = $customer->getDatabaseObj()
                            ->select('*')
                            ->from('writer_students')
                            ->where('Login', '=', $login)
                            ->andWhere('Password', '=', md5($pwd))
                            ->fetchRow();

                // attempt to login user: writer
                $userWrtr = $customer->getDatabaseObj()
                            ->select('*')
                            ->from('writer_writers')
                            ->where('Login', '=', $login)
                            ->andWhere('Password', '=', md5($pwd))
                            ->fetchRow();
                
                $user = array();
                if (!empty($userStud)) {
                    $_accountRealm = 'writer_students';
                    $user = $userStud;
                    $_userType = 'Student';
                } elseif (!empty($userWrtr)) {
                    $_accountRealm = 'writer_writers';
                    $user = $userWrtr;
                    $_userType = 'Writer';
                } else
                    $_accountRealm = '';
      
                //var_dump($user);
                if (!empty($user['ID'])) {
                    //echo '<br>-----------------------------Make User';
                    $_SESSION['WEB_USER'] = array(
                        'ID' => $user['ID'],
                        'NAME' => $user['Name'],
                        'LOGIN' => $user['Login'],
                        'REALM' => $_accountRealm,
                        'TYPE' => $_userType,
                        'TEMPORARY' => !empty($user['IsTemporary']),
                        'TOKEN' => $user['UserToken'],
                        'SINCE' => mktime(),
                        'LAST_ACCESS' => mktime()
                    );

                    // set last access
                    $customer_config_mdbc = $customer->GetCustomerConfiguration('MDBC');
                    $customer->getDatabaseObj()
                            ->update($_accountRealm)
                            ->set(array(
                                'DateLastAccess' => date($customer_config_mdbc['DB_DATE_FORMAT']),
                                'IsOnline' => 1
                            ))
                            ->where('ID', '=', $user['ID'])
                            ->query();

                    return 'USER_AUTHORIZED';
                } else
                    return 'USER_WRONG_CREDENTIALS';
            } else
                return 'USER_MUST_LOGIN';
        }

        // check for session expiration
        debug('Session time ' . $config['WEB']['SESSION_TIME']);
        $sessionIdle = ($_SESSION['WEB_USER']['LAST_ACCESS'] + $config['WEB']['SESSION_TIME']) < mktime();
        
        if ($sessionIdle) {
            //echo 'USER_TIMEOUT';
            // put user offline
            if (!empty($_SESSION['WEB_USER']['ID']))
                $customer->getDatabaseObj()
                        ->update($_SESSION['WEB_USER']['REALM'])
                        ->set(array('IsOnline' => 0))
                        ->where('ID', '=', $_SESSION['USER']['ID'])
                        ->query();
            $_SESSION['WEB_USER'] = false;
            return 'USER_TIMEOUT';
        }

        // keep user alive
        $_SESSION['WEB_USER']['LAST_ACCESS'] = mktime();
        return 'USER_ALIVE';

    }
}


?>
