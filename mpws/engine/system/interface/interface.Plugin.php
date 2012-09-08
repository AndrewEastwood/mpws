<?php

interface iPlugin {
    
    /* public api */
    public function run ($command);
    
    /* private structure */
    public function _run_main();
    public function _run_layout();
    public function _run_render();
    public function _run_jsapi();
    public function _run_cross();

}

?>