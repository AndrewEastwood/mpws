<?php

class pluginReporting extends objectBaseWebPlugin {

    protected function _displayTriggerAsPlugin () {
        parent::_displayTriggerAsPlugin();
        $ctx = contextMPWS::instance();
        //echo '111OLOLO';
        //echo "<br><br>getInnerMethod = " . $ctx->getLastCommand()->getInnerMethod();
        switch($ctx->getLastCommand(false)->getInnerMethod()) {
            case 'dashboard' : 
                $rez = $this->_displayPage_Dashboard();
                break;
            case 'default' : 
            default :
                $rez = $this->_displayPage_Default();
                break;
        }

        return $rez;
    }
    
    protected function _jsapiTriggerAsPlugin() {
        parent::_jsapiTriggerAsPlugin();
        
        echo 'TROLOLOLOLOLOLOLOLOLO';
    }
    
    private function _displayPage_Dashboard () {
        //echo "OLOLO";
    }
    
    private function _displayPage_Default () {
        //echo 'DEFAULT TRIGGER';
        switch (libraryRequest::getDisplay()) {
            case "manager" : {
                /* standart data edit and view component complex */
                $this->actionHandlerAsDataViewEdit('ReportManager');
                break;
            }
            case "editor" : {
                $this->actionReportScriptEditor();
                break;
            }
            case "monitor" : {
                $this->actionCustomMonitor();
                break;
            }
            case "view" : {
                break;
            }
        }
    }
    
    /* custom handlers */
    
    private function actionReportScriptEditor () {
        $ctx = contextMPWS::instance();
        
        switch (libraryRequest::getAction()) {
            case "edit" : {
                
            }
            default: {
                $cfg = $this->objectConfiguration_widget_customReportScriptEditor;
                $data = $ctx->contextCustomer->getDBO()
                        ->reset()
                        ->select($cfg['fields'])
                        ->from($cfg['source'])
                        ->fetchData();
                // get all reports
                $this->addWidgetSimple('customReportScriptEditor', $data);
                break;
            }
        }
    }
    
    private function actionCustomMonitor () {
        $ctx = contextMPWS::instance();
        $cfg = $this->objectConfiguration_widget_customMonitor;
        $data = $ctx->contextCustomer->getDBO()
                ->reset()
                ->select($cfg['fields'])
                ->from($cfg['source'])
                ->fetchData();
        //$sf = new extensionSalesForceClient();
        //var_dump($sf);
        
        
        // get all reports
        $this->addWidgetSimple('customMonitor', $data);
    }


    /******************************************/

    function ___executeImport () {

        echo 'AUTOPROCESS' . PHP_EOL;

        $proceed = false;
        if (
            /* user name */
            isset($_GET['u']) && md5($_GET['u']) == '729a5211c37867a5112c5d2110908903'
            and /* password */
            isset($_GET['p']) && md5($_GET['p']) == '1cd87f5976c0893cb50d0758f528963f'
        )
            $proceed = true;
        /*
        if (!$proceed) {
            header('HTTP/1.0 404 Not Found');
            exit;
        }*/


        $dataPath = '/home/sites/b-voice.co.uk/public_html/data/reports/si';

        echo 'Starting import at : ' . date('Y-m-d H:i:s') . ' to ' . $dataPath . PHP_EOL;
        echo '--------------------------------------------------------------' . PHP_EOL;

        /* get all reports */
        $filenames = array();
        chdir( dirname ( __FILE__ ) );
        $iterator = new RecursiveDirectoryIterator('outbox');
        $procFileCount = 0;
        foreach (new RecursiveIteratorIterator($iterator) as $fileinfo) {
            if ($fileinfo->isFile()) {
                echo '--------------------------------------------------------------' . PHP_EOL;
                echo '[' . date('Y-m-d H:i:s') . '] processing ' . $fileinfo->getPathname() . '  >> to  >> ' . $dataPath . '/' . str_replace('-', '/', $fileinfo->getFilename()) . PHP_EOL;;
                rename($fileinfo->getPathname(), $dataPath . '/' . str_replace('-', '/', $fileinfo->getFilename()));
                $procFileCount++;
            }
        }

        echo '--------------------------------------------------------------' . PHP_EOL;
        echo '[' . date('Y-m-d H:i:s') . '] Imported ' . $procFileCount . ' file(s).' . PHP_EOL;
    
    }
    
    function ___repo () {
        $proceed = false;
        if (
            /* user name */
            isset($_GET['u']) && md5($_GET['u']) == '729a5211c37867a5112c5d2110908903'
            and /* password */
            isset($_GET['p']) && md5($_GET['p']) == '1cd87f5976c0893cb50d0758f528963f'
            and /* date ranges */
            isset($_GET['startdate']) && isset($_GET['enddate'])
        )
            $proceed = true;

        if (!$proceed) {
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        //var_dump($_POST);
        //var_dump($_FILES);

        $dataPath = $_SERVER['DOCUMENT_ROOT'] . 'data/reports/si';

        $reportName['s'] = str_replace(array('-', '.', '_', '%2F'), '/', $_GET['startdate']);
        $reportName['e'] = str_replace(array('-', '.', '_', '%2F'), '/', $_GET['enddate']);
        $reportPath['s'] = $dataPath . '/' . $reportName['s'] . '.csv';
        $reportPath['e'] = $dataPath . '/' . $reportName['e'] . '.csv';


        //getData($reportPath['s']);

        $data['s'] = getData($reportPath['s']);
        $data['e'] = getData($reportPath['e']);


        //$transformedData = $data;


        $transformedData = false;
        // filter data by --status
        //$status = array('Closed', 'AutoClosed - No Client Response');
        // remove unnecessary fields
        foreach ($data as $idx => $d) {
            $dt = false;
            foreach ($d as $rIdx => $rVal) {
                if (/*!in_array($rVal['Status'], $status) && */is_numeric($rVal['Case Number']))
                    $dt[$rVal['Case Number']] = $rVal;
            }
            $transformedData[$idx] = $dt;
        }

        // common cases
        $transformedData['c'] = false;
        foreach ($transformedData['e'] as $key => $entry) {
            //echo $key;
            if (isset($transformedData['s'][$key])) {
                $transformedData['c'][$key] = array('PREV' => $transformedData['s'][$key], 'CURR' => $entry);
            } else
                $transformedData['c'][$key] = array('PREV' => array(), 'CURR' => $entry);
        }

        // substract previous values
        foreach ($transformedData['c'] as $key => $entry) {

            $NEW = $entry['CURR'];
            //
            $NEW['Time Spent - Communication'] -= $entry['PREV']['Time Spent - Communication'];
            $NEW['Time Spent - Engineering'] -= $entry['PREV']['Time Spent - Engineering'];

            // custom fields
            $NEW['Previous Time Spent - Communication'] = $entry['PREV']['Time Spent - Communication'];
            $NEW['Previous Time Spent - Engineering'] = $entry['PREV']['Time Spent - Engineering'];
            $NEW['Current Time Spent - Communication'] = $entry['CURR']['Time Spent - Communication'];
            $NEW['Current Time Spent - Engineering'] = $entry['CURR']['Time Spent - Engineering'];
            $NEW['PreviousWeekTime'] = $entry['PREV']['Time Spent - Communication'] + $entry['PREV']['Time Spent - Engineering'];
            $NEW['CurrentWeekTime'] = $entry['CURR']['Time Spent - Communication'] + $entry['CURR']['Time Spent - Engineering'];
            $NEW['GeneralTime'] = $NEW['Time Spent - Communication'] + $NEW['Time Spent - Engineering'];

            $transformedData['c'][$key]['NEW'] = $NEW;
        }

        // worklog
        $worklog = false;
        foreach ($transformedData['c'] as $key => $entry) {
            // include logged work
            if ($entry['NEW']['GeneralTime'] != 0)
                $worklog[$key] = $entry['NEW'];
        }


        // merge data by id
        //$dataTable = $data['s'];




        debugData('<hr size="1"/>$transformedData[e]<hr size="1"/>');
        debugData('<pre>' . print_r($transformedData['e'], true) . '</pre>');
        debugData('<hr size="1"/>$transformedData[s]<hr size="1"/>');
        debugData('<pre>' . print_r($transformedData['s'], true) . '</pre>');
        debugData('<hr size="1"/>$transformedData[c]<hr size="1"/>');
        debugData('<pre>' . print_r($transformedData['c'], true) . '</pre>');
        debugData('<hr size="1"/>$worklog<hr size="1"/>');
        debugData('<pre>' . print_r($worklog, true) . '</pre>');



        echo renderData($worklog, isset($_GET['dw']));


        function debugData ($data) {
            if (isset($_GET['debug']))
                echo $data;
        }


        function renderData ($dataTable, $useHeader) {
            //echo '<pre>' . print_r(getData($reportPath['s']), true) . '</pre>';
            // output data
            //header("Content-type:application/vnd.ms-excel");
            if ($useHeader) {
                header("Content-type:text/csv");
                header("Content-Disposition: inline; filename=worklog.csv ");
            }
            $printFiledNames = true;
            //echo '-';
            //echo '<pre>' . print_r($dataTable, true) . '</pre>';
            $dataLines = '';
            foreach ($dataTable as $rIdx => $data) {
                //echo '<pre>' . print_r($data, true) . '</pre>';
                //echo '<pre>' . print_r(array_keys($data), true) . '</pre>';
                if ($printFiledNames) {
                    $dataLines .= '"' . implode('","', array_keys($data)) . '"' . PHP_EOL;// . '<br><br>';
                    $printFiledNames = false;
                }
                $dataLines .= '"' . implode('","', $data) . '"' . PHP_EOL;// . '<br><br>';
            }
            //echo '-';

            return $dataLines;
        }


        function getData($dataFilePath) {
            $row = 1;
            $headLine = 1;

            $head = false;
            $data = false;
            $dataTable = false;

            if (($handle = fopen($dataFilePath, 'r')) !== FALSE) {
                while (($data = fgetcsv($handle, 2000, ',')) !== FALSE) {
                    $num = count($data);
                    //echo "<p> $num fields in line $row: <br /></p>";
                    if ($row == $headLine) {
                        $head = $data;
                        //echo count($head) . ' field names';
                        //echo '<pre>' . print_r($head, true) . '</pre>';
                    } else {
                        $dataLine = false;
                        for ($c=0; $c < $num; $c++) {
                            $dataLine[$head[$c]] = $data[$c];
                            //echo count($dataLine) . ' data items in dataline;';
                            //echo '&nbsp;[&nbsp;' . $data[$c] . '&nbsp;]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        }
                        $dataTable[] = $dataLine;
                        //echo '<pre>' . print_r($dataLine, true) . '</pre>';
                    }
                    $row++;
                    //echo '<hr size="1"/>';
                }
                fclose($handle);
            }

            //echo '<pre>' . print_r($dataTable, true) . '</pre>';
            return $dataTable;
        }


        //var_dump($reportName); 
        //var_dump($reportPath); 

    }
    
}


?>

