<?php

class pluginEditor {

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
        if ($model['USER']['ACTIVE'] && !empty($model['PLUGINS']['EDITOR']['COM'])) {
            foreach ($model['PLUGINS']['EDITOR']['COM'] as $key => $component)
                $model['html']['edtor']['com'][strtolower($key)] = $libView->getTemplateResult($model, $model['PLUGINS']['EDITOR']['COM'][$key]['template']);
            $model['html']['menu'] .= $model['html']['editor']['com']['menu'];
        }
        
        /* set html data */
        $model['html']['content'] .= $libView->getTemplateResult($model, $model['PLUGINS']['EDITOR']['template']);
    }

    public function layout($toolbox, $plugin) {
        //echo '***WRITER LAYOUT***';
        $libView = new libraryView();
        $model = &$toolbox->getModel();
        return $libView->getTemplateResult($model, $plugin['templates']['layout']);
    }
    
    public function api($toolbox, $plugin) {
    }
    
    /* components */
    private function _componentMenu($toolbox, $plugin) {
        $model = &$toolbox->getModel();
        $model['PLUGINS']['EDITOR']['COM']['MENU']['template'] = $plugin['templates']['component.menu'];
    }
    
    /* display triggers */
    private function _displayTriggerOnActive($toolbox, $plugin) {
    }
    private function _displayTriggerOnInActive($toolbox, $plugin) {
    }
    private function _displayTriggerOnCommonStart($toolbox, $plugin) {
        //echo 'Editor _displayTriggerOnCommonStart';
        /*$model = &$toolbox->getModel();
        
        // editor tools
        $tools = array(
            '/?action=edit&inner-session=' . md5('SecretKey!&$f_%') => 'Edit Static Content'
        );
        
        arrExtend($model['html']['TOOLS'], $tools);*/
    }
    private function _displayTriggerOnCommonEnd($toolbox, $plugin) {
        /* init components */
        $this->_componentMenu($toolbox, $plugin);
    }
    
}

?>