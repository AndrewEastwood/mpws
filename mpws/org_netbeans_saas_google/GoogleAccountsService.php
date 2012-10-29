<?php

include_once "org_netbeans_saas/RestConnection.php";
include_once "GoogleAccountsServiceAuthenticator.php";

class GoogleAccountsService {

    public function GoogleAccountsService() {
        
    }

    /*
      @param accountType resource URI parameter
      @param email resource URI parameter
      @param passwd resource URI parameter
      @param service resource URI parameter
      @param source resource URI parameter
      @return an instance of RestResponse */

    public static function accountsClientLogin($accountType, $email, $passwd, $service, $source) {
        $apiKey = GoogleAccountsServiceAuthenticator::getApiKey();
        $pathParams = array();
        $queryParams = array();
        $queryParams["accountType"] = $accountType;
        $queryParams["Email"] = $email;
        $queryParams["Passwd"] = $passwd;
        $queryParams["service"] = $service;
        $queryParams["source"] = $source;

        $conn = new RestConnection("https://www.google.com/accounts/ClientLogin", $pathParams, array());

        sleep(1);
        return $conn->post($queryParams);
    }

}

?>
