<?php

class configurationDefaultDatabase extends objectConfiguration {

    static $DEV = array(
        'HOST' => 'localhost',
        'USER' => 'root',
        'PWD' => '1111',
        'DB' => 'mpws_default',
        'CHARSET' => 'utf8',
        'STRING' => '',
        'DBOini' => array()
    );

    static $PROD = array(
        'HOST' => '',
        'USER' => '',
        'PWD' => '',
        'DB' => '',
        'CHARSET' => '',
        'STRING' => '',
        'DBOini' => array()
    );


    static function init ($config = array()) {

        // update complex params
        self::$DEV['STRING'] = sprintf("mysql:dbname=%s;host=%s", self::$DEV['DB'], self::$DEV['HOST']);

        // get runtime config
        $source = MPWS_ENV;
        $configObj = self::$$source;

        // extend config with requested config
        if (count($config))
            $configObj = array_merge($configObj, $config);

        // var_dump($configObj);

        // update values
        self::$HOST = $configObj['HOST'];
        self::$USER = $configObj['USER'];
        self::$PWD = $configObj['PWD'];
        self::$DB = $configObj['DB'];
        self::$CHARSET = $configObj['CHARSET'];
        self::$STRING = $configObj['STRING'];
        self::$DBOini = $configObj['DBOini'];

        return $configObj;
    }

    static $HOST = '';
    static $USER = '';
    static $PWD = '';
    static $DB = '';
    static $CHARSET = '';
    static $STRING = '';
    static $DBOini = '';

// host = 'localhost-default';
// username = '';
// password = '';
// name = 'mpws_default';
// charset = 'utf8';




            // case T_CONNECT_DB:
            //     return array(
            //         'DB_HOST' => $this->objectConfiguration_mdbc_host,
            //         'DB_USERNAME' => $this->objectConfiguration_mdbc_username,
            //         'DB_PASSWORD' => $this->objectConfiguration_mdbc_password,
            //         'DB_NAME' => $this->objectConfiguration_mdbc_name,
            //         'DB_CHARSET' => $this->objectConfiguration_mdbc_charset,
            //         'DB_CONNECTION_STRING' => sprintf("mysql:dbname=%s;host=%s", $this->objectConfiguration_mdbc_name, $this->objectConfiguration_mdbc_host)
            //     );
            // case T_CONNECT_ORM:
            //     return array(
            //         "connection_string" => sprintf("mysql:dbname=%s;host=%s;charset=%s", $this->objectConfiguration_mdbc_name, $this->objectConfiguration_mdbc_host, $this->objectConfiguration_mdbc_charset),
            //         "id_column" => 'ID',
            //         "username" => $this->objectConfiguration_mdbc_username,
            //         "password" => $this->objectConfiguration_mdbc_password
            //     );


}
configurationDefaultDatabase::init();

?>