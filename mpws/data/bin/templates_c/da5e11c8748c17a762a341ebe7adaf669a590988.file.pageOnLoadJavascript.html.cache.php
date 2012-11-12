<?php /* Smarty version Smarty-3.1.11, created on 2012-11-06 10:44:59
         compiled from "/var/www/mpws/web/plugin/reporting/template/component/pageOnLoadJavascript.html" */ ?>
<?php /*%%SmartyHeaderCode:734184112509390637e6ef2-30321597%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'da5e11c8748c17a762a341ebe7adaf669a590988' => 
    array (
      0 => '/var/www/mpws/web/plugin/reporting/template/component/pageOnLoadJavascript.html',
      1 => 1352191495,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '734184112509390637e6ef2-30321597',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_509390637ee8a1_41556864',
  'variables' => 
  array (
    'OBJECT' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_509390637ee8a1_41556864')) {function content_509390637ee8a1_41556864($_smarty_tpl) {?><!-- page javascript -->
<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['OBJECT']->value['SITE']->objectTemplatePath_simple_script, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array('_link'=>'/static/pluginReporingScriptEditor.js?plugin=reporting'), 0);?>


<script type="text/javascript">
    // Load the Visualization API and the piechart package.
    if (google)
        google.load('visualization', '1.0', {'packages':['corechart', 'table']});
</script>
<?php }} ?>