<?php

class objectConfiguration implements iConfiguration {


    static function extendConfigs ($configA, $configB = null, $useRecursiveMerge = false) {

        return libraryUtils::array_merge_recursive_distinct($configA, $configB);

//         if (!is_array($configB))
//             return $configA;
// // 
//         $target = array_merge(array(), $configA);

//         // var_dump($configB);
//         foreach ($configB as $key => $value) {

//             if (!isset($target[$key])) {
//                 $target[$key] = $value;
//                 continue;
//             }

//             $classConfigValue = $target[$key];

//             if (is_array($classConfigValue)) {
//                 if ($useRecursiveMerge)
//                     $target[$key] = array_replace_recursive($classConfigValue, is_array($value) ? $value : array($value));
//                 else
//                     $target[$key] = array_merge($classConfigValue, is_array($value) ? $value : array($value));
//             }
//             else {
//                 // echo 'setting value ' . $value . ' by the key ' . $key;
//                 $target[$key] = $value;
//             }
//         }

//         // if (!empty($this->_config['condition']) && $this->_config['condition']['values'])

//         // var_dump($configB);
//         // echo "extendConfig", $this->_config;
//         return $target;
    }

}

?>