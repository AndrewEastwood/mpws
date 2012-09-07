<?php


// startup context
class contextMPWS {
    
    private $_contexts;
    private $_commands;
    
    public function __construct () {
        $this->_contexts = array();
        $this->_commands = array();
    }
    
    public function __get($contextName) {
        if (startsWith($contextName, OBJECT_T_CONTEXT))
            return $this->getContext(str_replace(OBJECT_T_CONTEXT, '', $contextName));
    }
    
    private function loadContext($name) {
        
        
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
    
    public function getContext($name) {
        if (empty($this->_contexts[makeKey($name)]))
            $this->loadContext($name);
        
        return $this->_contexts[makeKey($name)];
    }
    
    public function addBatchCommands ( /* commands */ ) {
        $fn_args = getArguments(func_get_args());
        if (is_string($fn_args)) {
            $this->addCommand($fn_args);
            
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
    public function addCommand ($command, $custom_args = array()) {
        if (!is_string($command))
            throw new Exception('MPWS addCommand Setup Error. Wrong Command Object');
        $_cmd = $this->makeCommand($command, $custom_args);
        $this->_commands[$_cmd[makeKey('id')]] = $_cmd;
    }
    
    public function modifyCommand ($command, $custom_args = array()) {
        if (isset($this->_commands[$command])){
            $this->_commands[$command][makeKey('arguments')] = $custom_args;
        }
    }
    
    
    public function traceCommands () {
        foreach ($this->_commands as $cmd)
            echo '<pre>' . print_r($cmd, true) . '</pre>';
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
            // run commad
            $ctx->call($this, $cmd);
            // remove current command
            $this->_commands[$id] = null;
        }
        // cleanup all commands
        $this->_commands = array();
    }
    
    
}

?>