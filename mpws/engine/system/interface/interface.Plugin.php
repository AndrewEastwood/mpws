<?php

interface iPlugin {
    
    /* setup */
    public function setup ($owner);
    
    /* initail */
    public function getConfiguration ($name, $key);
    public function getTemplate ($name);
    
    /* running */
    public function runAction ($name, $context);

    /* perform */
    public function main();
    public function layout();
    public function render();
    public function api();
    public function cross();

}

?>