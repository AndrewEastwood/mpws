

    // static resources
    $customer['RESOURCES'] = array();

    /* basic files */
    $customer['RESOURCES']['STATIC'] = Array(
        /* StyleSheets */
        /* IMPORT Group is used for CSS only */
        'mpwsStyle.css' => Array(
            'DEFAULT' => Array(
                'shared.css'
            ),
            'OWNER' => Array(
                'start.css'
            ),
            'AUTO' => Array(),
            'IMPORT' => Array()
        ),
        'mpwsInner.css' => Array(
            'DEFAULT' => Array(
                'shared.css'
            ),
            'OWNER' => Array(
                'inner.css'
            ),
            'AUTO' => Array(),
            'IMPORT' => Array()
        ),
        'mpwsAdmin.css' => Array(
            'DEFAULT' => Array(),
            'OWNER' => Array(
                'admin.css'
            ),
            'AUTO' => Array(),
            'IMPORT' => Array()
        ),
        'mpwsTheme.css' => Array(
            'DEFAULT' => Array(),
            'OWNER' => Array(),
            'AUTO' => Array(
                'plugins/theme/jquery-ui-1.8.21.custom.css'
            ),
            'IMPORT' => Array()
        ),
        /* JavaScripts */
        'mpwsLight.js' => Array(
            'DEFAULT' => Array(
                'lib/mpws.core.js',
                'lib/mpws.api.js',
                'lib/mpws.ui.js',
                'plugins/jquery.cookie.js'
            ),
            'OWNER' => Array(
                'lib/less.js',
                'lib/slide.js',
                'lib/essay-about.js'
            ),
            'AUTO' => Array()
        ),
        'mpwsEditable.js' => Array(
            'DEFAULT' => Array(
                'plugins/nicEdit/nicEdit.js',
            ),
            'OWNER' => Array(),
            'AUTO' => Array()
        ),
        'mpwsActionIE6.js' => Array(
            'DEFAULT' => Array(),
            'OWNER' => Array(
                'lib/supersleight-min.js'
            ),
            'AUTO' => Array()
        )
    );
    
    /* Extend Base */
    
    $customer['RESOURCES']['STATIC']['mpwsFull.js'] = extendParent(
        $customer['RESOURCES']['STATIC']['mpwsLight.js'],
        Array(
            'DEFAULT' => Array(
                'plugins/jquery-ui/jquery-ui-1.8.22.custom.js',
                'plugins/jquery-ui/jquery.ui.timepicker.addon.js'
            )
        )
    );

    $customer['RESOURCES']['STATIC']['mpwsAdmin.js'] = $customer['RESOURCES']['STATIC']['mpwsFull.js'];
