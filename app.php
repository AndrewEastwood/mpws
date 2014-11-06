<?php
namespace engine;

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
    private $customer = false;
    private $header = false;
    private $buildVersion = false;

    function __construct ($runMode = 'display', $header = 'Content-Type: text/html; charset=utf-8') {
        // header data
        $this->header = $header;
        // request type
        $this->runMode = $runMode;
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

    public function customer () {
        return $this->customer;
    }

    public function getResponse () {
        return Response::getResponse();
    }

    public function getJSONResponse () {
        return Response::getJSONResponse();
    }

    public function startApplication () {
        header($this->header);
        session_start();
        $_customerScript = '\\web\\customer\\' . $this->customerName() . '\\customer';
        // glGetFullPath(DIR_WEB, DIR_CUSTOMER, MPWS_CUSTOMER, OBJECT_T_CUSTOMER . DOT . MPWS_CUSTOMER . EXT_SCRIPT);
        // $_customerClass = OBJECT_T_CUSTOMER . BS . MPWS_CUSTOMER;
        // include_once $_customerScript;
        // $customer = new $_customerClass();
        $this->customer = new $_customerScript($this);
        switch ($this->runMode()) {
            case 'api':
                $this->customer->runAsAPI();
                break;
            case 'auth':
                $this->customer->runAsAUTH();
                break;
            case 'upload':
                $this->customer->runAsUPLOAD();
                break;
            case 'display':
                $this->customer->runAsDISPLAY();
                break;
            // default:
            //     throw new Exception("Error Processing Request: Unknown request type", 1);
        }
    }
}

?>