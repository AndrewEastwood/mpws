<?php
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
?>
