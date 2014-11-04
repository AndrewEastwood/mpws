<?php

namespace web\base\atlantis\config;

use \engine\object\configuration as baseConfig;

class display extends baseConfig {

    // general
    // public $Version = 'atlantis';
    public $Homepage = 'localhost';

    // session
    public $SessionTime = 1800; #30 * 60

    // localization
    public $Locale = 'en_us';
    public $Lang = 'en';

    // render
    public $DefaultDisplay = 'layout';
    public $Layout = 'layout.hbs';
    public $LayoutBody = 'layoutBody.hbs';

    // security
    public $MasterJsApiKey = 'UUUDemo1!!!MstPwd#123!@#';

    // features
    public $IsManaged = true;
    public $AllowWideJsApi = false;
    public $AllowJobInstallerLink = true;
    public $Plugins = array();

    // encoding key
    public $EncKey = 'Qqq!@SdfTo__56%$#';

    // var $pageCharset = 'utf-8';
}

?>