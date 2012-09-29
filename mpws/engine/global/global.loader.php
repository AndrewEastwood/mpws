<?php

    spl_autoload_register('mpws__autoload');
    
    // mpws loader
    function mpws__autoload($className)
    {
        //echo 'loading ' . $className;

        if (empty($className))
            return false;

        // all libs must have name at least 4 chars
        if (strlen($className) < 3)
            return false;

        $libPath = '';
        //$DR = $_SERVER['DOCUMENT_ROOT'];
        $pieces = preg_split('/(?=[A-Z])/', $className, -1);
        $libName = implode('', array_slice($pieces, 1));

        if (count($pieces) <= 1) {
            //throw new Exception('Wrong loading library name: ' . $className);
            $libPath = '/engine/system/class/' . $className;
        } else {
            switch (strtolower($pieces[0])) {
                case 'controller': {
                    $libPath = '/engine/controller/controller.';
                    break;
                }
                case 'library': {
                    $libPath = '/engine/system/library/library.';
                    break;
                }
                case 'object': {
                    $libPath = '/engine/system/object/object.';
                    break;
                }
                case 'context': {
                    $libPath = '/engine/system/context/context.';
                    break;
                }
                case 'i': {
                    $libPath = '/engine/system/interface/interface.';
                    break;
                }
                case 'extension': {
                    //echo 'Including extension ' . $libName;
                    $libPath = '/engine/system/extension/extension.';
                    break;
                }
                default: {
                    $libPath = '/engine/system/class/' . $pieces[0];
                    break;
                }
            }

            _import($libPath . $libName . '.php');
        }
    }
    
    function _import ($path) {
        $DR = $_SERVER['DOCUMENT_ROOT'];
        if (startsWith($path, 'extension@')) {
            $extPkg = explode('@', $path);
            $extLibPath = '/engine/system/extension/';
            $extMap = parse_ini_file($DR . $extLibPath . 'extension_map.ini', true);
            //var_dump($extMap);
            // set extension path
            if (!empty($extMap[$extPkg[1]]))
                $path = $extLibPath . $extMap[$extPkg[1]];
        }
        $libPath = $DR . $path;
        //echo '<br> |+++++ ' . $libPath;
        if (!file_exists($libPath))
            ;//throw new Exception('Requested library ('.$libPath.') does not exist.');
        if (file_exists($libPath))
            require_once $libPath;
    }

?>
