

    // static resources
    $default['RESOURCES'] = array();

    $default['RESOURCES']['STATIC'] = Array(
        /* StyleSheets */
        /* IMPORT Group is used for CSS only */
        'toolboxDisplay.css' => Array(
            'DEFAULT' => Array(
                'plugins/theme/jquery-ui-1.8.21.custom.css'
            ),
            'OWNER' => Array(
            ),
            'AUTO' => Array(
                'display.css',
                'displayShared.css',
                'displayToolbox.css'
            ),
            'IMPORT' => Array(),
        ),
        /* JavaScripts */
        'toolboxAction.js' => Array(
            'DEFAULT' => Array(
                'lib/mpws.core.js',
                'lib/mpws.api.js'
            ),
            'OWNER' => Array(),
            'AUTO' => Array()
        )
    );
    
    // define css mode
    // CSS, LESS
    // will be added in 1.8.0.x
    //$default['RESOURCES']['MODE'] = 'CSS';