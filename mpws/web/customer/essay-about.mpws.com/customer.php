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
                $model['LAYOUT'] = 'layout_inner';
                $this->_pageMakeOrder($customer);
                break;
            }
            case 'buy-essays':{
                $model['LAYOUT'] = 'layout_inner';
                $this->_pageBuyEssay($customer);
                break;
            }
            case 'free-essays':{
                $model['LAYOUT'] = 'layout_inner';
                $this->_pageFreeEssay($customer);
                break;
            }
            case 'home':
                $this->_pageIndex($customer);
                break;
            case 'account':
                // set layout
                $model['LAYOUT'] = 'layout_inner';
                $this->_pageAccount($customer);
                break;
            case 'activate':
                $model['LAYOUT'] = 'layout_inner';
                $this->_pageActivate($customer);
                break;
            case 'purchase':
                $model['LAYOUT'] = 'layout_inner';
                $this->_pagePurchase($customer);
                break;
            default:{
                $model['LAYOUT'] = 'layout_inner';
                $this->_pageNotFound($customer);
                break;
            }
        }
        
        $this->_componentMenu($customer);
        
        
        // remove expired accounts
        $param['dbo'] = $customer->getDatabaseObj();
        libraryToolboxManager::callPluginMethod('writer', 'useremoval', $param);
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
        
        $layout = 'layout_startup';
        
        // overriden default layout
        if (!empty($model['LAYOUT']))
            $layout = $model['LAYOUT'];
       
        //echo $customer->getDump();
        
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
        //echo $p['oid'];
        $model = &$customer->getModel();
        if (!$model['USER']['ACTIVE'])
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
        
        $display = $customer->getCustomerConfiguration('display');
        
        // set all menus
        foreach ($display['PUBLIC_MENU'] as $key => $entry) {
            $model['CUSTOMER']['COMPONENT']['MENU_' . $key]['DATA'] = $entry;
            $model['CUSTOMER']['COMPONENT']['MENU_' . $key]['TEMPLATE'] = $customer->getCustomerTemplate('component.menu');
        }
        
        // check for user
        if ($model['USER']['ACTIVE']) {
            $key = strtoupper($_SESSION['WEB_USER']['TYPE']);
            $model['CUSTOMER']['COMPONENT']['MENU_ACCOUNT']['DATA'] = $display['ACCOUNT_MENU'][$key];
            $model['CUSTOMER']['COMPONENT']['MENU_ACCOUNT']['TEMPLATE'] = $customer->getCustomerTemplate('component.menu');
        }
        //var_dump($model['CUSTOMER']['COMPONENT']['MENU']);
    }

    /* pages */
    private function _pageNotFound ($customer) {
        $model = &$customer->getModel();
        $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.404');
    }
    private function _pageAccount ($customer, $innerReturnDisplay = false) {
        $model = &$customer->getModel();
        
        //echo '<br>******* 1 ****<br>';
        //echo 'customer is empty ' . (empty($customer)?'+':'-');
        //echo '<br>******* 2 ****<br>';
        
        //var_dump($model['USER']);
                // register action
        if (libraryRequest::isPostFormAction('register')) {
            $messages = array();
            
            // validate data
            $validator = $customer->getCustomerConfiguration('VALIDATOR');
            $data = libraryRequest::getPostMapContainer($validator['DATAMAP']['ACCOUNT_CREATE']);
            libraryValidator::validateData($data, $validator['FILTER']['ACCOUNT_CREATE'], $messages);
            
            if ($data['Password'] !== $data['Password2'])
                $messages[] = 'Confirmation password does not match with main.';
            
            if (!empty($messages)) {
                $model['CUSTOMER']['MESSAGES'] = $messages;
                $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.accountdesk.login');
                return;
            }
            
            // look for the same login or email
            $doubleLogin = $customer->getDatabaseObj()
                ->reset()
                ->select('Login')
                ->from('writer_students')
                ->where('Login', '=', $data['Login'])
                ->fetchRow();
            $doubleEmail = $customer->getDatabaseObj()
                ->reset()
                ->select('Login')
                ->from('writer_students')
                ->where('Email', '=', $data['Email'])
                ->fetchRow();
            if (!empty($doubleLogin))
                $messages[] = 'This login is already used.';
            if (!empty($doubleEmail))
                $messages[] = 'This email is already used.';

            if (!empty($messages)) {
                $model['CUSTOMER']['MESSAGES'] = $messages;
                $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.accountdesk.login');
                return;
            }
            
            $mdbc = $customer->getCustomerConfiguration('MDBC');
            $customer_config_mail = $customer->GetCustomerConfiguration('MAIL');
                
            // make new user token
            $_userToken = md5($data['Password'] . $data['Name'] . $data['Email'] . date($mdbc['DB_DATE_FORMAT']));
            
            // save new user
            $user = array(
                'Name' => $data['Name'],
                'Login' => $data['Login'],
                'Password' => md5($data['Password']),
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
            
            $libView = new libraryView();
            // form email object
            $recipient = $customer_config_mail['NOTIFY'];
            $recipient['TO'] = $user['Email'];
            $recipient['NAME'] = $user['Name'];
            $recipient['SUBJECT'] = 'Your account has been created';
            $recipient['DATA'] = array(
                'Login' => $user['Login'],
                'Name' => $user['Name'],
                'Password' => $data['Password'], // raw user password
                'TargetUrl' => $customer_config_mail['URLS']['ACTIVATE'] . $user['UserToken'],
                'SupportEmail' => $customer_config_mail['SUPPORT']['EMAIL']
            );
            //var_dump($recipient);
            // get html message
            $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.student.new'));
            // send email message to new user
            libraryMailer::sendEMail($recipient);
            
            /* system email notification */
            $recipient = $customer_config_mail['ACTION_TRIGGERS']['ON_NEW_REGISTRATION'];
            $recipient['SUBJECT'] = 'New User Is Registered';
            $recipient['DATA'] = array(
                'Name' => $user['Name']
            );
            $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.user_registered'));
            // send email message to system
            libraryMailer::sendEMail($recipient);
            
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.accountdesk.registered');
            return;
        }
        
        // check for login
        if (!$model['USER']['ACTIVE']) {
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.accountdesk.login');
            return;
        }

        // if account is not activated we show message
        if ($model['USER']['TEMPORARY'])
            $model['CUSTOMER']['MESSAGES'][] = '
                Your account will be deleted in 73 hours.<br>
                To activate your account please click <a href="/page/activate.html?digest='.$model['USER']['TOKEN'].'" target="blank">here</a>';

        
        
        //$messages = array();
        $display = libraryRequest::getDisplay('orders');
        $action = libraryRequest::getAction('default');

        
        //echo '<br><br>ACTION SWITCHER ['.$action.']<br><br>';
        //if (libraryRequest::isPostFormAction('logout')) {}
        
        // internal return display
        if (!empty($innerReturnDisplay)) {
            // set new display
            $display = $innerReturnDisplay;
            // set default action
            $action = 'default';
            // reset previous return
            $innerReturnDisplay = false;
        }
        
        //echo '<br>display is ' . $display;
        //echo '<br>action is ' . $action;
        
        // trigger display
        switch ($display) {
            case "settings": {
                $innerReturnDisplay = $this->_accountDeskCommonSettings($customer);
                break;
            }
            case "history": {
                $innerReturnDisplay = $this->_accountDeskCommonHistoryOrders($customer);
                break;
            }
            case "orders":
            default: {
                //$display = 'orders';
                switch ($action) {
                    case "details": {
                        $innerReturnDisplay = $this->_accountDeskCommonOrders_details($customer);
                        break;
                    }
                    default: {
                        $innerReturnDisplay = $this->_accountDeskCommonOrders_all($customer);
                        break;
                    }
                }
                break;
            }
        }
        
        // jump to another display
        if (!empty($innerReturnDisplay)) {
            //echo '<br> internal redirect to display ' . $innerReturnDisplay . '<br>';
            //echo '<br>customer is empty ' . (empty($customer)?'+':'-');
            $this->_pageAccount($customer, $innerReturnDisplay);
            //echo '<br>---------';
            return;
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
            //echo '<br><br><br>USING TEMPLATE NAME:  =====> ' . $templateName;
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.accountdesk.' . $templateName);
        }
        
        // set layout
        $model['LAYOUT'] = 'layout_admin';
    }
    private function _pagePurchase ($customer) {
        $model = &$customer->getModel();
        
        //var_dump($_POST);
        
        
        // get merchant id
        $merchant_order_id_data = explode('-', libraryRequest::getValue('merchant_order_id'));
        $merchant_product_id = libraryRequest::getValue('merchant_product_id');
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
        
        //echo '<pre>' . print_r($_GET, true) . '</pre>';
        

        // empty merchant id or product id
        if (empty($merchant_order_id) || empty($merchant_product_id)) {
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.purchase_error');
            return;
        }
        
        // check for double opening
        $invoiceInfo = $customer->getDatabaseObj()
                ->select('*')
                ->from('writer_invoices')
                ->where('merchant_order_id', '=', $merchant_order_id)
                ->fetchRow();

        //var_dump($invoiceInfo);
        
        // if second opening
        if (!empty($invoiceInfo)) {
            $model['LAYOUT'] = 'layout_startup';
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.index');
            return;
        }

        //echo 'MID:' . $merchant_order_id;
        //echo 'PRODUCT:' . $merchant_product_id;
        
        $orderInfo = array();
        // detect invoice type
        switch($merchant_product_id[0]) {
            // order
            case 'E': {
                //echo 'CUSTOM ORDER';
                $orderInfo = $customer->getDatabaseObj()
                    ->select('ID')
                    ->from('writer_orders')
                    ->where('OrderToken', '=', $merchant_order_id)
                    ->fetchRow();
                break;
            }
            // ready product
            case 'B': {
                //echo 'READY ESSAY';
                $orderInfo = $customer->getDatabaseObj()
                    ->select('ID')
                    ->from('writer_sales')
                    ->where('SalesToken', '=', $merchant_order_id)
                    ->fetchRow();
                break;
            }
        }
        
        // if no order
        if (empty($orderInfo)) {
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.purchase_error');
            return;
        }
        
        //echo 'Saving invoice';
        
        // save invoice
        $invoice = array();
        $invoice['inv_type'] = 'PAYMENT';
        $invoice['invoice_id'] = libraryRequest::getValue('invoice_id');
        $invoice['sid'] = libraryRequest::getValue('sid');
        $invoice['key'] = libraryRequest::getValue('key');
        $invoice['order_number'] = libraryRequest::getValue('order_number');
        $invoice['total'] = libraryRequest::getValue('total');
        $invoice['email'] = libraryRequest::getValue('email');
        $invoice['phone'] = libraryRequest::getValue('phone');
        $invoice['credit_card_processed'] = libraryRequest::getValue('credit_card_processed');
        $invoice['merchant_order_id'] = $merchant_order_id;
        
        libraryUtils::wrapArrayKeys($invoice, '`');
        
        $customer->getDatabaseObj()
                ->reset()
                ->insertInto('writer_invoices')
                ->fields(array_keys($invoice))
                ->values(array_values($invoice))
                ->query();
        
        // save user biliing information
        // check before if user has billing info
        if (!empty($user_login)) {
            
            // check if it is not modified by user
            $student = $customer->getDatabaseObj()
                    ->reset()
                    ->select('ModifiedBy')
                    ->from('writer_students')
                    ->where('Login', '=', $user_login)
                    ->fetchRow();
            
            //echo '<br>SAVE STUDENT BILLIING INFORMATION';
            //var_dump($student);
            
            // if it is unchanged
            if ($student['ModifiedBy'] == 'SYSTEM') {
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
                // save billing information
                $customer->getDatabaseObj()
                        ->reset()
                        ->update('writer_students')
                        ->set($user_billing_info)
                        ->where('Login', '=', $user_login)
                        ->query();
            }
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
        
        // check for double activation
        if (!$studentInfo['IsTemporary']) {
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.accountdesk.login');
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
        // it contains user info if buyer is loggined
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
            //echo 'IS PREVIEW OR SAVE';
            /* validate fileds */
            libraryValidator::validateData($data, $validator['FILTER']['ORDER'], $messages);
            $model['CUSTOMER']['MESSAGES'] = $messages;
        }
        
        //var_dump($messages);
        
        // time zones
        $model['CUSTOMER']['TIME_TZ'] = libraryUtils::getTimeZones();
        
        // preview or save data
        // if action detected and no error messages
        if ($_SESSION['MPWS_ORDER_SESSION'] == libraryRequest::getPostValue('session_key') && empty($messages)) {
            //echo 'PROCEED INSIDE';
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

                //echo 'PROCEED INSIDE';
                

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
                /*$timeZone = $customer->getDatabaseObj()
                        ->select('*')
                        ->from('mpws_timezone')
                        ->where('ID', '=', $data['TimeZoneID'])
                        ->fetchRow();*/

                
                //echo 'WILL SAVE DEADLINE ' . convDT($data['DateDeadline'], 'UTC', $timeZone['TZ']);
                
                $model['CUSTOMER']['DATA_PRICE'] = $priceInfo;
                $model['CUSTOMER']['DATA_DOC'] = $docInfo;
                $model['CUSTOMER']['DATA_SUBJECT'] = $subjInfo;
                //$model['CUSTOMER']['DATA_TIMEZONE'] = $timeZone;
                //$model['CUSTOMER']['DATA_DEADLINE'] = date($mdbc['DB_DATE_FORMAT'], $_deadlineTime);
                //echo '111111111';
                $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.make_order_preview');
            } // -end of preview
            // save order
            if (libraryRequest::isPostFormAction('checkout')) {

                // check for product existance
                $payment = $customer->getCustomerConfiguration('PAYMENT');
                
                // 2CO INTEGRATION
                // check or create new product
                $param = array(
                    'DATA' => array(
                        'ORDER' => $data,
                        'PRICE' => $priceInfo,
                        'SUBJECT' => $subjInfo,
                        'DOCUMENT' => $docInfo
                    ),
                    'CREATE_IF_EMPTY' => true,
                    'REALM' => 'E',
                    'ACCOUNT' => $payment['2CO']
                );
                $product = libraryToolboxManager::callPluginMethod('writer', '2co_product', $param);
                //echo '2CO Status: ' . $product;
                // check if product exists
                // we use "assigned_product_id" to sell current product
                if (empty($product['assigned_product_id'])) {
                    $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.purchase_error');
                    return;
                }
                // END OF 2CO INTEGRATION

                $mdbc = $customer->getCustomerConfiguration('MDBC');
                $customer_config_mail = $customer->GetCustomerConfiguration('MAIL');
                $libView = new libraryView();

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
                        'TimeZone' => $data['TimeZone'],
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
                    
                    /* SYSTEM NOTIFY */
                    if (!empty($customer_config_mail['ACTION_TRIGGERS']['ON_NEW_AUTOUSER'])) {
                        $recipient = $customer_config_mail['ACTION_TRIGGERS']['ON_NEW_AUTOUSER'];
                        $recipient['SUBJECT'] = 'New autouser created';
                        $recipient['DATA'] = array(
                            'Name' => $user['Name'],
                            'TargetUrl' => $customer_config_mail['URLS']['TOOLBOX_STUDENT_LINK_OID'] . $user['ID']
                        );
                        $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.system_new_autouser'));
                        // send email message to system
                        libraryMailer::sendEMail($recipient);
                    }
                    
                    /* NOTIFY BUYER IF NEW */
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
                //$_saveTime = mktime();
                //$_deadlineTime = $_saveTime + (60 * 60 * ($priceInfo['Hours'] + (24 * 7 * $priceInfo['Weeks']))); // uncommet to use standart deadline value by price
                
                // get timezone offset
                /*$timeZone = $customer->getDatabaseObj()
                        ->select('*')
                        ->from('mpws_timezone')
                        ->where('ID', '=', $data['TimeZoneID'])
                        ->fetchRow();*/
                
                // fill order information
                $data['StudentID'] = $user['ID'];
                $data['Price'] = $data['Pages'] * $priceInfo['Price'];
                $data['Discount'] = 0;
                $data['Credits'] = round($data['Price'] / 4, 0);
                $data['DateCreated'] = convDT(date($mdbc['DB_DATE_FORMAT']), 'UTC', $data['TimeZone']);
                $data['DateDeadline'] = convDT($data['DateDeadline'], 'UTC', $data['TimeZone']);
                //$data['DateDeadline'] = date($mdbc['DB_DATE_FORMAT'], $_deadlineTime);
                //$data['TimeZone'] = $timeZone['TimeZone'];
                $data['RefundToken'] = '';
                $data['OrderToken'] = $_o_token;

                // save new order
                $customer->getDatabaseObj()
                    ->reset()
                    ->insertInto('writer_orders')
                    ->fields(array_keys($data))
                    ->values(array_values($data))
                    ->query();
                
                /* SYSTEM NOTIFY */
                if (!empty($customer_config_mail['ACTION_TRIGGERS']['ON_NEW_ORDER'])) {
                    $recipient = $customer_config_mail['ACTION_TRIGGERS']['ON_NEW_ORDER'];
                    $recipient['SUBJECT'] = 'New order has been created';
                    $recipient['DATA'] = array(
                        'Name' => $user['Name'],
                        'TargetUrl' => $customer_config_mail['URLS']['TOOLBOX_ORDER_LINK'] . $_o_token
                    );
                    $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.system_order_created'));
                    // send email message to system
                    libraryMailer::sendEMail($recipient);
                }

                // set public data
                //$model['CUSTOMER']['DATA_EMAIL'] = $student['Email'];
                //$model['CUSTOMER']['DATA_TOKEN'] = $_o_token;
                
                // 2checkout integration
                // order general information
                $_order = $payment['2CO']['FORM'];
                $_order['product_id'] = $product['assigned_product_id'];
                $_order['merchant_order_id'] = $_o_token . '-' . $user['Login'];
                $_order['email'] = $user['Email'];
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
                //var_dump($_order);
                libraryRequest::locationRedirect($_order, $payment['2CO']['API']['METHODS']['purchase']);
                exit;
            }
            
        } else
            $isPreviewOrSave = false;
            
        // edit mode
        //echo 'EDIT MODE';
        
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
        /*$timezones = $customer->getDatabaseObj()
                ->select('*')
                ->from('mpws_timezone')
                ->fetchData();*/
        
        
        // set data
        $model['CUSTOMER']['DATA'] = $data;
        $model['CUSTOMER']['DATA_FIELDS'] = $dataFields;
        $model['CUSTOMER']['DATA_PRICES'] = $prices;
        $model['CUSTOMER']['DATA_DOCS'] = $documents;
        $model['CUSTOMER']['DATA_SUBJECTS'] = $subjects;
        //$model['CUSTOMER']['DATA_TIMEZONES'] = $timezones;
        
        // populate user fields
        if (!$isPreviewOrSave) {
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.make_order');
            if ($model['USER']['ACTIVE']) {
                $model['CUSTOMER']['DATA']['Email'] = $user['Email'];
                $model['CUSTOMER']['DATA']['TimeZone'] = $user['TimeZone'];
            }
        }
    }
    private function _pageBuyEssay ($customer) {
        $model = &$customer->getModel();
        
        // user object
        // it contains user info if buyer is loggined
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
        
        // get action
        $action = libraryRequest::getAction();
        
        // buy action
        if ($action == 'buy') {
            $oid = libraryRequest::getOID();
            
            if (empty($oid)) {
                $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.buy_essay_details_error');
                return;
            }

            $sale = $customer->getDatabaseObj()
                    ->reset()
                    ->select('*')
                    ->from('writer_sale')
                    ->where('ID', '=', $oid)
                    ->fetchRow();
            
            if (empty($sale)) {
                $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.buy_essay_details_error');
                return;
            }
            
            
            // check for product existance
            $payment = $customer->getCustomerConfiguration('PAYMENT');

            // 2CO INTEGRATION
            // check or create new product
            $param = array(
                'DATA' => array(
                    'ORDER' => array(
                        'PriceID' => $oid,
                        'Pages' => $sale['Pages'],
                        'Suma' => $sale['Price']
                    ),
                    'PRICE' => array(
                        'Name' => $sale['Title']
                    )
                ),
                'CREATE_IF_EMPTY' => true,
                'REALM' => 'B',
                'ACCOUNT' => $payment['2CO']
            );
            $product = libraryToolboxManager::callPluginMethod('writer', '2co_product', $param);
            //echo '2CO Status: ' . $product['assigned_product_id'];
            // check if product exists
            // we use "assigned_product_id" to sell current product
            if (empty($product['assigned_product_id'])) {
                $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.purchase_error');
                return;
            }
            // END OF 2CO INTEGRATION

            $mdbc = $customer->getCustomerConfiguration('MDBC');
            $customer_config_mail = $customer->GetCustomerConfiguration('MAIL');
            
            
            // make order token
            $_o_token = md5($user['UserToken'] . mktime());
            
            // fill order information
            $data['SaleID'] = $oid;
            if (!empty($user['ID']))
                $data['StudentID'] = $user['ID'];
            $data['SalesToken'] = $_o_token;
            $data['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
            
            // save new order
            $customer->getDatabaseObj()
                ->reset()
                ->insertInto('writer_sales')
                ->fields(array_keys($data))
                ->values(array_values($data))
                ->query();

            $libView = new libraryView();
            /* SYSTEM NOTIFY */
            $recipient = $customer_config_mail['ACTION_TRIGGERS']['ON_NEW_SALE'];
            $recipient['SUBJECT'] = 'New Sale';
            $recipient['DATA'] = array(
                'Title' => $sale['Title'],
                'Name' => $user['Name'],
                'TargetUrl' => $customer_config_mail['URLS']['TOOLBOX_SALE_LINK'] . $_o_token
            );
            $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.sale_created'));
            // send email message to system
            //libraryMailer::sendEMail($recipient);

            // 2checkout integration
            // order general information
            $_order = $payment['2CO']['FORM'];
            $_order['product_id'] = $product['assigned_product_id'];
            $_order['merchant_order_id'] = $_o_token;
            $_order['return_url'] = $_SERVER['HTTP_REFERER'];
            
            //var_dump($_order);
            libraryRequest::locationRedirect($_order, $payment['2CO']['API']['METHODS']['purchase']);
            exit;
        }
        // if details requested
        if ($action == 'details'){
            $oid = libraryRequest::getOID();
            
            if (empty($oid)) {
                $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.buy_essay_details_error');
                return;
            }
            
            $model['CUSTOMER']['DATA'] = $customer->getDatabaseObj()
                    ->reset()
                    ->select('*')
                    ->from('writer_sale')
                    ->where('ID', '=', $oid)
                    ->fetchRow();
            
            if (empty($model['CUSTOMER']['DATA'])) {
                $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.buy_essay_details_error');
                return;
            }
            
            $model['CUSTOMER']['REFERER'] = libraryRequest::storeOrGetRefererUrl(false);
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.buy_essay_details');
        } else {
            libraryRequest::storeOrGetRefererUrl();
            // show all essays
            $datatable = $customer->getCustomerConfiguration('DATATABLE');
            $model['CUSTOMER'] = libraryComponents::comDataTable($datatable['SALE'], $customer->getDatabaseObj(), 'Price <> 0');
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.buy_essay');
        }
    }
    private function _pageFreeEssay ($customer) {
        $model = &$customer->getModel();
        // get action
        $action = libraryRequest::getAction();
        // if details requested
        if ($action == 'details'){
            $oid = libraryRequest::getOID();
            
            if (empty($oid)) {
                $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.buy_essay_details_error');
                return;
            }
            
            $model['CUSTOMER']['DATA'] = $customer->getDatabaseObj()
                    ->reset()
                    ->select('*')
                    ->from('writer_sale')
                    ->where('ID', '=', $oid)
                    ->fetchRow();
            
            if (empty($model['CUSTOMER']['DATA'])) {
                $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.buy_essay_details_error');
                return;
            }
            
            $model['CUSTOMER']['REFERER'] = libraryRequest::storeOrGetRefererUrl(false);
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.free_essay_details');
        } else {
            libraryRequest::storeOrGetRefererUrl();
            // show all essays
            $datatable = $customer->getCustomerConfiguration('DATATABLE');
            $model['CUSTOMER'] = libraryComponents::comDataTable($datatable['SALE'], $customer->getDatabaseObj(), 'Price = 0');
            $model['CUSTOMER']['TEMPLATE'] = $customer->getCustomerTemplate('page.free_essay');
        }
    }

    private function _accountDeskCommonOrders_details ($customer) {
        $model = &$customer->getModel();
        $oid = libraryRequest::getOID();
        $messages = array();
        $mdbc = $customer->getCustomerConfiguration('MDBC');
        $customer_config_mail = $customer->GetCustomerConfiguration('MAIL');
        $libView = new libraryView();

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
        
        // do not display rejected order for writer
        // redirect to all orders page
        if ($model['USER']['IS_WRITER'] && $data_order['InternalStatus'] == 'REJECTED')
            return 'orders';
        
        // prevent crossprofile access to orders
        if (($model['USER']['IS_WRITER'] && $model['USER']['ID'] != $data_order['WriterID']) ||
            ($model['USER']['IS_STUDENT'] && $model['USER']['ID'] != $data_order['StudentID'])) {
            $model['CUSTOMER']['TEMPLATE_NAME'] = 'orders_error';
            return;
        }
        
        // get user
        $user_writer = array();
        $user_student = array();
        if ($model['USER']['ACTIVE']) {
            $user_student = $customer->getDatabaseObj()
                ->select('ID, Email, Name, TimeZone')
                ->from('writer_students')
                ->where('ID', '=', $data_order['StudentID'])
                ->fetchRow();
            $user_writer = $customer->getDatabaseObj()
                ->select('ID, Email, Name, TimeZone')
                ->from('writer_writers')
                ->where('ID', '=', $data_order['WriterID'])
                ->fetchRow();
        }
        
        /* order actions */
        // **********************************************************
        // start rework
        // **********************************************************
        if (libraryRequest::isPostFormAction('start working') && $model['USER']['IS_WRITER']) {
            $order_status = array(
                'PublicStatus' => 'IN PROGRESS',
                'InternalStatus' => 'OPEN'
            );
            $customer->getDatabaseObj()
                ->reset()
                ->update('writer_orders')
                ->set($order_status)
                ->where('ID', '=', $oid)
                ->query();
            /* save internal message */
            $message['Subject'] = 'Writer Started This Order';
            $message['Message'] = 'Public Status: IN PROGRESS';
            $message['WriterID'] = $model['USER']['ID'];
            $message['OrderID'] = $oid;
            $message['Owner'] = 'SYSTEM';
            $message['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
            $customer->getDatabaseObj()
                ->reset()
                ->insertInto('writer_messages')
                ->fields(array_keys($message))
                ->values(array_values($message))
                ->query();
            
            /* SYSTEM NOTIFY */
            if (!empty($customer_config_mail['ACTION_TRIGGERS']['ON_ORDER_ACCEPTED'])) {
                $recipient = $customer_config_mail['ACTION_TRIGGERS']['ON_ORDER_ACCEPTED'];
                $recipient['SUBJECT'] = 'Rework Strated';
                $recipient['DATA'] = array(
                    'Name' => $user_writer['Name'],
                    'TargetUrl' => $customer_config_mail['URLS']['TOOLBOX_ORDER_LINK_OID'] . $oid
                );
                $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.system_order_started'));
                // send email message to system
                libraryMailer::sendEMail($recipient);
            }
            /* USER NOTIFY */
            if (!empty($user_student['Email'])) {
                $recipient = $customer_config_mail['NOTIFY'];
                $recipient['TO'] = $user_student['Email'];
                $recipient['SUBJECT'] = 'Rework Started';
                $recipient['DATA'] = array(
                    'TargetUrl' => $customer_config_mail['URLS']['ACCOUNT_ORDER_LINK'] . $oid
                );
                $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.student_order_started'));
                // send email message to system
                libraryMailer::sendEMail($recipient);
            }
            
            /* alter already selected order info */
            $data_order['PublicStatus'] = 'IN PROGRESS';
            $data_order['InternalStatus'] = 'OPEN';
        }
        // **********************************************************
        // order accepted
        // **********************************************************
        if (libraryRequest::isPostFormAction('accept') && $model['USER']['IS_WRITER']) {
            $order_status = array(
                'PublicStatus' => 'IN PROGRESS'
            );
            $customer->getDatabaseObj()
                ->reset()
                ->update('writer_orders')
                ->set($order_status)
                ->where('ID', '=', $oid)
                ->query();
            /* save internal message */
            $message['Subject'] = 'Writer Started This Order';
            $message['Message'] = 'Public Status: IN PROGRESS';
            $message['WriterID'] = $model['USER']['ID'];
            $message['OrderID'] = $oid;
            $message['Owner'] = 'SYSTEM';
            $message['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
            $customer->getDatabaseObj()
                ->reset()
                ->insertInto('writer_messages')
                ->fields(array_keys($message))
                ->values(array_values($message))
                ->query();
            
            /* SYSTEM NOTIFY */
            if (!empty($customer_config_mail['ACTION_TRIGGERS']['ON_ORDER_ACCEPTED'])) {
                $recipient = $customer_config_mail['ACTION_TRIGGERS']['ON_ORDER_ACCEPTED'];
                $recipient['SUBJECT'] = 'Order Accepted';
                $recipient['DATA'] = array(
                    'Name' => $user_writer['Name'],
                    'TargetUrl' => $customer_config_mail['URLS']['TOOLBOX_ORDER_LINK_OID'] . $oid
                );
                $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.system_order_accepted'));
                // send email message to system
                libraryMailer::sendEMail($recipient);
            }
            /* USER NOTIFY */
            if (!empty($user_student['Email'])) {
                $recipient = $customer_config_mail['NOTIFY'];
                $recipient['TO'] = $user_student['Email'];
                $recipient['SUBJECT'] = 'Order Started';
                $recipient['DATA'] = array(
                    'TargetUrl' => $customer_config_mail['URLS']['ACCOUNT_ORDER_LINK'] . $oid
                );
                $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.student_order_accepted'));
                // send email message to system
                libraryMailer::sendEMail($recipient);
            }
            
            /* alter already selected order info */
            $data_order['PublicStatus'] = 'IN PROGRESS';
        }
        // **********************************************************
        // order rejected
        // **********************************************************
        if (libraryRequest::isPostFormAction('reject') && $model['USER']['IS_WRITER']) {
            $order_status = array(
                'InternalStatus' => 'REJECTED'
            );
            $customer->getDatabaseObj()
                ->reset()
                ->update('writer_orders')
                ->set($order_status)
                ->where('ID', '=', $oid)
                ->query();
            /* save internal message */
            $message['Subject'] = 'Order Is Rejected By Writer';
            $message['Message'] = 'Assign this order to another writer.';
            $message['WriterID'] = $model['USER']['ID'];
            $message['OrderID'] = $oid;
            $message['Owner'] = 'SYSTEM';
            $message['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
            $customer->getDatabaseObj()
                ->reset()
                ->insertInto('writer_messages')
                ->fields(array_keys($message))
                ->values(array_values($message))
                ->query();
            
            /* SYSTEM NOTIFY */
            if (!empty($customer_config_mail['ACTION_TRIGGERS']['ON_ORDER_REJECTED'])) {
                $recipient = $customer_config_mail['ACTION_TRIGGERS']['ON_ORDER_REJECTED'];
                $recipient['SUBJECT'] = 'Order Rejected';
                $recipient['DATA'] = array(
                    'Name' => $user_writer['Name'],
                    'TargetUrl' => $customer_config_mail['URLS']['TOOLBOX_ORDER_LINK_OID'] . $oid
                );
                $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.system_order_rejected'));
                // send email message to system
                libraryMailer::sendEMail($recipient);
            }
            
            // do not notify user about rejection
            // it is still NEW for user
            
            /* alter already selected order info */
            $data_order['InternalStatus'] = 'REJECTED';
            
            return 'orders';
        }
        // **********************************************************
        // writer sent to review
        // **********************************************************
        if (libraryRequest::isPostFormAction('send to review') && $model['USER']['IS_WRITER']) {
            $order_status = array(
                'InternalStatus' => 'PENDING'
            );
            $customer->getDatabaseObj()
                ->reset()
                ->update('writer_orders')
                ->set($order_status)
                ->where('ID', '=', $oid)
                ->query();
            /* save internal message */
            $message['Subject'] = 'Need Approval';
            $message['Message'] = 'Waiting for approval.';
            $message['WriterID'] = $model['USER']['ID'];
            $message['OrderID'] = $oid;
            $message['Owner'] = 'SYSTEM';
            $message['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
            $customer->getDatabaseObj()
                ->reset()
                ->insertInto('writer_messages')
                ->fields(array_keys($message))
                ->values(array_values($message))
                ->query();
            
            /* SYSTEM NOTIFY */
            if (!empty($customer_config_mail['ACTION_TRIGGERS']['ON_ORDER_TO_REVIEW'])) {
                $recipient = $customer_config_mail['ACTION_TRIGGERS']['ON_ORDER_TO_REVIEW'];
                $recipient['SUBJECT'] = 'Waiting For Approval';
                $recipient['DATA'] = array(
                    'Name' => $user_writer['Name'],
                    'TargetUrl' => $customer_config_mail['URLS']['TOOLBOX_ORDER_LINK_OID'] . $oid
                );
                $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.system_order_to_review'));
                // send email message to system
                libraryMailer::sendEMail($recipient);
            }
            
            // user will receive message as soon as webmaster approve this order
            // it is still IN PROGRESS for user

            /* alter already selected order info */
            $data_order['InternalStatus'] = 'PENDING';
        }
        // **********************************************************
        // student sent to rework
        // **********************************************************
        if (libraryRequest::isPostFormAction('send to rework') && $model['USER']['IS_STUDENT']) {
            
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
                $message['Subject'] = 'Buyer Wants To Rework';
                $message['Message'] = 'Ask owner for more details to rework.';
                $message['StudentID'] = $model['USER']['ID'];
                $message['OrderID'] = $oid;
                $message['Owner'] = 'SYSTEM';
                $message['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
                $customer->getDatabaseObj()
                    ->reset()
                    ->insertInto('writer_messages')
                    ->fields(array_keys($message))
                    ->values(array_values($message))
                    ->query();
            
                /* SYSTEM NOTIFY */
                if (!empty($customer_config_mail['ACTION_TRIGGERS']['ON_ORDER_TO_REWORK'])) {
                    $recipient = $customer_config_mail['ACTION_TRIGGERS']['ON_ORDER_TO_REWORK'];
                    $recipient['SUBJECT'] = 'Need To Rework';
                    $recipient['DATA'] = array(
                        'Name' => $user_student['Name'],
                        'TargetUrl' => $customer_config_mail['URLS']['TOOLBOX_ORDER_LINK_OID'] . $oid
                    );
                    $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.system_order_to_rework'));
                    // send email message to system
                    libraryMailer::sendEMail($recipient);
                }
                /* WRITER NOTIFY */
                if (!empty($user_writer['Email'])) {
                    $recipient = $customer_config_mail['NOTIFY'];
                    $recipient['TO'] = $user_writer['Email'];
                    $recipient['SUBJECT'] = 'Need To Rework';
                    $recipient['DATA'] = array(
                        'TargetUrl' => $customer_config_mail['URLS']['ACCOUNT_ORDER_LINK'] . $oid
                    );
                    $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.writer_order_to_rework'));
                    // send email message to system
                    libraryMailer::sendEMail($recipient);
                }
            
                /* alter already selected order info */
                $data_order['PublicStatus'] = 'REWORK';
                $data_order['InternalStatus'] = 'OPEN';
                $data_order['ReworkCount']++;
            }
        }
        // **********************************************************
        // want refund
        // **********************************************************
        if (libraryRequest::isPostFormAction('want refund') && $model['USER']['IS_STUDENT']) {
            $order_status = array(
                'PublicStatus' => 'TO REFUND',
                'InternalStatus' => 'CLOSED'
            );
            $customer->getDatabaseObj()
                ->reset()
                ->update('writer_orders')
                ->set($order_status)
                ->where('ID', '=', $oid)
                ->query();
            /* save internal message */
            $message['Subject'] = 'Buyer Wants Refund';
            $message['Message'] = 'You must clarify the reason of refund action.';
            $message['StudentID'] = $model['USER']['ID'];
            $message['OrderID'] = $oid;
            $message['Owner'] = 'SYSTEM';
            $message['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
            $customer->getDatabaseObj()
                ->reset()
                ->insertInto('writer_messages')
                ->fields(array_keys($message))
                ->values(array_values($message))
                ->query();
            
            /* SYSTEM NOTIFY */
            if (!empty($customer_config_mail['ACTION_TRIGGERS']['ON_ORDER_TO_REFUND'])) {
                $recipient = $customer_config_mail['ACTION_TRIGGERS']['ON_ORDER_TO_REFUND'];
                $recipient['SUBJECT'] = 'Buyer Wants Refund';
                $recipient['DATA'] = array(
                    'Name' => $user_student['Name'],
                    'TargetUrl' => $customer_config_mail['URLS']['TOOLBOX_ORDER_LINK_OID'] . $oid
                );
                $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.system_order_to_refund'));
                // send email message to system
                libraryMailer::sendEMail($recipient);
            }
            /* WRITER NOTIFY */
            if (!empty($user_writer['Email'])) {
                $recipient = $customer_config_mail['NOTIFY'];
                $recipient['TO'] = $user_writer['Email'];
                $recipient['SUBJECT'] = 'Buyer Wants Refund';
                $recipient['DATA'] = array(
                    'TargetUrl' => $customer_config_mail['URLS']['ACCOUNT_ORDER_LINK'] . $oid
                );
                $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.writer_order_to_refund'));
                // send email message to system
                libraryMailer::sendEMail($recipient);
            }
            
            /* alter already selected order info */
            $data_order['PublicStatus'] = 'TO REFUND';
            $data_order['InternalStatus'] = 'CLOSED';
        }
        // **********************************************************
        // reopen order
        // **********************************************************
        if (libraryRequest::isPostFormAction('reopen order') && $model['USER']['IS_STUDENT']) {
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
            $message['Subject'] = 'Order Is Reopened';
            $message['Message'] = 'Owner has reopened this order.';
            $message['StudentID'] = $model['USER']['ID'];
            $message['OrderID'] = $oid;
            $message['Owner'] = 'SYSTEM';
            $message['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
            $customer->getDatabaseObj()
                ->reset()
                ->insertInto('writer_messages')
                ->fields(array_keys($message))
                ->values(array_values($message))
                ->query();
            
            /* SYSTEM NOTIFY */
            if (!empty($customer_config_mail['ACTION_TRIGGERS']['ON_ORDER_REOPENED'])) {
                $recipient = $customer_config_mail['ACTION_TRIGGERS']['ON_ORDER_REOPENED'];
                $recipient['SUBJECT'] = 'Buyer Reopened Order';
                $recipient['DATA'] = array(
                    'Name' => $user_student['Name'],
                    'TargetUrl' => $customer_config_mail['URLS']['TOOLBOX_ORDER_LINK_OID'] . $oid
                );
                $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.system_order_reopened'));
                // send email message to system
                libraryMailer::sendEMail($recipient);
            }
            /* WRITER NOTIFY */
            if (!empty($user_writer['Email'])) {
                $recipient = $customer_config_mail['NOTIFY'];
                $recipient['TO'] = $user_writer['Email'];
                $recipient['SUBJECT'] = 'Buyer Reopened Order';
                $recipient['DATA'] = array(
                    'TargetUrl' => $customer_config_mail['URLS']['ACCOUNT_ORDER_LINK'] . $oid
                );
                $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.writer_order_reopened'));
                // send email message to system
                libraryMailer::sendEMail($recipient);
            }
            
            /* alter already selected order info */
            $data_order['PublicStatus'] = 'REOPEN';
            $data_order['InternalStatus'] = 'OPEN';
        }
        // **********************************************************
        // close order
        // **********************************************************
        if (libraryRequest::isPostFormAction('close order') && $model['USER']['IS_STUDENT']) {
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
            $message['Subject'] = 'Order Is Closed';
            $message['Message'] = 'Owner has closed this order.';
            $message['StudentID'] = $model['USER']['ID'];
            $message['OrderID'] = $oid;
            $message['Owner'] = 'SYSTEM';
            $message['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
            $customer->getDatabaseObj()
                ->reset()
                ->insertInto('writer_messages')
                ->fields(array_keys($message))
                ->values(array_values($message))
                ->query();
            
            /* SYSTEM NOTIFY */
            if (!empty($customer_config_mail['ACTION_TRIGGERS']['ON_ORDER_CLOSED'])) {
                $recipient = $customer_config_mail['ACTION_TRIGGERS']['ON_ORDER_CLOSED'];
                $recipient['SUBJECT'] = 'Buyer Closed Order';
                $recipient['DATA'] = array(
                    'Name' => $user_student['Name'],
                    'TargetUrl' => $customer_config_mail['URLS']['TOOLBOX_ORDER_LINK_OID'] . $oid
                );
                $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.system_order_closed'));
                // send email message to system
                libraryMailer::sendEMail($recipient);
            }
            /* WRITER NOTIFY */
            if (!empty($user_writer['Email'])) {
                $recipient = $customer_config_mail['NOTIFY'];
                $recipient['TO'] = $user_writer['Email'];
                $recipient['SUBJECT'] = 'Buyer Closed Order';
                $recipient['DATA'] = array(
                    'TargetUrl' => $customer_config_mail['URLS']['ACCOUNT_ORDER_LINK'] . $oid
                );
                $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.writer_order_closed'));
                // send email message to system
                libraryMailer::sendEMail($recipient);
            }
            
            /* alter already selected order info */
            $data_order['PublicStatus'] = 'CLOSED';
            $data_order['InternalStatus'] = 'CLOSED';
        }
        // **********************************************************
        // post new message
        // **********************************************************
        if (libraryRequest::isPostFormAction('post message')) {
            $validator = $customer->getCustomerConfiguration('VALIDATOR');
            $message = libraryRequest::getPostMapContainer($validator['DATAMAP']['MESSAGES']);
            libraryValidator::validateData($message, $validator['FILTER']['MESSAGES'], $messages);
            // check for errors
            if (!count($messages)) {
                if($model['USER']['IS_STUDENT'])
                    $message['StudentID'] = $model['USER']['ID'];
                if($model['USER']['IS_WRITER'])
                    $message['WriterID'] = $model['USER']['ID'];
                $message['OrderID'] = $oid;
                $message['Owner'] = strtoupper($model['USER']['TYPE']);
                $message['IsPublic'] = 1;
                $message['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
                $customer->getDatabaseObj()
                    ->reset()
                    ->insertInto('writer_messages')
                    ->fields(array_keys($message))
                    ->values(array_values($message))
                    ->query();
            
                /* SYSTEM NOTIFY */
                if (!empty($customer_config_mail['ACTION_TRIGGERS']['ON_ORDER_COMMENTED'])) {
                    $recipient = $customer_config_mail['ACTION_TRIGGERS']['ON_ORDER_COMMENTED'];
                    $recipient['SUBJECT'] = 'New Comment From ' . strtolower($model['USER']['TYPE']);
                    $recipient['DATA'] = array(
                        'Name' => strtolower($model['USER']['TYPE']),
                        'TargetUrl' => $customer_config_mail['URLS']['TOOLBOX_ORDER_LINK_OID'] . $oid
                    );
                    $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.system_order_commented'));
                    // send email message to system
                    libraryMailer::sendEMail($recipient);
                }
                /* notify writer with new message */
                if($model['USER']['IS_WRITER']) {
                    $recipient = $customer_config_mail['NOTIFY'];
                    $recipient['TO'] = $user_student['Email'];
                    $recipient['SUBJECT'] = 'New Comment';
                    $recipient['DATA'] = array(
                        'TargetUrl' => $customer_config_mail['URLS']['ACCOUNT_ORDER_LINK'] . $oid
                    );
                    $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.student_order_commented'));
                    // send email message to system
                    libraryMailer::sendEMail($recipient);
                }
                /* notify student with new message */
                if($model['USER']['IS_STUDENT']) {
                    $recipient = $customer_config_mail['NOTIFY'];
                    $recipient['TO'] = $user_writer['Email'];
                    $recipient['SUBJECT'] = 'New Comment';
                    $recipient['DATA'] = array(
                        'TargetUrl' => $customer_config_mail['URLS']['ACCOUNT_ORDER_LINK'] . $oid
                    );
                    $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $customer->getCustomerTemplate('mail.notify.writer_order_commented'));
                    // send email message to system
                    libraryMailer::sendEMail($recipient);
                }
            
            }
        } // post message
        // **********************************************************
        // save changes
        // **********************************************************
        if (libraryRequest::isPostFormAction('save changes') && $model['USER']['IS_WRITER']) {
            $docLink = libraryRequest::getPostValue('order_resolution_document');
            $customer->getDatabaseObj()
                ->reset()
                ->update('writer_orders')
                ->set(array('ResolutionDocumentLink' => $docLink))
                ->where('ID', '=', $oid)
                ->query();
            
            /* save internal message */
            $message['Subject'] = 'Resolution Document Is Modified';
            $message['Message'] = 'Document Link: ' . $docLink;
            $message['StudentID'] = $model['USER']['ID'];
            $message['OrderID'] = $oid;
            $message['Owner'] = 'SYSTEM';
            $message['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
            $customer->getDatabaseObj()
                ->reset()
                ->insertInto('writer_messages')
                ->fields(array_keys($message))
                ->values(array_values($message))
                ->query();
            
            /* alter already selected order info */
            $data_order['ResolutionDocumentLink'] = $docLink;
        }
        // **********************************************************
        // save changes
        // **********************************************************
        if (libraryRequest::isPostFormAction('save changes') && $model['USER']['IS_STUDENT']) {
            
            $deadline = libraryRequest::getPostValue('order_deadline');
            
            // conver to UTC deadline
            //$newDeadline = utime($deadline, );
            $utcDeadline = convDT($deadline, 'UTC', $data_order['TimeZone']);
            $utcNow = convDT(date($mdbc['DB_DATE_FORMAT']), 'UTC');
            
            //echo 'USER DEADLINE: ' . $deadline;
            //echo 'UTC DEADLINE: ' . $utcDeadline;

            // check if deadline is changed
            if ($utcDeadline !== $data_order['DateDeadline']) {
                // save new deadline
                if ($utcDeadline > $utcNow) {
                    $customer->getDatabaseObj()
                        ->reset()
                        ->update('writer_orders')
                        ->set(array('DateDeadline' => $utcDeadline))
                        ->where('ID', '=', $oid)
                        ->query();

                    /* save internal message */
                    $message['Subject'] = 'Date Deadline is changed';
                    $message['Message'] = $data_order['DateDeadline'] . ' => ' . $deadline;
                    if($model['USER']['IS_STUDENT'])
                        $message['StudentID'] = $model['USER']['ID'];
                    if($model['USER']['IS_STUDENT'])
                        $message['WriterID'] = $model['USER']['ID'];
                    $message['OrderID'] = $oid;
                    $message['Owner'] = 'SYSTEM';
                    $message['DateCreated'] = date($mdbc['DB_DATE_FORMAT']);
                    $customer->getDatabaseObj()
                        ->reset()
                        ->insertInto('writer_messages')
                        ->fields(array_keys($message))
                        ->values(array_values($message))
                        ->query();

                    /* notify writer with new deadline */


                    /* alter already selected order info */
                    $data_order['DateDeadline'] = $utcDeadline;
                } else 
                    $messages[] = 'Deadline - must be greater than ' . convDT(false, $data_order['TimeZone']);
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
            foreach ($currentSources as $val)
                $_c_sources[] = $val['SourceURL'];
            // get new sources
            $_sources = libraryRequest::getPostValue('order_source_links');
            if (empty($_sources))
                $_sources = array();
            // compare arrays
            $srcDiff = array_diff_assoc($_sources, $_c_sources);
            
            //echo '<pre>'.print_r($_c_sources, true).'</pre>';
            //echo '<pre>'.print_r($_sources, true).'</pre>';
            //echo '<pre>'.print_r($srcDiff, true).'</pre>';
            
            $isAllRemoved = (count($currentSources) > 0 && count($_sources) == 0);
            
            // proceed if sources were changed
            if (count($srcDiff) || $isAllRemoved) {
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
                $customer->getDatabaseObj()
                    ->reset()
                    ->insertInto('writer_messages')
                    ->fields(array_keys($message))
                    ->values(array_values($message))
                    ->query();
            }
        } // save changes action
        
        // collect order data and related items
        $customer->getDatabaseObj()
            ->select('*')
            ->from('writer_messages')
            ->where('OrderID', '=', $oid);
        
        // select public messages only if it is student account
        if ($model['USER']['IS_STUDENT'])
            $customer->getDatabaseObj()->andWhere('IsPublic', '=', 1);
        if ($model['USER']['IS_WRITER']) {
            $customer->getDatabaseObj()
                ->andWhere('StudentID', '<>', 'NULL')
                ->orWhere('WriterID', '<>', 'NULL'); 
        }
        
        $data_messages = $customer->getDatabaseObj()
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
        /*
        $data_timezone_o = $customer->getDatabaseObj()
            ->select('*')
            ->from('mpws_timezone')
            ->where('ID', '=', $data_order['TimeZoneID'])
            ->fetchRow();
        /*$data_timezone_u = $customer->getDatabaseObj()
            ->select('*')
            ->from('mpws_timezone')
            ->where('ID', '=', $user_student['TimeZoneID'])
            ->fetchRow();*-/
        $data_timezone_w = $customer->getDatabaseObj()
            ->select('*')
            ->from('mpws_timezone')
            ->where('ID', '=', $user_writer['TimeZoneID'])
            ->fetchRow();
        
        // --- order
        $dto['UTC'] = $data_order['DateDeadline'];
        $dto['ORDER'] = convDT($data_order['DateDeadline'], $data_timezone_o['TZ'], 'UTC');
        $dto['WRITER'] = convDT($data_order['DateDeadline'], $data_timezone_w['TZ'], 'UTC');
        
        // --- now
        $dto['nUTC'] = convDT(date($mdbc['DB_DATE_FORMAT']), 'UTC');
        $dto['nORDER'] = convDT(date($mdbc['DB_DATE_FORMAT']), $data_timezone_o['TZ']);
        $dto['nWRITER'] = convDT(date($mdbc['DB_DATE_FORMAT']), $data_timezone_w['TZ']);
        
        echo '<br>- - - -  -- - - - - - DEADLINE ORIGINAL<br>';
        echo '<br>UTC/SYSTEM: ' . $dto['UTC'];
        echo '<br>ORDER: ' . $dto['ORDER'];
        echo '<br>WRITER: ' . $dto['WRITER'];
        
        echo '<br>- - - -  -- - - - - - NOW<br>';
        echo '<br>SERVER: ' . date($mdbc['DB_DATE_FORMAT']);
        echo '<br>UTC/SYSTEM: ' . $dto['nUTC'];
        echo '<br>ORDER: ' . $dto['nORDER'];
        echo '<br>WRITER: ' . $dto['nWRITER'];
        
        echo '<br>- - - -  -- - - - - - HOURS LEFT<br>';
        echo '<br>UTC/SYSTEM: ' . libraryUtils::getDateTimeHoursDiff($data_order['DateDeadline'], $dto['nUTC']);
        echo '<br>ORDER: ' . libraryUtils::getDateTimeHoursDiff($dto['ORDER'], $dto['nORDER']);
        echo '<br>WRITER: ' . libraryUtils::getDateTimeHoursDiff($dto['WRITER'], $dto['nWRITER']);
        
        echo '<br>- - - -  -- - - - - - DEADLINE FOR WRITER -2Hours<br>';
        echo '<br>UTC/SYSTEM: ' . libraryUtils::subDateHours($dto['UTC'], 2, $mdbc['DB_DATE_FORMAT']);
        echo '<br>ORDER: ' . libraryUtils::subDateHours($dto['ORDER'], 2, $mdbc['DB_DATE_FORMAT']);
        echo '<br>WRITER: ' . libraryUtils::subDateHours($dto['WRITER'], 2, $mdbc['DB_DATE_FORMAT']);
        
        
        
        echo '<br>- - - -  -- - - - - - HOURS LEFT -2Hours<br>';
        echo '<br>UTC/SYSTEM: ' . (libraryUtils::getDateTimeHoursDiff($data_order['DateDeadline'], $dto['nUTC']) - 2);
        echo '<br>ORDER: ' . (libraryUtils::getDateTimeHoursDiff($dto['ORDER'], $dto['nORDER']) - 2);
        echo '<br>WRITER: ' . (libraryUtils::getDateTimeHoursDiff($dto['WRITER'], $dto['nWRITER']) - 2);
        */
        //var_dump($data_order['TimeZoneID']);
        
        //echo $data_timezone['TZ'];
        
        //echo '<br>- - - -  -- - - - - - NOW <br>';
        //toGreenwichTime(date('Y-m-d H:i:s'), $data_timezone['TZ']);
        //echo '<br>- - - -  -- - - - - - DEADLINE <br>';
        //toGreenwichTime($data_order['DateDeadline'], $data_timezone['TZ']);
        
        
        
        //$usertime = timeInfo($data_order['TimeZone']);
        //echo '<pre>' . print_r($usertime, true) . '</pre>';
        
        /* Local Deadlines */
        $dto['UTC'] = $data_order['DateDeadline'];
        $dto['ORDER'] = convDT($data_order['DateDeadline'], $data_order['TimeZone'], 'UTC');
        if (!empty($user_writer['TimeZone']))
            $dto['WRITER'] = convDT($data_order['DateDeadline'], $user_writer['TimeZone'], 'UTC');
        /* Local Times */
        $dto['nUTC'] = convDT(date($mdbc['DB_DATE_FORMAT']), 'UTC');
        $dto['nORDER'] = convDT(date($mdbc['DB_DATE_FORMAT']), $data_order['TimeZone']);
        if (!empty($user_writer['TimeZone']))
            $dto['nWRITER'] = convDT(date($mdbc['DB_DATE_FORMAT']), $user_writer['TimeZone']);
        /* Offsets */
        $dto['pUTC'] = convDT(false, 'UTC', false, 'P');
        $dto['pORDER'] = convDT(false, $data_order['TimeZone'], false, 'P');
        if (!empty($user_writer['TimeZone']))
            $dto['pWRITER'] = convDT(false, $user_writer['TimeZone'], false, 'P');
        /* Hours Left */
        $dto['LEFT'] = libraryUtils::getDateTimeHoursDiff($data_order['DateDeadline'], $dto['nUTC']);
        /* Date Created */
        $dto['dcUTC'] = $data_order['DateCreated'];
        $dto['dcORDER'] = convDT($data_order['DateCreated'], $data_order['TimeZone'], 'UTC');
        if (!empty($user_writer['TimeZone']))
            $dto['dcWRITER'] = convDT($data_order['DateCreated'], $user_writer['TimeZone'], 'UTC');
        
        /* Deadline -2 Hours */
        $dto['h2ORDER'] = libraryUtils::subDateHours($dto['ORDER'], 2, $mdbc['DB_DATE_FORMAT']);
        $dto['h2WRITER'] = libraryUtils::subDateHours($dto['WRITER'], 2, $mdbc['DB_DATE_FORMAT']);
        
        if ($model['USER']['IS_WRITER'])
            $model['CUSTOMER']['USER'] = $user_writer;
        if ($model['USER']['IS_STUDENT'])
            $model['CUSTOMER']['USER'] = $user_student;
        
        $model['CUSTOMER']['DATA'] = $data_order;
        $model['CUSTOMER']['TIME'] = $dto;
        //$model['CUSTOMER']['TIME'] = utime(date('Y-m-d H:i:s'), $data_order['TimeZone']);
        /*$model['CUSTOMER']['TIME_CREATED'] = utime($data_order['DateCreated'], $data_order['TimeZone']);
        $model['CUSTOMER']['TIME_DEADLINE'] = utime($data_order['DateDeadline'], $data_order['TimeZone']);*/
        $model['CUSTOMER']['DATA_DOCUMENT'] = $data_document;
        $model['CUSTOMER']['DATA_SUBJECT'] = $data_subject;
        $model['CUSTOMER']['DATA_MESSAGES'] = $data_messages;
        //$model['CUSTOMER']['DATA_STUDENT'] = $data_student;
        //$model['CUSTOMER']['DATA_WRITER'] = $data_writer;
        $model['CUSTOMER']['DATA_PRICE'] = $data_price;
        $model['CUSTOMER']['DATA_INVOICE_ORDER'] = $data_invoice_order;
        $model['CUSTOMER']['DATA_INVOICE_REFUND'] = $data_invoice_refund;
        $model['CUSTOMER']['DATA_SOURCES'] = $data_sources;
        //$model['CUSTOMER']['DATA_DEADLINE'] = libraryUtils::subDateHours($data_order['DateDeadline'], 2, $mdbc['DB_DATE_FORMAT']);
        $model['CUSTOMER']['MESSAGES'] = $messages;
        //$model['CUSTOMER']['template'] = $plugin['templates']['page.orders.details'];
    }
    private function _accountDeskCommonOrders_all ($customer) {
        $model = &$customer->getModel();
        // get new tasks
        //echo $_SESSION['WEB_USER']['TYPE'].'ID'. '='. $_SESSION['WEB_USER']['ID'];
        $data = array();
        if ($model['USER']['IS_WRITER']) {
            $data = $customer->getDatabaseObj()
                    ->reset()
                    ->select('*')
                    ->from('writer_orders')
                    ->where($_SESSION['WEB_USER']['TYPE'].'ID', '=', $_SESSION['WEB_USER']['ID'])
                    ->andWhere('PublicStatus', '<>', 'CLOSED')
                    ->andWhere('InternalStatus', '<>', 'REJECTED')
                    ->fetchData();
            // get user
            if ($model['USER']['ACTIVE']) {
                $mdbc = $customer->getCustomerConfiguration('MDBC');
                $model['CUSTOMER']['DB_DATE_FORMAT'] = $mdbc['DB_DATE_FORMAT'];
                $model['CUSTOMER']['USER'] = $customer->getDatabaseObj()
                    ->reset()
                    ->select('TimeZone')
                    ->from('writer_writers')
                    ->where('ID', '=', $model['USER']['ID'])
                    ->fetchRow();
            }
            
        }
        if ($model['USER']['IS_STUDENT'])
            $data = $customer->getDatabaseObj()
                    ->reset()
                    ->select('*')
                    ->from('writer_orders')
                    ->where($_SESSION['WEB_USER']['TYPE'].'ID', '=', $_SESSION['WEB_USER']['ID'])
                    ->andWhere('PublicStatus', '<>', 'CLOSED')
                    ->fetchData();
        
        $model['CUSTOMER']['DATA'] = libraryUtils::groupArrayRowsByField($data, 'PublicStatus');
    }
    private function _accountDeskCommonHistoryOrders ($customer) {
        $model = &$customer->getModel();
        $mdbc = $customer->getCustomerConfiguration('MDBC');
        
        // user
        $user_writer = array();
        if ($model['USER']['ACTIVE']) {
            $model['CUSTOMER']['USER'] = $customer->getDatabaseObj()
                ->select('TimeZone')
                ->from('writer_writers')
                ->where('ID', '=', $model['USER']['ID'])
                ->fetchRow();
        }
        
        $data_orders = array();
        $data_suma = 0;
        if (libraryRequest::isPostFormAction('show orders')) {
            $data_orders = $customer->getDatabaseObj()
                ->select('*')
                ->from('writer_orders')
                ->where($_SESSION['WEB_USER']['TYPE'].'ID', '=', $_SESSION['WEB_USER']['ID'])
                ->andWhere('PublicStatus', '=', 'CLOSED')
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
        $model['CUSTOMER']['DB_DATE_FORMAT'] = $mdbc['DB_DATE_FORMAT'];
        $model['CUSTOMER']['DATA'] = $data_orders;
        $model['CUSTOMER']['DATA_SUMA'] = $data_suma;
    }
    private function _accountDeskCommonSettings ($customer) {
        $model = &$customer->getModel();
        $messages = array();
        $data = array();
        $data_pwd = array();
        
        
        // change password (common usage)
        if (libraryRequest::isPostFormAction('change password')) {
            $validator = $customer->getCustomerConfiguration('VALIDATOR');
            $data_pwd = libraryRequest::getPostMapContainer($validator['DATAMAP']['ACCOUNT_PWD_UPDATE']);
            libraryValidator::validateData($data_pwd, $validator['FILTER']['ACCOUNT_PWD_UPDATE'], $messages);
            // get current password
            $currentUser = $customer->getDatabaseObj()
                ->reset()
                ->select('Email, Password')
                ->from($model['USER']['REALM'])
                ->where('ID', '=', $model['USER']['ID'])
                ->fetchRow();
            //eu_na$agava%
            if ($currentUser['Password'] !== md5($data_pwd['CurrentPassword']))
                $messages[] = 'Wrong current password.';
            
            if ($data_pwd['NewPassword'] !== $data_pwd['NewPasswordConfirm'])
                $messages[] = 'Confirmation password does not match with new.';
            
            
            //var_dump($data_pwd);
            //var_dump($currentUser);
            
            if (empty($messages)) {
                $customer->getDatabaseObj()
                    ->update($model['USER']['REALM'])
                    ->set(array('Password' => md5($data_pwd['NewPassword'])))
                    ->where('ID', '=', $model['USER']['ID'])
                    ->query();
                $messages[] = 'Your password was changed.';
                /* Notify User The Pwd Is Changed */
            }
            
            
        }
        // update profile information
        if (libraryRequest::isPostFormAction('update')) {
            $validatorKey = strtoupper($model['USER']['TYPE']) . '_ACCOUNT_UPDATE';
            $validator = $customer->getCustomerConfiguration('VALIDATOR');
            $data = libraryRequest::getPostMapContainer($validator['DATAMAP'][$validatorKey]);
            libraryValidator::validateData($data, $validator['FILTER'][$validatorKey], $messages);
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

            //var_dump($messages);
            //var_dump($data);
            //var_dump(array_keys($data));
            //echo '<br><br><br>';
            //var_dump(array_values($data));

            // user has changed account information
            $data['ModifiedBy'] = 'USER';
            
            if (empty($messages)) {
                $customer->getDatabaseObj()
                    ->update($model['USER']['REALM'])
                    ->set($data)
                    ->where('ID', '=', $model['USER']['ID'])
                    ->query();
                $messages[] = 'Your account information was updated successfully.';
            }
        }
        
        $selectFields = '';
        if ($model['USER']['IS_STUDENT'])
            $selectFields = 'Name, Login, Email, Phone,
                Billing_FirstName, Billing_LastName,
                Billing_Email, Billing_Phone, Billing_Address,
                Billing_City, Billing_State, Billing_PostalCode,
                Billing_Country, TimeZone';
        if ($model['USER']['IS_WRITER'])
            $selectFields = 'Name, Login, Subjects, CardNumber, 
                CardType, University, Email, IM, Phone, TimeZone';
        
        // get user information
        $data = $customer->getDatabaseObj()
            ->reset()
            ->select($selectFields)
            ->from($model['USER']['REALM'])
            ->where('ID', '=', $model['USER']['ID'])
            ->fetchRow();
        
        //var_dump($model['USER']['REALM']);
        //var_dump($selectFields);
        //var_dump($data);
        
        // time zones
        $model['CUSTOMER']['TIME_TZ'] = libraryUtils::getTimeZones();
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
        $user['IS_STUDENT'] = ($user['TYPE'] == 'Student');
        $user['IS_WRITER'] = ($user['TYPE'] == 'Writer');
        
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
            //echo '--------------------------- logout';
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
