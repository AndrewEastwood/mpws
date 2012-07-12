<?php


class libraryRequest {

    /* get values */
    static function getDisplay($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getValue('display', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getPage ($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getValue('page', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getAction ($defaultValue, $switch, $valueOnSwitch) {
        return self::getValue('action', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getApiFn ($defaultValue, $switch, $valueOnSwitch) {
        return self::getValue('fn', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getApiParam ($defaultValue, $switch, $valueOnSwitch) {
        $param = urldecode(self::getValue('p', $defaultValue, $switch, $valueOnSwitch));
        //var_dump($param);
        parse_str($param, $param);
        if (empty($param))
            return $defaultValue;

        $param = libraryUtils::cleanQueryArray($param);
        
        // if single parameter
        // p=sometext
        // return 'sometext'
        $c = current($param);
        if (count($param) == 1 && empty($c))
            return $c;
        return $param;
    }
    static function getOID ($defaultValue, $switch, $valueOnSwitch) {
        return self::getValue('oid', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getValue($key, $defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::value($_GET, $key, $defaultValue, $switch, $valueOnSwitch);
    }

    /* get post values */
    static function getPostDisplay($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getPostValue('display', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getPostPage ($defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::getPostValue('page', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getPostAction ($defaultValue, $switch, $valueOnSwitch) {
        return self::getPostValue('action', $defaultValue, $switch, $valueOnSwitch);
    }
    static function getPostValue($key, $defaultValue = null, $switch = null, $valueOnSwitch = null) {
        return self::value($_POST, $key, $defaultValue, $switch, $valueOnSwitch);
    }
    static function getPostFormAction () {
        return self::value($_POST, 'do');
    }
    static function isPostFormAction ($equalsToThisValue) { 
        $do = self::value($_POST, 'do');
        //echo '<br>isPostFormAction === ' . $equalsToThisValue . ' == ' . $do;
        //echo ' equals: ' . ($do === strtolower($equalsToThisValue)?1:0);
        return strtolower($do) === strtolower($equalsToThisValue);
    }
    static function isPostFormActionMatchAny (/* looking for any action */) {
        // we'll return tru if there are some action that you're looking for'
        $num = func_num_args();
        $do = self::value($_POST, 'do');
        for ($i = 0; $i < $num; $i++)
            if (strcasecmp($do, func_get_arg($i)) === 0)
                return true;
        return false;
    }
    static function isPostFormActionMatchAll (/* looking for any action */) {
        // we'll return tru if there are some action that you're looking for'
        $num = func_num_args();
        $do = self::value($_POST, 'do');
        for ($i = 0; $i < $num; $i++)
            if (strcasecmp($do, func_get_arg($i)) !== 0)
                return false;
        return true;
    }

    static function getPostContainer (/* required fields */) {
        $container = array();
        if (!empty($args)) {
            $num = func_num_args();
            for ($i = 0; $i < $num; $i++)
                $container[func_get_arg($i)] = self::getPostValue(func_get_arg($i));
        }
        return $container;
    }
    static function getPostMapContainer ($map) {
        $container = array();
        if (!empty($map)) {
            foreach ($map as $containerKey => $postKey)
                if (empty($postKey))
                    $container[$containerKey] = '';
                else
                    $container[$containerKey] = self::getPostValue($postKey);
        }
        return $container;
    }

    static function storeOrGetRefererUrl ($store = true, $scope = '') {

        if(empty($scope))
            $scope = self::getDisplay();

        //var_dump(parse_url($_SERVER['HTTP_REFERER']));
        if ($store) {
            $_SESSION['MPWS_STORED_URL'.$scope] = $_SERVER['REQUEST_URI'];
        } else {
            $_data = parse_url($_SESSION['MPWS_STORED_URL'.$scope]);
            return $_data['path'] . '?' . $_data['query'];
        }
    }

    static function getNewUrl($key = '', $value = '', $remove = array('page', 'action')) {
        $_data = false;
        parse_str($_SERVER['QUERY_STRING'], $_data);
        $_data[$key] = $value;

        // remove hidden keys

        if(is_array($remove))
            foreach ($remove as $keyToRemove)
                unset($_data[$keyToRemove]);
        elseif (is_string($remove))
            unset($_data[$remove]);
        //unset($_data['action']);
        //$str = http_build_query($_data);
        //var_dump($str);

        return http_build_query($_data);
    }

    /* common */
    static function value($method, $key, $defaultValue = null, $switch = null, $valueOnSwitch = null) {
        if ($switch)
            return $valueOnSwitch;
        else {
            if (isset($method[$key])) {
                return $method[$key];
            } else
                return $defaultValue;
        }
    }
    
    public static function getOrValidatePageSecurityToken($keyToValidate = '') {
        if (!empty($keyToValidate))
            return $keyToValidate === self::getOrValidatePageSecurityToken();
        
        // make token
        $p = libraryRequest::getPage('undefined');
        $phash = md5($p);
        return $phash;
    }

    
    public static function postRedirect ($values, $host, $action) {
        $post_data = '';
        foreach($values as $key => $value)
            $post_data .= ($key . '=' . $value . '&');
        $content_length = strlen($post_data);
        header('POST ' . $action . ' HTTP/1.1');
        header('Host: ' . $host);
        header('Connection: close');
        header('Content-type: application/x-www-form-urlencoded');
        header('Content-length: ' . $content_length);
        header('');
        header($post_data);
    }
    
    public static function locationRedirect($values, $location) {
        $post_data = '';
        foreach($values as $key => $value)
            $post_data .= ($key . '=' . $value . '&');
        header('Location: ' . $location . '?' . $post_data);
    }
 
}


?>
