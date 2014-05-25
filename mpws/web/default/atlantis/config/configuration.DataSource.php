<?php

class configurationDefaultDataSource extends objectConfiguration {

    static $Table_SystemAccounts = "mpws_accounts";

    static function jsapiGetCustomer ($ExternalKey = MPWS_CUSTOMER) {
        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_customer",
            "fields" => array("*"),
            "condition" => array(
                "ExternalKey" => self::jsapiCreateDataSourceCondition($ExternalKey),
                "Status" => self::jsapiCreateDataSourceCondition("ACTIVE")
            ),
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

}


?>