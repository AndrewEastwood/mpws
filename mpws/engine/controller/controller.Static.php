<?php

class controllerStatic {

    public function processRequests() {

        // echo $_GET['type'];

        $content = '';
        switch (strtolower($_GET['type'])) {
            case 'js':
            case 'css':
            case 'hbs':
                $content = $this->_ieText();
                break;
            case 'jpg':
            case 'gif':
            case 'png':
                $content = $this->_ieImage();
                break;
            case 'woff':
            case 'svg':
            case 'eot':
            case 'ttf':
                $content = $this->_ieFont();
                break;
            // case 'wysiwyg' : {
            //     $content = $this->_ieWYSIWYG();
            //     return;
            // }
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

    // private function _ieWYSIWYG () {
    //     $staticResMgr = new libraryStaticResourceManager();
    //     $_module_path = 'plugins/' . $_GET['name'] . '/' . $_GET['path'] . '.' . $_GET['type'];
    //     $filePath = $staticResMgr->GetContent(urldecode($_module_path), $_GET['realm']);
        
    //     if (empty($filePath))
    //         return false;
        
    //     switch ($_GET['type']) {
    //         case 'htm':
    //             header("Content-type: text/html");
    //             return readfile($filePath);
    //         case 'css':
    //             header("Content-type: text/css");
    //             return readfile($filePath);
    //         case 'js':
    //             header("Content-type: text/javascript");
    //             return readfile($filePath);  
    //         case 'jpg':
    //             header('Content-type: image/jpg');
    //             header('Content-Length: ' . filesize($filePath));
    //             return readfile($filePath);
    //         case 'gif':
    //             header('Content-type: image/gif');
    //             header('Content-Length: ' . filesize($filePath));
    //             return readfile($filePath);
    //         case 'png':
    //             header('Content-type: image/png');
    //             header('Content-Length: ' . filesize($filePath));
    //             return readfile($filePath);
    //     }
    // }
    private function _ieImage() {
        $staticResMgr = new libraryStaticResourceManager();
        $filePath = $staticResMgr->GetContent();
        if (empty($filePath))
            return false;

        switch (strtolower($_GET['type'])) {
            case 'jpg':
                header('Content-type: image/jpg');
                break;
            case 'gif':
                header('Content-type: image/gif');
                break;
            case 'png':
                header('Content-type: image/png');
                break;
        }
        //     //$im = imagecreatefromjpeg($filePath);
        // header('Content-type: image/jpg');
        // header('Content-Length: ' . filesize($filePath));
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
            case 'hbs':
                header("Content-type: text/x-handlebars-template");
                break;
        }
        return $staticResMgr->GetStaticContent();
    }

    private function _ieFont() {
        // echo 1;
        $staticResMgr = new libraryStaticResourceManager();
        $filePath = $staticResMgr->GetContent();
        if (empty($filePath))
            return false;

        switch (strtolower($_GET['type'])) {
            case 'woff':
                header('Content-type: application/x-font-woff');
                break;
            case 'eot':
                header('Content-type: application/octet-stream');
                break;
            case 'ttf':
                header('Content-type: application/octet-stream');
                break;
        }
        return readfile($filePath);
    }

}

?>
