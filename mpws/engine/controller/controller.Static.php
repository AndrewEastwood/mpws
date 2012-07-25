<?php

class controllerStatic {

    public function processRequests() {
        //var_dump($_SERVER);
        //var_dump($_GET);
        // check resource accesibility
        if (!$this->requestAccesibility()) {
            //echo 'denided';
            return false;
        }
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

    private function requestAccesibility() {
        //echo '|session realm is ' . $_SESSION['MPWS-REALM'];
        //return true;

        $r = strtolower($_GET['realm']);
        //echo '|request realm is ' . $r;
        //echo '|session realm is ' . $_SESSION['MPWS-REALM'];
        //var_dump($_GET);
        // for loggined administrators

        /* if (/*$_SESSION['MPWS-REALM'] === 'TOOLBOX' && * /isset($_SESSION['MPWS-AUTHORIZED']) && $_SESSION['MPWS-AUTHORIZED'] === true)
            return true;

        //echo 'realm is ' . $r;

        if ($r === 'toolbox' && isset($_SESSION['MPWS-AUTHORIZED']) && $_SESSION['MPWS-AUTHORIZED'] === false)
            return false;
        if ($r === 'mpws' || $r === 'common')
            return true; */
        
        //if ($r == 'common' && $SESSION['MPWS-REALM'])
        return true;
    }

    /* internal executors */
    private function _ieWYSIWYG () {
        $staticResMgr = new libraryStaticResourceManager();
        $_module_path = 'plugins/' . $_GET['name'] . '/' . $_GET['path'] . '.' . $_GET['type'];
        $filePath = $staticResMgr->GetContent(urldecode($_module_path), $_GET['realm']);
        
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
        $content = '';
        switch (strtolower($_GET['type'])) {
            case 'css':
                header("Content-type: text/css");
                $content = $staticResMgr->GetStylesheetContent($_GET['realm']);
                break;
            case 'js':
                header("Content-type: text/javascript");
                $content = $staticResMgr->GetJavascriptContent($_GET['realm']);
                break;
        }
        return $content;
    }

}

?>
