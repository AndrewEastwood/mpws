<?php

class libraryValidate {

    public static $FORMAT_PHONE = array('(###) ###-##-##');

    // is taken from http://stackoverflow.com/a/18119964
    public static function validatePhoneNumber ($number) {
        $format = trim(preg_replace('/[0-9]/', '#', $number));
        return (in_array($format, self::$FORMAT_PHONE)) ? true : false;
    }

    public static function validatePassword ($password) {
        $uppercase = preg_match('/[A-Z]/', $password);
        $lowercase = preg_match('/[a-z]/', $password);
        $number    = preg_match('/[0-9]/', $password);
        $special   = preg_match('/[!@#$%*]/', $password);

        $errors = array();

        if(!$uppercase)
          $errors[] = "PasswordDoesNotContainAnyUpperCase";

        if(!$lowercase)
          $errors[] = "PasswordDoesNotContainAnyLowerCase";

        if(!$number)
          $errors[] = "PasswordDoesNotContainAnyNumber";

        if(!$special)
          $errors[] = "PasswordDoesNotContainAnySpecial";

        return $errors;
    }

    // @dataArray - just array with data :)
    // @rules - array with keys to extract from $dataArray
    // 
    public static function getValidData (array $dataArray, array $dataRules) {

        $values = array();
        $errors = array();
        $totalErrors = 0;

        foreach ($dataRules as $keyToValidate => $rules) {

            $values[$keyToValidate] = $dataArray[$keyToValidate] ?: null;
            $errors[$keyToValidate] = array();

            // string
            if (in_array("string", $rules) && is_numeric($values[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNoString";
            }

            // numeric
            if (in_array("numeric", $rules) && !is_numeric($values[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNotNumeric";
            }

            // int
            if (in_array("numeric", $rules) && !is_int($values[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNotInt";
            }

            // bool
            if (in_array("bool", $rules) && !is_bool($values[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNotBoolean";
            }

            // notnull
            if (in_array("notNull", $rules) && is_null($values[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNull";
            }

            // notIsset
            if (!in_array("notIsset", $rules) && !isset($dataArray[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNotSet";
            }

            // notEmpty
            if (in_array("notEmpty", $rules) && empty($dataArray[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsEmpty";
            }

            // email
            if (in_array("isEmail", $rules) && false === filter_var($dataArray[$keyToValidate], FILTER_VALIDATE_EMAIL)) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNotEmail";
            }

            // email
            if (in_array("isPassword", $rules)) {
                $err = self::validatePassword($dataArray[$keyToValidate]);
                $errors[$keyToValidate] = array_merge($errors[$keyToValidate], $err);
            }

            // phone
            if (in_array("isPhone", $rules) && false === self::validatePhoneNumber($dataArray[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNotPhone";
                $errors[$keyToValidate][] = "PhoneAvailableFormats";
                $errors[$keyToValidate][] = join(", ", self::$FORMAT_PHONE);
            }

            if (!empty($rules['min']) || !empty($rules['max'])) {
                if (is_string($values[$keyToValidate])) {
                    if (isset($rules['min']) && strlen($values[$keyToValidate]) < $rules['min']) {
                        $errors[$keyToValidate][] = $keyToValidate . "LengthIsLowerThan_" . $rules['min'];
                    }
                    if (isset($rules['max']) && strlen($values[$keyToValidate]) > $rules['max']) {
                        $errors[$keyToValidate][] = $keyToValidate . "LengthIsGreaterThan_" . $rules['max'];
                    }
                } elseif (is_numeric($values[$keyToValidate])) {
                    if (isset($rules['min']) && $values[$keyToValidate] < $rules['min']) {
                        $errors[$keyToValidate][] = $keyToValidate . "IsLowerThan_" . $rules['min'];
                    }
                    if (isset($rules['max']) && $values[$keyToValidate] > $rules['max']) {
                        $errors[$keyToValidate][] = $keyToValidate . "IsGreaterThan_" . $rules['max'];
                    }
                } elseif (is_array($values[$keyToValidate])) {
                    if (isset($rules['min']) && count($values[$keyToValidate]) < $rules['min']) {
                        $errors[$keyToValidate][] = $keyToValidate . "ArraySizeIsLowerThan_" . $rules['min'];
                    }
                    if (isset($rules['max']) && count($values[$keyToValidate]) > $rules['max']) {
                        $errors[$keyToValidate][] = $keyToValidate . "ArraySizeIsGreaterThan_" . $rules['max'];
                    }
                }
            }

            // regex
            if (in_array("regex", $rules) && preg_match($rules["regex"], $values[$keyToValidate]) !== 1) {
                $errors[$keyToValidate] = $keyToValidate . "IsNull";
            }

            $totalErrors += count($errors[$keyToValidate]);
        }

        return array(
            "values" => $values,
            "errors" => $errors,
            "totalErrors" => $totalErrors
        );
    }

    public static function eachValueIsNotEmpty ($values, $keySToCheck = null) {
        $emptyDetected = false;
        $emptyKeys = array();
        if (!empty($keyToCheck)) {
            if (!is_array($keySToCheck))
                throw new Exception("Error Processing keySToCheck", 1);
            foreach ($keyToCheck as $key)
                if (isset($values[$key])) {
                    $emptyDetected &= !empty($value);
                    if (empty($value))
                        $emptyKeys[] = $key;
                }
                else
                    throw new Exception("Wrong key occured: " . $key, 1);
        } else {
            foreach ($values as $value)
                $emptyDetected &= !empty($value);
        }
        if ($emptyDetected)
            return $emptyKeys;
        return $emptyDetected;
    }

    public static function eachValueIsEmpty ($values, $keySToCheck = null) {
        $emptyDetected = true;
        $nonEmptyKeys = array();
        if (!empty($keyToCheck)) {
            if (!is_array($keySToCheck))
                throw new Exception("Error Processing keySToCheck", 1);
            foreach ($keyToCheck as $key)
                if (isset($values[$key])) {
                    $emptyDetected &= empty($value);
                    if (!empty($value))
                        $nonEmptyKeys[] = $key;
                }
                else
                    throw new Exception("Wrong key occured: " . $key, 1);
        } else {
            foreach ($values as $value)
                $emptyDetected &= empty($value);
        }
        if (!$emptyDetected)
            return $nonEmptyKeys;
        return $emptyDetected;
    }

}

?>