<?php


// startup context
class contextMPWS {
    
    private $_contexts;
    private $_commands;
    private $_runningContextName;
    private static $_instance;
    
    private function __construct () {
        $this->_contexts = array();
        $this->_commands = array();
    }
    
    public function __get($name) {
        // get context object
        if (startsWith($name, OBJECT_T_CONTEXT))
            return $this->getContext(str_replace(OBJECT_T_CONTEXT, '', $name));
        if ($name === 'pageModel')
            return $this->getPageModel();
    }
    
    public static function instance () {
        if (empty(self::$_instance))
            self::$_instance = new contextMPWS();
        return self::$_instance;
    }
    
    /* private */
    
    private function loadContext ( /* names */ ) {
        $fn_args = getArguments(func_get_args());
        if (is_string($fn_args)) {
            $contextObjectName = OBJECT_T_CONTEXT.$fn_args;
            // load or throw exception
            //echo '<br>MPWS Loads context: "' . $contextObjectName . '"';
            //$_ctx;
            //var_dump($_ctx);
            $this->_contexts[makeKey($fn_args)] = new $contextObjectName(); 
            return $this->_contexts[makeKey($fn_args)];
            
        } elseif (is_array($fn_args)) {
            foreach ($fn_args as $value) {
                $this->loadContext($value);
            }
        }
    }
    
    private function getCommand ($command, $custom_args = array()) {
        if (!is_string($command))
            throw new Exception('MPWS addCommand Setup Error. Wrong Command Object');
        return $this->makeCommand($command, $custom_args);
    }
    
    private function makeCommand ($stringFn, $custom_args = array()) {
        $cmd = array();
        list($caller, $fn, $context) = explode(DOG, $stringFn);
        
        // adjust arguments
        if (empty($fn) && empty($context)) {
            $fn = $caller;
            $caller = false;
        }

        // setup command owner
        if (empty($caller))
            $cmd[makeKey('caller')] = '*'; // broadcast command
        else
            $cmd[makeKey('caller')] = $caller; // will execute in $caller object
        
        // set context
        // it makes requested comamand crosscontextual that
        // allows to use it inside all defined contexts
        if (empty($context))
            $cmd[makeKey('context')] = false; // unspecified context
        else
            $cmd[makeKey('context')] = $context; // will run inside $context
        
        $cmd[makeKey('method')] = $fn;
        
        // append additional arguments
        if (!empty($custom_args)) {
            $_args = array();
            // reset indexces
            foreach ($custom_args as $val)
                $_args[] = $val;
            $cmd[makeKey('arguments')] = $_args;
        } else 
            $cmd[makeKey('arguments')] = false;
        
        $cmd[makeKey('id')] = $stringFn;
            
        return $cmd;
    }
    
    private function setCurrentContext ($currentContextName) {
        $prev = $this->_runningContextName;
        $this->_runningContextName = $currentContextName;
        return $prev;
    }
    
    private function getPageModel () {
        return libraryWebPageModel::instance();
    }
    
    /* public */
    
    public function getContext ($name) {
        if (empty($this->_contexts[makeKey($name)]))
            $this->loadContext($name);
        
        return $this->_contexts[makeKey($name)];
    }
    
    public function getCurrentContextName () {
        return $this->_runningContextName;
    }
    
    public function getCurrentContext () {
        return $this->getContext($this->_runningContextName);
    }
    
    public function addCommand ( /* commands */ ) {
        $fn_args = getArguments(func_get_args());
        if (is_string($fn_args)) {
            //$this->addCommand($fn_args);
            //var_dump($fn_args);
            $_cmd = false;
            if(func_num_args() == 2 && is_array(func_get_arg(1)))
                $_cmd = $this->getCommand($fn_args, func_get_arg(1));
            else
                $_cmd = $this->getCommand($fn_args);
            $this->_commands[$_cmd[makeKey('id')]] = $_cmd;
        } elseif (is_array($fn_args)) {
            
            // two args: batch commands, config
            if (func_num_args() == 2 &&
                is_array(func_get_arg(0)) &&
                is_array(func_get_arg(1))) {
                
                // common config
                $isCommon = count(func_get_arg(1)) == 1;
                if (!$isCommon && count(func_get_arg(0)) !== count(func_get_arg(1)))
                    throw new Exception('MPWS Context Batch Commands Setup Error. Wrong Configuration Object');
                
                $cmds = func_get_arg(0);
                $cfgs = func_get_arg(1);
                foreach ($cmds as $idx => $value)
                    if ($isCommon)
                        $this->addCommand($value, $cfgs);
                    else
                        $this->addCommand($value, $cfgs[$idx]);
            } else {
                foreach ($fn_args as $value) {
                    $this->addCommand($value);
                }
            }
        }
    }
    
    public function modifyCommand ($command, $custom_args = array()) {
        if (isset($this->_commands[$command])){
            $this->_commands[$command][makeKey('arguments')] = $custom_args;
        }
    }

    public function traceCommands () {
        foreach ($this->_commands as $cmd)
            debug($cmd, 'Context mpws, tracing command:');
    }
    
    public function processAll ($context, $override = false) {
        if (count($this->_commands) == 0)
            return false;
        // run all commands
        foreach ($this->_commands as $id => $cmd) {
            $runningContextName = $context; // use provided constext
            // get command context name
            if (!empty($cmd[makeKey('context')]) && !$override)
                $runningContextName = $cmd[makeKey('context')];
            // preload context
            $ctx = $this->getContext($runningContextName);
            // set current context name
            $prevoiusContextName = $this->setCurrentContext($runningContextName);
            // run commad
            $ctx->call($cmd);
            // restore previus context
            $this->setCurrentContext($prevoiusContextName);
            // remove current command
            $this->_commands[$id] = null;
        }
        // cleanup all commands
        $this->_commands = array();
    }
    
    public function directProcess ($command, $context, $override = false) {
        // prepare command
        $cmd = $this->getCommand($command);
        // use provided constext
        $runningContextName = $context;
        // get command context name
        if (!empty($cmd[makeKey('context')]) && !$override)
            $runningContextName = $cmd[makeKey('context')];
        // preload context
        $ctx = $this->getContext($runningContextName);
        // set current context name
        $prevoiusContextName = $this->setCurrentContext($runningContextName);
        // run commad
        $ctx->call($cmd);
        // restore previus context
        $this->setCurrentContext($prevoiusContextName);
        
    }
}

?>