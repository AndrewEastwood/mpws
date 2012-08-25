<?php

// document root path reference
$DR = $_SERVER['DOCUMENT_ROOT'];
// bootstrap
include $DR . '/engine/bootstrap.php';
// include global files
$globals = glob($DR . '/engine/global/global.*.php');
foreach ($globals as $globalFile)
    require_once $globalFile;

librarySecurity::wwwAuth();

$TOOLS = glob(DR . DS . 'tooling'.DS.'*');

echo '<h3>MPWS SERVICE PAGE</h3>';
echo '<ul class="menu">';
foreach ($TOOLS as $tool) {
    if (strtolower(basename($tool)) !== 'mpws')
        echo '<li><a id="'.basename($tool).'" href="'.basename($tool).'.php" target="tooling">' . basename($tool) . '</a></li>';
}
echo '<li><a href="?do=logout">Log out</a></li>';
echo '</ul>';
echo '<iframe name="tooling"></iframe>';
echo 
'<style type="text/css">
    body {background: #5170a3; margin: 0; padding: 0;}
    a, a:visited {color: #ddd; text-decoration: underline;}
    a:hover {color: #fff;}
    h3 {padding: 10px; color: #eee;}
    ul, li {margin: 0; padding: 0; list-style: none;}
    ul.menu {margin: 10px 50px 50px;}
    iframe {width: 100%; height: 700px; background-color: #eee;}
</style>

<script>
if(!location.hash)
    return;
var toolId = location.hash.substr(1);
var toolLink = document.getElementById(toolId);
document.tooling.location = toolLink.href;
</script>

';



//var_dump($TOOLS);

//fileatime

/*
$fp = fsockopen ("essay-about.mpws.com", 80, $errno, $errstr, 30);
if (!$fp) {
echo "$errstr ($errno)<br>\n";
} else {
fputs ($fp, "GET /page/buy-essays.html HTTP/1.1\r\nHost: essay-about.mpws.com\r\n\r\n");
while (!feof($fp)) {
echo fgets ($fp,128);
}
fclose ($fp);
}

*/
?>
