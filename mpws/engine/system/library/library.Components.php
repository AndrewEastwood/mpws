<?php

// dependencies
// library.Request.php

class libraryComponents
{
    public static function comDataTable ($config, $dbLink, $condition = '', $beforeConditionHook = '') {
        $com = array();
        
        $pageName = strtoupper(libraryRequest::getPage('Default'));
        $sessionSearchKey =  'MPWS_SEARCH_OF_' . $config['TABLE'] . '_' . $pageName;
        
        $com['SEARCHBOX'] = array();
        // detect search request
        if (libraryRequest::isPostFormAction('search')) {

            $searchbox = array();
            foreach ($_POST as $_pkey => $_pval) {
                $returnValue = strpos($_pkey, $config['SEARCH_KEY_PREFIX']);
                //echo 'prefix = ' . $config['SEARCH_KEY_PREFIX'] .'<br>';
                //echo 'post key = ' . $_pkey .'<br>';
                //echo 'result is = ' . $returnValue .'-----<br>';
                if ($returnValue === 0 && !empty($_pval))
                    $searchbox[str_replace($config['SEARCH_KEY_PREFIX'], '', $_pkey)] = '%'.mysql_escape_string($_pval).'%';
            }
            $_SESSION[$sessionSearchKey] = $searchbox;
            $com['SEARCHBOX']['ACTIVE'] = true;

        }
        if (libraryRequest::isPostFormAction('discard')) {
            $_SESSION[$sessionSearchKey] = false;
        }

        $com['RECORDS_ALL'] = $dbLink->getCount($config['TABLE'], $condition, $beforeConditionHook);

        if (empty($_SESSION[$sessionSearchKey])) {
            $com['SEARCHBOX']['ACTIVE'] = false;
            $com['RECORDS'] = $com['RECORDS_ALL'];
        } else {
        
            //echo 'IS ACTIVE';
        
            $com['SEARCHBOX']['ACTIVE'] = true;
            $com['SEARCHBOX']['FILTER'] = $_SESSION[$sessionSearchKey];
            $_searchBoxFilterString = array();
            if (!empty($condition))
                $_searchBoxFilterString[] = $condition . ' ';
            foreach ($_SESSION[$sessionSearchKey] as $sbKey => $sbVal)
                $_searchBoxFilterString[] = ' ' . $sbKey . ' LIKE \'' . $sbVal . '\' ';

            //echo implode('AND', $_searchBoxFilterString);
                
            $com['RECORDS'] = $dbLink->getCount($config['TABLE'], implode('AND', $_searchBoxFilterString), $beforeConditionHook);
        }

        //$com['RECORDS_ALL'] = $dbLink->getCount($config['TABLE']);

        //var_dump($_SESSION['MPWS_SEARCH_OF_' . $config['TABLE']]);

        $com['CURRENT'] = libraryRequest::getValue($config['PAGEKEY'], 1);
        $com['LIMIT'] = $config['LIMIT'];
        $com['PAGES'] = round($com['RECORDS'] / $com['LIMIT'] + 0.4);
        
        // cleanup junk page values
        $com['CURRENT'] = mysql_escape_string($com['CURRENT']);
        if (!is_numeric($com['CURRENT']) || 
            $com['CURRENT'] < 1 || 
            $com['CURRENT'] > $com['PAGES'])
            $com['CURRENT'] = 1;
        
        $com['OFFSET'] = ($com['CURRENT'] - 1) * $com['LIMIT'];

        // fill pages
        $com['PAGELINKS'] = array();
        // get pages offset
        $_edgeLeft = $com['CURRENT'] - $config['SIZE'];
        $_edgeRight = $com['CURRENT'] + $config['SIZE'];
        // validate edges
        if ($com['PAGES'] < ($config['SIZE'] * 2 + 1)) {
            $_edgeLeft = 1;
            $_edgeRight = $com['PAGES'];
        } elseif ($_edgeLeft < 1) {
            $_edgeLeft = 1;
            $_edgeRight = $config['SIZE'] * 2 + 1;
            if ($_edgeRight > $com['PAGES'])
                $_edgeRight = $com['PAGES'];
        }elseif ($_edgeRight > $com['PAGES']) {
            $_edgeRight = $com['PAGES'];
            $_edgeLeft = $_edgeRight - $config['SIZE'] * 2;
            if ($_edgeLeft < 1)
                $_edgeLeft = 1;
        }

        // set left custom edges
        if (!empty($config['EDGES']) && $com['PAGES'] > 2) {
            $_customEdges = explode('-', $config['EDGES']);
            foreach ($_customEdges as $_customEdgeKey)
                switch ($_customEdgeKey) {
                    case 'FIRST':
                        $com['EDGELINKS']['First'] = libraryRequest::getNewUrl($config['PAGEKEY'], 1);
                        break;
                    case 'PREV':
                        if ($com['CURRENT'] > 1)
                            $com['EDGELINKS']['<<'] = libraryRequest::getNewUrl($config['PAGEKEY'], $com['CURRENT'] - 1);
                        break;
                    case 'NEXT':
                        if ($com['CURRENT'] < $com['PAGES'])
                            $com['EDGELINKS']['>>'] = libraryRequest::getNewUrl($config['PAGEKEY'], $com['CURRENT'] + 1);
                        break;
                    case 'LAST':
                        $com['EDGELINKS']['Last'] = libraryRequest::getNewUrl($config['PAGEKEY'], $com['PAGES']);
                        break;
                }
        }

        for ($i = $_edgeLeft; $i <= $_edgeRight; $i++)
            $com['PAGELINKS'][$i] = libraryRequest::getNewUrl($config['PAGEKEY'], $i);

        if (!empty($dbLink)) {
            $dbLink
                ->reset()
                ->select('*')
                ->from($config['TABLE'])
                ->offset($com['OFFSET'])
                ->limit($com['LIMIT']);

            $_conditionasAdded = 0;
            // searchbox
            if($com['SEARCHBOX']['ACTIVE']) {
                $_firstConditionWasAdded = false;
                foreach ($_SESSION[$sessionSearchKey] as $sbKey => $sbVal) {
                    if ($_firstConditionWasAdded)
                        $dbLink->andWhere($sbKey, 'LIKE', $sbVal);
                    else {
                        $dbLink->where($sbKey, 'LIKE', $sbVal);
                        $_firstConditionWasAdded = true;
                    }
                    $_conditionasAdded++;
                }
            }
            
            if (!empty($condition)) {
                //echo 'adding condition';
                $_cnd = explode(' ', $condition, 3);
                //var_dump($_cnd);
                if ($_conditionasAdded == 0)
                    $dbLink->where(trim($_cnd[0], ' \'`"'), trim($_cnd[1]), trim($_cnd[2], ' \'"') . ' ');
                else
                    $dbLink->andWhere(trim($_cnd[0], ' \'`"'), trim($_cnd[1]), trim($_cnd[2], ' \'"') . ' ');
            }

            // sorting
            $sort = libraryRequest::getValue($config['SORTKEY'], 'ID.desc');
            if (!empty($sort)) {
                $sort = explode('.', trim($sort));
                if (count($sort) == 2 && !empty($sort[0]) && !empty($sort[1])) {
                    $_direction = trim(strtolower($sort[1]));
                    if ($_direction == 'asc' || $_direction == 'desc') {
                        $dbLink
                            ->orderBy($config['TABLE'].'.'.$sort[0])
                            ->order($_direction);
                    }
                    
                }
            }
           

            $com['DATA'] = $dbLink->fetchData();
        }

        return $com;
    }

    public static function getDataTableView ($config, $dbLink) {
        // get params
        
        
        
        $condition = $config['datatable']['condition'];
        $beforeConditionHook = $config['datatable']['conditionHook'];
        
        $com = array();
        
        $pageName = strtoupper(libraryRequest::getPage('Default'));
        $sessionSearchKey =  'MPWS_SEARCH_OF_' . $config['source'] . '_' . $pageName;
        
        $com['SEARCHBOX'] = array(
            'FIELDS' => array(),
            'ACTIVE' => false,
            'FILTER' => array(),
            'WORDS' => array()
        );
        // add search fields
        foreach ($config['searchbox']['fields'] as $searchFieldName)
            $com['SEARCHBOX']['FIELDS'][$searchFieldName] = $config['searchbox']['searchKeyPrefix'] . $searchFieldName;
        // detect search request
        if (libraryRequest::isPostFormAction('search')) {

            $searchbox = array();
            foreach ($_POST as $_pkey => $_pval) {
                $returnValue = strpos($_pkey, $config['searchbox']['searchKeyPrefix']);
                //echo 'prefix = ' . $config['SEARCH_KEY_PREFIX'] .'<br>';
                //echo 'post key = ' . $_pkey .'<br>';
                //echo 'result is = ' . $returnValue .'-----<br>';
                if ($returnValue === 0 && !empty($_pval)) {
                    $_searckKeyField = str_replace($config['searchbox']['searchKeyPrefix'], '', $_pkey);
                    $searchbox[$_searckKeyField] = '%'.mysql_escape_string($_pval).'%';
                }
            }
            $_SESSION[$sessionSearchKey] = $searchbox;
            $com['SEARCHBOX']['ACTIVE'] = true;

        }
        if (libraryRequest::isPostFormAction('discard')) {
            $_SESSION[$sessionSearchKey] = false;
        }

        $com['RECORDS_ALL'] = $dbLink->getCount($config['source'], $condition, $beforeConditionHook);

        if (empty($_SESSION[$sessionSearchKey])) {
            $com['SEARCHBOX']['ACTIVE'] = false;
            $com['RECORDS'] = $com['RECORDS_ALL'];
        } else {
            //echo 'IS ACTIVE';
            $com['SEARCHBOX']['ACTIVE'] = true;
            $com['SEARCHBOX']['FILTER'] = $_SESSION[$sessionSearchKey];
            $_searchBoxFilterString = array();
            if (!empty($condition))
                $_searchBoxFilterString[] = $condition . ' ';
            foreach ($_SESSION[$sessionSearchKey] as $sbKey => $sbVal) {
                $_searchBoxFilterString[] = ' ' . $sbKey . ' LIKE \'' . $sbVal . '\' ';
                $com['SEARCHBOX']['WORDS'][$sbKey] = trim($sbVal, '%');
            }
            //echo implode('AND', $_searchBoxFilterString);
            $com['RECORDS'] = $dbLink->getCount($config['source'], implode('AND', $_searchBoxFilterString), $beforeConditionHook);
        }

        //$com['RECORDS_ALL'] = $dbLink->getCount($config['TABLE']);

        //var_dump($_SESSION['MPWS_SEARCH_OF_' . $config['TABLE']]);
        
        $com['CURRENT'] = libraryRequest::getValue($config['pagination']['pageKey'], 1);
        $com['LIMIT'] = $config['datatable']['limit'];
        $com['PAGES'] = round($com['RECORDS'] / $com['LIMIT'] + 0.4);
        
        // cleanup junk page values
        $com['CURRENT'] = mysql_escape_string($com['CURRENT']);
        if (!is_numeric($com['CURRENT']) || 
            $com['CURRENT'] < 1 || 
            $com['CURRENT'] > $com['PAGES'])
            $com['CURRENT'] = 1;
        
        $com['OFFSET'] = ($com['CURRENT'] - 1) * $com['LIMIT'];
        //var_dump($config);
        // fill pages
        $com['PAGELINKS'] = array();
        $com['EDGELINKS'] = array();
        // get pages offset
        $_edgeLeft = $com['CURRENT'] - $config['pagination']['size'];
        $_edgeRight = $com['CURRENT'] + $config['pagination']['size'];
        // validate edges
        if ($com['PAGES'] < ($config['pagination']['size'] * 2 + 1)) {
            $_edgeLeft = 1;
            $_edgeRight = $com['PAGES'];
        } elseif ($_edgeLeft < 1) {
            $_edgeLeft = 1;
            $_edgeRight = $config['pagination']['size'] * 2 + 1;
            if ($_edgeRight > $com['PAGES'])
                $_edgeRight = $com['PAGES'];
        }elseif ($_edgeRight > $com['PAGES']) {
            $_edgeRight = $com['PAGES'];
            $_edgeLeft = $_edgeRight - $config['pagination']['size'] * 2;
            if ($_edgeLeft < 1)
                $_edgeLeft = 1;
        }

        // set left custom edges
        if (!empty($config['pagination']['edges']) && $com['PAGES'] > 2) {
            $_customEdges = explode('-', $config['pagination']['edges']);
            foreach ($_customEdges as $_customEdgeKey)
                switch ($_customEdgeKey) {
                    case 'FIRST':
                        $com['EDGELINKS']['FIRST'] = libraryRequest::getNewUrl($config['pagination']['pageKey'], 1);
                        break;
                    case 'PREV':
                        if ($com['CURRENT'] > 1)
                            $com['EDGELINKS']['PREV'] = libraryRequest::getNewUrl($config['pagination']['pageKey'], $com['CURRENT'] - 1);
                        break;
                    case 'NEXT':
                        if ($com['CURRENT'] < $com['PAGES'])
                            $com['EDGELINKS']['NEXT'] = libraryRequest::getNewUrl($config['pagination']['pageKey'], $com['CURRENT'] + 1);
                        break;
                    case 'LAST':
                        $com['EDGELINKS']['LAST'] = libraryRequest::getNewUrl($config['pagination']['pageKey'], $com['PAGES']);
                        break;
                }
        }

        for ($i = $_edgeLeft; $i <= $_edgeRight; $i++)
            $com['PAGELINKS'][$i] = libraryRequest::getNewUrl($config['pagination']['pageKey'], $i);

        if (!empty($dbLink)) {
            $dbLink
                ->reset()
                ->select('*')
                ->from($config['source'])
                ->offset($com['OFFSET'])
                ->limit($com['LIMIT']);

            $_conditionasAdded = 0;
            // searchbox
            if($com['SEARCHBOX']['ACTIVE']) {
                $_firstConditionWasAdded = false;
                foreach ($_SESSION[$sessionSearchKey] as $sbKey => $sbVal) {
                    if ($_firstConditionWasAdded)
                        $dbLink->andWhere($sbKey, 'LIKE', $sbVal);
                    else {
                        $dbLink->where($sbKey, 'LIKE', $sbVal);
                        $_firstConditionWasAdded = true;
                    }
                    $_conditionasAdded++;
                }
            }
            
            if (!empty($condition)) {
                //echo 'adding condition';
                $_cnd = explode(' ', $condition, 3);
                //var_dump($_cnd);
                if ($_conditionasAdded == 0)
                    $dbLink->where(trim($_cnd[0], ' \'`"'), trim($_cnd[1]), trim($_cnd[2], ' \'"') . ' ');
                else
                    $dbLink->andWhere(trim($_cnd[0], ' \'`"'), trim($_cnd[1]), trim($_cnd[2], ' \'"') . ' ');
            }

            // sorting
            $sort = libraryRequest::getValue($config['filtering']['sortKey'], 'ID.asc');
            
            if (!empty($sort)) {
                $sort = explode('.', trim($sort));
                if (count($sort) == 2 && !empty($sort[0]) && !empty($sort[1])) {
                    $_direction = trim(strtolower($sort[1]));
                    //echo '#####' . $config['source'].'.'.$sort[0] . '####';
                    if ($_direction == 'asc' || $_direction == 'desc') {
                        $dbLink
                            ->orderBy($config['source'].'.'.$sort[0])
                            ->order($_direction);
                    }
                    
                }
            }
           

            $com['DATA'] = $dbLink->fetchData();
        }
        
        
        // dtv obj
        $dtv = array(
            "SOURCE" => array(
                "RECORDS" => $com['DATA'],
                "LIMIT" => $com['LIMIT'],
                "TOTAL" => $com['RECORDS_ALL']
            ),
            "PAGING" => array(
                "LINKS" => $com['PAGELINKS'],
                "EDGES" => $com['EDGELINKS'],
                "CURRENT" => $com['CURRENT'],
                "LIMIT" => $com['LIMIT'],
                "OFFSET" => $com['OFFSET'],
                "PAGES" => $com['PAGES'],
                "TOTAL" => $com['RECORDS_ALL'],
                "AVAILABLE" => $com['RECORDS']
            ),
            "SEARCH" => $com['SEARCHBOX']
        );

        return $dtv;
        
    }
    
    
    public static function getDataEditor ($config, $dbLink, $actionHooks = false) {
        

        if (empty($dbLink))
            throw new Exception('libraryComponents: getDataEditor => dbLink is empty');
        
        // component structure
        $com = array(
            // append form configuration
            "FORM" => $config['form'], 
            "SOURCE" => false,
            "FIELDS" => false,
            "ISNEW" => true,
            "EDIT_PAGE"  => "new",
            "REFERER" => libraryRequest::storeOrGetRefererUrl(false),
            "ERRORS" => false,
            "VALID" => true
        );
        // get edit page name
        $editPage = strtolower(libraryRequest::getPostFormAction());
        // normalize page name
        $editPage = strtolower(trim($editPage));
        // get oid
        $oid = libraryRequest::getOID();
        // adjust states
        if (isset($oid) && $com['EDIT_PAGE'] == "new") {
            $editPage = "edit";
            $com['ISNEW'] = false;
        }
        // get fields
        $_fieldsDB = $dbLink->getFields($config['source']);
        $_fieldsCOM = array();
        if (!empty($config['fields']['editable'])) {
            foreach ($_fieldsDB as $fieldEntry)
                if (in_array ($fieldEntry['Field'], $config['fields']['editable']))
                    $_fieldsCOM[] = $fieldEntry;
        } else
            $_fieldsCOM = $_fieldsDB;
        // set fields
        $com['FIELDS'] = $_fieldsCOM;
        //var_dump($com['FIELDS']);
        // do common work on save or preview actions
        if ($editPage == 'save' || $editPage == 'preview') {
            $validatorRezult = libraryValidator::validateStandartMpwsFields($config['fields']['editable'], $config['validators']);
            $com['ERRORS'] = $validatorRezult['ERRORS'];
            // check unique fields
            if (empty($com["ERRORS"]) && isset($config['fields']['unique']) && !empty($config['fields']['unique'])) {
                //var_dump($config['fields']['unique']);
                foreach ($config['fields']['unique'] as $_fieldThatMustBeUnique) {
                    $_existedRow = $dbLink
                        ->reset()
                        ->select('*')
                        ->from($config['source'])
                        ->where($_fieldThatMustBeUnique, '=', $validatorRezult['DATA'][$_fieldThatMustBeUnique])
                        ->fetchRow();
                    if (!empty($_existedRow))
                        $com['ERRORS'][] = 'Duplicate' . $_fieldThatMustBeUnique;
                }
            }
            // set editor state on error
            if (!empty($com["ERRORS"])) {
                var_dump($com['ERRORS']);
                $editPage = 'edit';
                $com['VALID'] = false;
            }
            // save
            if ($editPage == 'save' && $com['VALID']) {
                $_data = $validatorRezult['DATA'];
                // prepend fields
                $appendFields = getNonEmptyValue($config['fields']['appendBeforeSave'], array());
                foreach ($appendFields as $appendFieldName)
                    $_data[$appendFieldName] = false;
                // before save hook
                if (isset($actionHooks['ON_BEFORE_SAVE'])) {
                    $__action = $actionHooks['ON_BEFORE_SAVE'];
                    $__action($config, $_data);
                }
                // standart actions
                // obfuscate passwords
                if (isset($_data['Password']))
                    $_data['Password'] = md5($_data['Password']);
                // init empty fields
                if ($com['ISNEW']) {
                    if(isset($_data['DateCreated']))
                        $_data['DateCreated'] = date('Y-m-d H:i:s');
                    if(isset($_data['DateLastAccess']))
                        $_data['DateLastAccess'] = date('Y-m-d H:i:s');
                }
                // adjust field values
                foreach($com['FIELDS'] as $fieldEntry) {
                    // checkbox
                    $_type = strtolower($fieldEntry['Type']);
                    if ($_type == 'boolean' || $_type == 'bool' || $_type == 'tinyint(1)') {
                       /* adjust data */
                        if (empty($_data['Active']))
                            $_data['Active'] = 0;
                        else
                            $_data['Active'] = 1;
                    }
                }
                // update date
                if (isset($_data['DateUpdated']))
                    $_data['DateUpdated'] = date('Y-m-d H:i:s');
                // remove fields
                $removeFields = getNonEmptyValue($config['fields']['removeBeforeSave'], array());
                foreach ($removeFields as $removeFieldName)
                    unset ($_data[$removeFieldName]);
                // save
                var_dump($_data);
                /*$dbLink
                    ->reset()
                    ->insertInto($config['source'])
                    ->fields(array_keys($_data))
                    ->values(array_values($_data))
                    ->query();*/
                // after save hook
                if (isset($actionHooks['ON_AFTER_SAVE'])) {
                    $__action = $actionHooks['ON_AFTER_SAVE'];
                    $__action($config, $_data);
                }
                // send email
            }
        }
        // get data
        if ($editPage == 'edit' && !$com['ISNEW']) {
            $com['SOURCE'] = $dbLink
                ->reset()
                ->select('*')
                ->from($config['source'])
                ->where('ID', '=', $oid)
                ->fetchRow();
            // truncate on load
            $fieldsToTruncate = $config['form']['edit']['truncateOnLoad'];
            foreach ($fieldsToTruncate as $_fldName)
                $com['SOURCE'][$_fldName] = null;
            //var_dump($com['SOURCE']);
        }

        $com['EDIT_PAGE'] = getNonEmptyValue($editPage, "new");
        //echo "EDIT PAGE IS:  " . $com['EDIT_PAGE'];
        return $com;
    }
}

?>
