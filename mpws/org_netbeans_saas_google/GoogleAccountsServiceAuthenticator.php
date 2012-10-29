<?php

include("GoogleAccountsServiceAuthenticatorProfile.php");

class GoogleAccountsServiceAuthenticator {

    public static function getApiKey() {
        $apiKey = GoogleAccountsServiceAuthenticatorProfile::getApiKey();
        if ($apiKey == null || $apiKey == "") {
            throw new Exception("Please specify your api key in the apikey.php file.");
        }
        return $apiKey;
    }

}

?>
