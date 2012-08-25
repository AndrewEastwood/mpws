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
librarySecurity::wwwAuth();


/*********** TOOL IMPL *************/

// get pages
$pages = libraryUtils::crawl_page('http://' . MPWS_CUSTOMER, array('action' => 'buy'));
$msg = false;

// save sitemap
if (libraryRequest::isPostFormAction('save sitemap')) {
    $gxml = new libraryGsgXml('http://' . MPWS_CUSTOMER);
    foreach ($pages as $pageLink => $state)
        $gxml->addUrl($pageLink);
    file_put_contents(DR.DS.'sitemap.xml', $gxml->generateXml());
    $msg[] = date('Y-m-d H:i:s') . ': sitemap.xml is saved';
}

// show html
echo MPWS_CUSTOMER;
echo '<hr size="2">';
if (!empty($msg))
    foreach ($msg as $message)
        echo '<h4>' . $message . '</h4>';
echo '<form method="POST" action=""><input type="submit" name="do" value="Save Sitemap"></form>';
echo '<pre>' . print_r($pages, true) . '</pre>';


?>