<?php

class configurationDefaultDisplay extends objectConfiguration {

    // general
    static $Version = MPWS_VERSION;
    static $Homepage = 'localhost';

    // session
    static $SessionTime = 1800; #30 * 60

    // localization
    static $Locale = 'en_us';
    static $Lang = 'en';

    // render
    static $DefaultDisplay = 'layout';

    // security
    static $MasterJsApiKey = 'UUUDemo1!!!MstPwd#123!@#';

    // features
    static $IsManaged = true;
    static $AllowWideJsApi = false;
    static $AllowJobInstallerLink = true;
    static $Plugins = array();

    // encoding key
    static $EncKey = 'Qqq!@SdfTo__56%$#';

    // static $pageCharset = 'utf-8';
}

?>