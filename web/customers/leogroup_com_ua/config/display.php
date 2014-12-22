<?php
namespace web\customers\leogroup_com_ua\config;

class display extends \web\base\atlantis\config\display {

    public $Locale = 'ua_uk';
    public $Title = 'Побутова техніка';
    public $Scheme = 'http';
    public $Host = 'leogroup.com.ua';
    public $Homepage = '//leogroup.com.ua';
    public $Plugins = array("shop", "account", "dashboard");

    // seo
    public $SeoSnapshotURL = 'http://api.seo4ajax.com/ca6a7f515d9ff96c30c21373a1b7da66/?_escaped_fragment_=';
    public $SeoSiteMapUrl = 'http://api.seo4ajax.com/ca6a7f515d9ff96c30c21373a1b7da66/sitemap.xml';
}

?>