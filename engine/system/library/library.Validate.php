<?php

class libraryValidate {

    public static $FORMAT_PHONE = '(###) ###-##-##';

    // is taken from http://stackoverflow.com/a/18119964
    public static function validatePhoneNumber ($number) {
        $count = 0;
        $format = trim(preg_replace('/[0-9]/', '#', $number, -1, $count));
        if ($count !== substr_count(self::$FORMAT_PHONE, '#'))
            return false;
        return ($format === self::$FORMAT_PHONE) ? true : false;
    }

    public static function validatePassword ($password) {
        $uppercase = preg_match('/[A-Z]/', $password);
        $lowercase = preg_match('/[a-z]/', $password);
        $number    = preg_match('/[0-9]/', $password);
        $special   = preg_match('/[!@#$%&*?]/', $password);

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
        $dataTypes = array('string', 'int', 'float', 'null', 'bool', 'numeric', 'notNull');

        foreach ($dataRules as $keyToValidate => $rules) {

            $values[$keyToValidate] = isset($dataArray[$keyToValidate]) ? $dataArray[$keyToValidate] : null;
            $errors[$keyToValidate] = array();

            $wrongTypeCount = 0;
            $acceptedTypes = array_intersect($dataTypes, $rules);
            $acceptedTypesCount = count($acceptedTypes);

            // string
            if (in_array("string", $rules) && is_numeric($values[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNoString";
                $wrongTypeCount++;
            }

            // numeric
            if (in_array("numeric", $rules) && !is_numeric($values[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNotNumeric";
                $wrongTypeCount++;
            }

            // int
            if (in_array("int", $rules) && !is_int($values[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNotInt";
                $wrongTypeCount++;
            }

            // float
            if (in_array("float", $rules) && !is_float($values[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNotFloat";
                $wrongTypeCount++;
            }

            // bool
            if (in_array("bool", $rules) && !is_bool($values[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNotBoolean";
                $wrongTypeCount++;
            }

            // null
            if (in_array("null", $rules) && !is_null($values[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNotBoolean";
                $wrongTypeCount++;
            } else if (in_array("notNull", $rules) && is_null($values[$keyToValidate])) {
                // notnull
                $errors[$keyToValidate][] = $keyToValidate . "IsNull";
                $wrongTypeCount++;
            }

            // var_dump($keyToValidate);
            // var_dump($values[$keyToValidate]);
            // var_dump($wrongTypeCount);
            // var_dump($acceptedTypesCount);

            // we clear type errors when value type is acceptable
            if ($wrongTypeCount < $acceptedTypesCount)
                $errors[$keyToValidate] = array();

            // var_dump($errors);
            // var_dump($dataArray);
            // var_dump('isset:'.$keyToValidate . ':' . (isset($dataArray[$keyToValidate]) ? 1:0));

            // exists
            if (!array_key_exists($keyToValidate, $dataArray)) {
                if (in_array("skipIfUnset", $rules)) {
                    if (isset($rules["defaultValueIfUnset"]))
                        $values[$keyToValidate] = $rules["defaultValueIfUnset"];
                    else {
                        // var_dump('unset: '. $keyToValidate);
                        unset($values[$keyToValidate]);
                        unset($errors[$keyToValidate]);
                    }
                    continue;
                } else {
                    $errors[$keyToValidate][] = $keyToValidate . "IsNotExists";
                }
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
                $errors[$keyToValidate][] = "FormatMustBe__" . str_replace(' ', '_', self::$FORMAT_PHONE);
            }

            if (!empty($rules['min']) || !empty($rules['max'])) {
                if (is_string($values[$keyToValidate])) {
                    if (isset($rules['min']) && strlen($values[$keyToValidate]) < $rules['min']) {
                        $errors[$keyToValidate][] = $keyToValidate . "LengthIsLowerThan_" . $rules['min'];
                    }
                    if (isset($rules['max']) && strlen($values[$keyToValidate]) > $rules['max']) {
                        $errors[$keyToValidate][] = $keyToValidate . "LengthIsGreaterThan_" . $rules['max'];
                    }
                } elseif (is_numeric($values[$keyToValidate]) || is_int($values[$keyToValidate]) || is_float($values[$keyToValidate])) {
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
                $errors[$keyToValidate][] = $keyToValidate . "IsNull";
            }

            // equalTo
            if (isset($rules['equalTo']) && $values[$keyToValidate] !== $dataArray[$rules['equalTo']]) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNotEqualTo_" . $rules['equalTo'];
            }

            // inPairWith
            if (isset($rules['inPairWith']) && !empty($rules['inPairWith']) && !isset($dataArray[$rules['inPairWith']])) {
                $errors[$keyToValidate][] = $keyToValidate . "MissedRelatedField_" . $rules['inPairWith'];
            }

            if (empty($errors[$keyToValidate]))
                unset($errors[$keyToValidate]);
            else
                $totalErrors += count($errors[$keyToValidate]);
        }

        return array(
            "values" => $values,
            "errors" => $errors,
            "totalErrors" => $totalErrors,
            "count" => count($values)
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