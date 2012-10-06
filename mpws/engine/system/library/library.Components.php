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

    public static function getDataTableView ($config, $dbLink, $params = false) {
        // get params
        $condition = $params['CONDITION'];
        $beforeConditionHook = $params['CONDITION_HOOK_BEFORE'];
        
        $com = array();
        
        $pageName = strtoupper(libraryRequest::getPage('Default'));
        $sessionSearchKey =  'MPWS_SEARCH_OF_' . $config['source'] . '_' . $pageName;
        
        $com['SEARCHBOX'] = array();
        // detect search request
        if (libraryRequest::isPostFormAction('search')) {

            $searchbox = array();
            foreach ($_POST as $_pkey => $_pval) {
                $returnValue = strpos($_pkey, $config['searchbox']['searchKeyPrefix']);
                //echo 'prefix = ' . $config['SEARCH_KEY_PREFIX'] .'<br>';
                //echo 'post key = ' . $_pkey .'<br>';
                //echo 'result is = ' . $returnValue .'-----<br>';
                if ($returnValue === 0 && !empty($_pval))
                    $searchbox[str_replace($config['searchbox']['searchKeyPrefix'], '', $_pkey)] = '%'.mysql_escape_string($_pval).'%';
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
            foreach ($_SESSION[$sessionSearchKey] as $sbKey => $sbVal)
                $_searchBoxFilterString[] = ' ' . $sbKey . ' LIKE \'' . $sbVal . '\' ';
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

        // fill pages
        $com['PAGELINKS'] = array();
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
            $sort = libraryRequest::getValue($config['filtering']['sortKey'], 'ID.desc');
            if (!empty($sort)) {
                $sort = explode('.', trim($sort));
                if (count($sort) == 2 && !empty($sort[0]) && !empty($sort[1])) {
                    $_direction = trim(strtolower($sort[1]));
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
    
    
    
}

?>
