<?php

namespace web\base\atlantis\config;

use \engine\objects\configuration as baseConfig;

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
    // seo
    public $SeoSnapshotURL = 'http://api.seo4ajax.com/ca6a7f515d9ff96c30c21373a1b7da66/?_escaped_fragment_=';
    public $SeoSiteMapUrl = 'http://api.seo4ajax.com/ca6a7f515d9ff96c30c21373a1b7da66/sitemap.xml';
}

?>