<?php /* Smarty version Smarty-3.1.11, created on 2013-12-12 19:12:28
         compiled from "/var/www/mpws/rc_1.0/web/customer/toolbox/template/layout/defaultToolbox.html" */ ?>
<?php /*%%SmartyHeaderCode:14769319735206340ce1e7e4-84126817%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '784a3f42e7f6316dff3b60d20ea358e590c716ce' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/customer/toolbox/template/layout/defaultToolbox.html',
      1 => 1377429668,
      2 => 'file',
    ),
    '13fc0739f57dedff11a7d9378923d6d565f25193' => 
    array (
      0 => '/var/www/mpws/rc_1.0/web/default/v1.0/template/layout/defaultSystem.html',
      1 => 1386545737,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14769319735206340ce1e7e4-84126817',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5206340d0303d6_45843972',
  'variables' => 
  array (
    'OBJECT' => 0,
    'INFO' => 0,
    'CURRENT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5206340d0303d6_45843972')) {function content_5206340d0303d6_45843972($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/devdata/Develop/github/web/mpws/engine/system/extension/Smarty-3.1.11/libs/plugins/modifier.capitalize.php';
?><!DOCTYPE html>
<html>
<head>
    <title>
    <?php echo $_smarty_tpl->tpl_vars['OBJECT']->value['SITE']->objectProperty_site_title;?>

    <?php echo $_smarty_tpl->tpl_vars['OBJECT']->value['SITE']->objectProperty_site_titleSeparator;?>

    <?php echo $_smarty_tpl->tpl_vars['OBJECT']->value['SITE']->{"objectProperty_site_page".((string)smarty_modifier_capitalize($_smarty_tpl->tpl_vars['INFO']->value['GET']['PAGE']))};?>

</title>
    
    <link rel="stylesheet" type="text/css" href="/static/i/customer/<?php echo $_smarty_tpl->tpl_vars['INFO']->value['CUSTOMER'];?>
/default.css">
    
    <?php if (!empty($_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'])){?>
    <link rel="stylesheet" type="text/css" href="/static/i/plugin/<?php echo $_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'];?>
/default.css">
        
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['OBJECT']->value['WOB'][makeKey($_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'])]->objectTemplatePath_component_pageOnLoadStylesheet, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>

    <?php }?>
    <meta name="locale" content="<?php echo $_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->getObjectLocale();?>
">
</head>
<body>

<div class="MPWSLayout MPWSLayoutDefaultSystem">
    <?php if ($_smarty_tpl->tpl_vars['INFO']->value['USER']['ACTIVE']){?>
        
    <?php $_smarty_tpl->tpl_vars["allWidgets"] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_macro_widgetGrabber, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_widgets'=>$_smarty_tpl->tpl_vars['MODEL']->value['WIDGET']), 0));?>

    <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->{"objectTemplatePath_page_system:default"}, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_content'=>array($_smarty_tpl->tpl_vars['allWidgets']->value)), 0);?>


    <?php }else{ ?>
        
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['CURRENT']->value['OBJECT']->objectTemplatePath_page_login, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>

        
    <?php }?>
</div>

<div class="static-content-wrapper render-hidden" title="page basic javascript container">
    
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <script type="text/javascript" src="/static/i/customer/<?php echo $_smarty_tpl->tpl_vars['INFO']->value['CUSTOMER'];?>
/starter.js"></script>
    
    <?php if (!empty($_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'])){?>
    <script type="text/javascript" src="/static/i/plugin/<?php echo $_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'];?>
/starter.js"></script>
        
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['OBJECT']->value['WOB'][makeKey($_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'])]->objectTemplatePath_component_pageOnLoadJavascript, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>

    <?php }?>
    
    <script type="text/javascript" class="temporary">
        APP.Internal.configureAppLoader({
            VERSION: null,
            // security token
            CUSTOMER: '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['CUSTOMER'];?>
',
            // security token
            TOKEN: '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['TOKEN'];?>
',
            // page
            PAGE: '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['GET']['PAGE'];?>
',
            // display
            DISPLAY: '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['GET']['DISPLAY'];?>
',
            // action
            ACTION: '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['GET']['ACTION'];?>
',
            // action
            PLUGIN: '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['GET']['PLUGIN'];?>
',
            // ROUTER: 'router/customer.base',
            URL: {
                // available static urls
                staticUrlBase: '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['URL']['STATIC_BASE'];?>
',
                staticUrlCustomer: '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['URL']['STATIC_CUSTOMER'];?>
',
                staticUrlPlugin: '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['URL']['STATIC_PLUGIN'];?>
'
            },
            STATES: {
                pageDebug: true
            },
            REQUIREJS: {
                router: {
                    modulePageMap: [{
                        match: [".*"],
                        deps: ["router/customer", "router/plugin"]
                    }]
                },
                app: {
                    baseUrl: "<?php echo $_smarty_tpl->tpl_vars['INFO']->value['URL']['STATIC_BASE'];?>
",
                    paths: {
                        // general paths
                        lib: '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['URL']['STATIC_CUSTOMER'];?>
lib',
                        router: '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['URL']['STATIC_CUSTOMER'];?>
router',
                        widget: '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['URL']['STATIC_CUSTOMER'];?>
widget',
                        model: '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['URL']['STATIC_CUSTOMER'];?>
model',
                        view: '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['URL']['STATIC_CUSTOMER'];?>
view',
                        plugin: '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['URL']['STATIC_PLUGIN'];?>
',
                        page: '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['URL']['STATIC_PLUGIN'];?>
',
                        // version suppress
                        "lib/jquery": '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['URL']['STATIC_CUSTOMER'];?>
lib/jquery-1.9.1',
                        "lib/jquery_ui": '<?php echo $_smarty_tpl->tpl_vars['INFO']->value['URL']['STATIC_CUSTOMER'];?>
lib/jquery-ui-1.10.3.custom'
                    }
                }
            }
        });
    </script>
    
</div>

</body>
</html><?php }} ?>