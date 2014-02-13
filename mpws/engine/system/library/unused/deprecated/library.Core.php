<?php

// PHP Документ.
// Бібліотека Core.
// -----------------------
//
// Призначений для обробки та перетворення інформації.
//
// Для використання цього документу або його
// частин коду потрібно вказати авторське
// право в вигляді коментаря перед частиною
// коду, яка використовуєтья з цього документу.
//
// Автор: © 2009, Андрій Іваськевич.
// Author: © 2009, Andriy Ivaskevych.
//
// E-mail: soulcor@narod.ru

class Core
{    // _конструктор    function __construct() { }

    // _деструктор
    function __destruct() { }

    // Отримує значення з масиву за ключом.
    // $list - масив значень.
    // $key - ключ.
    // $type - тип глобального масиву (get, post, getpost, postget) *якщо встановлене значення то масив $list до уваги не бероеться.
    // Повертає отримане значення за ключем.
    public function GetValueByKey($list, $key, $type)
    {        // Значення
        $value = '';

        // Тип отримання значення
        switch ($type)
        {
            case 'post':
            {
                if (isset($_POST[$key]))
                    $value = $_POST[$key];
                break;
            }
            case 'get':
            {
                if (isset($_GET[$key]))
                    $value = $_GET[$key];
                break;
            }
            case 'postget':
            {
                if (isset($_POST[$key]))
                    $value = $_POST[$key];
                if (isset($_GET[$key]))
                    $value = $_GET[$key];
                break;
            }
            case 'getpost':
            {
                if (isset($_GET[$key]))
                    $value = $_GET[$key];
                if (isset($_POST[$key]))
                    $value = $_POST[$key];
                break;
            }
            default:
            {
                if (array_key_exists($key, $list))
                    $value = $list[$key];
            }
        }

        if (isset($value) && strlen($value) != 0)
            $value = strtolower($value);

        return $value;
    }

    // Отримує значення номеру сторінки таблиці з записами.
    // $pageName - назва сторінки.
    // Повертає ціле значення номеру сторінки або 0.
    public function GetPageValue($pageName, $offsetMultiplier = 1)
    {        if (isset($_GET[$pageName])) {
            $p = intval($_GET[$pageName]);
            $p--;
            if ($p >= 0) return $p * $offsetMultiplier;
        }
        return 0;
    }

    // Перевіряє чи певний індекс існує в масиві.
    // $list - масив.
    // $key - ключ який перевіряється на входження до масиву.
    // $defKey - ключ по замовчуванню. (якщо $key не знайдений в списку $list).
    // Повертає $key або $defKey, якщо вони входитять в $list, якщо ні, то повертає нульовий рядок.
    public function CheckForValidKey($list, $key, $defKey)
    {
        global $_POST;
        global $_GET;

        $validKey = '';

        if (array_key_exists($defKey, $list))
            $validKey = $defKey;

        switch ($type)
        {
            case 'post' :
            {
                if (isset($_POST[$key]) and array_key_exists($_POST[$key], $list))
                    $validKey = $_POST[$key];
                break;
            }
            case 'get' :
            {
                if (isset($_GET[$key]) and array_key_exists($_GET[$key], $list))
                    $validKey = $_GET[$key];
                break;
            }
            case 'postget' :
            {
                if (isset($_POST[$key]) and array_key_exists($_POST[$key], $list))
                    $validKey = $_POST[$key];
                if (isset($_GET[$key]) and array_key_exists($_GET[$key], $list))
                    $validKey = $_GET[$key];
                break;
            }
            case 'getpost' :
            {
                if (isset($_GET[$key]) and array_key_exists($_GET[$key], $list))
                    $validKey = $_GET[$key];
                if (isset($_POST[$key]) and array_key_exists($_POST[$key], $list))
                    $validKey = $_POST[$key];
                break;
            }
            default : {}
        }

        return strtolower($validKey);
    }

    // Циклічний зсув елементів масиву.
    // $array - масив елементів.
    // $size - кількість елементів які треба посунути.
    // $type - напрямок зсувау (left або right) *по замовчуванню right.
    // Нічого не повертає.
    public function ShiftArray(&$array, $size, $type)
    {        if ($size > count($array)) $size = $size - count($array);
        switch($type) {            case 'left': {                $s_arr = array_splice($array, 0, $size);
                $array = array_merge($array, $s_arr);                break;
            }
            case 'right':
            default: {                $s_arr = array_splice($array, count($array)- $size, $size);
                $array = array_merge($s_arr, $array);                break;
            }
        }
    }

    // Перевіряє вхідне значення на належність в масиві.
    // $value - вхідне значення (не ключ).
    // $array - масив, з значеннями.
    // Повертає true, якщо значення належить масиву інакше false.
    public function ArrayValueExists($value, $array, $skey = null)
    {        $count = 0;
        foreach ($array as $key => $val)
        {            if (is_array($val))
                $count += $this->ArrayValueExists($value, $val);

            if ($value === $val)
            {                if (isset($skey) && $skey === $key)
                    $count++;
                if (!isset($skey))
                    $count++;
            }
        }

        return $count;
    }

    // Додає значення до масиву.
    // $inputArray - вхідний масив.
    // $value - значення яке додається.
    // $key - ключ для значення (по заморвчкванню null).
    // $type - позиція в масиві, або begin або end (по замовчуванню begin).
    // Повертає новий масив з доданим значенням.
    public function AddItemToArray($inputArray, $value, $key = null, $type = 'begin')
    {        $mergeArray = Array();

        if (isset($key))
            $mergeArray[$key] = $value;
        else
            $mergeArray[] = $value;

        if (strcasecmp($type, 'begin') == 0)
            $inputArray = array_merge($mergeArray, $inputArray);
        else
            $inputArray = array_merge($inputArray, $mergeArray);

        return $inputArray;
    }

    public function GetXmlData($xmlFName)
    {        if (empty($xmlFName))
            return false;

        return new SimpleXMLElement(file_get_contents($xmlFName));
    }

    //
    //
    //
    //
    public function GetItemsToArray($type, $indexes)
    {        $_output = Array();
        $indexes = preg_split("/[\s,\s \s;\s:]+/", $indexes);
        switch (strtoupper($type))
        {            case 'GET':
                foreach ($indexes as $key => $val)
                    if (array_key_exists($val, $_GET))
                        $_output[$val] = $_GET[$val];
                    else
                        $_output[$val] = FALSE;
                break;
            case 'POST':
            default:
                foreach ($indexes as $key => $val)
                    if (array_key_exists($val, $_POST))
                        $_output[$val] = $_POST[$val];
                    else
                        $_output[$val] = FALSE;
                break;
        }

        return $_output;
    }

    // Get max vale from array.
    // $array - data array.
    // $key - some field key (dafault is FALSE).
    // $caseSensitive - use for key name (default is FALSE).
    // $value - default initialised value (default is 0).
    // Return max value from array by user definded key.
    public function GetMaxValue($array, $key = FALSE, $caseSensitive = FALSE, $value = 0)
    {        $_value = $value;
        foreach ($array as $_key => $val) {            if (is_array($val))
                $_value = $this->GetMaxValue($val, $key, $caseSensitive, $_value);
            else {                if ($k) {
                    if ($caseSensitive && $_key !== $key)
                        continue;
                    if (!$caseSensitive && strcasecmp($_key, $key) !== 0)
                        continue;
                }
                if ($_value < $val)
                    $_value = $val;
            }
        }

        return $_value;
    }

    public function GetMinValue($array, $k = FALSE, $caseSensitive = FALSE, $v = 0)
    {
        $value = $v;
        foreach ($array as $key => $val) {            if ($k) {
                if ($caseSensitive && $key !== $k)
                    continue;
                if (!$caseSensitive && strcasecmp($key, $k) !== 0)
                    continue;
            }
            if (is_array($val))
                $value = $this->GetMaxValue($val, $k, $caseSensitive, $value);
            else
                if ($value > $val)
                    $value = $val;
        }

        return $value;
    }

    public function IncludeFile($filePath)
    {
        return include(realpath($filePath));
    }

    public function IsIncluded($filePath,  $useInclude = false)
    {
        $isAppended = false;
        $appended = get_included_files();

        foreach ($appended as $value)
        {
            $_iname = basename($value);
            $_rname = basename($filePath);
            echo "Included File: ".$value;
            echo "<br />Comparing names: ".$_iname." and ".$_rname."<br />";
            if (strcmp($_iname, $_rname) == 0)
            {
                $isAppended = true;
                echo "File is already included";
                break;
            }
        }

        return $isAppended;
    }


    // Змінює GET параметри.
    // $key - новий або існуючий ключ.
    // $value - нове значення для ключа.
    // $deletedKeys - масив параметрів, які будуть видалені.
    // Повертає стрічку GET з зміненим або доданим параметром ?...&$key=$value&.
    public function SetGET($key = '', $value = '', $deletedKeys = Array(), $prefix = '?')
    {
        $get = $prefix;
        $b = false;
        foreach($_GET as $id => $val) {            if ($this->ArrayValueExists($id, $deletedKeys))
                continue;
            if ($id == $key) {
                $v = urlencode($value);
                $b = true;
            }
            else
                $v = $val;
            $get .= $id.'='.urlencode($v).'&amp;';
        }
        if ($b === false && $key !== '')
            $get .= $key.'='.urlencode($value).'&amp;';

        return $get;
    }

    // Функція отримує останню збережену url-адресу з GET параметрами.
    // $prefix - символи, які будуть добавлені до початку адреси.
    // $defurl - адреса, яка буде повертатися, коли небуде збереженої адреси.
    // Повертає url-адресу у вигляді стрічки.
    public function LastURL($save = FALSE, $id = '', $prefix = '', $defurl = '', $getlast = FALSE, $usedNoCaheLink = FALSE)
    {
        $link_stack = Array();
        $last_url = $defurl;

        if (!isset($_SESSION['lasturl'.$id]))
            $_SESSION['lasturl'.$id] = false;//session_register('lasturl'.$id);

        if (isset($_SESSION['lasturl'.$id]) && !empty($_SESSION['lasturl'.$id]))
            $link_stack = explode('|', $_SESSION['lasturl'.$id]);

        //var_dump($link_stack);
        if ($save) {            $curr_url = $_SERVER['QUERY_STRING'];

            if (empty($curr_url))
                return false;

            if ($usedNoCaheLink) {                $_p = explode('&', $curr_url);
                if (count($_p) == 1) $curr_url = str_replace($_p[0], '', $curr_link);
                $_lParam = array_pop($_p);
                if (strstr($_lParam, '=') == FALSE) $curr_url = str_replace($_p[0], '', $curr_link);
            }
            $newStack = Array();            foreach ($link_stack as $key => $val)
                if (strcasecmp($val, $curr_url) != 0) array_push($newStack, $val); else break;
            array_push($newStack, $curr_url);
            $newStack = implode('|', $newStack);
            //if (!isset($_SESSION['lasturl'.$id])) session_register('lasturl'.$id);
            $_SESSION['lasturl'.$id] = $newStack;
        } else if (count($link_stack) >= 2) {
            $z = array_pop($link_stack);
            $y = array_pop($link_stack);
            array_push($link_stack, $y);
            array_push($link_stack, $z);
            if ($getlast) $last_url = $z; else $last_url = $y;
        }

        return $prefix.$last_url;
    }

    // Відкриває адресу з параметрами.
    // $url - адреса.
    // Переходить за вказаною адресою або повертає false.
    public function GoToURL($url)
    {        if (!empty($url)) header("Location: " + $url);
        return false;
    }

    // Виконує PHP код.
    // $code - код.
    // $useCheck - перевірка параметра на виконання.
    // $data - вхідні дані.
    // Повертає результат виконання коду.
    public function EvaluateValue($exeCode, $useCheck = FALSE, $data = FALSE, $allow = TRUE)
    {
        $_isEval = false;
        $out = false;

        if (!empty($data) && is_array($data))
        {
            extract($data);
            //echo '<br>extracted '.$a.' elements<br>';
        }

        //echo 'defValue = '.$_defOut.'; value = '.$value.'; code = '.$exeCode.'<br>';

        // Виконуємо провірку коду за потребою
        if ($useCheck)
        {
            $_p = strpos($exeCode, ':');
            $_type = substr($exeCode, 0, $_p);
            $_isEval = ($_p >= 0 && strtolower($_type) == 'eval');
            if ($_isEval)
                $exeCode = substr($exeCode, $_p + 1);
        }

        // Виконуємо дані як код або повертаємо їх як значення
        if ($allow && $_isEval)
            eval($exeCode);

        if (!$_isEval)
            $out = $exeCode;
        else
            if (!$allow && isset($defValue))
                $out = $defValue;

        return $out;
    }

    // Обєднує GET параметри з вхідним гіперпосиланням.
    // $link - вхідне гіперпосилання.
    // Повертає обєднане гіперпосилання.
    public function JoinRequestToLink($link)
    {
        if (empty($link))
            return FALSE;

        $_rs = explode('&', $link);
        $rs = false;

        foreach ($_rs as $key => $val)
        {
            $_item = explode('=', $val);
            $rs[$_item[0]] = $_item[1];
        }

        $outputQuery = false;

        foreach ($_GET as $key => $val)
        {
            if (isset($rs[$key]))
            {
                $outputQuery[] = $key.'='.$rs[$key];
                unset($rs[$key]);
            }
            else
                $outputQuery[] = $key.'='.$val;
        }

        foreach ($rs as $key => $val)
            $outputQuery[] = $key.'='.$val;

        return '?'.implode('&', $outputQuery);
    }

    // Конвертування об'єкта в тип даних JSON.
    // $a - об'єкт даних.
    // Повертає символьни рядок в форматі JSON.
    public function PhpToJson($a = false)
    {        /**
        * Конвертирование объекта в тип данных json
        * Взято из JsHttpRequest: PHP backend for JavaScript DHTML loader.
        *
        * Convert a PHP scalar, array or hash to JS scalar/array/hash. This function is
        * an analog of json_encode(), but it can work with a non-UTF8 input and does not
        * analyze the passed data. Output format must be fully JSON compatible.
        *
        * @param mixed $a Any structure to convert to JS.
        * @return string JavaScript equivalent structure.
        *
        * This library is free software; you can redistribute it and/or
        * modify it under the terms of the GNU Lesser General Public
        * License as published by the Free Software Foundation; either
        * version 2.1 of the License, or (at your option) any later version.
        * See http://www.gnu.org/copyleft/lesser.html
        *
        * Do not remove this comment if you want to use the script!
        * Не удаляйте данный комментарий, если вы хотите использовать скрипт!
        *
        * This backend library also supports POST requests additionally to GET.
        *
        * @author Dmitry Koterov
        * @version 5.x $Id$
        *
        */

        if (is_null($a))
            return 'null';
        if ($a === false)
            return 'false';
        if ($a === true)
            return 'true';
        if (is_scalar($a))
        {
            if (is_float($a))
            {
                // Always use "." for floats.
                $a = str_replace(",", ".", strval($a));
            }
            // All scalars are converted to strings to avoid indeterminism.
            // PHP's "1" and 1 are equal for all PHP operators, but
            // JS's "1" and 1 are not. So if we pass "1" or 1 from the PHP backend,
            // we should get the same result in the JS frontend (string).
            // Character replacements for JSON.
            static $jsonReplaces = array(
                array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'),
                array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"')
            );

            return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a).'"';
        }

        $isList = true;
        for ($i = 0, reset($a); $i < count($a); $i++, next($a))
        {
            if (key($a) !== $i)
            {
                $isList = false;
                break;
            }
        }

        $result = array();
        if ($isList)
        {
            foreach ($a as $v)
                $result[] = $this->PhpToJSON($v);

            return '['.join(',', $result).']';
        }
        else
        {
            foreach ($a as $k => $v)
                $result[] = $this->PhpToJSON($k).':'.$this->PhpToJSON($v);

            return '{'.join(',', $result).'}';
        }
    }

    // Конвертування дані типу JSON в об'єкт.
    // $data - дані.
    // Повертає перекордований об'єкт.
    public function JsonToPhp($data)
    {        return json_decode($data);
    }

    public function setValueByKeyRecursive($array, $key, $value, $level = 0) {
    
        $r = false;
        foreach ($array as $k => $v) {
        
            if ($k == $key) {
                //echo '<div>set item for ' . $key. ' = with array = <pre>' . print_r($value, true) . '</pre></div>';
                
                $array[$k]['items'][] = $value;
                //echo '<div>result is <pre>' . print_r($array, true) . '</pre></div>';
                //print_r($v);
                $r = true;
                return $array;
            }
            if (is_array($v)) {
            
                $r = $this->setValueByKeyRecursive($v, $key, $value, $level + 1);
                if (is_array($r))
                    return $r;
            }
        }
        
        if ($level == 0) {
            $array[$key] = $value;
            return $array;
        }
        
        return false;
    }

    public function array_searchRecursive( $needle, $haystack, $strict=false, $path=array() )
    {
        if( !is_array($haystack) ) {
            return false;
        }
        foreach( $haystack as $key => $val ) {
            if( is_array($val) && $subPath = array_searchRecursive($needle, $val, $strict, $path) ) {
                $path = array_merge($path, array($key), $subPath);
                return $path;
            } elseif( (!$strict && $val == $needle) || ($strict && $val === $needle) ) {
                $path[] = $key;
                return $path;
            }
        }
        return false;
    }

    public function array_searchRecursiveByKey( $needle, $haystack, $strict=false, $path=array() )
    {
        if( !is_array($haystack) ) {
            return false;
        }
        foreach( $haystack as $key => $val ) {
            if( is_array($val) && $subPath = array_searchRecursive($needle, $val, $strict, $path) ) {
                $path = array_merge($path, array($key), $subPath);
                return $path;
            } elseif( (!$strict && $key == $needle) || ($strict && $key === $needle) ) {
                $path[] = $key;
                return $path;
            }
        }
        return false;
    }


}

?>
