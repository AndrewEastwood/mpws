<?php

class configurationDefaultDataSource extends objectConfiguration {

    static $Table_SystemAccounts = "mpws_accounts";

    static function jsapiGetCustomer ($ExternalKey = MPWS_CUSTOMER) {
        // if (empty($ExternalKey))
        //     $ExternalKey = ;

        $filter = "ExternalKey (=) ? + Status (=) ?";
        $values = array($ExternalKey, "ACTIVE");

        // if (glIsToolbox() && unmanaged) {
        //     $filter = "";
        //     $values = "";
        // }

        return self::jsapiGetDataSourceConfig(array(
            "source" => "mpws_customer",
            "fields" => array("*"),
            "condition" => array(
                "filter" => $filter, //"shop_products.Status = ? AND shop_products.Enabled = ?",
                "values" => $values
            ),
            "limit" => 1,
            "options" => array(
                "expandSingleRecord" => true
            )
        ));
    }

}


?>