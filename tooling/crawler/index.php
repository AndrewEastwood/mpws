<?php

// TOOL: Simple Page Crawler
// OWNER: MPWS
// ------------------------

// document root path reference
$DR = $_SERVER['DOCUMENT_ROOT'];
// bootstrap
include $DR . '/engine/bootstrap.php';
// include global files
$globals = glob($DR . '/engine/global/global.*.php');
foreach ($globals as $globalFile)
    require_once $globalFile;
//\engine\lib\security::wwwAuth();
if (!\engine\lib\security::cookieAuth()) {
    echo '<form method="post" action="">
        <input type="text" name="user" value=""/>
        <input type="text" name="pwd" value=""/>
        <input type="submit" name="do" value="Login"/>
    </form>';
    exit;
}


/*********** TOOL IMPL *************/

// get pages
/*
$pages = \engine\lib\utils::crawl_page('http://' . MPWS_CUSTOMER, array('action' => 'buy'));
$msg = false;

// save sitemap
if (\engine\lib\request::isPostFormAction('save sitemap')) {
    $gxml = new libraryGsgXml('http://' . MPWS_CUSTOMER);
    foreach ($pages as $pageLink => $state)
        $gxml->addUrl($pageLink);
    file_put_contents(DR.DS.'sitemap.xml', $gxml->generateXml());
    $msg[] = date('Y-m-d H:i:s') . ': sitemap.xml is saved';
}
*/


$sitemap_path = DR . DS . "sitemap.xml";

if (\engine\lib\request::isPostFormAction('upload sitemap')) {
    //var_dump($_FILES);
    if(move_uploaded_file($_FILES["sitemap"]['tmp_name'], $sitemap_path))
        $msg[] = date('Y-m-d H:i:s') . ': sitemap.xml is uploaded';
}


// show html
echo MPWS_CUSTOMER;
if (file_exists($sitemap_path))
    echo '<a href="/sitemap.xml" target="blank"> (open file)</a>';
echo '<hr size="2">';
if (!empty($msg))
    foreach ($msg as $message)
        echo '<h4>' . $message . '</h4>';
//echo '<form method="POST" action=""><input type="submit" name="do" value="Save Sitemap"></form>';
echo '<form method="POST" action="" enctype="multipart/form-data">
    <div><input type="file" name="sitemap">
    <input type="submit" name="do" value="Upload Sitemap"></div>
    <div><strong>Use <a href="http://www.xml-sitemaps.com/" target="blank">http://www.xml-sitemaps.com/</a> to generate sitemap</strong></div>
    </form>';
echo '<pre>' . print_r($pages, true) . '</pre>';


?>