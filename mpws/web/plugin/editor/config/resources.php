

    // static resources
    $plugin['RESOURCES'] = array();

    $plugin['RESOURCES']['STATIC'] = extendParent(
        $default['RESOURCES']['STATIC'],
        Array(
            'toolboxAction.js' => Array(
                'DEFAULT' => Array(
                    'plugins/nicEdit/nicEdit.js'
                ),
                'OWNER' => Array(
                    'lib/writer.js'
                )
            ),
            'toolboxDisplay.css' => Array(
                'OWNER' => Array(
                    'display_editor.css'
                )
            ),
        )
    );