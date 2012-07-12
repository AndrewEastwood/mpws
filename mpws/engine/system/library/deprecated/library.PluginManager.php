<?php

// PHP Документ.
// Бібліотека PluginManager.
// -----------------------
//
// Призначений для роботи з додатками.
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

class libraryPluginManager
{        // public read only    var $_plugins = false;
    var $_count = false;
    var $_names = Array();
    var $_titles = Array();
    var $_dir = false;

    function __construct () { }

    function __destruct () { }

    public function __get ($member) {        //echo  "Accessing to property ".$member
        $m = '_'.$member;        if (isset($this->$m)) return $this->$m; else return false;
    }

        public function Plugin ($name) {
                return $this->_plugins[$name];
        }

        public function Reload ($plist) {                //echo "Reloading all plugins";
                $this->PReset();
        return $this->Load($this->dir, $plist);
        }

        public function SetAvialible ($PluginName) {        if (array_key_exists($PluginName, $this->_plugins)) {            $this->_plugin = $this->_plugins[$PluginName];
            return true;
        }

        return false;
        }

        public function Avialible ($PluginName) {        return array_key_exists($PluginName, $this->_plugins);
        }

    // Завантажує дотатки з вказаної папки.
    // $plugin_directory - папка з додатками.
    // $plugin_names - назва додатків.
    // $plugins - реалізовані додатки.
    // Повертає true, якщо було завантажено хоча б один додаток, інакше false.
    public function Load ($dir, $plist) {
                global $_SESSION;
        
        $this->_dir = $dir;
        $pd = strtolower($dir);
        $valuesToLoad= Array();

        //echo "Accessing to plugin directory ".$pd;

        if ($plist == null)
        {
            //echo "Getting all existed plugins.";
            $valuesToLoad = $this->_getFiles("*.php", true);}
        else
        {
            //echo "Loading only defined plugins.";
            $valuesToLoad = $plist;
        }

        //echo "Plugins to load: <br />.var_dump($valuesToLoad);

        //var_dump($_SESSION['user_perm']);
        $plugin = array();
        foreach ($valuesToLoad as $key => $val)
        {
            $pn = strtolower($val);
            //echo 'loading:' . $pn;
            
            //var_dump(in_array('ALL', $_SESSION['user_perm']));
            //var_dump(in_array($pn, $_SESSION['user_perm']));

            if (!(in_array('ALL', $_SESSION['user_perm']) || in_array($pn, $_SESSION['user_perm'])))
                continue; //echo 'loading:' . $pn;
            
            if (file_exists($pd.'/'.$pn.'/'.$pn.'.plugin.php'))
            {
               include($pd.'/'.$pn.'/'.$pn.'.plugin.php');
               $plugin[$pn] = new $pn;
            }
        }

        $this->PUpdate($plugin);

        return $plugin;
    }

    /* PRIVATE MEMBERS */

    public function IncludeFile($filePath, $useInclude = false)
    {
        return include(realpath($filePath));
    }

    private function _getFiles($pattern = false, $FileOnly = NULL)
    {        $dirHandle = @opendir($ppd);

        if ($dirOrFile == NULL)
            while (false != ($paths[] = readdir($dirHandle)));
        else
            while (true) {
                if(false == ($_itemName = readdir($dirHandle)))
                    break;

                if ($FileOnly && is_file($ppd.'/'.$_itemName))
                    $filesArray[] = $_itemName;
                if (!$FileOnly && is_dir($ppd.'/'.$_itemName))
                    $dirArray[] = $_itemName;
            }

        $paths = array_merge($filesArray, $dirArray);

        if ($pattern) {
               // Відділяємо з масиву потрібні файли використовуючи фільтр
               $filesArray = preg_grep($pattern, $filesArray);
               // Переіндексовуємо масив
               $filesArray = array_values($filesArray);
        }

        // Закриваємо папку
        closedir($dirHandle);

        return $filesArray;
    }

    private function PReset()
    {
        $this->_plugins = Array();
            $this->_count = count($this->_plugins);
            $this->_names = Array();
            $this->_titles = Array();
    }

    private function PUpdate($plugs)
    {
        $this->_plugins = $plugs;
            $this->_count = count($plugs);
            $this->_names = Array();
            $this->_titles = Array();

        foreach ($plugs as $key => $val)
        {
            $p = $plugs[$key];
                $this->_titles[$key] = $p->config['_TITLE'];
                $this->_names[$key] = $p->config['_NAME'];
        }
    }

    // Get plugin's report.
    // $plugin_directory - plugin's folder.
    // $plugin_name - plugin's name.
    // $report_name - report's name.
    // Return report as Array() or null.
    public function GetPluginReport($pluginDir, $report_name)
    {
        // exit when no report
        if (empty($pluginDir) or empty($report_name)) return null;
        // define local variables
        $rd = strtolower($pluginDir);
        $fpath = $rd.DS.$report_name.'.xml';
        $xml = null;
        // open xml report and get data
        if (file_exists($fpath)) $xml = simplexml_load_file($fpath);
        if (isset($xml)) {
            $xmlData = Array();
            foreach ($xml as $key => $val) $xmlData[$key] = iconv('UTF-8', 'WINDOWS-1251', $val);
            $xml = $xmlData;
        }
        return $xml;
    }

}

?>
