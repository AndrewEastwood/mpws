<?php

class configurationCustomerDatabase extends configurationDefaultDatabase {

    static $DEV = array(
        'USER' => 'admin1',
        'PWD' => '1111',
        'DB' => 'mpws_light'
    );

    static function init () {

        // append default config values
        self::$DEV = array_merge(parent::$DEV, self::$DEV);
        self::$PROD = array_merge(parent::$PROD, self::$PROD);

        // update complex params
        self::$DEV['STRING'] = sprintf("mysql:dbname=%s;host=%s;charset=%s", self::$DEV['DB'], self::$DEV['HOST'], parent::$DEV['CHARSET']);
        self::$DEV['DBOini'] = array(
            "connection_string" => self::$DEV['STRING'],
            "id_column" => 'ID',
            "username" => self::$DEV['USER'],
            "password" => self::$DEV['PWD']
        );

        $source = MPWS_ENV;
        parent::init(self::$$source);
    }

// host = 'localhost';
// username = 'admin1';
// password = '1111';
// name = 'mpws_light';

                    // "connection_string" => sprintf("mysql:dbname=%s;host=%s;charset=%s", $this->objectConfiguration_mdbc_name, $this->objectConfiguration_mdbc_host, $this->objectConfiguration_mdbc_charset),
                    // "id_column" => 'ID',
                    // "username" => $this->objectConfiguration_mdbc_username,
                    // "password" => $this->objectConfiguration_mdbc_password
}
configurationCustomerDatabase::init();

?>