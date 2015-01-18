<?php
define('MPWS_ROOT', dirname(__FILE__) . '/');

spl_autoload_register(function ($className) {
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    if (file_exists(MPWS_ROOT . DIRECTORY_SEPARATOR . $fileName)) {
        require $fileName;
    }
    return false;
});

spl_autoload_register(function ($className) {
    $fileName = MPWS_ROOT . DIRECTORY_SEPARATOR . 'engine' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR;
    $fileName .= $className . '.php';
    if (file_exists($fileName)) {
        require $fileName;
    }
    return false;
});

use \engine\lib\request as Request;
use \engine\lib\response as Response;
use \engine\lib\path as Path;
use \engine\lib\utils as Utils;
use \engine\lib\database as DB;
use \engine\lib\site as Site;

// detect running customer name
// define('DR', glGetDocumentRoot());
// detect running customer name
// define('MPWS_TOOLBOX', 'toolbox');
// define('MPWS_CUSTOMER', glGetCustomerName());
// framework version
// define('MPWS_VERSION', 'atlantis');
// evironment mode
// define('MPWS_ENV', 'DEV'); // [PROD | DEV]
// how to show output of the debug function
// see: global/global.methods.php
// define('MPWS_LOG_LEVEL', 2); // 
// Path Formatters
// define ("DOT", ".");
// define ("DS", "/");
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
error_reporting(E_ALL);
ini_set("display_errors", 1);

Request::setPhpInput(file_get_contents('php://input'));

class app {

    private $isDebug = true;
    private $isToolbox = false;
    private $customerName = false;
    private $displayCustomer = false;
    private $runMode = false;
    private $site = false;
    private $header = false;
    private $buildVersion = false;
    private $environment = 'development';
    private $appName = 'atlantis';
    private $settings = array(
        'sessionTime' => 1800,
        'defaultDisplay'=> 'layout',
        'layout' =>'layout.hbs',
        'layoutBody' =>'layoutBody.hbs',
        'database' => array(
            'development' => array (
                'host' => 'localhost',
                'username' => 'root',
                'password' => '1111',
                'db' => 'mpws_light',
                "id_column" => 'ID',
                'charset' => 'utf8',
                'connection_string' => "mysql:dbname=mpws_light;host=localhost;charset=utf8",
                "driver_options" => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="STRICT_ALL_TABLES"',
                    PDO::ATTR_AUTOCOMMIT => false,
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
                )
            ),
            'production' => array (
                'host' => 'localhost',
                'username' => 'root',
                'password' => '1111',
                'db' => 'mpws_light',
                "id_column" => 'ID',
                'charset' => 'utf8',
                'connection_string' => "mysql:dbname=mpws_light;host=localhost;charset=utf8",
                "driver_options" => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="STRICT_ALL_TABLES"',
                    PDO::ATTR_AUTOCOMMIT => false,
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
                )
            )
        ),
        'urls' => array(
            'static' => '/static/',
            'api' => '/api/',
            'upload' => '/upload/'
        )
    );
    private $db = null;

    function __construct ($runMode = 'display', $header = 'Content-Type: text/html; charset=utf-8') {
        // header data
        $this->header = $header;
        // request type
        $this->runMode = empty($runMode) ? 'display' : $runMode;
        // check whether we runt toolbox mode
        $this->isToolbox = preg_match("/^" . 'toolbox' . "\./", $_SERVER['HTTP_HOST']) > 0;
        // get customer name
        $h = current(explode(':', $_SERVER['HTTP_HOST']));
        $h = strtolower($h);
        $host_parts = explode ('.', $h);
        $this->customerName = '';
        if ($host_parts[0] === 'www' || $host_parts[0] === 'toolbox')
            $this->customerName = implode('.', array_splice($host_parts, 1));
        else
            $this->customerName = $h;
        $this->customerName = str_replace('.', '_', $this->customerName);
        // get display customer
        $this->displayCustomer = $this->isToolbox() ? 'toolbox' : $this->customerName();
        // get build version
        $this->buildVersion = file_get_contents(Path::createPathWithRoot('version.txt'));
        // get runtime env
        if (file_exists('env.txt')) {
            $this->environment = file_get_contents(Path::createPathWithRoot('env.txt'));
        }
        if (empty($this->environment)) {
            $this->environment = 'development';
        }
        // setup DB
        $this->db = new DB($this->getDBConnection());
        $this->site = new Site();
    }

    private function getDBConnection () {
        $dbConfig = $this->getSettings('database');
        $env = $this->getEnvironment();
        return isset($dbConfig[$env]) ? $dbConfig[$env] : die("No database configuration for '$env' environment");
    }

    public function getAppName () {
        return $this->appName;
    }

    public function getDB () {
        return $this->db;
    }

    public function getEnvironment () {
        return $this->environment;
    }

    public function getBuildVersion () {
        return $this->buildVersion;
    }

    public function isDebug () {
        return $this->isDebug;
    }

    public function isToolbox () {
        return $this->isToolbox;
    }

    public function customerName () {
        return $this->customerName;
    }

    public function displayCustomer () {
        return $this->displayCustomer;
    }

    public function runMode () {
        return $this->runMode;
    }

    public function getSite () {
        return $this->site;
    }

    public function getResponse () {
        return Response::getResponse();
    }

    public function getJSONResponse () {
        return Response::getJSONResponse();
    }

    public function startApplication () {
        if (is_array($this->header))
            foreach ($this->header as $value) {
                header($value);
            }
        else
            header($this->header);

        $this->site->init();

        // $_customerScript = Utils::getCustomerClassName($this->customerName());// '\\web\\customers\\' . $this->customerName() . '\\customer';
        // glGetFullPath(DIR_WEB, DIR_CUSTOMER, MPWS_CUSTOMER, OBJECT_T_CUSTOMER . DOT . MPWS_CUSTOMER . EXT_SCRIPT);
        // $_customerClass = OBJECT_T_CUSTOMER . BS . MPWS_CUSTOMER;
        // include_once $_customerScript;
        // $customer = new $_customerClass();
        // $this->customer = new $_customerScript($this);
        $options = func_get_args();
        switch ($this->runMode()) {
            case 'api':
                $this->getSite()->runAsAPI($options);
                break;
            // case 'auth':
            //     $this->getSite()->runAsAUTH($options);
            //     break;
            case 'upload':
                $this->getSite()->runAsUPLOAD($options);
                break;
            case 'display':
                $this->getSite()->runAsDISPLAY($options);
                break;
            case 'snapshot':
                $this->getSite()->runAsSNAPSHOT($options);
                break;
            case 'sitemap':
                $this->getSite()->runAsSITEMAP($options);
                break;
            // case 'background':
            //     $this->customer->runAsBACKGROUND($options);
            //     // $this->startBackgroundTask($options);
            //     break;
            // default:
            //     throw new Exception("Error Processing Request: Unknown request type", 1);
        }
    }

    public function getSettings ($option = false) {
        if (isset($this->settings[$option])) {
            return $this->settings[$option];
        }
        return $this->settings;
    }

    // public function startBackgroundTask ($name = false) {
    //     echo 'start background task here';
    //     // exec('php test.php', $output, $return);
    //     // // Return will return non-zero upon an error
    //     // if (!$return) {
    //     //     echo "PDF Created Successfully";
    //     // } else {
    //     //     echo "PDF not created";
    //     // }
    //     $pid = pcntl_fork();
    //     if ($pid == -1) {
    //          die('could not fork');
    //     } else if ($pid) {
    //          // we are the parent
    //          pcntl_wait($status); //Protect against Zombie children
    //     } else {
    //          // we are the child
    //     }
    // }
}

?>