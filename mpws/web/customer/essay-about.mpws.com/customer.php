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
        $model = &$toolbox->getModel();
        $p = libraryRequest::getApiParam();
        
        //var_dump($p);
        

        if (empty($p['token']) || !libraryRequest::getOrValidatePageSecurityToken($p['token']))
            return;
        
        $result = false;
        switch (libraryRequest::getApiFn('none')) {
            case 'check_login':
                //debug(libraryRequest::getApiParam());
                $result = $this->api_check_login($toolbox, $plugin);
                break;
            case 'writer_orders':
                break;
            case 'mark_as_read':
                $result = $this->api_mark_as_read($toolbox, $plugin);
                break;
            case 'assign_to_writer':
                $result = $this->api_assign_to_writer($toolbox, $plugin);
                break;
            case 'get_teamload':
                $result = $this->api_get_teamload($toolbox, $plugin);
                break;
        }
        
        return $result;
        //echo 'Customer API |';
    }
    
    private function api_check_login ($toolbox, $plugin) {
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
        $action = libraryRequest::getAction('new');

        //echo '<br><br>ACTION SWITCHER ['.$action.']<br><br>';
        //if (libraryRequest::isPostFormAction('logout')) {}
        switch ($action) {
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
                $this->_accountDeskCommonOrders($customer);
                $action = 'orders';
                break;
            }
        }

        // set template
        if (empty($model['CUSTOMER']['TEMPLATE'])) {
            $templateName = strtolower($action.'_'.$_SESSION['WEB_USER']['TYPE']);
            echo '<br><br><br>USING STANDART TEMPLATE:  =====> ' . $templateName;
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
        $data = libraryRequest::getPostMapContainer($validator['DATAMAP']['ORDER']);
        $model['CUSTOMER']['DATA'] = $data;
        
        //echo '<br><br><br><br><br><br><br>';
        
        /*
            $libView = new libraryView();
            $customer_config_mail = $customer->GetCustomerConfiguration('MAIL');
            $recipient = $customer_config_mail['ACTION_TRIGGERS']['ON_NEW_ORDER'];
            $recipient['TO'] = 'soulcor@gmail.com';
            $recipient['SUBJECT'] = 'New order has been created';
            $recipient['DATA'] = array(
                'Name' => 'eu_spuc5pja0r',
                'TargetUrl' => $customer_config_mail['URLS']['TOOLBOX_ORDER_LINK'] . '5c920345b06da0f16e68ae658ca46353'
            );
            $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.order_created'));
            // send email message to system
            libraryMailer::sendEMail($recipient);
            //unset($recipient['MESSAGE']);
            var_dump($recipient);  */    

        //var_dump($validator['DATAMAP']['ORDER']);
        // preview
        
        //var_dump(libraryRequest::getPostValue('order_source_links'));
        
        // session key
        $_sessionKey = md5(mt_rand(1, 1000));
        $model['CUSTOMER']['DATA_SESSION'] = $_sessionKey;
        
        if ($_SESSION['MPWS_ORDER_SESSION'] == libraryRequest::getPostValue('session_key') && libraryRequest::isPostFormActionMatchAny('proceed', 'checkout')) {

            // set new session key
            $_SESSION['MPWS_ORDER_SESSION'] = $_sessionKey;
            
            //echo 'PROCEED OR CHECKOUT!!!!!!!';
            
            /* validate fileds */
            libraryValidator::validateData($data, $validator['FILTER']['ORDER'], $messages);
            
            // if ok to proceed
            if (empty($messages)) {
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
                }
                // save order
                if (libraryRequest::isPostFormAction('checkout')) {
                   
                    $mdbc = $customer->getCustomerConfiguration('MDBC');
                    $customer_config_mail = $customer->GetCustomerConfiguration('MAIL');
                    
                    // add condition to avoid new profile when user is loggined
                    if ($model['USER']['ACTIVE']) {
                        // fill all billing fields
                        
                        
                    } else {
                        // make new user
                        
                    }
                    
                    
                    // make temp user
                    $_userName = 'eu_' . libraryUtils::genRandomString(10);
                    $_userPwd = 'eu_' . libraryUtils::generatePassword(9, 8);
                    
                    // make user token
                    $_userToken = md5($_userPwd . $_userName . $data['Email'] . date($mdbc['DB_DATE_FORMAT']));
                    
                    // make order token
                    $_o_token = md5($_userToken . mktime());
                    
                    $student = array(
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
                    $customer->getDatabaseObj()
                        ->reset()
                        ->insertInto('writer_students')
                        ->fields(array_keys($student))
                        ->values(array_values($student))
                        ->query();
                    
                    
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
                    
                    // get new student's row
                    $newStudent = $customer->getDatabaseObj()
                        ->select('ID')
                        ->from('writer_students')
                        ->where('UserToken', '=', $_userToken)
                        ->fetchRow();
                    

                    $_saveTime = mktime();
                    $_deadlineTime = $_saveTime + (60 * 60 * ($priceInfo['Hours'] + (24 * 7 * $priceInfo['Weeks'])));
                    
                    
                    // fill order information
                    $data['StudentID'] = $newStudent['ID'];
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
                    
                    /* NOTIFY BUYER */
                    //$notifyWriter = libraryRequest::getPostValue('writer_notify');
                    //if (!empty($notifyWriter)) {
                        // form email object
                        $recipient = $customer_config_mail['NOTIFY'];
                        $recipient['TO'] = $student['Email'];
                        $recipient['NAME'] = $_userName;
                        $recipient['SUBJECT'] = 'Your account has been created';
                        $recipient['DATA'] = array(
                            'Login' => $_userName,
                            'Name' => $_userName,
                            'Password' => $_userPwd,
                            'TargetUrl' => $customer_config_mail['URLS']['ACTIVATE'] . $_userToken,
                            'SupportEmail' => $customer_config_mail['SUPPORT']['EMAIL']
                        );
                        //var_dump($recipient);
                        // get html message
                        $libView = new libraryView();
                        $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.student.new'));
                        // send email message to new user
                        libraryMailer::sendEMail($recipient);
                        
                        
                        /* SYSTEM NOTIFY */
                        $recipient = $customer_config_mail['ACTION_TRIGGERS']['ON_NEW_ORDER'];
                        $recipient['SUBJECT'] = 'New order has been created';
                        $recipient['DATA'] = array(
                            'Name' => $_userName,
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
                        $_order['merchant_order_id'] = $_o_token . '-' . $_userName;
                        $_order['email'] = $student['Email'];
                        $_order['submit'] = 'Buy from 2CO';
                        // billing information
                        // if user is loggined
                        if (!empty($model['USER']['ID'])) {
                            $user = $customer->getDatabaseObj()
                                ->select('*')
                                ->from('writer_students')
                                ->where('ID', '=', $model['USER']['ID'])
                                ->fetchRow();
                            
                        }
                        
                        libraryRequest::locationRedirect($_order, 'https://www.2checkout.com/checkout/purchase');
                        exit;
                        
                        //$model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.make_order_saved');
                    //}
                    
                    return;
                }
            } else {
                $model['CUSTOMER']['MESSAGES'] = $messages;
                $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.make_order');
            }
        }
        else
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.make_order');
        
            
        
        // set session key
        $_SESSION['MPWS_ORDER_SESSION'] = $_sessionKey;
        
        //if (libraryRequest::isPostFormAction('edit'))
            
        //echo 'EDITOR!!!!!!!';

        //var_dump($messages);

        // get order fields
        $query = $customer->getCustomerConfiguration('QUERY');
        $orderFields = $customer->getDatabaseObj()->fetchData($query['GET_ORDER_FIELDS']);
        $dataFields = array();
        foreach ($orderFields as $field) {
            $dataFields[$field['Field']] = libraryUtils::getArrayFromDBEnum($field['Type']);
        }
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

        //echo '<pre>' . print_r($data, true) . '</pre>';

        $model['CUSTOMER']['DATA'] = $data;
        $model['CUSTOMER']['DATA_FIELDS'] = $dataFields;
        $model['CUSTOMER']['DATA_PRICES'] = $prices;
        $model['CUSTOMER']['DATA_DOCS'] = $documents;
        $model['CUSTOMER']['DATA_SUBJECTS'] = $subjects;
        
        
        if (!libraryRequest::isPostFormActionMatchAny('proceed', 'checkout') && !empty($model['USER']['ID'])) {
            $user = $customer->getDatabaseObj()
                ->select('Email')
                ->from('writer_students')
                ->where('ID', '=', $model['USER']['ID'])
                ->fetchRow();
            $model['CUSTOMER']['DATA']['Email'] = $user['Email'];
        }
                

        //var_dump($model['CONFIG']);
        //var_dump($fields);
        //var_dump($dataFields);
        //var_dump($data);

        
        

    }


    private function _accountDeskCommonOrders ($customer, $orderStatus = array('NEW', 'PENDING', 'REWORK',  'IN PROGRESS', 'REJECTED')) {
        
        
        echo '<pre>' . print_r($customer->getCustomerConfiguration('MAIL'), true) . '</pre>';
        
        
        $model = &$customer->getModel();
        // get new tasks
        echo $_SESSION['WEB_USER']['TYPE'].'ID'. '='. $_SESSION['WEB_USER']['ID'];
        $data = array();
        foreach ($orderStatus as $orderStatus)
            $data[$orderStatus] = $customer->getDatabaseObj()
                    ->reset()
                    ->select('ID, Title, Price, Pages, Format, DateCreated, DateDeadline, PublicStatus, ReworkCount')
                    ->from('writer_orders')
                    ->where($_SESSION['WEB_USER']['TYPE'].'ID', '=', $_SESSION['WEB_USER']['ID'])
                    ->andWhere('PublicStatus', '=', $orderStatus)
                    ->fetchData();
        // do not show student rejected status
        // render rows with previous status
        
        foreach ($data['REJECTED'] as $key => $row)
            $data['REJECTED'][$key]['Status'] = 'New';
        $data['NEW'] = array_merge($data['NEW'], $data['REJECTED']);
        
        unset($data['REJECTED']);
        
        
        $model['CUSTOMER']['DATA'] = $data;
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
        if (libraryRequest::isPostFormAction('update')) {

            $validator = $customer->getCustomerConfiguration('VALIDATOR');
            //$validatorKey = 'ACCOUNT_UPDATE' . strtoupper($model['USER']['TYPE']);
            $validatorKey = 'ACCOUNT_UPDATE';
            $data = libraryRequest::getPostMapContainer($validator['DATAMAP'][$validatorKey]);
            libraryValidator::validateData($data, $validator['FILTER'][$validatorKey], $messages);

            if (empty($messages)) {
                /*$customer->getDatabaseObj()
                    ->update($model['USER']['REALM'])
                    ->set(array_keys($data))
                    ->values(array_values($data))
                    ->where('ID', '=', $model['USER']['ID'])
                    ->query();*/
                $model['CUSTOMER']['MESSAGES'][] = 'Your account information was updated successfully.';
            } else
                $model['CUSTOMER']['MESSAGES'] = array_merge($model['CUSTOMER']['MESSAGES'], $messages);
        } 
        else // get user information
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
        
        $data['Password'] = false;
        
        $model['CUSTOMER']['DATA'] = $data;
        
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
