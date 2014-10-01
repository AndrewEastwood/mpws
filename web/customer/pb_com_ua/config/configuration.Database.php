<?php

class configurationCustomerDatabase extends configurationDefaultDatabase {

    static $DEV = array(
        'USER' => 'root',
        'PWD' => '1111',
        'DB' => 'mpws_light'
    );

    static $PROD = array(
        'HOST' => 'db2.ho.ua',
        'USER' => 'mikser',
        'PWD' => 'KL3fsa)(',
        'DB' => 'mikser'
    );

    static function init ($config = array()) {

        // append default config values
        self::$DEV = array_merge(parent::$DEV, self::$DEV);
        self::$PROD = array_merge(parent::$PROD, self::$PROD);

        // update complex params
        self::$DEV['STRING'] = sprintf("mysql:dbname=%s;host=%s;charset=%s", self::$DEV['DB'], self::$DEV['HOST'], parent::$DEV['CHARSET']);
        self::$DEV['DBOini'] = array(
            "connection_string" => self::$DEV['STRING'],
            "id_column" => 'ID',
            "username" => self::$DEV['USER'],
            "password" => self::$DEV['PWD'],
            "driver_options" => array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="STRICT_ALL_TABLES"',
                PDO::ATTR_AUTOCOMMIT => false,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
            )
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