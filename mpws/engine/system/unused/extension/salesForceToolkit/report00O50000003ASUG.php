<?php

chdir( dirname ( __FILE__ ) );

// include SF client
require_once ('SforcePartnerClient.php');


function downloadData () {
    echo 'SF REPORT' . PHP_EOL;

    // login configuration
    define("USERNAME", "");
    define("PASSWORD", "");
    define("SECURITY_TOKEN", "");

    //echo getcwd() . "\n";

    $mySforceConnection = new SforcePartnerClient();
    //var_dump($mySforceConnection);
    $mySforceConnection->createConnection("partner.wsdl.xml");
    //var_dump($mySforceConnection);
    $client = $mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);

    //var_dump($client->sessionId);


    // report configuration 
    $reportId = ''; //00O50000003ASUG
    $url = "http://salesforce.com";
    $reportUrl = $url."/".$reportId."?export=1&enc=UTF-8&xf=csv";

    echo 'Starting data import at : ' . date('Y-m-d H:i:s') . ' from ' . $reportUrl . PHP_EOL;
    echo '--------------------------------------------------------------' . PHP_EOL;
    echo '[' . date('Y-m-d H:i:s') . '] curl_init' . PHP_EOL;
    // init cURL section
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $reportUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // Not doing any verification of SSL certificates
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_COOKIE, 'sid='.$client->sessionId);
    setcookie("sid", $client->sessionId, 0, "/", ".salesforce.com", 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    echo '[' . date('Y-m-d H:i:s') . '] curl_exec' . PHP_EOL;
    $result = curl_exec ($ch);
    echo '[' . date('Y-m-d H:i:s') . '] Received ' . strlen($result) . ' bytes ' . PHP_EOL;
    curl_close ($ch);
    echo '[' . date('Y-m-d H:i:s') . '] curl_close' . PHP_EOL;
    echo '[' . date('Y-m-d H:i:s') . '] Saving into a file' . PHP_EOL;
    file_put_contents('../outbox/' . date('Y-m-d') . '.csv'  , $result);
    echo '[' . date('Y-m-d H:i:s') . '] closing connection in SF client' . PHP_EOL;
    try {
        $mySforceConnection->logout();
    } catch (Exception $e) {
        echo '[' . date('Y-m-d H:i:s') . '] ERROR -> Caught exception: ',  $e->getMessage(), ' | ' . PHP_EOL;
    }

    echo PHP_EOL . '--------------------------------------------------------------' . PHP_EOL;
}


?>