<?php


// startup context
class contextMPWS {
    
    private $_contexts;
    private $_commands;
    private $_runningContextName;
    private $_processData;
    private $_callStack;
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
        // echo 'Making command of ' . $stringFn;
        $ctxcmd = new mpwsCommand();
        $rawCommand = explode(DOG, $stringFn);
        
        $caller = false;
        $fn = false;
        $context = false;
        
        if (!empty($rawCommand[0]))
            $caller = $rawCommand[0];
        if (!empty($rawCommand[1]))
            $fn = $rawCommand[1];
        if (!empty($rawCommand[2]))
            $context = $rawCommand[2];
        
        // adjust arguments
        if (empty($fn) && empty($context)) {
            $fn = $caller;
            $caller = false;
        }

        // setup command owner
        if (empty($caller))
            $ctxcmd->setCaller('*'); // broadcast command
        else
            $ctxcmd->setCaller($caller); // will execute in $caller object

        // set context
        // it makes requested comamand crosscontextual that
        // allows to use it inside all defined contexts
        if (empty($context))
            $ctxcmd->setContext(false); // unspecified context
        else
            $ctxcmd->setContext($context); // will run inside $context
        
        if (strstr($fn, COLON))
            list($fn, $innerFn) = explode(COLON, $fn);
        
        $ctxcmd->setMethod($fn);
        if (isset($innerFn))
            $ctxcmd->setInnerMethod($innerFn);

        // append additional arguments
        if (!empty($custom_args)) {
            $_args = array();
            // reset indexces
            foreach ($custom_args as $val)
                $_args[] = $val;
            $ctxcmd->setArguments($_args);
        } else 
            $ctxcmd->setArguments(false);
        
        $ctxcmd->setID($stringFn);
            
        return $ctxcmd;
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
    
    /**
     * Adds command into the command stack
     * Accepts commands in the array: array('main', 'test')
     * @throws Exception 
     */
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
            $this->_commands[$_cmd->getID()] = $_cmd;
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
            $commandContextName = $cmd->getContext();
            if (!empty($commandContextName) && !$override)
                $runningContextName = $cmd->getContext();
            // preload context
            debug('context.MPWS > processAll goes with context name >>> ' . $runningContextName);
            $ctx = $this->getContext($runningContextName);
            // set current context name
            $prevoiusContextName = $this->setCurrentContext($runningContextName);
            // save command
            $this->_callStack[] = $cmd;
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
    
    /**
     * Run commant within the provided context
     * 
     * @param string $command
     * @param objectContext $context
     * @param bool $override
     * @return type 
     */
    public function directProcess ($command, $context, $override = false) {
        //echo "MPWS Context directProcess " . $command . '<br>';
        // prepare command
        $cmd = $this->getCommand($command);
        // use provided context
        $runningContextName = $context;
        // get command context name
        $commandContextName = $cmd->getContext();
        if (!empty($commandContextName) && $override)
            $runningContextName = $cmd->getContext();
        // preload context
        $ctx = $this->getContext($runningContextName);
        // set current context name
        $prevoiusContextName = $this->setCurrentContext($runningContextName);
        // save command
        $this->_callStack[] = $cmd;
        // run commad
        //echo "MPWS Context running command <pre>" . print_r($cmd, true) . '</pre>';
        $rez = $ctx->call($cmd);
        // restore previus context
        $this->setCurrentContext($prevoiusContextName);
        // remove process data
        $this->_processData = false;
        return $rez;
    }
    
    // set process data
    public function getProcessData () {
        return $this->_processData;
    }
    public function setProcessData ($data) {
        $this->_processData = $data;
        return $this;
    }
    public function getLastCommand ($pop = true, $returnEmpty = true) {
        if (count($this->_callStack) > 0) {
            if ($pop)
                return array_pop($this->_callStack);
            else
                return end($this->_callStack);
        }
        if($returnEmpty)
            return $this->makeCommand('empty');
        throw new Exception ('contextMPWS: command stack is empty.');
    }
}

?>