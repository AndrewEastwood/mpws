<?php

class libraryValidator {

    public static function validateData ($data, $rules, &$messages = array()) {
        $_errorsAt = array();
        //echo 'validate string ';
        foreach ($data as $key => $value) {
            if (isset($rules[$key])) {
                //echo '<br>|  ['.$key.'] validating value = ' . (empty($value)?'EMPTY':$value);
                //echo ' PATTERN = ' . $rules[$key];
               
                if (!isset($value)) {
                    //echo '   ........<= has errro!!!';
                    $messages[] = 'Error is occured in the field "' . $key .'"';
                    $_errorsAt[] = $key;
                } elseif (!empty($rules[$key]) && !self::validateString($value, $rules[$key])) {
                    //echo '   ........<= has errro!!!';
                    $messages[] = 'Error is occured in the field "' . $key .'"';
                    $_errorsAt[] = $key;
                }
            }
        }
        //echo '<br><br><br><br><br>';
        return $_errorsAt;
    }

    public static function validateString ($string, $rule) {
        //echo 'validate string with rule ' . $rule . '<br>';
        
        // normlize rule
        if (!startsWith($rule, "/") && !endsWith($rule, "/"))
            $rule = "/" . $rule . "/";
        
        
        // call validate method
        if ($rule[0] === '@') {
            $fn = substr($rule, 1);
            $card = explode('|', $string);
            if (count($card) != 2)
                return false;
            return self::$fn($card[0], $card[1]);
        }

        $matches = null;
        $rez = $returnValue = preg_match($rule, $string, $matches);
        if ($rez === 0)
            return false;
        return (count($matches) == 1 && $matches[0] === $string);
    }

    function validateCreditCard($type, $ccnum) {
        //Original JS code is here:
        //http://www.breakingpar.com/bkp/home.nsf/0/87256B280015193F87256CC70060A01B
        if ($type == "Visa") {
            // Visa: length 16, prefix 4, dashes optional.
            $re = '/^4\d{3}-?\d{4}-?\d{4}-?\d{4}$/';
        } else if ($type == "MC") {
            // Mastercard: length 16, prefix 51-55, dashes optional.
            $re = '/^5[1-5]\d{2}-?\d{4}-?\d{4}-?\d{4}$/';
        } else if ($type == "Disc") {
            // Discover: length 16, prefix 6011, dashes optional.
            $re = '/^6011-?\d{4}-?\d{4}-?\d{4}$/';
        } else if ($type == "AmEx") {
            // American Express: length 15, prefix 34 or 37.
            $re = '/^3[4,7]\d{13}$/';
        } else if ($type == "Diners") {
            // Diners: length 14, prefix 30, 36, or 38.
            $re = '/^3[0,6,8]\d{12}$/';
        }
        $matches = null;
        preg_match($re, $ccnum, $matches);
        if (count($matches) != 1 || $matches[0] !== $ccnum) return false;
        // Remove all dashes for the checksum checks to eliminate negative numbers
        $ccnum =  str_replace('-', '', $ccnum);
        // Checksum ("Mod 10")
        // Add even digits in even length strings or odd digits in odd length strings.
        $checksum = 0;
        for ($i=(2-(strlen($ccnum) % 2)); $i<=strlen($ccnum); $i+=2) {
            $checksum += $ccnum[$i-1];
        }
        // Analyze odd digits in even length strings or even digits in odd length strings.
        for ($i=(strlen($ccnum) % 2) + 1; $i<strlen($ccnum); $i+=2) {
            $digit = $ccnum[$i-1] * 2;
            if ($digit < 10) { $checksum += $digit; } else { $checksum += ($digit-9); }
        }
        if (($checksum % 10) == 0) return true; else return false;
    }
    
    
    public static function validateStandartMpwsFields ($fields, $rules) {
        // getting standart field names
        $_fields = array();
        foreach ($fields as $value) {
            $_fields[$value] = 'mpws_field_' . strtolower($value);
        }
        // get data
        $_data = libraryRequest::getPostMapContainer($_fields);
        //var_dump($_fields);
        // validate data
        return self::validateData($_data, $rules);
    }

}


?>
