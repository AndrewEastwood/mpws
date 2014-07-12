<?php

class libraryValidate {

    public static function eachValueIsNotEmpty ($values, $keySToCheck = null) {
        $emptyDetected = false;
        if (!empty($keyToCheck)) {
            if (!is_array($keySToCheck))
                throw new Exception("Error Processing keySToCheck", 1);
            foreach ($keyToCheck as $key)
                if (isset($values[$key]))
                    $emptyDetected &= !empty($value);
                else
                    throw new Exception("Wrong key occured: " . $key, 1);
        } else {
            foreach ($values as $value)
                $emptyDetected &= !empty($value);
        }
        return $emptyDetected;
    }

    public static function eachValueIsEmpty ($values, $keySToCheck = null) {
        $emptyDetected = true;
        if (!empty($keyToCheck)) {
            if (!is_array($keySToCheck))
                throw new Exception("Error Processing keySToCheck", 1);
            foreach ($keyToCheck as $key)
                if (isset($values[$key]))
                    $emptyDetected &= empty($value);
                else
                    throw new Exception("Wrong key occured: " . $key, 1);
        } else {
            foreach ($values as $value)
                $emptyDetected &= empty($value);
        }
        return $emptyDetected;
    }

}

?>