<?php
namespace engine\objects;

class multiExtendable {

    private $extensions;

    public function addExtension ($extension) {
        // echo 'adding Extension: ' . get_class($extension) . PHP_EOL;
        // var_dump($extension);
        var_dump(basename(get_class($extension)));
        $this->extensions[get_class($extension)] = $extension;
    }

    public function getExtension ($extensionName) {
        return $this->extensions['extension' . $extensionName] ?: null;
    }

    public function hasExtension ($extensionName) {
        return !empty($this->extensions['extension' . $extensionName]);
    }

    public function __call($method, $args) {
        // var_dump($method);
        // var_dump('---------------------------------------------------------------------------------------------------------------------------');
        foreach ($this->extensions as $object) {
            // var_dump($object);
            if (method_exists($object, $method)) {
                return call_user_func_array(array($object, $method),$args);
            }
        }
        throw new \Exception(sprintf('Dynamic call of unexistant method %s on instance of class %s.',$method,get_class($this)));
    }
}

?>