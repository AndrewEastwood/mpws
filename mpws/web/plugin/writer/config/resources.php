

    // static resources
    $plugin['RESOURCES'] = array();

    $plugin['RESOURCES']['STATIC'] = extendParent(
        $default['RESOURCES']['STATIC'],
        Array(
            'toolboxAction.js' => Array(
                'DEFAULT' => Array(
                    'plugins/jquery-ui/jquery-ui-1.8.22.custom.js',
                    'plugins/jquery-ui/jquery.ui.timepicker.addon.js',
                    'plugins/nicEdit/nicEdit.js'
                ),
                'OWNER' => Array(
                    'lib/writer.js'
                )
            ),
            'toolboxDisplay.css' => Array(
                'OWNER' => Array(
                    'display_writer.css'
                )
            ),
        )
    );