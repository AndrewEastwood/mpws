<?php

class pluginEditor {

    public function main($toolbox, $plugin) {
        //echo '<br> ******* EDITOR  ****** ';
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
        if ($model['USER']['ACTIVE'] && !empty($model['PLUGINS']['EDITOR']['COM'])) {
            foreach ($model['PLUGINS']['EDITOR']['COM'] as $key => $component)
                $model['html']['editor']['com'][strtolower($key)] = $libView->getTemplateResult($model, $model['PLUGINS']['EDITOR']['COM'][$key]['template']);
            $model['html']['menu'] .= $model['html']['editor']['com']['menu'];
        }
        
        //var_dump($model['html']['menu']);
        
        /* set html data */
        $model['html']['content'] .= $libView->getTemplateResult($model, $model['PLUGINS']['EDITOR']['template']);
    }

    public function layout($toolbox, $plugin) {
        
        //var_dump();
        
        //echo '***EDITOR LAYOUT***';
        $libView = new libraryView();
        $model = &$toolbox->getModel();
        return $libView->getTemplateResult($model, $plugin['templates']['layout']);
    }
    
    public function api($toolbox, $plugin) {
    }
    
    /* components */
    private function _componentMenu($toolbox, $plugin) {
        //echo '<br> ******* EDITOR _componentMenu  ****** ';
        $model = &$toolbox->getModel();
        $model['PLUGINS']['EDITOR']['COM']['MENU']['template'] = $plugin['templates']['component.menu'];
        //echo $plugin['templates']['component.menu'];
        //echo $model['PLUGINS']['EDITOR']['COM']['MENU']['template'];
        
    }
    
    /* display triggers */
    private function _displayTriggerOnActive($toolbox, $plugin) {
        $_SESSION['MPWS_PLUGIN_ACTIVE'] = 'EDITOR';

        switch (libraryRequest::getDisplay('home')) {
            case 'content' : {
                $this->_displayContent($toolbox, $plugin);
                break;
            }
            case 'live' : {
                // /?action=edit&inner-session=<?=md5('SecretKey!&$f_%')
                $sk = 'SecretKey!&$f_%';
                $innerkey = md5(MPWS_CUSTOMER . $sk);
                setcookie("MPWS_LIVE_EDIT", $innerkey, time()+3600, "/", MPWS_CUSTOMER);
                $map = array(
                    'action' => 'edit',
                    'inner-session' => $innerkey
                );
                libraryRequest::locationRedirect($map, 'http://' . MPWS_CUSTOMER);
                break;
                
            }
        }

    }
    private function _displayTriggerOnInActive($toolbox, $plugin) {
    }
    private function _displayTriggerOnCommonStart($toolbox, $plugin) {
        //echo 'Editor _displayTriggerOnCommonStart';
        /*$model = &$toolbox->getModel();
        
        // editor tools
        $tools = array(
            '/?action=edit&inner-session=' . md5('SecretKey!&$f_%') => 'Live Site Edit Mode'
        );
        
        arrExtend($model['html']['TOOLS'], $tools);*/
    }
    private function _displayTriggerOnCommonEnd($toolbox, $plugin) {
        /* init components */
        $this->_componentMenu($toolbox, $plugin);
    }
    
    /* display */
    private function _displayContent($toolbox, $plugin, $postaction = '') {
        //echo '_displayContent';
        $action = libraryRequest::getAction();
        if (!empty($postaction))
            $action = $postaction;
        $innerAction = '';
        switch($action) {
            case 'edit':
            case 'create':
                $innerAction = $this->_displayContentEdit($toolbox, $plugin);
                break;
            default:
                $innerAction = $this->_displayContentDefault($toolbox, $plugin);
                break;
        }

        if (!empty($innerAction) && strcasecmp($innerAction, $action) != 0)
            $this->_displayContent($toolbox, $plugin, $innerAction);
    }

    /* content */
    private function _displayContentDefault ($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        libraryRequest::storeOrGetRefererUrl();
        $model['PLUGINS']['EDITOR'] = libraryComponents::comDataTable($plugin['config']['DATATABLE']['CONTENT'], $toolbox->getDatabaseObj());
        $model['PLUGINS']['EDITOR']['template'] = $plugin['templates']['page.content.datatable'];
        
    }
    private function _displayContentEdit ($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        $messages = array();
        //var_dump($plugin);
        $action = libraryRequest::getAction();
        $oid = libraryRequest::getOID();
        $data = array();
        $model['PLUGINS']['EDITOR']['oid'] = $oid;
        $model['PLUGINS']['EDITOR']['action'] = $action;
        $model['PLUGINS']['EDITOR']['referer'] = libraryRequest::storeOrGetRefererUrl(false);
        
        if (libraryRequest::isPostFormAction('save')) {
            $data = libraryRequest::getPostMapContainer($plugin['config']['VALIDATOR']['DATAMAP']['CONTENT']);
            
            //var_dump($data);
            
            /* validate fileds */
            libraryValidator::validateData($data, $plugin['config']['VALIDATOR']['FILTER']['CONTENT'], $messages);
            // if ok to proceed
            //echo 'olololo';
            if (empty($messages)) {
                if ($action === 'edit') {
                    // check if oid is not empty
                    if (empty($oid)) {
                        // set template
                        $model['PLUGINS']['EDITOR']['template'] = $plugin['templates']['state.error'];
                        return;
                    }
                    $toolbox->getDatabaseObj()
                        ->update('editor_content')
                        ->set($data)
                        ->where('ID', '=', $oid)
                        ->query();
                    // force return to home page
                    return 'home';
                } elseif ($action === 'create') {
                    $toolbox->getDatabaseObj()
                        ->insertInto('editor_content')
                        ->fields(array_keys($data))
                        ->values(array_values($data))
                        ->query();
                    // force return to home page
                    return 'home';
                } else
                    $model['PLUGINS']['EDITOR']['template'] = $plugin['templates']['state.error'];
                return;
            } else
                $model['PLUGINS']['EDITOR']['messages'] = $messages;
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
                ->from('editor_content')
                ->where('ID', '=', $oid)
                ->fetchRow();
            if (empty($document)) {
                // set template
                $model['PLUGINS']['EDITOR']['template'] = $plugin['templates']['state.error'];
                return;
            }
            if (!libraryRequest::isPostFormActionMatchAny('save'))
                $data = $document;
        }
        if ($action === 'create') {
            
        }

        // set template
        $model['PLUGINS']['EDITOR']['DATA'] = $data;
        $model['PLUGINS']['EDITOR']['template'] = $plugin['templates']['page.content.create'];
    }
}

?>