<?php

class mpwsCommand {
    private $_id;
    private $_caller;
    private $_method;
    private $_inner;
    private $_context;
    private $_arguments;
    
    public function setID($val) { $this->_id = $val; }
    public function setCaller($val) { $this->_caller = $val; }
    public function setMethod($val) { $this->_method = $val; }
    public function setInnerMethod($val) { $this->_inner = $val; }
    public function setContext($val) { $this->_context = $val; }
    public function setArguments($val) { $this->_arguments = $val; }
    
    public function getID() { return $this->_id; }
    public function getCaller() { return $this->_caller; }
    public function getMethod() { return $this->_method; }
    public function getInnerMethod() { return $this->_inner; }
    public function getContext() { return $this->_context; }
    public function getArguments() { return $this->_arguments; }
    
    public function __toString() {
        return 'mpwsCommand: ' . $this->getCaller() . DOG
            . $this->getMethod() . COLON . $this->getInnerMethod() . DOG
            . $this->getContext();
    }
}

?>
