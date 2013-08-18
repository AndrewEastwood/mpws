<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsModifierCompiler
 */

/**
 * Smarty compress modifier plugin
 *
 * Type:     modifier<br>
 * Name:     compress<br>
 * Purpose:  convert string to compresscase
 *
 * @link http://www.smarty.net/manual/en/language.modifier.compress.php compress (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com>
 * @author Uwe Tews
 * @param array $params parameters
 * @return string with compiled code
 */

function smarty_modifiercompiler_compress($params, $compiler)
{
    return 'glCompressStr(' . $params[0] . ')';
}

?>