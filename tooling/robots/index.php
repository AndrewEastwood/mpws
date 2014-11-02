<?php

// TOOL: Robots.txt editor
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

$robots = DR . DS . 'robots.txt';
$msg = false;


if (\engine\lib\request::isPostFormAction('save robots')) {
    $data = \engine\lib\request::fromPOST('robots_content');
    file_put_contents($robots, $data);
    $msg[] = date('Y-m-d H:i:s') . ': Robots.txt is saved';
} else
    $data = file_get_contents($robots);

if (!empty($msg))
    foreach ($msg as $message)
        echo '<h4>' . $message . '</h4>';
    
echo '
<form method="POST" action="">
    <textarea name="robots_content" cols="150" rows="15">'.$data.'</textarea>
    <br>
    <input type="submit" name="do" value="Save Robots">
</form>';

?>
