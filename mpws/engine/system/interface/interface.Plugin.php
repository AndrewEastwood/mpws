<?php

interface iPlugin {
    
    /* setup */
    public function setup ();
    
    /* initail */
    public function getConfiguration ($key);
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