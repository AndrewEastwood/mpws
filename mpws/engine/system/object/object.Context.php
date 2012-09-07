<?php

class objectContext {
    
    function __construct () { }
    
    public function call ($runner, $command) {
        
        echo 'objectContext: command: <pre>' . print_r($command, true) . '</pre>';
        
        throw new Exception('MPWS Base Context Object: you must implmenet call method');
    }
    
}

?>