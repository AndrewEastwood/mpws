<?php

    // loader
    function __autoload($className)
    {
        //echo 'loading ' . $className;

        if (empty($className))
            return false;

        // all libs must have name at least 4 chars
        if (strlen($className) < 3)
            return false;

        $libPath = '';
        $DR = $_SERVER['DOCUMENT_ROOT'];
        $pieces = preg_split('/(?=[A-Z])/', $className, -1);

        if (count($pieces) <= 1)
            throw new Exception('Wrong loading library name.');
        else {
            switch (strtolower($pieces[0])) {
                case 'controller': {
                    $libPath = '/engine/controller/controller.';
                    break;
                }
                case 'library': {
                    $libPath = '/engine/system/library/library.';
                    break;
                }
            }
            unset($pieces[0]);
            $libPath = $DR . $libPath . implode('', $pieces) . '.php';
            //echo $libPath;
            if (file_exists($libPath))
                require_once $libPath;
            else
                throw new Exception('Requested library ('.$libPath.') does not exist.');
        }
    }

?>
