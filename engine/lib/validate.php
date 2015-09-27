<?php

namespace engine\lib;

class validate {

    public static $FORMAT_PHONE = '(###) ###-##-##';

    // is taken from http://stackoverflow.com/a/18119964
    public static function validatePhoneNumber ($number) {
        $count = 0;
        $format = trim(preg_replace('/[0-9]/', '#', $number, -1, $count));
        if ($count !== substr_count(self::$FORMAT_PHONE, '#'))
            return false;
        return ($format === self::$FORMAT_PHONE) ? true : false;
    }

    public static function getEmptyPhoneNumber () {
        return str_replace('#', 0, self::$FORMAT_PHONE);
    }
    public static function getEmptyEmail () {
        return 'mpws+' . mktime() . '@mailinator.com';
    }

    public static function validatePassword ($password) {
        $uppercase = preg_match('/[A-Z]/', $password);
        $lowercase = preg_match('/[a-z]/', $password);
        $number    = preg_match('/[0-9]/', $password);
        $special   = preg_match('/[!@#$%&*?\)\(]/', $password);

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
        $dataTypes = array('string', 'int', 'float', 'null', 'bool', 'sqlbool', 'numeric', 'notNull', 'array');

        foreach ($dataRules as $keyToValidate => $rules) {

            $values[$keyToValidate] = isset($dataArray[$keyToValidate]) ? $dataArray[$keyToValidate] : null;
            $errors[$keyToValidate] = array();

            // exists
            if (!array_key_exists($keyToValidate, $dataArray)) {
                if (in_array("skipIfUnset", $rules, true)) {
                    unset($errors[$keyToValidate]);
                    if (isset($rules["defaultValueIfUnset"]))
                        $values[$keyToValidate] = $rules["defaultValueIfUnset"];
                    else {
                        // var_dump('unset: '. $keyToValidate);
                        unset($values[$keyToValidate]);
                    }
                } else {
                    $errors[$keyToValidate][] = $keyToValidate . "IsNotExists";
                }
                continue;
            }

            if (in_array("skipIfNull", $rules, true) && is_null($values[$keyToValidate])) {
                unset($errors[$keyToValidate]);
                unset($values[$keyToValidate]);
                continue;
            }

            if (in_array("skipIfEmpty", $rules, true) && empty($values[$keyToValidate])) {
                if (isset($values[$keyToValidate]) && empty($values[$keyToValidate]) && isset($rules["defaultValueIfEmpty"])) {
                    $values[$keyToValidate] = $rules["defaultValueIfEmpty"];
                    continue;
                }
                unset($errors[$keyToValidate]);
                continue;
            }

            $wrongTypeCount = 0;
            $acceptedTypes = array_intersect($dataTypes, $rules);
            $acceptedTypesCount = count($acceptedTypes);

            // string
            if (in_array("string", $rules, true) && (!is_string($values[$keyToValidate]) || is_numeric($values[$keyToValidate]))) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNoString";
                $wrongTypeCount++;
            }

            // array
            if (in_array("array", $rules, true) && !is_array($values[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNotArray";
                $wrongTypeCount++;
            }

            // numeric
            if (in_array("numeric", $rules, true) && !is_numeric($values[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNotNumeric";
                $wrongTypeCount++;
            }

            // int
            if (in_array("int", $rules, true) && !is_int($values[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNotInt";
                $wrongTypeCount++;
            }

            // float
            if (in_array("float", $rules, true) && !is_float($values[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNotFloat";
                $wrongTypeCount++;
            }

            // bool
            if (in_array("bool", $rules, true)) {
                if (!is_bool($values[$keyToValidate])) {
                    $errors[$keyToValidate][] = $keyToValidate . "IsNotBoolean";
                    $wrongTypeCount++;
                } elseif (in_array("transformToTinyInt", $rules, true)) {
                    $values[$keyToValidate] = $values[$keyToValidate] ? 1 : 0;
                }
            }

            // bool
            if (in_array("sqlbool", $rules, true)) {
                if (isset($dataArray[$keyToValidate])) {
                    $values[$keyToValidate] = !empty($values[$keyToValidate]) ? 1 : 0;
                } else {
                    $errors[$keyToValidate][] = $keyToValidate . "IsNotSet";
                    $wrongTypeCount++;
                }
            }

            // null
            if (in_array("null", $rules, true) && !is_null($values[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNotBoolean";
                $wrongTypeCount++;
            } else if (in_array("notNull", $rules, true) && is_null($values[$keyToValidate])) {
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

            // notEmpty
            if (in_array("notEmpty", $rules, true) && empty($dataArray[$keyToValidate])) {
                $errors[$keyToValidate][] = $keyToValidate . "IsEmpty";
            }

            // email
            if (in_array("isEmail", $rules, true) && false === filter_var($dataArray[$keyToValidate], FILTER_VALIDATE_EMAIL)) {
                $errors[$keyToValidate][] = $keyToValidate . "IsNotEmail";
            }

            // email
            if (in_array("isPassword", $rules, true) && isset($dataArray[$keyToValidate])) {
                $err = self::validatePassword($dataArray[$keyToValidate]);
                $errors[$keyToValidate] = array_merge($errors[$keyToValidate], $err);
            }

            // phone
            if (in_array("isPhone", $rules, true) && false === self::validatePhoneNumber($dataArray[$keyToValidate])) {
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
            if (in_array("regex", $rules, true) && preg_match($rules["regex"], $values[$keyToValidate]) !== 1) {
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

            // replace value if orig is true
            if (isset($rules['ifTrueSet']) && $values[$keyToValidate]) {
                $values[$keyToValidate] = $rules['ifTrueSet'];
                // echo 'setting up true value';
            }

            // replace value if orig is true
            if (isset($rules['ifFalseSet']) && !$values[$keyToValidate]) {
                $values[$keyToValidate] = $rules['ifFalseSet'];
                // echo 'setting up false value';
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