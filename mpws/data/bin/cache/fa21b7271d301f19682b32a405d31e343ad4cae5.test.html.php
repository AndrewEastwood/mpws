<?php /*%%SmartyHeaderCode:1278538729504fd318bf1889-23586382%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fa21b7271d301f19682b32a405d31e343ad4cae5' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/templates/page/test.html',
      1 => 1347410497,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1278538729504fd318bf1889-23586382',
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_504fda43824ad0_94028204',
  'variables' => 
  array (
    'model' => 0,
    'debug' => 0,
  ),
  'has_nocache_code' => false,
  'cache_lifetime' => 3600,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_504fda43824ad0_94028204')) {function content_504fda43824ad0_94028204($_smarty_tpl) {?><!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <title>Toolbox</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <pre><div><b>[DEBUG INFO] 03:41:39</b> controllerToolbox => processRequests</div><div><b>[DEBUG INFO] 03:41:39</b> contextToolbox __construct</div><div><b>[DEBUG INFO] 03:41:39</b> contextToolbox => Running command: main</div><b>[DEBUG INFO] 03:41:39</b><div style="margin:10px;padding:10px;border:1px solid #333;background:#aaa;color:#333";> libraryPluginManager: runPluginAsync action:<pre>Array
(
    [CALLER] => *
    [CONTEXT] => 
    [METHOD] => main
    [ARGUMENTS] => 
    [ID] => main
)
</pre></div><div><b>[DEBUG INFO] 03:41:39</b> libraryPluginManager: runPluginAsync => running plugin toolbox</div><div><b>[DEBUG INFO] 03:41:39</b> libraryPluginManager: getPluginWithContext plugin path: /var/www/mpws/rc_1.0/web/plugin/toolbox/plugin.toolbox.php</div><div><b>[DEBUG INFO] 03:41:39</b> libraryPluginManager: getPluginWithContext plugin name: plugintoolbox</div><div><b>[DEBUG INFO] 03:41:39</b> Base init: "toolbox" as "plugin" v.1.0</div><b>[DEBUG INFO] 03:41:39</b><div style="margin:10px;padding:10px;border:1px solid #333;background:#aaa;color:#333";> objectBase: set Extender: objectExtWithStorage<pre>Array
(
    [0] => Array
        (
            [NAME] => toolbox
            [TYPE] => plugin
            [VERSION] => 1.0
            [LOCALE] => en_us
            [CLASS] => plugin.toolbox
            [SCRIPT] => plugin.toolbox.php
            [PATH_DEF] => /var/www/mpws/rc_1.0/web/default/v1.0
            [PATH_OWN] => /var/www/mpws/rc_1.0/web/plugin/toolbox
        )

)
</pre></div><div><b>[DEBUG INFO] 03:41:39</b>objectExtWithStorage __construct with arguments:<pre>Array
(
    [array] => Array
        (
            [0] => Array
                (
                    [0] => Array
                        (
                            [NAME] => toolbox
                            [TYPE] => plugin
                            [VERSION] => 1.0
                            [LOCALE] => en_us
                            [CLASS] => plugin.toolbox
                            [SCRIPT] => plugin.toolbox.php
                            [PATH_DEF] => /var/www/mpws/rc_1.0/web/default/v1.0
                            [PATH_OWN] => /var/www/mpws/rc_1.0/web/plugin/toolbox
                        )

                )

        )

)
</pre></div><b>[DEBUG INFO] 03:41:39</b><div style="margin:10px;padding:10px;border:1px solid #333;background:#aaa;color:#333";> objectBase: set Extender: objectExtWithResource<pre>Array
(
    [0] => Array
        (
            [NAME] => toolbox
            [TYPE] => plugin
            [VERSION] => 1.0
            [LOCALE] => en_us
            [CLASS] => plugin.toolbox
            [SCRIPT] => plugin.toolbox.php
            [PATH_DEF] => /var/www/mpws/rc_1.0/web/default/v1.0
            [PATH_OWN] => /var/www/mpws/rc_1.0/web/plugin/toolbox
        )

)
</pre></div><div><b>[DEBUG INFO] 03:41:39</b>objectExtWithResource __construct with arguments:<pre>Array
(
    [array] => Array
        (
            [0] => Array
                (
                    [0] => Array
                        (
                            [NAME] => toolbox
                            [TYPE] => plugin
                            [VERSION] => 1.0
                            [LOCALE] => en_us
                            [CLASS] => plugin.toolbox
                            [SCRIPT] => plugin.toolbox.php
                            [PATH_DEF] => /var/www/mpws/rc_1.0/web/default/v1.0
                            [PATH_OWN] => /var/www/mpws/rc_1.0/web/plugin/toolbox
                        )

                )

        )

)
</pre></div><b>[DEBUG INFO] 03:41:39</b><div style="margin:10px;padding:10px;border:1px solid #333;background:#aaa;color:#333";> objectBase: set Extender: objectExtWithConfiguration<pre>Array
(
    [0] => Array
        (
            [NAME] => toolbox
            [TYPE] => plugin
            [VERSION] => 1.0
            [LOCALE] => en_us
            [CLASS] => plugin.toolbox
            [SCRIPT] => plugin.toolbox.php
            [PATH_DEF] => /var/www/mpws/rc_1.0/web/default/v1.0
            [PATH_OWN] => /var/www/mpws/rc_1.0/web/plugin/toolbox
        )

)
</pre></div><div><b>[DEBUG INFO] 03:41:39</b>objectExtWithConfiguration __construct with arguments:<pre>Array
(
    [array] => Array
        (
            [0] => Array
                (
                    [0] => Array
                        (
                            [NAME] => toolbox
                            [TYPE] => plugin
                            [VERSION] => 1.0
                            [LOCALE] => en_us
                            [CLASS] => plugin.toolbox
                            [SCRIPT] => plugin.toolbox.php
                            [PATH_DEF] => /var/www/mpws/rc_1.0/web/default/v1.0
                            [PATH_OWN] => /var/www/mpws/rc_1.0/web/plugin/toolbox
                        )

                )

        )

)
</pre></div><div><b>[DEBUG INFO] 03:41:39</b> objectBaseWebPlugin: __construct => toolbox</div><b>[DEBUG INFO] 03:41:39</b><div style="margin:10px;padding:10px;border:1px solid #333;background:#aaa;color:#333";> objectBaseWebPlugin: run function:<pre>Array
(
    [CALLER] => *
    [CONTEXT] => 
    [METHOD] => main
    [ARGUMENTS] => 
    [ID] => main
)
</pre></div><div><b>[DEBUG INFO] 03:41:39</b> objectBaseWebPlugin => _run_main</div><div><b>[DEBUG INFO] 03:41:39</b> objectBaseWebPlugin => _displayTriggerOnCommonStart</div><div><b>[DEBUG INFO] 03:41:39</b> objectBaseWebPlugin => _displayTriggerOnInActive</div><div><b>[DEBUG INFO] 03:41:39</b> objectBaseWebPlugin => _displayTriggerOnCommonEnd</div><div><b>[DEBUG INFO] 03:41:39</b> objectBaseWeb: getTemplatePath: widget.demo</div><div><b>[DEBUG INFO] 03:41:39</b> objectExtWithResource => getResource: template, widget.demo</div><b>[DEBUG INFO] 03:41:39</b><div style="margin:10px;padding:10px;border:1px solid #333;background:#aaa;color:#333";> <pre>Array
(
    [NAME] => toolbox
    [TYPE] => plugin
    [VERSION] => 1.0
    [LOCALE] => en_us
    [CLASS] => plugin.toolbox
    [SCRIPT] => plugin.toolbox.php
    [PATH_DEF] => /var/www/mpws/rc_1.0/web/default/v1.0
    [PATH_OWN] => /var/www/mpws/rc_1.0/web/plugin/toolbox
)
</pre></div><div><b>[DEBUG INFO] 03:41:39</b>libraryStaticResourceManager getTemplatePath with arguments:<pre>Array
(
    [string] => Array
        (
            [0] => plugin
            [1] => toolbox
            [2] => widget.demo
        )

    [array] => Array
        (
            [3] => Array
                (
                    [NAME] => toolbox
                    [TYPE] => plugin
                    [VERSION] => 1.0
                    [LOCALE] => en_us
                    [CLASS] => plugin.toolbox
                    [SCRIPT] => plugin.toolbox.php
                    [PATH_DEF] => /var/www/mpws/rc_1.0/web/default/v1.0
                    [PATH_OWN] => /var/www/mpws/rc_1.0/web/plugin/toolbox
                )

        )

)
</pre></div><div><b>[DEBUG INFO] 03:41:39</b> objectBaseWeb: getTemplatePath: Downloaded Template: widget.demo</div><div><b>[DEBUG INFO] 03:41:39</b> objectBaseWeb: getTemplatePath: widget.demo2</div><div><b>[DEBUG INFO] 03:41:39</b> objectExtWithResource => getResource: template, widget.demo2</div><b>[DEBUG INFO] 03:41:39</b><div style="margin:10px;padding:10px;border:1px solid #333;background:#aaa;color:#333";> <pre>Array
(
    [NAME] => toolbox
    [TYPE] => plugin
    [VERSION] => 1.0
    [LOCALE] => en_us
    [CLASS] => plugin.toolbox
    [SCRIPT] => plugin.toolbox.php
    [PATH_DEF] => /var/www/mpws/rc_1.0/web/default/v1.0
    [PATH_OWN] => /var/www/mpws/rc_1.0/web/plugin/toolbox
)
</pre></div><div><b>[DEBUG INFO] 03:41:39</b>libraryStaticResourceManager getTemplatePath with arguments:<pre>Array
(
    [string] => Array
        (
            [0] => plugin
            [1] => toolbox
            [2] => widget.demo2
        )

    [array] => Array
        (
            [3] => Array
                (
                    [NAME] => toolbox
                    [TYPE] => plugin
                    [VERSION] => 1.0
                    [LOCALE] => en_us
                    [CLASS] => plugin.toolbox
                    [SCRIPT] => plugin.toolbox.php
                    [PATH_DEF] => /var/www/mpws/rc_1.0/web/default/v1.0
                    [PATH_OWN] => /var/www/mpws/rc_1.0/web/plugin/toolbox
                )

        )

)
</pre></div><div><b>[DEBUG INFO] 03:41:39</b> objectBaseWeb: getTemplatePath: Downloaded Template: widget.demo2</div><div><b>[DEBUG INFO] 03:41:39</b> objectBaseWeb: getTemplatePath: page.test</div><div><b>[DEBUG INFO] 03:41:39</b> objectExtWithResource => getResource: template, page.test</div><b>[DEBUG INFO] 03:41:39</b><div style="margin:10px;padding:10px;border:1px solid #333;background:#aaa;color:#333";> <pre>Array
(
    [NAME] => toolbox
    [TYPE] => plugin
    [VERSION] => 1.0
    [LOCALE] => en_us
    [CLASS] => plugin.toolbox
    [SCRIPT] => plugin.toolbox.php
    [PATH_DEF] => /var/www/mpws/rc_1.0/web/default/v1.0
    [PATH_OWN] => /var/www/mpws/rc_1.0/web/plugin/toolbox
)
</pre></div><div><b>[DEBUG INFO] 03:41:39</b>libraryStaticResourceManager getTemplatePath with arguments:<pre>Array
(
    [string] => Array
        (
            [0] => plugin
            [1] => toolbox
            [2] => page.test
        )

    [array] => Array
        (
            [3] => Array
                (
                    [NAME] => toolbox
                    [TYPE] => plugin
                    [VERSION] => 1.0
                    [LOCALE] => en_us
                    [CLASS] => plugin.toolbox
                    [SCRIPT] => plugin.toolbox.php
                    [PATH_DEF] => /var/www/mpws/rc_1.0/web/default/v1.0
                    [PATH_OWN] => /var/www/mpws/rc_1.0/web/plugin/toolbox
                )

        )

)
</pre></div><div><b>[DEBUG INFO] 03:41:39</b> objectBaseWeb: getTemplatePath: Downloaded Template: page.test</div><div><b>[DEBUG INFO] 03:41:39</b> Fetching wigets: 2</div><div><b>[DEBUG INFO] 03:41:39</b> Rendering template: /var/www/mpws/rc_1.0/web/default/v1.0/templates/widget/demo.html</div><div><b>[DEBUG INFO] 03:41:39</b> Rendering template: /var/www/mpws/rc_1.0/web/default/v1.0/templates/widget/demo2.html</div></pre>
        <hr size="2"/>
        <div>
            HELLO WORLD!!!;
;

            demo 2 = CAPTURE TEST FROM DEMO1 aaa;

            CAPTURE TEST FROM DEMO1 aaa
            
        </div>
    </body>
</html>
<?php }} ?>