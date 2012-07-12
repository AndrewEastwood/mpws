<?php

    define ("TITLE", "essay-about");
    define ("SITE", "");
    define ("S_SITE_U", SITE);
    define ("SITEPATH", $_SERVER['DOCUMENT_ROOT'] . '/' . S_SITE_U);
    define ("SITEURL", 'http://' . $_SERVER['HTTP_HOST'] . '/' . S_SITE_U);
    define ("SITERES", SITEURL . "web/resources/" . strtolower(TITLE) . "/");
    define ("SITEWEB", SITEURL . "web/");

    // save globlas

    $GLOBALS['SITE']['TITLE'] =  TITLE;
    $GLOBALS['SITE']['SITE'] = SITE;
    $GLOBALS['SITE']['SITEPATH'] = SITEPATH;
    $GLOBALS['SITE']['SITEURL'] = SITEURL;
    $GLOBALS['SITE']['SITERES'] = SITERES;
    $GLOBALS['SITE']['SITEWEB'] = SITEWEB;

?>
