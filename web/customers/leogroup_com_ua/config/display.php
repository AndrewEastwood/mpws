<?php
namespace web\customers\leogroup_com_ua\config;

class display extends \web\base\atlantis\config\display {

    public $Locale = 'ua_uk';
    public $Title = 'Побутова техніка';
    public $Scheme = 'http';
    public $Host = 'leogroup.com.ua';
    public $Homepage = '//leogroup.com.ua';
    public $Plugins = array("shop", "account", "dashboard");

}

?>