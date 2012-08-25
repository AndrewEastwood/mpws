<?php

if ($_GET['do'] === 'logout') {
    Header('HTTP/1.0 401 Unauthorized');
    exit;
}

$realm = 'Restricted area';

//user => password
$users = array('service' => 'mpws5170a3');

//var_dump($_SERVER);

if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Digest realm="'.$realm.
           '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');

    die('You are not authorized user.');
}


// analyze the PHP_AUTH_DIGEST variable
if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) ||
    !isset($users[$data['username']])) {
    //$_SERVER['PHP_AUTH_DIGEST'] = false;
    header('HTTP/1.1 401 Unauthorized');
    die('Wrong Credentials!');
    }


// generate the valid response
$A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

if ($data['response'] != $valid_response)
    die('Wrong Credentials!');

// ok, valid username & password
//echo 'You are logged in as: ' . $data['username'];


// function to parse the http auth header
function http_digest_parse($txt)
{
    // protect against missing data
    $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));

    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }

    return $needed_parts ? false : $data;
}


// document root path reference
$DR = $_SERVER['DOCUMENT_ROOT'];
// bootstrap
include $DR . '/engine/bootstrap.php';
// include global files
$globals = glob($DR . '/engine/global/global.*.php');
foreach ($globals as $globalFile)
    require_once $globalFile;
    
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
