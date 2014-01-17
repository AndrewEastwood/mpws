<?php

class configurationDefaultDisplay extends objectConfiguration {

    // general
    static $Version = MPWS_VERSION;
    static $Homepage = 'localhost';

    // session
    static $SessionTime = 1800; #30 * 60

    // localization
    static $Locale = 'en_us';

    // render
    static $DefaultDisplay = 'index';

    // security
    static $MasterJsApiKey = 'UUUDemo1!!!MstPwd#123!@#';

    // features
    static $AllowWideJsApi = false;
    static $AllowJobInstallerLink = true;
    static $Plugins = array("toolbox");

    // encoding key
    static $EncKey = 'Qqq!@SdfTo__56%$#';

}

?>