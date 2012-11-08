<?php

class pluginReporting extends objectBaseWebPlugin {

    private $_dirWithReportScripts = 'scripts';
    private $_dirWithReportData = 'data';
    private $_dirWithReportUI = 'ui';
    
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
        $p = libraryRequest::getApiParam();
        $rez = false;
        // detect action
        //var_dump($p['custom']);
        switch(libraryRequest::getApiFn()) {
            // request url: 
            // api.js?caller=reporting&fn=fetchdata&p=token%3DXXXXX%26oid%3D1%26type%3Dreleases%26startdate%3D2012-10-23%26enddate%3D2012-10-30%26realm%3Dplugin
            // token=XXXXX&oid=1&type=releases&startdate=2012-10-23&enddate=2012-10-30&realm=plugin&script=%scriptNameToRender%
            case "fetchdata" : {
                //echo 'fetching data';
                $rez = $this->customGetReportData($p['oid'], $p['custom']['type'], $p['custom']['startdate'], $p['custom']['enddate']);
                break;
            }
            case "sf-import" : {
                echo 'SF import';
                
                // get SF account
                $sfAccount = $this->objectConfiguration_accounts_salesForceAI;
                $this->customSalesForceImport($sfAccount);
                break;
            }
            case "outbox-proc" : {
                break;
            }
            case "render" : {
                //echo "RENDER OK";
                // TODO
                // get report widget        
                if (empty($p['oid']) || !is_numeric($p['oid']))
                    throw new Exception('ReportData: wrong request');
                //if (empty($script))
                //    throw new Exception('ReportData: wrong script name');
                $ctx = contextMPWS::instance();
                $cfg = $this->objectConfiguration_widget_customMonitor;
                // fetch owner record
                $report = $ctx->contextCustomer->getDBO()
                        ->reset()
                        ->select('*')
                        ->from($cfg['source'])
                        ->where('ID', '=', $p['oid'])
                        ->fetchRow();
                // fetch report script ui
                $uiFilepath = libraryPath::getStandartDataPathWithDBR($report, $this->_dirWithReportUI.DS.$p['custom']['script'].EXT_TEMPLATE);
                $scriptFilepath = libraryPath::getStandartDataPathWithDBR($report, $this->_dirWithReportScripts.DS.$p['custom']['script'].EXT_JS);
                //echo $scriptFilepath;
                //echo file_get_contents($scriptFilepath);
                $rez = libraryUtils::getJSON(array(
                    "KEY" => $p['custom']['script'],
                    "UI" => file_get_contents($uiFilepath),
                    "SCRIPT" => file_get_contents($scriptFilepath)
                ));
                break;
            }
        }
        // put data
        $ctx = contextMPWS::instance();
        $ctx->pageModel->addStaticData($this->customJsRenderData($rez));
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
            case "script-editor" : {
                $this->actionHandlerCustomReportScriptEditor();
                break;
            }
            case "monitor" : {
                $this->actionHandlerCustomMonitor();
                break;
            }
            case "view" : {
                break;
            }
            case "api" : {
                $this->addWidgetSimple('customApiSettings');
                break;
            }
        }
    }
    
    /* action hooks */
    /*final protected function hookBeforeAddWidgetDataRecordManager ($widgetName, &$wgtData, $wgtConfig) {
        parent::hookBeforeAddWidgetDataRecordManager($widgetName, $wgtData, $wgtConfig);
        $ext = array();
        switch ($widgetName) {
            case "ReportManager" : {
                
                
                break;
            }
        }

        $wgtData['MANAGER'] = $ext;
    }*/
    
    /* custom action handlers */
    
    private function actionHandlerCustomReportScriptEditor () {
        $ctx = contextMPWS::instance();
        $cfg = $this->objectConfiguration_widget_customReportScriptEditor;
        
        $data = array();
        $errors = array();
        $data['ACTION'] = libraryRequest::getAction();
        
        switch ($data['ACTION']) {
            case "editreport" : {
                
                
                
                
                // check owner existance
                $ownerOID = libraryRequest::getOID();
                $scriptName = libraryRequest::getValue('script');
                if (empty($ownerOID) || !is_numeric($ownerOID))
                    $errors['owneroid'] = 'wrongOwnerOID';
                // fetch owner record
                $ownerData = $ctx->contextCustomer->getDBO()
                        ->reset()
                        ->select('*')
                        ->from($cfg['source'])
                        ->where('ID', '=', $ownerOID)
                        ->fetchRow();
                // check owner data for existance
                if (empty($ownerData))
                    $errors['ownerdata'] = 'emptyOwnerRecord';
                // get script path
                $scriptFilepath = libraryPath::getStandartDataPathWithDBR($ownerData, $this->_dirWithReportScripts.DS.$scriptName.EXT_JS, true);
                
                // fetch data
                if (libraryRequest::isPostFormAction('save')) {
                    // save data
                    $scriptData = libraryUtils::getWithEOL(libraryRequest::getPostFormField('data'));
                    file_put_contents($scriptFilepath, $scriptData);
                } else {
                    // get data content
                    $scriptData = file_get_contents($scriptFilepath);
                }
                $data['SCRIPT'] = $scriptData;
            }
            default: {
                // get all reports
                $data['REPORTS'] = $ctx->contextCustomer->getDBO()
                        ->reset()
                        ->select($cfg['fields'])
                        ->from($cfg['source'])
                        ->fetchData();
                break;
            }
        }
        $this->addWidgetSimple('customReportScriptEditor', $data);
    }
    
    private function actionHandlerCustomMonitor () {
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
    
    /* all custom methods are below */

    private function customGetReportData ($oid, $type, $start, $end) {
        $data = false;
        
        if (empty($oid) || !is_numeric($oid))
            throw new Exception('ReportData: wrong request');
        //if (empty($script))
        //    throw new Exception('ReportData: wrong script name');
        $ctx = contextMPWS::instance();
        $cfg = $this->objectConfiguration_widget_customMonitor;
        // fetch owner record
        $report = $ctx->contextCustomer->getDBO()
                ->reset()
                ->select('*')
                ->from($cfg['source'])
                ->where('ID', '=', $oid)
                ->fetchRow();

        // data selector
        switch($type) {
            // default feature
            case 'standart' : {
                $data = $this->customGetWorklogSF_rt1($report, $start, $end, 'GeneralTime');
                break;
            }
            // custom implementation
            case 'releases' : {
                $data = $this->customGetWorklogByReleasesSF_rt1($report, $start, $end, false);
                //debugData('<pre>' . print_r($data, true) . '</pre>');
                //combine all releases into one JSON object
                $releases = false;
                foreach($data as $relKey => $relEntry)
                    if (!empty($relEntry))
                        $releases[] = $this->customJsRenderData($relEntry);
                //$data = json_encode($releases);
                //$data = PhpToJson($data);
                //debugData('<hr size="1"/>RELEASES-SI<hr size="1"/><pre>' . print_r($data, true) . '</pre>');
                $data = implode(PHP_EOL . '^^^^^^^^^^^^^^^^^' . PHP_EOL, $releases);
                break;
            }
            // custom implementation
            case 'releases-full' : {
                $data = $this->customGetWorklogByReleasesSF_rt1($report, $start, $end, 'full');
                //debugData('<pre>' . print_r($data, true) . '</pre>');
                $data = $data['full'];
                break;
            }
        }
        return $data;
    }
    
    private function customGetWorklogSF_rt1 ($report, $sDt, $eDt, $filterEmptyValues = '') {
        //global $dataPath;
        //echo 'getWorklog : data ' . $sDt . ' >>>>  ' . $eDt .'<br>';
        $dataPath = libraryPath::getStandartDataPathWithDBR($report, $this->_dirWithReportData, true);
        
        $reportName['s'] = str_replace(array('-', '.', '_', '%2F'), DS, $sDt);
        $reportName['e'] = str_replace(array('-', '.', '_', '%2F'), DS, $eDt);
        $reportPath['s'] = $dataPath . DS . $reportName['s'] . '.csv';
        $reportPath['e'] = $dataPath . DS . $reportName['e'] . '.csv';
        $reportMetadataPath['s'] = $dataPath . DS . $reportName['s'] . '.dat';
        $reportMetadataPath['e'] = $dataPath . DS . $reportName['e'] . '.dat';


        //getData($reportPath['s']);

        $data['s'] = $this->customCommonGetData($reportPath['s']);
        $data['e'] = $this->customCommonGetData($reportPath['e']);

        $metadata['s'] = $this->customCommonGetMetadata($reportMetadataPath['s']);
        $metadata['e'] = $this->customCommonGetMetadata($reportMetadataPath['e']);

        //echo('<hr size="1"/>$data<hr size="1"/>');
        //echo('<pre>' . print_r($data, true) . '</pre>');

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
            // if there is previous info about the running record
            if (isset($transformedData['s'][$key])) {
                $transformedData['c'][$key] = array('PREV' => $transformedData['s'][$key], 'CURR' => $entry);
            } else
                $transformedData['c'][$key] = array('PREV' => array(), 'CURR' => $entry);

            // previous record metadata

            /*
            $pMd = extractMetdataInfo($metadata['s'], array(
                'path' => 'CUSTOM_FIELDS.CF_ReleaseName',
                'valueKey' => 'CF_ReleaseName'
            ));
            $transformedData['c'][$key]['PREV_METADATA'] = $pMd;

            $cMd = extractMetdataInfo($metadata['e'], array(
                'path' => 'CUSTOM_FIELDS.CF_ReleaseName',
                'valueKey' => 'CF_ReleaseName'
            ));
            $transformedData['c'][$key]['CURR_METADATA'] = $cMd;
            */

        }
        
        //echo('<pre>' . print_r($transformedData['c'], true) . '</pre>');
        

        // substract previous values
        foreach ($transformedData['c'] as $key => $entry) {

            $NEW = $entry['CURR'];
            
            
            if (!isset($NEW['Time Spent - Communication'])) 
                $NEW['Time Spent - Communication'] = 0;
            if (!isset($entry['PREV']['Time Spent - Communication'])) 
                $entry['PREV']['Time Spent - Communication'] = 0;
            if (!isset($entry['CURR']['Time Spent - Communication'])) 
                $entry['CURR']['Time Spent - Communication'] = 0;
            
            if (!isset($NEW['Time Spent - Engineering'])) 
                $NEW['Time Spent - Engineering'] = 0;
            if (!isset($entry['PREV']['Time Spent - Engineering'])) 
                $entry['PREV']['Time Spent - Engineering'] = 0;
            if (!isset($entry['CURR']['Time Spent - Engineering'])) 
                $entry['CURR']['Time Spent - Engineering'] = 0;
            
            
            //echo $NEW["Case Number"] . '|' . $NEW["Case Record Type"] . '|' . $NEW['Time Spent - Communication'] . '<br>';

            // existed fields
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

            // additional fields
            //$NEW['CF_AtWork'] = 0;
            //$NEW['CF_ReleaseName'] = 0;

            //echo '-------------<br>';
            
            $transformedData['c'][$key]['NEW'] = $NEW;
        }

        //debugData('<pre>' . print_r($transformedData['c'], true) . '</pre>');

        /*
        debugData('<hr size="1"/>$transformedData[e]<hr size="1"/>');
        debugData('<pre>' . print_r($transformedData['e'], true) . '</pre>');
        debugData('<hr size="1"/>$transformedData[s]<hr size="1"/>');
        debugData('<pre>' . print_r($transformedData['s'], true) . '</pre>');
        debugData('<hr size="1"/>$transformedData[c]<hr size="1"/>');
        debugData('<pre>' . print_r($transformedData['c'], true) . '</pre>');
        debugData('<hr size="1"/>$worklog<hr size="1"/>');
        debugData('<pre>' . print_r($worklog, true) . '</pre>');
        */

        // worklog
        $worklog = false;
        foreach ($transformedData['c'] as $key => $entry) {
            // include logged work
            //echo $entry['NEW']['GeneralTime'];
            if (empty($filterEmptyValues))
                $worklog[$key] = $entry['NEW'];
            else
                if (!empty($entry['NEW'][$filterEmptyValues]))
                    $worklog[$key] = $entry['NEW'];
        }

        //debugData('<pre>' . print_r($worklog, true) . '</pre>');

        return $worklog;
    }

    private function customGetWorklogByReleasesSF_rt1 ($report, $sDt, $eDt, $relName = false) {
            // get date range
            $dateRanges = libraryUtils::createDateRangeArray($sDt, $eDt);

            // get metadata files
            $metaDataFiels = false;
            foreach($dateRanges as $dt) {
                $_metadata = $this->customCommonGetMetadataOfDate($report, $dt);
                if (!empty($_metadata))
                    $metaDataFiels[$dt] = $_metadata;
            }

            // group dates by relese names
            $releases = false;
            foreach($metaDataFiels as $dtKey => $mdEntry) {
                if (!empty($relName))
                    $releaseName = $relName;
                else
                    $releaseName = libraryUtils::getPathValue($mdEntry, 'CUSTOM_FIELDS.CF_ReleaseName');

                $releases[$releaseName]['MD'][$dtKey] = $mdEntry;
                $releases[$releaseName][] = $dtKey;

                // add to next release if it is final relase
                $releaseNextName = libraryUtils::getPathValue($mdEntry, 'CUSTOM_FIELDS.CF_NextRelease');
                if (empty($relName) && !empty($releaseNextName)) {
                    $releases[$releaseNextName]['MD'][$dtKey] = $mdEntry;
                    $releases[$releaseNextName][] = $dtKey;
                }
            }

            //debugData('<pre>' . print_r($releases, true) . '</pre><hr size="1"/><hr size="1"/>');

            //exit;

            $releaseDayRenages = false;

            // get data by releases
            $releaseWorklogs = false;
            foreach($releases as $relKey => $relDates) {
                //$_sDt = current($relDates);
                //$_eDt = end($relDates);
                $_sDt = $relDates[0];
                $_eDt = end($relDates);
                //echo 'relese ' . $relKey . ' and report between '  . $_sDt . ' and ' . $_eDt . '<br>';

                //$sDt = $relDates[0];
                //$eDt = $relDates[count($relDates) - 1];
                //var_dump(getWorklog($sDt, $eDt));
                //debugData('<br> getting worklog ' . $_sDt . ' >>> ' . $_eDt . ' | cmp: ' . strcmp($_sDt, $_eDt));
                if (strcmp($_sDt, $_eDt) != 0) {

                    $releaseWorklogs[$relKey] = $this->customGetWorklogSF_rt1($report, $_sDt, $_eDt);

                    //debugData('<br> ------------- worklog: <pre>' . print_r($releaseWorklogs[$relKey], true) . '</pre><br><hr size="1"/><hr size="1"/>');


                    //$date = "1998-08-14";
                    $newdate = strtotime ( '-1 week' , strtotime ( $_eDt ) ) ;
                    $newdate = date ( 'Y-m-d' , $newdate );
                    //echo '<br> ===== new date is ' . $newdate;
                    $isFullRelease = ($newdate === $_sDt);
                    $releaseDayRenages[$relKey] = array(
                        "START" => $_sDt,
                        "END" => $_eDt,
                        "IS_FULL_RELEASE" => $isFullRelease,
                        "SKIP_MD_OF_DAY" => $isFullRelease? $_sDt : false
                    );
                    /*
                    debugData('<br> ------------- worklog info: <pre>' . print_r($releaseDayRenages[$relKey], true) . '</pre><br><hr size="1"/><hr size="1"/>');

                    $additionalWLInfo = array();
                    $totCases = 0;
                    foreach ($releaseWorklogs[$relKey] as $caseKey => $caseEntry) {
                        $_impl = $caseEntry['Implementer'];
                        if (empty($additionalWLInfo[$_impl]))
                            $additionalWLInfo[$_impl] = array();
                        if ($_impl == 'Pavlo Bilyk')
                            $totCases++;
                        if (!empty($caseEntry['GeneralTime'])) {
                            $additionalWLInfo[$_impl]['TASKS'] += 1;
                            $additionalWLInfo[$_impl]['HOURS'] += $caseEntry['GeneralTime'];
                        }
                        if ($_impl == 'Pavlo Bilyk')
                            debugData('=====================================================[' . $caseEntry["Case Number"] . '] ==> ' . $caseEntry["GeneralTime"].'<br>');
                    }
                    debugData('Pavlo Bilyk ______ total cases = ' . $totCases);
                    debugData('<br> ------------- USER INFO OF RELEASE '.$relKey.': <pre>' . print_r($additionalWLInfo, true) . '</pre><br><hr size="1"/><hr size="1"/>');
                    */
                }
            }

            //debugData('<pre>' . print_r($releases, true) . '</pre>');

            //exit;

            // mereging values of custom fields
            $combinedDataByReleases = false;
            foreach($releases as $relKey => $relEntry) {
                //debugData('<br> ---- running release: ' . $relKey . '<br>');
                //debugData('<br> ------------- release internal configuration: <pre>' . print_r($releaseDayRenages[$relKey], true) . '</pre><br>');
                if (!isset($releaseWorklogs[$relKey])) {
                    //debugData('<br> ----------------- empty release: ' . $relKey . '<br>');
                    continue;
                }

                $_releaseData = isset($combinedDataByReleases[$relKey])? $combinedDataByReleases[$relKey] : false;

                //$last_value = end(array_values($array));

                foreach ($relEntry as $relEntryKey => $relEntryKeyEntry) {
                    //debugData('<br> ------------- running key: ' . $relEntryKey . '<br>');
                    //debugData('<br> ------------- ' . (($last_value === $relEntryKeyEntry) ? ("IS LAST VALUE ->  " . $relEntryKeyEntry) : $relEntryKeyEntry) . '<br>');
                    //echo 'and it is MD : ' . (($relEntryKey === 'MD') ? 'YES' : 'NO') . '<br>';

                    // check and skip runnig day if it is defined in release internal configuration
                    // it prevents of extra days and other values in full releases (will not inlude metadata file of last Thursday only)
                    if ($releaseDayRenages[$relKey]["IS_FULL_RELEASE"] && $releaseDayRenages[$relKey]["SKIP_MD_OF_DAY"] === $relEntryKeyEntry) {
                        //debugData('<br> ------------- skipping this day: ' . $relEntryKeyEntry . '<br>');
                        continue;
                    }

                    if ($relEntryKey !== 'MD') {
                        //debugData($relEntryKeyEntry . ' | ');
                        // set initla data files with first date ($relEntryKeyEntry)

                        $__addFld = libraryUtils::getPathValue($relEntry['MD'][$relEntryKeyEntry], 'CUSTOM_FIELDS');
                        if (empty($_releaseData['ADD']) || count($_releaseData['ADD']) < count($__addFld))
                            $_releaseData['ADD'] = $__addFld;
                        //if (isset($_releaseData['ADD']['CF_NextRelease']))
                        //    $_releaseData['ADD']['CF_ReleaseName'] = $_releaseData['ADD']['CF_NextRelease'];

                            //$_releaseData['RUL'] = getPathValue($relEntry['MD'][$relEntryKeyEntry], 'RULES');
                        if (empty($_releaseData['CDF']))
                            $_releaseData['CDF'] = libraryUtils::getPathValue($relEntry['MD'][$relEntryKeyEntry], 'CONDITIONS');

                        else {
                            //$_releaseData['ADD'] = array_merge_recursive($_releaseData['ADD'], getPathValue($relEntry['MD'][$relEntryKeyEntry], 'CUSTOM_FIELDS'));
                            $_releaseData['CDF'] = array_merge_recursive($_releaseData['CDF'], libraryUtils::getPathValue($relEntry['MD'][$relEntryKeyEntry], 'CONDITIONS'));
                            //foreach()
                        }
                        $_releaseData['RUL'] = libraryUtils::getPathValue($relEntry['MD'][$relEntryKeyEntry], 'RULES');
                        // removing control field CF_NextRelease
                        if (isset($_releaseData['ADD']['CF_NextRelease']))
                            unset($_releaseData['ADD']['CF_NextRelease']);
                    }
                }

                //debugData('<pre>' . print_r($_releaseData, true) . '</pre><hr size="1"/><hr size="1"/>');
                $combinedDataByReleases[$relKey] = $_releaseData;
            }
            //debugData('<pre>' . print_r($combinedDataByReleases, true) . '</pre><hr size="1"/><hr size="1"/>');

            // adding custom fields and set conditional values
            foreach ($releaseWorklogs as $logReleaseKey => &$workLogEntry) {
                //echo '<br> --- running data entry for release ' . $logReleaseKey . '<br>';
                // adding custom fields

                $releaseCustomFields = $combinedDataByReleases[$logReleaseKey];

                foreach ($workLogEntry as $caseKey => &$caseEntry) {
                    // simple custom fields (add only)
                    foreach($releaseCustomFields['ADD'] as $customFieldKey => $customFieldValue)
                        $caseEntry[$customFieldKey] = $customFieldValue;

                    // conditional fields
                    $customMergedFields = false;
                    foreach($releaseCustomFields['CDF'] as $conditionalFieldName => $conditionalRules) {
                        // if current case has conditional key
                        //echo '------ looking for conditional key ' . $conditionalFieldName . '<br>';
                        if (isset($caseEntry[$conditionalFieldName])) {
                            // get value of matched data field
                            $_matchedDataFieldValue = $caseEntry[$conditionalFieldName];
                            //echo '------ matched value is ' . $_matchedDataFieldValue . '<br>';
                            // check if there is a rule for matched value
                            if (isset($conditionalRules[$_matchedDataFieldValue])) {
                                $_fieldRule = $conditionalRules[$_matchedDataFieldValue];
                                // perform adding custom data fields
                                //echo '------ found rule: ' . print_r($_fieldRule, true) . '<br>';
                                //$customMergedFields = false;
                                foreach ($_fieldRule['_fields'] as $fKey => $fEntry) {

                                    // get merging rule
                                    $__key = key($fEntry);
                                    $__val = current($fEntry);
                                    $mergingRule = empty($releaseCustomFields['RUL'][$__key])? "" : $releaseCustomFields['RUL'][$__key];

                                    // set next value by rules or ini as new
                                    if (isset($customMergedFields[$__key])) {
                                        switch($mergingRule) {
                                            case "REPLACE":
                                                $customMergedFields[$__key] = $__val;
                                                break;
                                            case "MERGE":
                                            default:
                                                $customMergedFields[$__key] += $__val;
                                                break;
                                        }
                                    } else
                                        $customMergedFields[$__key] = $__val;
                                }
                                //echo '------ generated custom fields: ' . print_r($customMergedFields, true) . '<br>';
                                /*
                                switch ($_fieldRule['_condition']) {
                                    case '==' : {

                                    }
                                }*/
                            }
                        }
                    }
                    //debugData('<hr size="1"/>customMergedFields<hr size="1"/><pre>' . print_r($customMergedFields, true) . '</pre>');
                    foreach($customMergedFields as $customFieldKey => $customFieldValue)
                        $caseEntry[$customFieldKey] = $customFieldValue;
                }
            }


            //debugData('<pre>' . print_r($releaseWorklogs, true) . '</pre>');


            return $releaseWorklogs;

            /*
            // get all metadata file from the date range
            $prevDate = false;
            foreach($dateRanges as $dt) {
                if(!$prevDate) {
                    $prevDate = $dt;
                    continue;
                }
                debugData('<hr size="1"/>$worklog for        [ '.$prevDate.' >>>> '.$dt.' ] <hr size="1"/>');
                $worklog = getWorklog($prevDate, $dt);
                echo renderData($worklog, isset($_GET['dw']));
                $prevDate = $dt;
            }
            */
    }
    
    private function customJsRenderData ($data, $useHeader = false) {
        //echo '<pre>' . print_r(getData($reportPath['s']), true) . '</pre>';
        // output data
        //header("Content-type:application/vnd.ms-excel");
        if ($useHeader) {
            if (is_array($data))
                header("Content-type:text/csv");
            else
                header("Content-type:text/json");
            header("Content-Disposition: inline; filename=worklog.json");
        }

        if (!is_array($data))
            return $data;

        $printFiledNames = true;
        //echo '-';
        //echo '<pre>' . print_r($dataTable, true) . '</pre>';
        $dataLines = '';
        foreach ($data as $rIdx => $dataRow) {
            //echo '<pre>' . print_r($data, true) . '</pre>';
            //echo '<pre>' . print_r(array_keys($data), true) . '</pre>';
            if ($printFiledNames) {
                $dataLines .= '"' . implode('","', array_keys($dataRow)) . '"' . PHP_EOL;// . '<br><br>';
                $printFiledNames = false;
            }
            $dataLines .= '"' . implode('","', $dataRow) . '"' . PHP_EOL;// . '<br><br>';
        }
        //echo '-';

        return $dataLines;
    }

    // custom function over getMetadata with default arguments
    private function customCommonGetMetadataOfDate($report, $dt) {
        $dataPath = libraryPath::getStandartDataPathWithDBR($report, $this->_dirWithReportData, true);
        return $this->customCommonGetMetadata($dataPath . DS . str_replace(array('-', '.', '_', '%2F'), DS, $dt) . '.dat');
    }

    private function customCommonGetMetadata ($dataFilePath, $getFullData = true, $opts = false) {

        if (!file_exists($dataFilePath))
            return false;

        // get data content and decode
        $metadata = json_decode(file_get_contents($dataFilePath), true);

        if ($getFullData)
            return $metadata;

        if (isset($opts['ReturnKey']) && isset($metadata[$opts['ReturnKey']]))
            return $metadata[$opts['ReturnKey']];
    }

    private function customCommonGetData($dataFilePath) {
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

    // sales force import
    private function customSalesForceImport ($account) {

        define("USERNAME", $account['user']);
        define("PASSWORD", $account['pwd']);
        define("SECURITY_TOKEN", $account['key']);

        $mySforceConnection = new extensionSalesForceClient();
        //var_dump($mySforceConnection);
        $mySforceConnection->createConnection("partner.wsdl.xml");
        //var_dump($mySforceConnection);
        $client = $mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);
        // report configuration 00O50000003ASUG
        $reportUrl = $account['url'];
        
        
        $logInfo = 'Starting data import at : ' . date('Y-m-d H:i:s') . ' from ' . $reportUrl . PHP_EOL;
        $logInfo .= '--------------------------------------------------------------' . PHP_EOL;
        $logInfo .= '[' . date('Y-m-d H:i:s') . '] curl_init' . PHP_EOL;
        // init cURL section
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $reportUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Not doing any verification of SSL certificates
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_COOKIE, 'sid='.$client->sessionId);
        setcookie("sid", $client->sessionId, 0, "/", ".salesforce.com", 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        $logInfo .= '[' . date('Y-m-d H:i:s') . '] curl_exec' . PHP_EOL;
        $result = curl_exec ($ch);
        $logInfo .= '[' . date('Y-m-d H:i:s') . '] Received ' . strlen($result) . ' bytes ' . PHP_EOL;
        curl_close ($ch);
        $logInfo .= '[' . date('Y-m-d H:i:s') . '] curl_close' . PHP_EOL;
        $logInfo .= '[' . date('Y-m-d H:i:s') . '] Saving into a file' . PHP_EOL;
        file_put_contents('../outbox/' . date('Y-m-d') . '.csv'  , $result);
        $logInfo .= '[' . date('Y-m-d H:i:s') . '] closing connection in SF client' . PHP_EOL;
        try {
            $mySforceConnection->logout();
        } catch (Exception $e) {
            $logInfo .= '[' . date('Y-m-d H:i:s') . '] ERROR -> Caught exception: '.  $e->getMessage(). ' | ' . PHP_EOL;
        }
        $logInfo .= PHP_EOL . '--------------------------------------------------------------' . PHP_EOL;
    }
    
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

}


?>