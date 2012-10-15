<?php

class controllerStatic {

    public function processRequests() {

        $content = '';
        switch (strtolower($_GET['request'])) {
            case 'text' : {
                $content = $this->_ieText();
                break;
            }
            case 'image' : {
                $content = $this->_ieImage();
                return;
            }
            case 'wysiwyg' : {
                $content = $this->_ieWYSIWYG();
                return;
            }
        }
        switch (strtolower($_GET['action'])) {
            case 'default' :
            default: { 
                echo $content;
                break;
            }
        }
    }

    /* internal executors */

    private function _ieWYSIWYG () {
        $staticResMgr = new libraryStaticResourceManager();
        $_module_path = 'plugins/' . $_GET['name'] . '/' . $_GET['path'] . '.' . $_GET['type'];
        $filePath = $staticResMgr->GetContent(urldecode($_module_path), $_GET['realm']);
        
        if (empty($filePath))
            return false;
        
        switch ($_GET['type']) {
            case 'htm':
                header("Content-type: text/html");
                return readfile($filePath);
            case 'css':
                header("Content-type: text/css");
                return readfile($filePath);
            case 'js':
                header("Content-type: text/javascript");
                return readfile($filePath);  
            case 'jpg':
                header('Content-type: image/jpg');
                header('Content-Length: ' . filesize($filePath));
                return readfile($filePath);
            case 'gif':
                header('Content-type: image/gif');
                header('Content-Length: ' . filesize($filePath));
                return readfile($filePath);
            case 'png':
                header('Content-type: image/png');
                header('Content-Length: ' . filesize($filePath));
                return readfile($filePath);
        }
    }
    private function _ieImage() {
        $staticResMgr = new libraryStaticResourceManager();
        $filePath = $staticResMgr->GetContent(urldecode($_GET['name']).'.'.strtolower($_GET['type']), $_GET['realm']);
        if (empty($filePath))
            return false;
            //$im = imagecreatefromjpeg($filePath);
        header('Content-type: image/jpg');
        header('Content-Length: ' . filesize($filePath));
        return readfile($filePath);
        //echo 'ololo' . urldecode($_GET['name']);
        //imagejpeg($im);
        //return $content;
        // Free up memory
        //imagedestroy($im);
    }
    private function _ieText() {
        $staticResMgr = new libraryStaticResourceManager();
        //$resFile = $_GET['name'];
        //$resType = $_GET['type'];
        switch (strtolower($_GET['type'])) {
            case 'css':
                header("Content-type: text/css");
                break;
            case 'js':
                header("Content-type: text/javascript");
                break;
        }
        return $staticResMgr->GetStaticContent();
    }

}

?>
