<?php

// TOOL: Simple File Manager
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
//librarySecurity::wwwAuth();
if (!librarySecurity::cookieAuth()) {
    echo '<form method="post" action="">
        <input type="text" name="user" value=""/>
        <input type="text" name="pwd" value=""/>
        <input type="submit" name="do" value="Login"/>
    </form>';
    exit;
}


/*********** TOOL IMPL *************/

//echo '<pre>' . print_r($_GET, true) . '</pre>';
// remove single directory
if (!empty($_GET['remove']) && is_dir($_GET['remove']) && file_exists($_GET['remove']))
    libraryFileManager::rrmdir($_GET['remove'], null, true);

// get temp files
$TEMP = glob(DR . DS . 'data'.DS.'temp'.DS.'*');

echo '<h3>File Manager</h3>';
echo '<strong>Links remove file/directory stright away without confirmation! Please be careful using such links!</strong>';
echo '<h4>All Temporary Directories</h4>';
echo '<ul class="menu">';
foreach ($TEMP as $tempDirectory) {
    echo '<li>';
    echo basename($tempDirectory);
    echo ' [ ' . date('Y-m-d H:i:s', fileatime($tempDirectory)) . ' ] ';
    echo ' [ <a href="?remove='.urlencode($tempDirectory).'">Remove Directory</a> ] ';
    // get directory items
    $CONTENT = glob($tempDirectory.DS.'*');
    echo '<ul class="submenu">';
    foreach ($CONTENT as $tempFile)
        echo '<li><a href="/upload/temp-'.basename($tempDirectory).DS.basename($tempFile).'" target="blank">' . basename($tempFile) . '</a></li>';
    echo '</li>';
    echo '</ul>';
}
echo '</ul>';

?>