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
        // var_dump($pieces);
        if (count($pieces) === 3 && empty($pieces[0]))
            array_shift($pieces);
        $libName = implode('', array_slice($pieces, 1));

        // var_dump($pieces);

        if (count($pieces) <= 1) {
            //throw new Exception('Wrong loading library name: ' . $className);
            $libPath = '/engine/system/library/' . $className;
        } else {
            switch ($pieces[0]) {
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
                // case 'context': {
                //     $libPath = '/engine/system/context/context.';
                //     break;
                // }
                case 'I': {
                    $libPath = '/engine/system/interface/interface.';
                    break;
                }
                case 'configuration': {
                    $libName = $pieces[2];
                    $len = count($pieces);

                    // var_dump($pieces);

                    if ($len > 3)
                        for ($i = 3; $i < $len; $i++)
                            $libName .= $pieces[$i];

                    if (strcasecmp($pieces[1], OBJECT_T_CUSTOMER) === 0)
                        $libPath = '/web/customer/' . MPWS_CUSTOMER . '/config/configuration.';
                    elseif (strcasecmp($pieces[1], OBJECT_T_DEFAULT) === 0)
                        $libPath = '/web/default/' . MPWS_VERSION . '/config/configuration.';
                    else
                        $libPath = '/web/plugin/' . strtolower($pieces[1]) . '/config/configuration.';


                    // echo PHP_EOL;
                    // echo PHP_EOL;
                    // echo 'LIBNAME = ' . $libName;
                    // echo PHP_EOL;
                    // echo 'PATH = ' . $libPath;

                    break;
                }
                case 'api': {
                    $libName = $pieces[2];
                    $len = count($pieces);

                    // var_dump($pieces);

                    if ($len > 3)
                        for ($i = 3; $i < $len; $i++)
                            $libName .= $pieces[$i];

                    if (strcasecmp($pieces[1], OBJECT_T_CUSTOMER) === 0)
                        $libPath = '/web/customer/' . MPWS_CUSTOMER . '/api/';
                    elseif (strcasecmp($pieces[1], OBJECT_T_DEFAULT) === 0)
                        $libPath = '/web/default/' . MPWS_VERSION . '/api/';
                    else
                        $libPath = '/web/plugin/' . strtolower($pieces[1]) . '/api/';
                }
                case 'extension': {
                    //echo 'Including extension ' . $libName;
                    $libPath = '/engine/system/extension/extension.';
                    break;
                }
                default: {
                    $libPath = '/engine/system/library/' . $pieces[0];
                    break;
                }
            }

            $libPath = $_SERVER['DOCUMENT_ROOT'] . $libPath . $libName . EXT_SCRIPT;
            if (file_exists($libPath))
                require_once $libPath;
        }
    }

?>
