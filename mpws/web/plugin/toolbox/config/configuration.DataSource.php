<?php

class configurationToolboxDataSource extends objectConfiguration {

    // Product base configuration >>>>>
    static function jsapiGetAdminAccount ($email, $password) {
        return self::jsapiGetDataSourceConfig(array(
            "action" => "select",
            "source" => "toolbox_admins",
            "condition" => array(
                "filter" => "Login (=) ? + Password (=) ? + Status (=) ?",
                "values" => array($email, $password, "ACTIVE")
            ),
            "fields" => array("ID", "Login", "Password"),
            "offset" => 0,
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }
    // <<<< Product base configuration

}

?>