<?php

class objectContext {
    
    function __construct () { }
    
    public function call ($command, $runner) {
        
        //debug('objectContext:');
        //debug($command);
        
        throw new Exception('MPWS Base Context Object: you must implmenet call method');
    }
    
}

?>