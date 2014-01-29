<?php

class libraryUtils {

    /**
     * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
     * keys to arrays rather than overwriting the value in the first array with the duplicate
     * value in the second array, as array_merge does. I.e., with array_merge_recursive,
     * this happens (documented behavior):
     * 
     * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('org value', 'new value'));
     * 
     * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
     * Matching keys' values in the second array overwrite those in the first array, as is the
     * case with array_merge, i.e.:
     * 
     * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => 'new value');
     * 
     * Parameters are passed by reference, though only for performance reasons. They're not
     * altered by this function.
     * 
     * @param array $array1
     * @param mixed $array2
     * @author daniel@danielsmedegaardbuus.dk
     * @return array
     */
    static function &array_merge_recursive_distinct(array &$array1, &$array2 = null)
    {
      $merged = $array1;
     
      if (is_array($array2))
        foreach ($array2 as $key => $val)
          if (is_array($array2[$key]))
            $merged[$key] = is_array($merged[$key]) ? self::array_merge_recursive_distinct($merged[$key], $array2[$key]) : $array2[$key];
          else
            $merged[$key] = $val;
     
      return $merged;
    }

}

?>