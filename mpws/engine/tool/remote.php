<?php

require_once('simple_html_dom.php');
    
$data = $_GET['url'];
$cdata = urldecode($data);

$html = file_get_html($cdata);

//var_dump(htmlspecialchars($html));

// Find all images 
foreach($html->find('form') as $element) {
    //$element->action = 'http://localhost/libs/tools/remote.php?url=' . $element->action;
    $element->target = 'mpwspalceholder';
    $element->onsubmit = "$('#p1').remove();";

}

// Find all images 
foreach($html->find('link') as $element) 
    $element->href = 'https://docs.google.com' . $element->href;

$preppender = '
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<link type="text/css" src="static/mpws-display.css" rel="Stylesheet"/>
<div id="p1"><div>';

$appender = '
</div></div>
<iframe name="mpwspalceholder" width="500px" height="500px" frameborder="0px" onload=""></iframe>

';

foreach($html->find('body') as $element) 
    $element->innertext = $preppender . $element->innertext . $appender;


echo $html;

?>
