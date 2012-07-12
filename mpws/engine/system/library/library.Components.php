<?php

// dependencies
// library.Request.php

class libraryComponents
{
    public static function comDataTable ($config, $dbLink, $condition = '') {
        $com = array();

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
            $_SESSION['MPWS_SEARCH_OF_' . $config['TABLE']] = $searchbox;
            $com['SEARCHBOX']['ACTIVE'] = true;

        }
        if (libraryRequest::isPostFormAction('discard')) {
            $_SESSION['MPWS_SEARCH_OF_' . $config['TABLE']] = false;
        }

        $com['RECORDS_ALL'] = $dbLink->getCount($config['TABLE'], $condition);

        if (empty($_SESSION['MPWS_SEARCH_OF_' . $config['TABLE']])) {
            $com['SEARCHBOX']['ACTIVE'] = false;
            $com['RECORDS'] = $com['RECORDS_ALL'];
        } else {
            $com['SEARCHBOX']['ACTIVE'] = true;
            $com['SEARCHBOX']['FILTER'] = $_SESSION['MPWS_SEARCH_OF_' . $config['TABLE']];
            $_searchBoxFilterString = array();
            if (!empty($condition))
                $_searchBoxFilterString[] = $condition;
            foreach ($_SESSION['MPWS_SEARCH_OF_' . $config['TABLE']] as $sbKey => $sbVal)
                $_searchBoxFilterString[] = ' ' . $sbKey . ' LIKE \'' . $sbVal . '\' ';
            $com['RECORDS'] = $dbLink->getCount($config['TABLE'], implode('AND', $_searchBoxFilterString));
        }

        //$com['RECORDS_ALL'] = $dbLink->getCount($config['TABLE']);

        //var_dump($_SESSION['MPWS_SEARCH_OF_' . $config['TABLE']]);

        $com['CURRENT'] = libraryRequest::getValue($config['PAGEKEY'], 1);
        $com['LIMIT'] = $config['LIMIT'];
        $com['PAGES'] = round($com['RECORDS'] / $com['LIMIT'] + 0.4);
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
                foreach ($_SESSION['MPWS_SEARCH_OF_' . $config['TABLE']] as $sbKey => $sbVal) {
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
                $dbLink->where(trim($_cnd[0], ' \'`"'), trim($_cnd[1]), trim($_cnd[2], ' \'"'));
                else
                $dbLink->andWhere(trim($_cnd[0], ' \'`"'), trim($_cnd[1]), trim($_cnd[2], ' \'"'));
            }

            // sorting
            $sort = libraryRequest::getValue($config['SORTKEY'], 'ID.desc');
            if (!empty($sort)) {
                $sort = explode('.', trim($sort));
                if (count($sort) == 2 && !empty($sort[0]) && !empty($sort[1])) {
                    $_direction = trim(strtolower($sort[1]));
                    if ($_direction == 'asc' || $_direction == 'desc') {
                        $dbLink
                            ->orderBy($sort[0])
                            ->order($_direction);
                    }
                    
                }
            }
           

            $com['DATA'] = $dbLink->fetchData();
        }

        return $com;
    }

}

?>
