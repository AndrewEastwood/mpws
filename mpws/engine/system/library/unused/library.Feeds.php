<?php

/**
 * Description of libraryFeeds
 *
 * @author mpws
 */
class libraryFeeds {

    public static function importTrigger ($importType, $opts) {
        switch (strtolower($importType)) {
            case 'salesforce':
                self::importSalesForce($opts['account'], $opts['feedUrl'], $opts['settings']);
                break;
            default :
                throw new Exception('Unknown feed import type');
                break;
        }
    }
    
    public static function processInbox () {
        
    }
    
    
    /****** import methods ******/ 
    
    public static function importSalesForce ($account, $feedUrl, $opts) {
        
        // required fields
        // $account:
        // user
        // pwd
        // token
        // --------
        // $opts
        // saveLog
        // emailLog
        // exportAfterImport
        
        
        if (empty($account))
            throw new Exception('importSalesForce: bad account');

        if (empty($feedUrl))
            throw new Exception('importSalesForce: bad feed url');
        
        if (empty($opts))
            throw new Exception('importSalesForce: bad settings');
        
        // setup SalesForce Client
        
        $mySforceConnection = new extensionSalesForceClient();
        //var_dump($mySforceConnection);
        $mySforceConnection->createConnection("partner.wsdl.xml");
        //var_dump($mySforceConnection);
        $client = $mySforceConnection->login($account['user'], $account['pwd'].$account['key']);
        
        
        // init varaibles
        $logInfo = 'Starting data import at : ' . date('Y-m-d H:i:s') . ' from ' . $reportUrl . PHP_EOL;
        $logInfo .= '--------------------------------------------------------------' . PHP_EOL;
        $logInfo .= '[' . date('Y-m-d H:i:s') . '] curl_init' . PHP_EOL;
        
        
        // init cURL section
        // and fetch data
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $feedUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Not doing any verification of SSL certificates
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_COOKIE, 'sid='.$client->sessionId);
        setcookie("sid", $client->sessionId, 0, "/", ".salesforce.com", 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        $logInfo .= '[' . date('Y-m-d H:i:s') . '] curl_exec' . PHP_EOL;
        $result = curl_exec ($ch);
        $logInfo .= '[' . date('Y-m-d H:i:s') . '] Received ' . strlen($result) . ' bytes ' . PHP_EOL;
        // close connection
        curl_close ($ch);
        $logInfo .= '[' . date('Y-m-d H:i:s') . '] curl_close' . PHP_EOL;
        $logInfo .= '[' . date('Y-m-d H:i:s') . '] Saving into a file' . PHP_EOL;
        
        // save data to inbox folder
        //file_put_contents('../outbox/' . date('Y-m-d') . '.csv'  , $result);
        
        $logInfo .= '[' . date('Y-m-d H:i:s') . '] closing connection in SF client' . PHP_EOL;
        try {
            $mySforceConnection->logout();
        } catch (Exception $e) {
            $logInfo .= '[' . date('Y-m-d H:i:s') . '] ERROR -> Caught exception: '.  $e->getMessage(). ' | ' . PHP_EOL;
        }
        $logInfo .= PHP_EOL . '--------------------------------------------------------------' . PHP_EOL;
        
        // save log
        // email log
        // start export on demand
    } 
    
    
}

?>
