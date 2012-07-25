<?php

class pluginWriter {

    public function main($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        if (!$model['USER']['ACTIVE'])
            return;
        
        $this->_displayTriggerOnCommonStart($toolbox, $plugin);
        if (libraryRequest::getPage() === strtolower($plugin['key']))
            $this->_displayTriggerOnActive($toolbox, $plugin);
        else
            $this->_displayTriggerOnInActive($toolbox, $plugin);
        $this->_displayTriggerOnCommonEnd($toolbox, $plugin);
    }

    /* combine data with template */
    public function render($toolbox, $plugin) {
        //echo '***WRITER RENDER***';
        $model = &$toolbox->getModel();
        $libView = new libraryView();

        /* gat all components as html */
        if ($model['USER']['ACTIVE'] && !empty($model['PLUGINS']['TOOLBOX']['COM'])) {
            foreach ($model['PLUGINS']['WRITER']['COM'] as $key => $component)
                $model['html']['writer']['com'][strtolower($key)] = $libView->getTemplateResult($model, $model['PLUGINS']['WRITER']['COM'][$key]['template']);
            $model['html']['menu'] .= $model['html']['writer']['com']['menu'];
        }

        /* set html data */
        $model['html']['content'] .= $libView->getTemplateResult($model, $model['PLUGINS']['WRITER']['template']);
    }

    public function layout($toolbox, $plugin) {
        //echo '***WRITER LAYOUT***';
        $libView = new libraryView();
        $model = &$toolbox->getModel();
        return $libView->getTemplateResult($model, $plugin['templates']['layout']);
    }

    public function api($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        $p = libraryRequest::getApiParam();
        
        //var_dump($p);
        

        if (!$model['USER']['ACTIVE'] || empty($p['token']) || !libraryRequest::getOrValidatePageSecurityToken($p['token']))
            return;
        /*
        echo '<br>requested token: ' . $p['token'];
        echo '<br>user active? ' . ($model['USER']['ACTIVE']?'yes':'no');
        echo '<br>string token empty? ' . (empty($p['token'])?'yes':'no');
        echo '<br>token match? ' . (libraryRequest::getOrValidatePageSecurityToken($p['token'])?'yes':'no');
        echo '<br><br><br><br>';
        */
        
        $result = false;
        switch (libraryRequest::getApiFn('none')) {
            case 'user_messages':
                //debug(libraryRequest::getApiParam());
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
        //echo 'ololololo api writer';
    }
    
    private function api_assign_to_writer ($toolbox, $plugin) {
        $p = libraryRequest::getApiParam();
        
    }
    private function api_mark_as_read ($toolbox, $plugin) {
        $p = libraryRequest::getApiParam();
        
        if (isset($p['checked']) && isset($p['oid']))
            $toolbox->getDatabaseObj()
                ->update('writer_messages')
                ->set(array('IsUnread' => $p['checked']))
                ->where('ID', '=', $p['oid'])
                ->query();
        else
            return false;
        
        return true;
        
        //echo libraryUtils::getJSON($p);
    }
    private function api_get_teamload ($toolbox, $plugin) {
        
        //echo $plugin['config']['QUERY']['API']['STAT_WR_ORDERS'];
        
        $stat['writerToOrderCount'] = $toolbox->getDatabaseObj()
                ->fetchData($plugin['config']['QUERY']['API']['STAT_WR_ORDERS']);
        
        
        return libraryUtils::getJSON($stat);
        
        
    }
    
    /* display triggers */
    private function _displayTriggerOnActive($toolbox, $plugin) {
        //$model = &$toolbox->getModel();

        // remove expired accounts
        $param['dbo'] = $toolbox->getDatabaseObj();
        $this->cross_useremoval($param);

        switch (libraryRequest::getDisplay('home')) {
            case 'queue' : {
                $this->_displayQueue($toolbox, $plugin);
                break;
            }
            case 'statistic' : {
                $this->_displayStatistic($toolbox, $plugin);
                break;
            }
            case 'prices' : {
                $this->_displayPrices($toolbox, $plugin);
                break;
            }
            case 'documents' : {
                $this->_displayDocuments($toolbox, $plugin);
                break;
            }
            case 'sale' : {
                $this->_displaySale($toolbox, $plugin);
                break;
            }
            case 'sales' : {
                $this->_displaySales($toolbox, $plugin);
                break;
            }
            case 'subjects' : {
                $this->_displaySubjects($toolbox, $plugin);
                break;
            }
            case 'writers' : {
                $this->_displayWriters($toolbox, $plugin);
                break;
            }
            case 'students' : {
                $this->_displayStudents($toolbox, $plugin);
                break;
            }
            case 'orders' : {
                $this->_displayOrders($toolbox, $plugin);
                break;
            }
            case 'messages' : {
                $this->_displayMessages($toolbox, $plugin);
                break;
            }
            case 'home' :
            default : {
                // do default action
                $this->_displayHome($toolbox, $plugin);
            }
        }

    }
    private function _displayTriggerOnInActive($toolbox, $plugin) {

    }
    private function _displayTriggerOnCommonStart($toolbox, $plugin) {

    }
    private function _displayTriggerOnCommonEnd($toolbox, $plugin) {
        /* init components */
        $this->_componentMenu($toolbox, $plugin);
        $this->_componentNewIncoming($toolbox, $plugin);
    }

    /* components */
    private function _componentMenu($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        $model['PLUGINS']['WRITER']['COM']['MENU']['template'] = $plugin['templates']['component.menu'];
    }
    private function _componentNewIncoming($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        /* get new orders */
        $model['PLUGINS']['WRITER']['COM']['INCOMIG']['ORDERS'] = $toolbox->getDatabaseObj()
                ->getCount('writer_orders', ' WriterID=0 && PublicStatus <> "CLOSED"');
        /* get new sales */
        $model['PLUGINS']['WRITER']['COM']['INCOMIG']['SALES'] = $toolbox->getDatabaseObj()
                ->getCount('writer_sales', ' IsActive = 1');
        
        if ($model['PLUGINS']['WRITER']['COM']['INCOMIG']['ORDERS'])
            $model['html']['messages'][] = 'You have ' . $model['PLUGINS']['WRITER']['COM']['INCOMIG']['ORDERS'] . ' new orders. (<a href="writer.html?display=orders&sort=WriterID.asc">see all</a>)';
        if ($model['PLUGINS']['WRITER']['COM']['INCOMIG']['SALES'])
            $model['html']['messages'][] = 'You have sold ' . $model['PLUGINS']['WRITER']['COM']['INCOMIG']['SALES'] . ' documents. (<a href="writer.html?display=sales&sort=IsActive.asc">see all</a>)';
    }
    private function _componentMessages ($toolbox, $plugin) { }
    
    /* display */
    private function _displayQueue($toolbox, $plugin, $postaction = '') {
        $action = libraryRequest::getAction();
        if (!empty($postaction))
            $action = $postaction;
        $innerAction = '';
        switch($action) {
            default:
                $innerAction = $this->_displayQueueDefault($toolbox, $plugin);
                break;
        }
        if (!empty($innerAction) && strcasecmp($innerAction, $action) != 0)
            $this->_displayQueue($toolbox, $plugin, $innerAction);
    }
    private function _displayStatistic($toolbox, $plugin, $postaction = '') {
        $action = libraryRequest::getAction();
        if (!empty($postaction))
            $action = $postaction;
        $innerAction = '';
        switch($action) {
            default:
                $innerAction = $this->_displayStatisticDefault($toolbox, $plugin);
                break;
        }
        if (!empty($innerAction) && strcasecmp($innerAction, $action) != 0)
            $this->_displayStatistic($toolbox, $plugin, $innerAction);
    }
    private function _displayPrices($toolbox, $plugin, $postaction = '') {
        //echo '_displayWritersDefault';
        $action = libraryRequest::getAction();
        if (!empty($postaction))
            $action = $postaction;
        $innerAction = '';
        switch($action) {
            case 'edit':
            case 'create':
                $innerAction = $this->_displayPricesEdit($toolbox, $plugin);
                break;
            case 'delete':
                $innerAction = $this->_displayPricesDelete($toolbox, $plugin);
                break;
            default:
                $innerAction = $this->_displayPricesDefault($toolbox, $plugin);
                break;
        }

        if (!empty($innerAction) && strcasecmp($innerAction, $action) != 0)
            $this->_displayPrices($toolbox, $plugin, $innerAction);
    }
    private function _displayDocuments($toolbox, $plugin, $postaction = '') {
        //echo '_displayWritersDefault';
        $action = libraryRequest::getAction();
        if (!empty($postaction))
            $action = $postaction;
        $innerAction = '';
        switch($action) {
            case 'edit':
            case 'create':
                $innerAction = $this->_displayDocumentsEdit($toolbox, $plugin);
                break;
            case 'delete':
                $innerAction = $this->_displayDocumentsDelete($toolbox, $plugin);
                break;
            default:
                $innerAction = $this->_displayDocumentsDefault($toolbox, $plugin);
                break;
        }

        if (!empty($innerAction) && strcasecmp($innerAction, $action) != 0)
            $this->_displayDocuments($toolbox, $plugin, $innerAction);
    }
    private function _displaySale($toolbox, $plugin, $postaction = '') {
        //echo '_displayWritersDefault';
        $action = libraryRequest::getAction();
        if (!empty($postaction))
            $action = $postaction;
        $innerAction = '';
        switch($action) {
            case 'edit':
            case 'create':
                $innerAction = $this->_displaySaleEdit($toolbox, $plugin);
                break;
            case 'delete':
                $innerAction = $this->_displaySaleDelete($toolbox, $plugin);
                break;
            default:
                $innerAction = $this->_displaySaleDefault($toolbox, $plugin);
                break;
        }

        if (!empty($innerAction) && strcasecmp($innerAction, $action) != 0)
            $this->_displaySale($toolbox, $plugin, $innerAction);
    }
    private function _displaySales($toolbox, $plugin, $postaction = '') {
        //echo '_displayWritersDefault';
        $action = libraryRequest::getAction();
        if (!empty($postaction))
            $action = $postaction;
        $innerAction = '';
        switch($action) {
            case 'details':
                $innerAction = $this->_displaySalesDetails($toolbox, $plugin);
                break;
            default:
                $innerAction = $this->_displaySalesDefault($toolbox, $plugin);
                break;
        }

        if (!empty($innerAction) && strcasecmp($innerAction, $action) != 0)
            $this->_displaySales($toolbox, $plugin, $innerAction);
    }
    private function _displaySubjects($toolbox, $plugin, $postaction = '') {
        //echo '_displayWritersDefault';
        $action = libraryRequest::getAction();
        if (!empty($postaction))
            $action = $postaction;
        $innerAction = '';
        switch($action) {
            case 'edit':
            case 'create':
                $innerAction = $this->_displaySubjectsEdit($toolbox, $plugin);
                break;
            case 'delete':
                $innerAction = $this->_displaySubjectsDelete($toolbox, $plugin);
                break;
            default:
                $innerAction = $this->_displaySubjectsDefault($toolbox, $plugin);
                break;
        }

        if (!empty($innerAction) && strcasecmp($innerAction, $action) != 0)
            $this->_displaySubjects($toolbox, $plugin, $innerAction);
    }
    private function _displayWriters($toolbox, $plugin, $postaction = '') {
        //echo '_displayWritersDefault';
        $action = libraryRequest::getAction();
        if (!empty($postaction))
            $action = $postaction;
        $innerAction = '';
        switch($action) {
            case 'edit':
            case 'create':
                $innerAction = $this->_displayWritersEdit($toolbox, $plugin);
                break;
            case 'delete':
                $innerAction = $this->_displayWritersDelete($toolbox, $plugin);
                break;
            case 'details':
                $innerAction = $this->_displayWritersDetails($toolbox, $plugin);
                break;
            case 'orderhistory':
                $innerAction = $this->_displayWritersDetailsOrderHistory($toolbox, $plugin);
                break;
            default:
                $innerAction = $this->_displayWritersDefault($toolbox, $plugin);
                break;
        }

        if (!empty($innerAction) && strcasecmp($innerAction, $action) != 0)
            $this->_displayWriters($toolbox, $plugin, $innerAction);
    }
    private function _displayOrders($toolbox, $plugin, $postaction = '') {
        $action = libraryRequest::getAction();
        if (!empty($postaction))
            $action = $postaction;
        $innerAction = '';
        switch($action) {
            case 'details':
                $innerAction = $this->_displayOrdersDetails($toolbox, $plugin);
                break;
            default:
                $innerAction = $this->_displayOrdersDefault($toolbox, $plugin);
                break;
        }

        if (!empty($innerAction) && strcasecmp($innerAction, $action) != 0)
            $this->_displayOrders($toolbox, $plugin, $innerAction);
    }
    private function _displayStudents($toolbox, $plugin, $postaction = '') {
        $action = libraryRequest::getAction();
        if (!empty($postaction))
            $action = $postaction;
        $innerAction = '';
        switch($action) {
            case 'edit':
                $innerAction = $this->_displayStudentsEdit($toolbox, $plugin);
                break;
            case 'delete':
                $innerAction = $this->_displayStudentsDelete($toolbox, $plugin);
                break;
            case 'details':
                $innerAction = $this->_displayStudentsDetails($toolbox, $plugin);
                break;
            default:
                $innerAction = $this->_displayStudentsDefault($toolbox, $plugin);
                break;
        }

        if (!empty($innerAction) && strcasecmp($innerAction, $action) != 0)
            $this->_displayStudents($toolbox, $plugin, $innerAction);
    }
    private function _displayMessages($toolbox, $plugin, $postaction = '') {
        $action = libraryRequest::getAction();
        if (!empty($postaction))
            $action = $postaction;
        $innerAction = '';
        switch($action) {
            case 'send-to-writer':
            case 'send-to-student':
            case 'send-to-order':
                $innerAction = $this->_displayMessagesCreate($toolbox, $plugin);
                break;
            case 'delete':
                $innerAction = $this->_displayMessagesDelete($toolbox, $plugin);
                break;
            case 'details':
                $innerAction = $this->_displayMessagesDetails($toolbox, $plugin);
                break;
            default:
                $innerAction = $this->_displayMessagesDefault($toolbox, $plugin);
                break;
        }

        if (!empty($innerAction) && strcasecmp($innerAction, $action) != 0)
            $this->_displayMessages($toolbox, $plugin, $innerAction);
    }
    private function _displayHome($toolbox, $plugin, $postaction = '') {
        //echo '_displayWritersDefault';
        $model = &$toolbox->getModel();
        /*$users = $toolbox->getDatabaseObj()->select('*')
            ->from('mpws_users')
            ->fetchData();*/
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.home'];
    }

    private function _displayQueueDefault ($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        libraryRequest::storeOrGetRefererUrl();
        //$model['PLUGINS']['WRITER'] = libraryComponents::comDataTable($plugin['config']['DATATABLE']['ORDERS'], $toolbox->getDatabaseObj(), 'Status = "NEW"');
        
        $customer_config_mdbc = $toolbox->getCustomerObj()->GetCustomerConfiguration('MDBC');
        $statuses = array('OPEN', 'REJECTED', 'PENDING');
        foreach ($statuses as $stat) {
            $model['PLUGINS']['WRITER']['DATA'][$stat] = 
                $toolbox->getDatabaseObj()
                    ->reset()
                    ->select('*')
                    ->from('writer_orders')
                    ->where('WriterID', '=', '0')
                    ->andWhere('InternalStatus', '=', $stat)
                    ->orderBy('DateCreated')
                    ->order('DESC')
                    ->fetchData();
        }
        
        //var_dump($model['PLUGINS']['WRITER']['DATA']);
        
        //var_dump($model['PLUGINS']['WRITER']['DATA']);
        $model['PLUGINS']['WRITER']['DATE_FORMAT'] = $customer_config_mdbc['DB_DATE_FORMAT'];
        
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.queue.datatable'];
    }
    
    private function _displayStatisticDefault ($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        libraryRequest::storeOrGetRefererUrl();
        
        
        //$mdbc = $toolbox->getCustomerObj()->getCustomerConfiguration('MDBC');
        
        
        // SELECT FLOOR((unix_timestamp(`DateDeadline`) - unix_timestamp(NOW())) / 3600) as `TimeLeft` FROM `writer_orders` 
        
        
        // get tasks that expires
        $data = $toolbox->getDatabaseObj()->fetchData($plugin['config']['QUERY']['API']['STAT_ORDER_DEADLINE']);
        $expiredTasks4 = array();
        $expiredTasks3 = array();
        $expiredTasks2 = array();
        $expiredTasks1 = array();
        $expiredTasks0 = array();
        //var_dump($data);
        // sort data
        foreach ($data as $entry) {
            // 4 ...3
            if ($entry['HoursLeft'] > 3)
                $expiredTasks4[] = $entry;
            
            // 3 .. 2
            if ($entry['HoursLeft'] >= 2 && $entry['HoursLeft'] <= 3)
                $expiredTasks3[] = $entry;
            
            // 2 .. 1
            if ($entry['HoursLeft'] >= 1 && $entry['HoursLeft'] <= 2)
                $expiredTasks2[] = $entry;
            
            // 1 .. 0
            if ($entry['HoursLeft'] > 0 && $entry['HoursLeft'] <= 1)
                $expiredTasks1[] = $entry;
            
            // expired
            if ($entry['HoursLeft'] < 0)
                $expiredTasks0[] = $entry;
        }
        
        // get online writers that do not have tasks
        $freeWriters = $toolbox->getDatabaseObj()->fetchData($plugin['config']['QUERY']['API']['STAT_WRITERS_FREE']);

        // get rejected orders by writers
        $order_groups['PENDING'] = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_orders')
                ->where('InternalStatus', '=', 'PENDING')
                ->fetchData();
        
        // waiting for approval before sending to review
        $order_groups['REJECTED'] = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_orders')
                ->where('InternalStatus', '=', 'REJECTED')
                ->fetchData();
        // orders to rework by students
        $order_groups['REWORK'] = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_orders')
                ->where('PublicStatus', '=', 'REWORK')
                ->fetchData();
        // orders to refund by students
        $order_groups['TO REFUND'] = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_orders')
                ->where('PublicStatus', '=', 'TO REFUND')
                ->fetchData();
        // reopened
        $order_groups['REOPEN'] = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_orders')
                ->where('PublicStatus', '=', 'REOPEN')
                ->fetchData();
        
        
        $model['PLUGINS']['WRITER']['DATA_FREE_WRITERS'] = $freeWriters;
        
        $model['PLUGINS']['WRITER']['DATA_EXPIRED'] = array();
        if (!empty($expiredTasks0))
            $model['PLUGINS']['WRITER']['DATA_EXPIRED']['0'] = $expiredTasks0;
        if (!empty($expiredTasks1))
            $model['PLUGINS']['WRITER']['DATA_EXPIRED']['1'] = $expiredTasks1;
        if (!empty($expiredTasks2))
            $model['PLUGINS']['WRITER']['DATA_EXPIRED']['2'] = $expiredTasks2;
        if (!empty($expiredTasks3))
            $model['PLUGINS']['WRITER']['DATA_EXPIRED']['3'] = $expiredTasks3;
        if (!empty($expiredTasks4))
            $model['PLUGINS']['WRITER']['DATA_EXPIRED']['4'] = $expiredTasks4;
        
        // orders by statuses
        $model['PLUGINS']['WRITER']['DATA_O_GROUPS'] = $order_groups;
        
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.statistic.teamload'];
    }
    
    /* prices */
    private function _displayPricesDefault ($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        libraryRequest::storeOrGetRefererUrl();
        //$count_subjects = $toolbox->getDatabaseObj()->getCount('writer_subjects');
        //$count_documents = $toolbox->getDatabaseObj()->getCount('writer_documents');
        //if ($count_subjects > 0 && $count_documents > 0) {
            $model['PLUGINS']['WRITER'] = libraryComponents::comDataTable($plugin['config']['DATATABLE']['PRICES'], $toolbox->getDatabaseObj());
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.prices.datatable'];
        //} else
            //$model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.prices.error'];
    }
    private function _displayPricesDelete ($toolbox, $plugin) {
        $model = &$toolbox->getModel();

        $action = libraryRequest::getAction();
        $oid = libraryRequest::getOID();
        $data = array();

        $model['PLUGINS']['WRITER']['oid'] = $oid;
        $model['PLUGINS']['WRITER']['action'] = $action;
        if(empty($oid)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
            return;
        }

        $data = $toolbox->getDatabaseObj()
            ->select('*')
            ->from('writer_prices')
            ->where('ID', '=', $oid)
            ->fetchRow();

        if (empty($data)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
            return;
        }

        if (libraryRequest::isPostFormAction('delete')) {
            $toolbox->getDatabaseObj()
                ->deleteFrom('writer_prices')
                ->where('ID', '=', $oid)
                ->query();

            return 'home';
        }

        $model['PLUGINS']['WRITER']['DATA'] = $data;
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.prices.delete_preview'];

    }
    private function _displayPricesEdit ($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        $messages = array();
        //var_dump($plugin);
        $action = libraryRequest::getAction();
        $oid = libraryRequest::getOID();
        $data = array();
        $model['PLUGINS']['WRITER']['oid'] = $oid;
        $model['PLUGINS']['WRITER']['action'] = $action;
        $model['PLUGINS']['WRITER']['referer'] = libraryRequest::storeOrGetRefererUrl(false);
        
        if (libraryRequest::isPostFormAction('save')) {
            $data = libraryRequest::getPostMapContainer($plugin['config']['VALIDATOR']['DATAMAP']['PRICES']);
            
            //var_dump($data);
            
            /* validate fileds */
            libraryValidator::validateData($data, $plugin['config']['VALIDATOR']['FILTER']['PRICES'], $messages);
            // if ok to proceed
            //echo 'olololo';
            if (empty($messages)) {
                if ($action === 'edit') {
                    // check if oid is not empty
                    if (empty($oid)) {
                        // set template
                        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
                        return;
                    }
                    $toolbox->getDatabaseObj()
                        ->update('writer_prices')
                        ->set($data)
                        ->where('ID', '=', $oid)
                        ->query();
                    // force return to home page
                    return 'home';
                } elseif ($action === 'create') {
                    $toolbox->getDatabaseObj()
                        ->insertInto('writer_prices')
                        ->fields(array_keys($data))
                        ->values(array_values($data))
                        ->query();
                    // force return to home page
                    return 'home';
                } else
                    $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
                return;
            } else
                $model['PLUGINS']['WRITER']['messages'] = $messages;
        }
        if (libraryRequest::isPostFormAction('cancel')) {
            return 'home';
        }
        if (libraryRequest::isPostFormAction('edit')) {
            // uncomment to use preview form
            //data = libraryRequest::getPostMapContainer($plugin['config']['VALIDATOR']['DATAMAP']['SUBJECTS']);
        }
        // in edit mode validate oid
        if ($action === 'edit' && !empty($oid)) {
            $document = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_prices')
                ->where('ID', '=', $oid)
                ->fetchRow();
            if (empty($document)) {
                // set template
                $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
                return;
            }
            if (!libraryRequest::isPostFormActionMatchAny('save'))
                $data = $document;
        }
        if ($action === 'create') {
            
        }

        // set template
        $model['PLUGINS']['WRITER']['DATA'] = $data;
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.prices.create'];
        
        
        
        
    }

    /* sale */
    private function _displaySaleDefault ($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        libraryRequest::storeOrGetRefererUrl();
        $model['PLUGINS']['WRITER'] = libraryComponents::comDataTable($plugin['config']['DATATABLE']['SALE'], $toolbox->getDatabaseObj());
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.sale.datatable'];
    }
    private function _displaySaleDelete ($toolbox, $plugin) {

        $model = &$toolbox->getModel();

        $action = libraryRequest::getAction();
        $oid = libraryRequest::getOID();
        $data = array();

        $model['PLUGINS']['WRITER']['oid'] = $oid;
        $model['PLUGINS']['WRITER']['action'] = $action;
        if(empty($oid)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
            return;
        }

        $data = $toolbox->getDatabaseObj()
            ->select('*')
            ->from('writer_sale')
            ->where('ID', '=', $oid)
            ->fetchRow();

        if (empty($data)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
            return;
        }

        if (libraryRequest::isPostFormAction('delete')) {
            $toolbox->getDatabaseObj()
                ->deleteFrom('writer_sale')
                ->where('ID', '=', $oid)
                ->query();

            return 'home';
        }

        $model['PLUGINS']['WRITER']['DATA'] = $data;
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.sale.delete_preview'];

    }
    private function _displaySaleEdit ($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        $messages = array();
        //var_dump($plugin);
        $action = libraryRequest::getAction();
        $oid = libraryRequest::getOID();
        $data = array();
        $model['PLUGINS']['WRITER']['oid'] = $oid;
        $model['PLUGINS']['WRITER']['action'] = $action;
        $model['PLUGINS']['WRITER']['referer'] = libraryRequest::storeOrGetRefererUrl(false);

        if (libraryRequest::isPostFormAction('save')) {
            $data = libraryRequest::getPostMapContainer($plugin['config']['VALIDATOR']['DATAMAP']['SALE']);
            
            //var_dump($data);
            
            /* validate fileds */
            libraryValidator::validateData($data, $plugin['config']['VALIDATOR']['FILTER']['SALE'], $messages);
            // if ok to proceed
            //echo 'olololo';
            if (empty($messages)) {
                if ($action === 'edit') {
                    // check if oid is not empty
                    if (empty($oid)) {
                        // set template
                        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
                        return;
                    }
                    $toolbox->getDatabaseObj()
                        ->update('writer_sale')
                        ->set($data)
                        ->where('ID', '=', $oid)
                        ->query();
                    // force return to home page
                    return 'home';
                } elseif ($action === 'create') {
                    $customer_config_mdbc = $toolbox->getCustomerObj()->GetCustomerConfiguration('MDBC');
                    $data['DateCreated'] = date($customer_config_mdbc['DB_DATE_FORMAT']);
                    $toolbox->getDatabaseObj()
                        ->insertInto('writer_sale')
                        ->fields(array_keys($data))
                        ->values(array_values($data))
                        ->query();
                    // force return to home page
                    return 'home';
                } else
                    $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
                return;
            } else
                $model['PLUGINS']['WRITER']['messages'] = $messages;
        }
        if (libraryRequest::isPostFormAction('cancel')) {
            return 'home';
        }
        if (libraryRequest::isPostFormAction('edit')) {
            // uncomment to use preview form
            //data = libraryRequest::getPostMapContainer($plugin['config']['VALIDATOR']['DATAMAP']['SUBJECTS']);
        }
        // in edit mode validate oid
        if ($action === 'edit' && !empty($oid)) {
            $document = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_sale')
                ->where('ID', '=', $oid)
                ->fetchRow();
            if (empty($document)) {
                // set template
                $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
                return;
            }
            if (!libraryRequest::isPostFormActionMatchAny('save'))
                $data = $document;
        }
        if ($action === 'create') {
            
        }

        // set template
        $model['PLUGINS']['WRITER']['DATA'] = $data;
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.sale.create'];
    }

    /* sales */
    private function _displaySalesDefault ($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        libraryRequest::storeOrGetRefererUrl();
        
        $dbo = $toolbox->getDatabaseObj()
            ->stopReset()
            ->leftJoin('writer_sale')
            ->on('writer_sales.SaleID', '=', 'writer_sale.ID');
                
        // custom search join
        $customJoin = ' LEFT JOIN writer_sale ON writer_sales.SaleID = writer_sale.ID ';
        
        $model['PLUGINS']['WRITER'] = libraryComponents::comDataTable($plugin['config']['DATATABLE']['SALES'], $dbo, false, $customJoin);
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.sales.datatable'];
    }
    private function _displaySalesDetails ($toolbox, $plugin) {
        $model = &$toolbox->getModel();

        $action = libraryRequest::getAction();
        $oid = libraryRequest::getOID();
        
        // check for token id
        $token = libraryRequest::getValue('token');
        
        $model['PLUGINS']['WRITER']['oid'] = $oid;
        $model['PLUGINS']['WRITER']['action'] = $action;
        $model['PLUGINS']['WRITER']['referer'] = libraryRequest::storeOrGetRefererUrl(false);
        $model['PLUGINS']['WRITER']['INTERNAL_ACTION'] = false;

        // check for empty oid
        if(empty($oid)) {
            // and for token
            if (empty($token)) {
                // set template
                $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
                return;
            }
            $data_order = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_sales')
                ->where('SalesToken', '=', $token)
                ->fetchRow();
            if (empty($data_order['ID'])) {
                // set template
                $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
                return;
            }
            else
                $oid = $data_order['ID']; 
        }

        // get order record
        $data_order = $toolbox->getDatabaseObj()
            ->select('*')
            ->from('writer_sales')
            ->where('ID', '=', $oid)
            ->fetchRow();

        if (libraryRequest::isPostFormAction('close sale')) {
            
        }
        
        
        $data_invoice_order = array();
        if (!empty($data_order['SalesToken']))
            $data_invoice_order = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_invoices')
                ->where('merchant_order_id', '=', $data_order['SalesToken'])
                ->fetchRow();
        
        $data_invoice_refund = array();
        if (!empty($data_order['RefundToken']))
            $data_invoice_refund = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_invoices')
                ->where('merchant_order_id', '=', $data_order['RefundToken'])
                ->fetchRow();

        $data_student = $toolbox->getDatabaseObj()
            ->select('*')
            ->from('writer_students')
            ->where('ID', '=', $data_order['StudentID'])
            ->fetchRow();
        
        $data_sale = $toolbox->getDatabaseObj()
            ->select('*')
            ->from('writer_sale')
            ->where('ID', '=', $data_order['SaleID'])
            ->fetchRow();
        

        $model['PLUGINS']['WRITER']['DATA'] = $data_order;
        $model['PLUGINS']['WRITER']['DATA_STUDENT'] = $data_student;
        $model['PLUGINS']['WRITER']['DATA_SALE'] = $data_sale;
        $model['PLUGINS']['WRITER']['DATA_INVOICE_ORDER'] = $data_invoice_order;
        $model['PLUGINS']['WRITER']['DATA_INVOICE_REFUND'] = $data_invoice_refund;
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.sales.details'];
    }
    
    /* documents */
    private function _displayDocumentsDefault ($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        libraryRequest::storeOrGetRefererUrl();
        $model['PLUGINS']['WRITER'] = libraryComponents::comDataTable($plugin['config']['DATATABLE']['DOCUMENTS'], $toolbox->getDatabaseObj());
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.documents.datatable'];
    }
    private function _displayDocumentsDelete ($toolbox, $plugin) {

        $model = &$toolbox->getModel();

        $action = libraryRequest::getAction();
        $oid = libraryRequest::getOID();
        $data = array();

        $model['PLUGINS']['WRITER']['oid'] = $oid;
        $model['PLUGINS']['WRITER']['action'] = $action;
        if(empty($oid)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
            return;
        }

        $data = $toolbox->getDatabaseObj()
            ->select('*')
            ->from('writer_documents')
            ->where('ID', '=', $oid)
            ->fetchRow();

        if (empty($data)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
            return;
        }

        if (libraryRequest::isPostFormAction('delete')) {
            $toolbox->getDatabaseObj()
                ->deleteFrom('writer_documents')
                ->where('ID', '=', $oid)
                ->query();

            return 'home';
        }

        $model['PLUGINS']['WRITER']['DATA'] = $data;
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.documents.delete_preview'];

    }
    private function _displayDocumentsEdit ($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        $messages = array();
        //var_dump($plugin);
        $action = libraryRequest::getAction();
        $oid = libraryRequest::getOID();
        $data = array();
        $model['PLUGINS']['WRITER']['oid'] = $oid;
        $model['PLUGINS']['WRITER']['action'] = $action;
        $model['PLUGINS']['WRITER']['referer'] = libraryRequest::storeOrGetRefererUrl(false);

        if (libraryRequest::isPostFormAction('save')) {
            $data = libraryRequest::getPostMapContainer($plugin['config']['VALIDATOR']['DATAMAP']['DOCUMENTS']);
            /* validate fileds */
            libraryValidator::validateData($data, $plugin['config']['VALIDATOR']['FILTER']['DOCUMENTS'], $messages);
            // if ok to proceed
            //echo 'olololo';
            if (empty($messages)) {
                if ($action === 'edit') {
                    // check if oid is not empty
                    if (empty($oid)) {
                        // set template
                        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
                        return;
                    }
                    $toolbox->getDatabaseObj()
                        ->update('writer_documents')
                        ->set($data)
                        ->where('ID', '=', $oid)
                        ->query();
                    // force return to home page
                    return 'home';
                } elseif ($action === 'create') {
                    $toolbox->getDatabaseObj()
                        ->insertInto('writer_documents')
                        ->fields(array_keys($data))
                        ->values(array_values($data))
                        ->query();
                    // force return to home page
                    return 'home';
                } else
                    $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
                return;
            } else
                $model['PLUGINS']['WRITER']['messages'] = $messages;
        }
        if (libraryRequest::isPostFormAction('cancel')) {
            return 'home';
        }
        if (libraryRequest::isPostFormAction('edit')) {
            // uncomment to use preview form
            //data = libraryRequest::getPostMapContainer($plugin['config']['VALIDATOR']['DATAMAP']['SUBJECTS']);
        }
        // in edit mode validate oid
        if ($action === 'edit' && !empty($oid)) {
            $document = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_documents')
                ->where('ID', '=', $oid)
                ->fetchRow();
            if (empty($document)) {
                // set template
                $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
                return;
            }
            if (!libraryRequest::isPostFormActionMatchAny('save'))
                $data = $document;
        }
        if ($action === 'create') {
            
        }

        // set template
        $model['PLUGINS']['WRITER']['DATA'] = $data;
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.documents.create'];
    }

    /* subjects */
    private function _displaySubjectsDefault ($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        libraryRequest::storeOrGetRefererUrl();
        $model['PLUGINS']['WRITER'] = libraryComponents::comDataTable($plugin['config']['DATATABLE']['SUBJECTS'], $toolbox->getDatabaseObj());
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.subjects.datatable'];
    }
    private function _displaySubjectsDelete ($toolbox, $plugin) {

        $model = &$toolbox->getModel();

        $action = libraryRequest::getAction();
        $oid = libraryRequest::getOID();
        $data = array();

        $model['PLUGINS']['WRITER']['oid'] = $oid;
        $model['PLUGINS']['WRITER']['action'] = $action;
        if(empty($oid)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
            return;
        }

        $data = $toolbox->getDatabaseObj()
            ->select('*')
            ->from('writer_subjects')
            ->where('ID', '=', $oid)
            ->fetchRow();

        if (empty($data)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
            return;
        }

        if (libraryRequest::isPostFormAction('delete')) {
            $toolbox->getDatabaseObj()
                ->deleteFrom('writer_subjects')
                ->where('ID', '=', $oid)
                ->query();

            return 'home';
        }

        $model['PLUGINS']['WRITER']['DATA'] = $data;
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.subjects.delete_preview'];

    }
    private function _displaySubjectsEdit ($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        $messages = array();
        //var_dump($plugin);
        $action = libraryRequest::getAction();
        $oid = libraryRequest::getOID();
        $data = array();
        $model['PLUGINS']['WRITER']['oid'] = $oid;
        $model['PLUGINS']['WRITER']['action'] = $action;
        $model['PLUGINS']['WRITER']['referer'] = libraryRequest::storeOrGetRefererUrl(false);

        if (libraryRequest::isPostFormAction('save')) {
            $data = libraryRequest::getPostMapContainer($plugin['config']['VALIDATOR']['DATAMAP']['SUBJECTS']);
            /* validate fileds */
            libraryValidator::validateData($data, $plugin['config']['VALIDATOR']['FILTER']['SUBJECTS'], $messages);
            // if ok to proceed
            //echo 'olololo';
            if (empty($messages)) {
                if ($action === 'edit') {
                    // check if oid is not empty
                    if (empty($oid)) {
                        // set template
                        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
                        return;
                    }
                    $toolbox->getDatabaseObj()
                        ->update('writer_subjects')
                        ->set($data)
                        ->where('ID', '=', $oid)
                        ->query();
                    // force return to home page
                    return 'home';
                } elseif ($action === 'create') {
                    $toolbox->getDatabaseObj()
                        ->insertInto('writer_subjects')
                        ->fields(array_keys($data))
                        ->values(array_values($data))
                        ->query();
                    // force return to home page
                    return 'home';
                } else
                    $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
                return;
            } else
                $model['PLUGINS']['WRITER']['messages'] = $messages;
        }
        if (libraryRequest::isPostFormAction('cancel')) {
            return 'home';
        }
        if (libraryRequest::isPostFormAction('edit')) {
            // uncomment to use preview form
            //data = libraryRequest::getPostMapContainer($plugin['config']['VALIDATOR']['DATAMAP']['SUBJECTS']);
        }
        // in edit mode validate oid
        if ($action === 'edit' && !empty($oid)) {
            $subject = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_subjects')
                ->where('ID', '=', $oid)
                ->fetchRow();
            if (empty($subject)) {
                // set template
                $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
                return;
            }
            if (!libraryRequest::isPostFormActionMatchAny('save'))
                $data = $subject;
        }
        if ($action === 'create') {
            
        }

        // set template
        $model['PLUGINS']['WRITER']['DATA'] = $data;
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.subjects.create'];
    }

    /* writer */
    private function _displayWritersDefault ($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        libraryRequest::storeOrGetRefererUrl();
        $model['PLUGINS']['WRITER'] = libraryComponents::comDataTable($plugin['config']['DATATABLE']['WRITERS'], $toolbox->getDatabaseObj());
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.writers.datatable'];
        
    }
    private function _displayWritersDetailsOrderHistory ($toolbox, $plugin) {
        $model = &$toolbox->getModel();

        $action = libraryRequest::getAction();
        $oid = libraryRequest::getOID();
        $data = array();

        $model['PLUGINS']['WRITER']['oid'] = $oid;
        $model['PLUGINS']['WRITER']['action'] = $action;
        $model['PLUGINS']['WRITER']['referer'] = libraryRequest::storeOrGetRefererUrl(false);
        
        if(empty($oid)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.writers.error'];
            return;
        }
        
        $data = $toolbox->getDatabaseObj()
            ->select('*')
            ->from('writer_writers')
            ->where('ID', '=', $oid)
            ->fetchRow();

        //$toolbox->getDatabaseObj()
        $data_credits = 0;
        $data_orders = array();
        if (libraryRequest::isPostFormAction('show orders')) {
            $data_orders = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_orders')
                ->where('WriterID', '=', $oid)
                ->andWhere('DateCreated', '>', libraryRequest::getPostValue('start_date'))
                ->andWhere('DateCreated', '<', libraryRequest::getPostValue('end_date'))
                ->fetchData();
            
            foreach ($data_orders as $entry)
                $data_credits += $entry['Credits'];
            
            $model['PLUGINS']['WRITER']['DATA_DATE_START'] = libraryRequest::getPostValue('start_date');
            $model['PLUGINS']['WRITER']['DATA_DATE_END'] = libraryRequest::getPostValue('end_date');
        }
        
        
        //$data_orders = $toolbox->getDatabaseObj()->fetchData();
        
        
        if (empty($data)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.writers.error'];
            return;
        }
        
        
        $model['PLUGINS']['WRITER']['DATA'] = $data;
        $model['PLUGINS']['WRITER']['DATA_ORDERS'] = $data_orders;
        $model['PLUGINS']['WRITER']['DATA_CREDITS'] = $data_credits;
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.writers.details_order_history'];
        
        
    }
    private function _displayWritersDetails ($toolbox, $plugin) {

        $model = &$toolbox->getModel();

        $action = libraryRequest::getAction();
        $oid = libraryRequest::getOID();
        $data = array();

        $model['PLUGINS']['WRITER']['oid'] = $oid;
        $model['PLUGINS']['WRITER']['action'] = $action;
        $model['PLUGINS']['WRITER']['referer'] = libraryRequest::storeOrGetRefererUrl(false);


        if(empty($oid)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.writers.error'];
            return;
        }

        $data = $toolbox->getDatabaseObj()
            ->select('*')
            ->from('writer_writers')
            ->where('ID', '=', $oid)
            ->fetchRow();
        
        // get order this year
        $data_orders = $toolbox->getDatabaseObj()
            ->select('*')
            ->from('writer_orders')
            ->where('WriterID', '=', $oid)
            ->andWhere('DateCreated', '>', date('Y-01-01 00:00:00'))
            ->orderBy('DateCreated')
            ->order('DESC')
            ->fetchData();
        

        if (empty($data)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.writers.error'];
            return;
        }

        $model['PLUGINS']['WRITER']['DATA'] = $data;
        $model['PLUGINS']['WRITER']['DATA_ORDERS'] = $data_orders;
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.writers.details'];

    }
    private function _displayWritersDelete ($toolbox, $plugin) {

        $model = &$toolbox->getModel();

        $action = libraryRequest::getAction();
        $oid = libraryRequest::getOID();
        $data = array();

        $model['PLUGINS']['WRITER']['oid'] = $oid;
        $model['PLUGINS']['WRITER']['action'] = $action;


        if(empty($oid)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.writers.error'];
            return;
        }

        $data = $toolbox->getDatabaseObj()
            ->select('*')
            ->from('writer_writers')
            ->where('ID', '=', $oid)
            ->fetchRow();

        if (empty($data)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.writers.error'];
            return;
        }

        if (libraryRequest::isPostFormAction('delete')) {

            $toolbox->getDatabaseObj()
                ->deleteFrom('writer_writers')
                ->where('ID', '=', $oid)
                ->query();

            if (libraryRequest::getPostValue('writer_notify', false)) {
                // form email object
                $customer_config_mail = $toolbox->getCustomerObj()->GetCustomerConfiguration('MAIL');
                $recipient = $customer_config_mail['WEBMASTER'];
                $recipient['TO'] = $data['Email'];
                $recipient['NAME'] = $data['Name'];
                $recipient['SUBJECT'] = 'Your account has been deleted';
                $recipient['DATA'] = array(
                    'Login' => $data['Login'],
                    'Name' => $data['Name'],
                    'SupportEmail' => $customer_config_mail['SUPPORT']['EMAIL']
                );
                // get html message
                $libView = new libraryView();
                $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $plugin['templates']['mail.writers.deleted']);
                // send email message
                libraryMailer::sendEMail($recipient);
            }

            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.writers.delete_save'];
            return;
        }

        $model['PLUGINS']['WRITER']['data'] = $data;
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.writers_delete_preview'];
    }
    private function _displayWritersEdit ($toolbox, $plugin) {

        $model = &$toolbox->getModel();

        $messages = array();

        //var_dump($plugin);

        $action = libraryRequest::getAction();
        $oid = libraryRequest::getOID();
        $data = array();

        $model['PLUGINS']['WRITER']['oid'] = $oid;
        $model['PLUGINS']['WRITER']['action'] = $action;
        $model['PLUGINS']['WRITER']['referer'] = libraryRequest::storeOrGetRefererUrl(false);

        //echo $model['PLUGINS']['WRITER']['referer'];

        //var_dump($_POST);
        //echo libraryRequest::getPostFormAction();
        if (libraryRequest::isPostFormActionMatchAny('preview', 'save')) {

            $data = libraryRequest::getPostMapContainer($plugin['config']['VALIDATOR']['DATAMAP']['WRITERS']);
            /* validate fileds */
            if ($action === 'edit')
                $data['Password'] = 'SomePwd!1';
            libraryValidator::validateData($data, $plugin['config']['VALIDATOR']['FILTER']['WRITERS'], $messages);

            // if ok to proceed
            if (empty($messages)) {

                // set template
                if (libraryRequest::isPostFormAction('save')) {
                    // set common values
                    $data['Active'] = empty($data['Active'])?0:1;

                    if ($action === 'edit') {
                        // check if oid is not empty
                        if (empty($oid)) {
                            // set template
                            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.writers.error'];
                            return;
                        }
                        // we do not modify writer's password'
                        unset($data['Password']);
                        $toolbox->getDatabaseObj()
                            ->update('writer_writers')
                            ->set($data)
                            ->where('ID', '=', $oid)
                            ->query();
                        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.writers.create_save'];
                    }
                    elseif ($action === 'create') {
                        $customer_config_mdbc = $toolbox->getCustomerObj()->GetCustomerConfiguration('MDBC');
                        $customer_config_mail = $toolbox->getCustomerObj()->GetCustomerConfiguration('MAIL');
                        $_opwd = $data['Password'];
                        $data['Password'] = md5($data['Password']);
                        $data['DateLastAccess'] = date($customer_config_mdbc['DB_DATE_FORMAT']);
                        $data['DateCreated'] = date($customer_config_mdbc['DB_DATE_FORMAT']);
                        $toolbox->getDatabaseObj()
                            ->insertInto('writer_writers')
                            ->fields(array_keys($data))
                            ->values(array_values($data))
                            ->query();

                        $notifyWriter = libraryRequest::getPostValue('writer_notify');
                        if (!empty($notifyWriter)) {
                            // form email object
                            $recipient = $customer_config_mail['WEBMASTER'];
                            $recipient['TO'] = $data['Email'];
                            $recipient['NAME'] = $data['Name'];
                            $recipient['SUBJECT'] = 'Your account has been created';
                            $recipient['DATA'] = array(
                                'Login' => $data['Login'],
                                'Name' => $data['Name'],
                                'Password' => $_opwd,
                                'TargetUrl' => $customer_config_mail['URLS']['LOGIN'],
                                'SupportEmail' => $customer_config_mail['SUPPORT']['EMAIL']
                            );
                            // get html message
                            $libView = new libraryView();
                            $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $plugin['templates']['mail.writers.created']);
                            // send email message
                            libraryMailer::sendEMail($recipient);
                            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.writers.create_save'];
                        }
                    }
                    else
                        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.writers.create_error'];
                }
                elseif (libraryRequest::isPostFormAction('preview')) {
                    $model['PLUGINS']['WRITER']['data'] = $data;
                    $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.writers.create_preview'];
                } else
                    $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page_writers_error'];
                return;
            } else
                $model['PLUGINS']['WRITER']['messages'] = $messages;
        }
        if (libraryRequest::isPostFormAction('cancel')) {
            return 'home';
        }
        if (libraryRequest::isPostFormAction('edit')) {
            $data = libraryRequest::getPostMapContainer($plugin['config']['VALIDATOR']['DATAMAP']['WRITERS']);
        }
        // in edit mode validate oid
        if ($action === 'edit' && !empty($oid)) {
            $writer = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_writers')
                ->where('ID', '=', $oid)
                ->fetchRow();
            if (empty($writer)) {
                // set template
                $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.writers.error'];
                return;
            }
            $writer['Password'] = '';
            if (!libraryRequest::isPostFormActionMatchAny('edit', 'preview'))
                $data = $writer;
        }
        if ($action === 'create') {
            
        }
        // set template
        $model['PLUGINS']['WRITER']['data'] = $data;
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.writers.create'];
    }

    /* students */
    private function _displayStudentsDefault ($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        libraryRequest::storeOrGetRefererUrl();
        $model['PLUGINS']['WRITER'] = libraryComponents::comDataTable($plugin['config']['DATATABLE']['STUDENTS'], $toolbox->getDatabaseObj());
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.students.datatable'];
        
    }
    private function _displayStudentsDetails ($toolbox, $plugin) {

        $model = &$toolbox->getModel();

        $action = libraryRequest::getAction();
        $oid = libraryRequest::getOID();
        $data = array();

        $model['PLUGINS']['WRITER']['oid'] = $oid;
        $model['PLUGINS']['WRITER']['action'] = $action;
        $model['PLUGINS']['WRITER']['referer'] = libraryRequest::storeOrGetRefererUrl(false);
        $model['PLUGINS']['WRITER']['INTERNAL_ACTION'] = false;

        if(empty($oid)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
            return;
        }

        if (libraryRequest::isPostFormAction('send message')) {

            echo 'sending message';
            $model['PLUGINS']['WRITER']['INTERNAL_ACTION'] = 'MESSAGE_SEND';
            //return;
        }
        $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_orders')
                ->where('StudentID', '=', $oid);
                    
        if (libraryRequest::isPostFormAction('show orders')) {
            $toolbox->getDatabaseObj()
                ->andWhere('DateCreated', '>', libraryRequest::getPostValue('start_date'))
                ->andWhere('DateCreated', '<', libraryRequest::getPostValue('end_date'));
            
            $model['PLUGINS']['WRITER']['DATA_DATE_START'] = libraryRequest::getPostValue('start_date');
            $model['PLUGINS']['WRITER']['DATA_DATE_END'] = libraryRequest::getPostValue('end_date');
        }
        
        
        $data_orders = $toolbox->getDatabaseObj()->fetchData();

        $data = $toolbox->getDatabaseObj()
            ->select('*')
            ->from('writer_students')
            ->where('ID', '=', $oid)
            ->fetchRow();

        if (empty($data)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
            return;
        }

        $model['PLUGINS']['WRITER']['DATA'] = $data;
        $model['PLUGINS']['WRITER']['DATA_ORDERS'] = $data_orders;
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.students.details'];
    }
    
    /* messages */
    private function _displayMessagesDefault ($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        libraryRequest::storeOrGetRefererUrl();
        $model['PLUGINS']['WRITER'] = libraryComponents::comDataTable($plugin['config']['DATATABLE']['MESSAGES'], $toolbox->getDatabaseObj());
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.messages.datatable'];
        
    }
    private function _displayMessagesCreate ($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        $messages = array();
        //var_dump($plugin);
        $action = libraryRequest::getAction();
        $oid = libraryRequest::getOID();
        $data = array();
        $model['PLUGINS']['WRITER']['oid'] = $oid;
        $model['PLUGINS']['WRITER']['action'] = $action;
        $model['PLUGINS']['WRITER']['referer'] = libraryRequest::storeOrGetRefererUrl(false);

        //echo 'action is ' . $action;

        switch($action) {
            case 'send-to-writer': {
                $model['PLUGINS']['WRITER']['TO_LIST'] = $toolbox->getDatabaseObj()
                        ->select('ID', 'Name')
                        ->from('writer_writers');
                break;
            }
            case 'send-to-student': {
                $model['PLUGINS']['WRITER']['TO_LIST'] = $toolbox->getDatabaseObj()
                        ->select('ID', 'Name')
                        ->from('writer_students');
                break;
            }
            case 'send-to-order': {
                $model['PLUGINS']['WRITER']['TO_LIST'] = $toolbox->getDatabaseObj()
                        ->select('ID', 'Title as `Name`')
                        ->from('writer_orders');
                break;
            }
        }

        //echo $to;

        if (libraryRequest::isPostFormActionMatchAny('preview', 'send')) {
            $data = libraryRequest::getPostMapContainer($plugin['config']['VALIDATOR']['DATAMAP']['MESSAGES']);
            /* validate fileds */
            libraryValidator::validateData($data, $plugin['config']['VALIDATOR']['FILTER']['MESSAGES'], $messages);
            // if ok to proceed
            if (empty($messages)) {
                if (libraryRequest::isPostFormAction('send')) {
                    // check if oid is not empty
                    $customer_config_mdbc = $toolbox->getCustomerObj()->GetCustomerConfiguration('MDBC');
                    $data['DateCreated'] = date($customer_config_mdbc['DB_DATE_FORMAT']);
                    $data['Owner'] = 'WEBMASTER';
                    switch($action) {
                        case 'send-to-writer': {
                            $data['WriterID'] = $data['To'];
                            break;
                        }
                        case 'send-to-student': {
                            $data['StudentID'] = $data['To'];
                            break;
                        }
                        case 'send-to-order': {
                            $data['OrderID'] = $data['To'];
                            break;
                        }
                    }
                    unset($data['To']);

                    $toolbox->getDatabaseObj()
                        ->reset()
                        ->insertInto('writer_messages')
                        ->fields(array_keys($data))
                        ->values(array_values($data))
                        ->query();
                    $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.messages.create_send'];
                }
                elseif (libraryRequest::isPostFormAction('preview')) {
                    $model['PLUGINS']['WRITER']['TO_LIST'] = $toolbox->getDatabaseObj()
                        ->where('ID', '=', $data['To'])
                        ->fetchRow();
                    $model['PLUGINS']['WRITER']['DATA'] = $data;
                    $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.messages.create_preview'];
                } else
                    $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
                //var_dump($model['PLUGINS']['WRITER']['TO_LIST']);
                return;
            } else
                $model['PLUGINS']['WRITER']['messages'] = $messages;
        }
        if (libraryRequest::isPostFormAction('cancel')) {
            return 'home';
        }
        if (libraryRequest::isPostFormAction('edit')) {
            $data = libraryRequest::getPostMapContainer($plugin['config']['VALIDATOR']['DATAMAP']['MESSAGES']);
            //var_dump($data);
        }
        //var_dump($data);
        //var_dump($model['PLUGINS']['WRITER']['TO_LIST']);

        $model['PLUGINS']['WRITER']['TO_LIST'] = $toolbox->getDatabaseObj()->fetchData();


        // set template
        $model['PLUGINS']['WRITER']['DATA'] = $data;
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.messages.create'];
    }
    private function _displayMessagesDetails ($toolbox, $plugin) {
        $model = &$toolbox->getModel();

        $action = libraryRequest::getAction();
        $oid = libraryRequest::getOID();
        $data = array();

        $model['PLUGINS']['WRITER']['oid'] = $oid;
        $model['PLUGINS']['WRITER']['action'] = $action;
        $model['PLUGINS']['WRITER']['referer'] = libraryRequest::storeOrGetRefererUrl(false);
        $model['PLUGINS']['WRITER']['INTERNAL_ACTION'] = false;

        if(empty($oid)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
            return;
        }

        if (libraryRequest::isPostFormAction('send message')) {

            echo 'sending message';
            $model['PLUGINS']['WRITER']['INTERNAL_ACTION'] = 'MESSAGE_SEND';
            //return;
        }
        if (libraryRequest::isPostFormAction('order history')) {

            $model['PLUGINS']['WRITER']['INTERNAL_ACTION'] = 'ORDERS_SHOW';
            echo 'order history';
            //return;
        }

        $data = $toolbox->getDatabaseObj()
            ->select('*')
            ->from('writer_messages')
            ->where('ID', '=', $oid)
            ->fetchRow();

        /* get recipients */
        if (!empty($data['StudentID']))
            $data['RECIPIENT']['STUDENTS'] = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_students')
                ->where('ID', '=', $data['StudentID'])
                ->fetchRow();
        if (!empty($data['WriterID']))
            $data['RECIPIENT']['WRITERS'] = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_writers')
                ->where('ID', '=', $data['WriterID'])
                ->fetchRow();
        /* assigned order */
        if (!empty($data['OrderID']))
            $data['RECIPIENT']['ORDERS'] = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_orders')
                ->where('ID', '=', $data['OrderID'])
                ->fetchRow();

        if (empty($data)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
            return;
        }

        $model['PLUGINS']['WRITER']['DATA'] = $data;
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.messages.details'];
    }
    
    /* orders */
    private function _displayOrdersDefault ($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        libraryRequest::storeOrGetRefererUrl();
        $model['PLUGINS']['WRITER'] = libraryComponents::comDataTable($plugin['config']['DATATABLE']['ORDERS'], $toolbox->getDatabaseObj());
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.orders.datatable'];
        
    }
    private function _displayOrdersDetails ($toolbox, $plugin) {
        $model = &$toolbox->getModel();

        $action = libraryRequest::getAction();
        $oid = libraryRequest::getOID();
        $data = array();
        
        // check for token id
        $token = libraryRequest::getValue('token');
        

        $model['PLUGINS']['WRITER']['oid'] = $oid;
        $model['PLUGINS']['WRITER']['action'] = $action;
        $model['PLUGINS']['WRITER']['referer'] = libraryRequest::storeOrGetRefererUrl(false);
        $model['PLUGINS']['WRITER']['INTERNAL_ACTION'] = false;

        // check for empty oid
        if(empty($oid)) {
            // and for token
            if (empty($token)) {
                // set template
                $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
                return;
            }
            $data_order = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_orders')
                ->where('OrderToken', '=', $token)
                ->fetchRow();
            if (empty($data_order['ID'])) {
                // set template
                $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
                return;
            }
            else
                $oid = $data_order['ID']; 
        }

        if (libraryRequest::isPostFormAction('send message')) {
            echo 'sending message';
            $model['PLUGINS']['WRITER']['INTERNAL_ACTION'] = 'MESSAGE_SEND';
            //return;
        }
        if (libraryRequest::isPostFormAction('order history')) {
            $model['PLUGINS']['WRITER']['INTERNAL_ACTION'] = 'ORDERS_SHOW';
            echo 'order history';
            //return;
        }
        if (libraryRequest::isPostFormAction('approve order')) {
            $order_status = array(
                'PublicStatus' => 'PENDING',
                'InternalStatus' => 'APPROVED'
            );
            $toolbox->getDatabaseObj()
                ->reset()
                ->update('writer_orders')
                ->set($order_status)
                ->where('ID', '=', $oid)
                ->query();
            
            $customer_config_mdbc = $toolbox->getCustomerObj()->getCustomerConfiguration('MDBC');
            /* save internal message */
            $message['Subject'] = 'Order Was Approved To Review.';
            $message['Message'] = libraryUtils::arrayHtmlDump($order_status);
            $message['OrderID'] = $oid;
            $message['Owner'] = 'SYSTEM';
            $message['DateCreated'] = date($customer_config_mdbc['DB_DATE_FORMAT']);
            $toolbox->getDatabaseObj()
                ->reset()
                ->insertInto('writer_messages')
                ->fields(array_keys($message))
                ->values(array_values($message))
                ->query();
            
        }
        if (libraryRequest::isPostFormAction('update order')) {
            //echo 'saving order';
            
            $order_new_changes = array(
                'PublicStatus' => libraryRequest::getPostValue('set_order_publicstatus'),
                'InternalStatus' => libraryRequest::getPostValue('set_order_internalstatus'),
                'Credits' => libraryRequest::getPostValue('order_credits')
            );
            
            $toolbox->getDatabaseObj()
                ->update('writer_orders')
                ->set($order_new_changes)
                ->where('ID', '=', $oid)
                ->query();
            
            $customer_config_mdbc = $toolbox->getCustomerObj()->getCustomerConfiguration('MDBC');
            /* save internal message */
            $message['Subject'] = 'Order Was Updated.';
            $message['Message'] = libraryUtils::arrayHtmlDump($order_new_changes);
            $message['OrderID'] = $oid;
            $message['Owner'] = 'SYSTEM';
            $message['DateCreated'] = date($customer_config_mdbc['DB_DATE_FORMAT']);
            $toolbox->getDatabaseObj()
                ->reset()
                ->insertInto('writer_messages')
                ->fields(array_keys($message))
                ->values(array_values($message))
                ->query();
            
        }

        // get order record
        $data_order = $toolbox->getDatabaseObj()
            ->select('*')
            ->from('writer_orders')
            ->where('ID', '=', $oid)
            ->fetchRow();
        
        // get writer info
        $currentWriterID = $data_order['WriterID'];
        if (libraryRequest::isPostFormAction('assign to writer'))
            $currentWriterID = libraryRequest::getPostValue('assign_order_to');
        $data_writer = $toolbox->getDatabaseObj()
            ->select('*')
            ->from('writer_writers')
            ->where('ID', '=', $currentWriterID)
            ->fetchRow();
        
        if (libraryRequest::isPostFormAction('assign to writer')) {
            $toolbox->getDatabaseObj()
                ->update('writer_orders')
                ->set(array('WriterID' => $currentWriterID))
                ->where('ID', '=', $oid)
                ->query();
            
            $customer_config_mail = $toolbox->getCustomerObj()->GetCustomerConfiguration('MAIL');
            $customer_config_mdbc = $toolbox->getCustomerObj()->getCustomerConfiguration('MDBC');
                
            /* save internal message */
            if ($currentWriterID == 0) {
                $message['Subject'] = 'Order Was Unassigned.';
                $message['Message'] = 'Sent to Task Queue';
            } else {
                $message['Subject'] = 'Order Was Assigned To Writer.';
                $message['Message'] = 'Assigned To: <a href="writer.html?display=writers&action=details&oid='.$currentWriterID.'">' . $data_writer['Name'] . '</a>';
            }
            $message['OrderID'] = $oid;
            $message['Owner'] = 'SYSTEM';
            $message['DateCreated'] = date($customer_config_mdbc['DB_DATE_FORMAT']);
            $toolbox->getDatabaseObj()
                ->reset()
                ->insertInto('writer_messages')
                ->fields(array_keys($message))
                ->values(array_values($message))
                ->query();
            
            /* NOTIFY WRITER ABOUT NEW ASSIGNMENT */
            // when $new_writer_id == 0 - then order is unassigned
            if ($currentWriterID != 0 && false) {
                // get new writer info
                
                // form email object
                $recipient = $customer_config_mail['NOTIFY'];
                $recipient['TO'] = $data_writer['Email'];
                $recipient['NAME'] = $data_writer['Name'];
                $recipient['SUBJECT'] = 'New order is assigned to you';
                $recipient['DATA'] = array(
                    'DateDeadline' => libraryUtils::subDateHours($data_order['DateDeadline'], 2, $customer_config_mdbc['DB_DATE_FORMAT']),
                    'HoursLeft' => libraryUtils::getDateTimeHoursDiff($data_order['DateDeadline']) - 2,
                    'TargetUrl' => $customer_config_mail['URLS']['LOGIN'],
                    'SupportEmail' => $customer_config_mail['SUPPORT']['EMAIL']
                );
                // get html message
                $libView = new libraryView();
                $recipient['MESSAGE'] = $libView->getTemplateResult($recipient, $plugin['templates']['mail.writers.assignment']);
                //var_dump($recipient);
                // send email message
                libraryMailer::sendEMail($recipient);
            }
            
        }
        
        $data_invoice_order = array();
        if (!empty($data_order['OrderToken']))
            $data_invoice_order = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_invoices')
                ->where('merchant_order_id', '=', $data_order['OrderToken'])
                ->fetchRow();
        
        $data_invoice_refund = array();
        if (!empty($data_order['RefundToken']))
            $data_invoice_refund = $toolbox->getDatabaseObj()
                ->select('*')
                ->from('writer_invoices')
                ->where('merchant_order_id', '=', $data_order['RefundToken'])
                ->fetchRow();
        
        $data_messages = $toolbox->getDatabaseObj()
            ->select('*')
            ->from('writer_messages')
            ->where('OrderID', '=', $oid)
            ->orderBy('DateCreated')
            ->order('DESC')
            ->fetchData();
        
        $data_student = $toolbox->getDatabaseObj()
            ->select('*')
            ->from('writer_students')
            ->where('ID', '=', $data_order['StudentID'])
            ->fetchRow();
        
        $data_price = $toolbox->getDatabaseObj()
            ->select('*')
            ->from('writer_prices')
            ->where('ID', '=', $data_order['PriceID'])
            ->fetchRow();
        
        $data_writers = $toolbox->getDatabaseObj()
            ->select('ID', 'Name')
            ->from('writer_writers')
            ->fetchData();
        
        $data_sources = $toolbox->getDatabaseObj()
            ->select('SourceURL')
            ->from('writer_sources')
            ->where('OrderToken', '=', $data_order['OrderToken'])
            ->fetchData();
        
        $data_document = $toolbox->getDatabaseObj()
            ->select('Name')
            ->from('writer_documents')
            ->where('ID', '=', $data_order['DocumentID'])
            ->fetchRow();
        
        $data_subject = $toolbox->getDatabaseObj()
            ->select('Name')
            ->from('writer_subjects')
            ->where('ID', '=', $data_order['SubjectID'])
            ->fetchRow();
        
        $isWriterEmpty = empty($data_writer);// && !empty($data_order['WriterID']);

        if (empty($data_order)) {
            // set template
            $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['state.error'];
            return;
        }
        
        // dropdown menus
        $_dr_publicStatus = $toolbox->getDatabaseObj()->getEnumValues('writer_orders', 'PublicStatus');
        $_dr_internalStatus = $toolbox->getDatabaseObj()->getEnumValues('writer_orders', 'InternalStatus');
        
        //var_dump($_dr_publicStatus);
        

        $model['PLUGINS']['WRITER']['DATA'] = $data_order;
        $model['PLUGINS']['WRITER']['DATA_P_STAT'] = $_dr_publicStatus;
        $model['PLUGINS']['WRITER']['DATA_I_STAT'] = $_dr_internalStatus;
        $model['PLUGINS']['WRITER']['DATA_DOCUMENT'] = $data_document;
        $model['PLUGINS']['WRITER']['DATA_SUBJECT'] = $data_subject;
        $model['PLUGINS']['WRITER']['DATA_MESSAGES'] = $data_messages;
        $model['PLUGINS']['WRITER']['DATA_STUDENT'] = $data_student;
        $model['PLUGINS']['WRITER']['DATA_WRITER'] = $data_writer;
        $model['PLUGINS']['WRITER']['DATA_PRICE'] = $data_price;
        $model['PLUGINS']['WRITER']['DATA_INVOICE_ORDER'] = $data_invoice_order;
        $model['PLUGINS']['WRITER']['DATA_INVOICE_REFUND'] = $data_invoice_refund;
        $model['PLUGINS']['WRITER']['TO_LIST'] = $data_writers;
        $model['PLUGINS']['WRITER']['DATA_SOURCES'] = $data_sources;
        $model['PLUGINS']['WRITER']['DATA_WRITER_REMOVED'] = $isWriterEmpty;
        $model['PLUGINS']['WRITER']['template'] = $plugin['templates']['page.orders.details'];
    }

    /* cross methods */
    public function cross_method ($params = false) {
        if (empty($params['fn']))
            return false;
        //echo '<br>calling method:' . $params['fn'] . '<br>';
        $fRez = null;
        switch($params['fn']) {
            case 'useremoval': {
                $fRez = $this->cross_useremoval($params);
                break;
            }
            case '2co_product': {
                $fRez = $this->cross_2co_product($params);
                break;
            }
            case '2co_import': {
                $fRez = $this->cross_2co_import($params);
                break;
            }
            case '2co_product_list': {
                $fRez = $this->cross_2co_product_list($params);
                break;
            }
        }
        return $fRez;
    }
    
    private function cross_useremoval ($params = false) {
        //echo 'cross_useremoval<br><br><br>';
        if (empty($params['dbo']))
            return false;
        
        $dbo = $params['dbo'];
        $dbo->reset()
            ->stopSanitize()
            ->deleteFrom('writer_students')
            ->where('IsTemporary', '=', 1)
            ->andWhere('DateCreated', '<', 'NOW() - INTERVAL 3 DAY')
            ->query();
        
        //var_dump($exAccounts);
        //echo '<pre>' . print_r($exAccounts, true) . '</pre>';
        return true;
    }
    
    private function cross_2co_product ($params) {
        
        echo 'inside cross_2co_product';
        
        //var_dump($params);
        
        if (empty($params['ACCOUNT']))
            return 'ACCOUNT_EMPTY';
        
        if (empty($params['ACCOUNT']['API']))
            return 'API_UNDEFINED';
        
        if (empty($params['DATA']['ORDER']))
            return 'ORDER_EMPTY';
        
        if (empty($params['DATA']['PRICE']))
            return 'PRICE_EMPTY';
        
        $account_api = $params['ACCOUNT']['API'];
        $order = $params['DATA']['ORDER'];
        
        if (empty($order['PriceID']) || empty($order['Pages']))
            return 'PRICE_OR_PAGE_EMPTY';
        
        // make product id
        $checkoutPID = $order['PriceID'].$order['Pages'];
        
        // add realm
        if (!empty($params['REALM']))
            $checkoutPID = $params['REALM'].$checkoutPID;
        
        // get 2checkout products 
        $ch = curl_init($account_api['METHODS']['list_products'] . '?vendor_product_id=' . $checkoutPID);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $account_api['USER'].':'.$account_api['PWD']);
        $output = curl_exec($ch);
        curl_close($ch);

        // convert responce to native array
        $coResponse = json_decode($output, true);
        //echo '<pre>' . print_r($coResponse, true) . '</pre>';

        // return error code if occured
        if(isset($coResponse['errors'][0]['code'])) {
            //echo 'Vendor Product ID ' . $checkoutPID;
            $code = $coResponse['errors'][0]['code'];
            // return code if we do not create new product
            if ($code === 'RECORD_NOT_FOUND' && empty($params['CREATE_IF_EMPTY']))
                return $code;
        }
        
        $isAdded = ($coResponse['response_code'] === 'OK' && count($coResponse['products']));

        // skip adding new product
        if ($isAdded || empty($params['CREATE_IF_EMPTY'])) {
            
            if ($isAdded)
                return $coResponse['products'][0];
            
            // return responce code 
            if (!empty($coResponse['response_code']))
                return $coResponse['response_code'];
            return false;
        }
        
        // create new product on demand
        $product = array(
            'name' => $params['DATA']['PRICE']['Name'],
            'vendor_product_id' => $checkoutPID,
            'category_id' => '15',
        );
        //product price
        if (!empty($order['Suma']))
            $product['price'] = $order['Suma'];
        else
            $product['price'] = ($params['DATA']['PRICE']['Price'] * $order['Pages']);
        // product description
        $description = 'Essay.'.PHP_EOL;
        if (!empty($params['DATA']['PRICE']))
            $description .= trim($params['DATA']['PRICE']['Name']) . '.' . PHP_EOL;
        /*
        if (!empty($params['DATA']['SUBJECT']))
            $description .= $params['DATA']['SUBJECT']['Name'] . PHP_EOL;
        if (!empty($params['DATA']['DOCUMENT']))
            $description .= $params['DATA']['DOCUMENT']['Name'] . PHP_EOL;
        */
        $description .= 'Total pages: ' . $order['Pages'];
        $product['description'] = $description;
        $product['long_description'] = $description;

        // create post data
        $_postData = '';
        foreach ($product as $key => $val)
            $_postData .= $key . '=' . urlencode(trim($val)) . '&';

        //echo '<br>API POST DATA: ' . $_postData;
        
        //echo "https://www.2checkout.com/api/products/create_product?" . implode('&', $product); 
        $ch = curl_init($account_api['METHODS']['create_product']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $account_api['USER'].':'.$account_api['PWD']);
        $output = curl_exec($ch);
        curl_close($ch);
        
        // convert responce to native array
        $coResponse = json_decode($output, true);
        //echo '<pre>' . print_r($coResponse, true) . '</pre>';
        
        // return error code if occured
        if(isset($coResponse['errors'][0]['code']))
            return $coResponse['errors'][0]['code'];

        // return responce code
        //if (!empty($coResponse['response_code']))
        //    return $coResponse['response_code'];
        
        return $coResponse;
    }
    
    private function cross_2co_import () {
        // import all price types 
        // we'll delete product native serivice page
        return false;
    }
    
    private function cross_2co_product_list ($params) {
        echo 'inside cross_2co_product';
        
        //var_dump($params);
        
        if (empty($params['ACCOUNT']['API']))
            return 'API_UNDEFINED';
        
        if (empty($params['PRODUCT_ID']))
            return 'PRODUCT_ID_EMPTY';

        $account_api = $params['ACCOUNT']['API'];
 
        // get 2checkout products 
        $ch = curl_init($account_api['METHODS']['list_products'] . '?vendor_product_id=' . $params['PRODUCT_ID']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $account_api['USER'].':'.$account_api['PWD']);
        $output = curl_exec($ch);
        curl_close($ch);
        
        // convert responce to native array
        $coResponse = json_decode($output, true);
        
        // return error code if occured
        if(isset($coResponse['errors'][0]['code'])) {
            //echo 'Vendor Product ID ' . $checkoutPID;
            $code = $coResponse['errors'][0]['code'];
            // return code if we do not create new product
            if ($code === 'RECORD_NOT_FOUND' && empty($params['CREATE_IF_EMPTY']))
                return $code;
        }
        
        $isAdded = ($coResponse['response_code'] === 'OK' && count($coResponse['products']));

        // skip adding new product
        if ($isAdded) {
            return $coResponse['products'][0];
        } 
        
        return $coResponse['response_code'];
    }
}


?>
