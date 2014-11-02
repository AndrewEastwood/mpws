<?php

namespace web\default\atlantis\config;

use \engine\object\configuration as baseConfig;

class display extends baseConfig {

    // general
    var $Version = MPWS_VERSION;
    var $Homepage = 'localhost';

    // session
    var $SessionTime = 1800; #30 * 60

    // localization
    var $Locale = 'en_us';
    var $Lang = 'en';

    // render
    var $DefaultDisplay = 'layout';

    // security
    var $MasterJsApiKey = 'UUUDemo1!!!MstPwd#123!@#';

    // features
    var $IsManaged = true;
    var $AllowWideJsApi = false;
    var $AllowJobInstallerLink = true;
    var $Plugins = array();

    // encoding key
    var $EncKey = 'Qqq!@SdfTo__56%$#';

    // var $pageCharset = 'utf-8';
}

?>