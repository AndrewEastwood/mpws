<?php

class librarySecure {

    public static function EncodeAccountPassword ($rawPassword) {
        $key = '!MPWSservice123';
        return md5($key . $rawPassword);
    }

}

?>