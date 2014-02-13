<?php

// PHP ��������.
// ��������� View.
// -----------------------
//
// ����������� ��� ���������� ����� � HTML.
//
// ��� ������������ ����� ��������� ��� ����
// ������ ���� ������� ������� ���������
// ����� � ������ ��������� ����� ��������
// ����, ��� �������������� � ����� ���������.
//
// �����: � 2009, ����� ����������.
// Author: � 2009, Andriy Ivaskevych.
//
// E-mail: soulcor@narod.ru

class libraryView
{    // _�����������
    function __construct() { }

    // _����������
    function __destruct() { }

    // ����� �������.
    // $records - ��� �������.
    // $params - ���������� �������.
    // $useEval - ������������ ����������� ���� � ����� �������.
    // $className - ������ ������ ����� ����� �������.
    // $useNoCacheLink - ����� ���������� ���������.
    // ������� ���������� ��������� HTML - �������.
    public function htmlTable($records, $params = Array(), $useEval = FALSE, $useNoCacheLink = FALSE, $className = 'dataGrid')
    {        // ���� ���� ����� �� ��������
        if (empty($records))
            return FALSE;

        // ��������� �������
        $_captions = Array();
        $_width = Array();
        $_aligment = Array();
        $_values = Array();
        $_actions = Array();
        $_others = Array(
            'table' => '',
            'c_row' => '',
            'c_cell' => Array(),
            'd_row1' => '',
            'd_row2' => '',
            'd_cell' => Array()
        );
        $_size = -1;

        // ���������� ��������� �������
        if (!empty($params))
        {
            $_captions = $params['captions'];
            $_width = $params['width'];
            $_aligment = $params['aligment'];
            $_values = $params['values'];
            $_actions = $params['actions'];
            $_others = $params['other'];
            $_size = $params['size'];
        }
        else
        {
            $_r = current($records);
            $_c = count($_r);

            $_captions = array_keys(current($_r));
            $_captions = array_combine($_captions, $_captions);
            $_width = array_combine($_captions, array_pad($_width, $_c, 1));
            $_aligment = array_combine($_captions, array_pad($_aligment, $_c, 'left'));
            $_others['c_cell'] = array_combine($_captions, array_pad($_others['c_cell'], $_c, ''));
            $_others['d_cell'] = array_combine($_captions, array_pad($_others['c_cell'], $_c, ''));
        }

        $HTML = '<table class="'.$className.'" '.$_others['table'].'>'."\n";

        // ��������� ��������� �������
        $HTML .= "\t".'<tr class="'.$className.'Caption" '.$_others['c_row'].'>'."\n";
        foreach ($_captions as $key => $value)
        {            // ���� �������� � ����� �� ������������ ���� ��������� �����
            if (empty($value))
                $value = '&nbsp;';
            // �������� �� ��� �������� ������
            if (isset($_actions['captions'][$key]))
            {
                $action = $this->_createAction($_actions['captions'][$key], false, $value, $useEval);
                $value = '<a href="'.$action.'" class="'.$className.'CapLink">'.$value.'</a>';
            }
            //��������� �������� � ������
            if (isset($_aligment['captions'][$key]))
                $HTML .= "\t\t".'<td width="'.$_width[$key].'" align="'.$_aligment['captions'][$key].'" class="'.$className.'Caption"'.' '.$_others['c_cell'][$key].'>'."\n";
            else
                $HTML .= "\t\t".'<td width="'.$_width[$key].'" class="'.$className.'Caption"'.' '.$_others['c_cell'][$key].'>'."\n";
            // �������� ������ �� �������
            $HTML .= "\t\t\t".$value."\n";
            $HTML .= "\t\t".'</td>'."\n";
        }
        $HTML .= "\t".'</tr>'."\n";

        // ���� ��������� -1 �� �������� �� ������ �������
        if ($_size < 0)
            $_size = count($records);

        // ���������� ���� �������
        for ($r = 0; $r < $_size; $r++)
        {            // ���� ���� ����� �� ��������� ���� ���������            if (empty($records[$r]))
                break;            // ���������� ���� �� ������ �����
            if ($r % 2 == 0)
                $HTML .= "\t".'<tr class="'.$className.'DataRow1" '.$_others['d_row1'].'>'."\n";
            else
                $HTML .= "\t".'<tr class="'.$className.'DataRow2" '.$_others['d_row2'].'>'."\n";
            // �������� ������
            $value = '';
            $celldata = '';
            // �������� ����� �������
            $row = $records[$r];
            // ��� ������� ���� ������� ��������� ��������
            foreach ($_captions as $key => $v)
            {
                // ���������� ������ ��������� � �������
                if (isset($row[$key]))
                    $value = $row[$key];
                else
                    $value = false;

                $celldata = $value;

                // ������ �������� ��� ������ � ������������ ����
                if (!empty($_values[$key]))
                {
                    $_data = Array(
                        'row' => $row,
                        'value' => $value,
                        'defValue' => $value);
                    $celldata = $this->_evaluateValue($_values[$key], true, $_data, $useEval);
                }
                // �������� �� ��� �������� ������
                if (!empty($_actions['data'][$key]))
                {
                    $action = $this->_createAction($_actions['data'][$key], $row, $value, $useEval);
                    if (!strpos(strtolower($celldata), 'input'))
                        $celldata = '<a href="'.$action.'" class="'.$className.'Link">'.$celldata.'</a>';
                }
                // �������� HTML ������ � ������� ��� ��������
                if (isset($_aligment['data'][$key]))
                    $HTML .= "\t\t".'<td align="'.$_aligment['data'][$key].'" class="'.$className.'DataCell" '.$_others['d_cell'][$key].'>'."\n";
                else
                    $HTML .= "\t\t".'<td class="'.$className.'DataCell" '.$_others['d_cell'][$key].'>'."\n";
                // ���������� ������� �� ��������� ��� ������
                $HTML .= "\t\t\t".$celldata."\n";
                $HTML .= "\t\t".'</td>'."\n";
            }
            $HTML .= "\t".'</tr>'."\n";
        }
        $HTML .= '</table>'."\n";

        return $HTML;
    }


    // Create table using div elements.
    // $records - ��� �������.
    // $params - ���������� �������.
    // $useEval - ������������ ����������� ���� � ����� �������.
    // $className - ������ ������ ����� ����� �������.
    // $useNoCacheLink - ����� ���������� ���������.
    // ������� ���������� ��������� HTML - �������.
    public function htmlStyledTable($records, $params = Array(), $useEval = FALSE, $useNoCacheLink = FALSE, $className = 'dataGrid')
    {
        // ���� ���� ����� �� ��������
        if (empty($records))
            return FALSE;

        // ��������� �������
        $_captions = Array();
        $_width = Array();
        $_aligment = Array();
        $_values = Array();
        $_actions = Array();
        $_others = Array(
            'table' => '',
            'c_row' => '',
            'c_cell' => Array(),
            'd_row1' => '',
            'd_row2' => '',
            'd_cell' => Array()
        );
        $_size = -1;

        // ���������� ��������� �������
        if (!empty($params))
        {
            $_captions = $params['captions'];
            $_width = $params['width'];
            $_aligment = $params['aligment'];
            $_values = $params['values'];
            $_actions = $params['actions'];
            $_others = $params['other'];
            $_size = $params['size'];
        }
        else
        {
            $_r = current($records);
            $_c = count($_r);

            $_captions = array_keys(current($_r));
            $_captions = array_combine($_captions, $_captions);
            $_width = array_combine($_captions, array_pad($_width, $_c, 1));
            $_aligment = array_combine($_captions, array_pad($_aligment, $_c, 'left'));
            $_others['c_cell'] = array_combine($_captions, array_pad($_others['c_cell'], $_c, ''));
            $_others['d_cell'] = array_combine($_captions, array_pad($_others['c_cell'], $_c, ''));
        }

        $HTML = '<div class="'.$className.'" '.$_others['table'].'>'.NLN;

        // ��������� ��������� �������
        $HTML .= TAB.'<div class="'.$className.'Row '.$className.'Caption" '.$_others['c_row'].'>'.NLN;
        foreach ($_captions as $key => $value)
        {
            // ���� �������� � ����� �� ������������ ���� ��������� �����
            if (empty($value))
                $value = '&nbsp;';
            // �������� �� ��� �������� ������
            if (isset($_actions['captions'][$key]))
            {
                $action = $this->_createAction($_actions['captions'][$key], false, $value, $useEval);
                $value = $this->htmlLink($action, $value, $useNoCacheLink, 'class="'.$className.'CapLink"');
                //$value = '<a href="'.$action.'" class="'.$className.'CapLink">'.$value.'</a>';
            }
            //��������� �������� � ������
            if (isset($_aligment['captions'][$key]))
                $HTML .= TAB.TAB.'<span align="'.$_aligment['captions'][$key].'" class="'.$className.'Cell '.$className.'CaptionCell std-ui-width'.$_width[$key].'" '.$_others['c_cell'][$key].'>'.NLN;
            else
                $HTML .= TAB.TAB.'<span class="'.$className.'Cell '.$className.'CaptionCell std-ui-width'.$_width[$key].'" '.$_others['c_cell'][$key].'>'.NLN;
            // �������� ������ �� �������
            $HTML .= TAB.TAB.TAB.$value.NLN;
            $HTML .= TAB.TAB.'</span>'.NLN;
        }
        $HTML .= TAB.'</div>'.NLN;

        // ���� ��������� -1 �� �������� �� ������ �������
        if ($_size < 0)
            $_size = count($records);

        // ���������� ���� �������
        for ($r = 0; $r < $_size; $r++)
        {
            // ���� ���� ����� �� ��������� ���� ���������
            if (empty($records[$r]))
                break;
            // ���������� ���� �� ������ �����
            if ($r % 2 == 0)
                $HTML .= TAB.'<div class="'.$className.'Row '.$className.'DataRow '.$className.'DataRowEven" '.$_others['d_row1'].'>'.NLN;
            else
                $HTML .= TAB.'<div class="'.$className.'Row '.$className.'DataRow '.$className.'DataRowOdd" '.$_others['d_row2'].'>'.NLN;
            // �������� ������
            $value = '';
            $celldata = '';
            // �������� ����� �������
            $row = $records[$r];
            // ��� ������� ���� ������� ��������� ��������
            foreach ($_captions as $key => $v)
            {
                // ���������� ������ ��������� � �������
                if (isset($row[$key]))
                    $value = $row[$key];
                else
                    $value = false;

                $celldata = $value;

                // ������ �������� ��� ������ � ������������ ����
                if (!empty($_values[$key]))
                {
                    $_data = Array(
                        'row' => $row,
                        'value' => $value,
                        'defValue' => $value);
                    $celldata = $this->_evaluateValue($_values[$key], true, $_data, $useEval);
                }
                // �������� �� ��� �������� ������
                if (!empty($_actions['data'][$key]))
                {
                    $action = $this->_createAction($_actions['data'][$key], $row, $value, $useEval);
                    if (!strpos(strtolower($celldata), 'input'))
                        $celldata = $this->htmlLink($action, $celldata, $useNoCacheLink, 'class="'.$className.'Link"');
                        //$celldata = '<a href="'.$action.'" class="'.$className.'Link">'.$celldata.'</a>';
                }
                // �������� HTML ������ � ������� ��� ��������
                if (isset($_aligment['data'][$key]))
                    $HTML .= TAB.TAB.'<span align="'.$_aligment['data'][$key].'" class="'.$className.'DataCell std-ui-width'.$_width[$key].'" '.$className.'DataCellValue" '.$_others['d_cell'][$key].'>'.NLN;
                else
                    $HTML .= TAB.TAB.'<span class="'.$className.'DataCell std-ui-width'.$_width[$key].'" '.$_others['d_cell'][$key].'>'.NLN;
                // ���������� ������� �� ��������� ��� ������
                $HTML .= TAB.TAB.TAB.$celldata.NLN;
                $HTML .= TAB.TAB.'</span>'.NLN;
            }
            $HTML .= TAB.'</div>'.NLN;
        }
        $HTML .= '</div>'.NLN;

        return $HTML;
    }

    // ������� �������������.
    // $href - �������������.
    // $value - �������� �������������.
    // $useNoCacheValue - ������������ ��������� ��������� ��� ���������.
    // $otherTags - ��������� ����.
    // ������� HTML �������������.
    public function htmlLink ($href, $value, $useNoCacheValue = FALSE, $otherTags = '') {        if (empty($href))
            $href = '';
        if (empty($value))
            $value = 'value';
        if (!empty($otherTags))
            $otherTags = ' '.$otherTags;

        if ($useNoCacheValue) {
            if (strpos($href, '?') === FALSE)
                $href .= '?';
            else
                $href .= '&';
            $href .= 'nocache='.rand();
        }
        //echo sprintf(HRUNLOG, "Link is: ".$href);
        return '<a href="'.$href.'"'.$otherTags.'>'.$value.'</a>';
    }

    // ����� ������, �� ������������
    // $name - ����� ������.
    // $size - ��������� ������.
    // $data - ���.
    // $selectedValue - �������� �� ������������.
    // $valueKey - ������ �������� � ������ �����. (���� ��� � ����� ������)
    // $textKey - ������ ������ � ������ �����. (���� ��� � ����� ������)
    // $otherTags - �������� ���� ������.
    // ������� ���������� ����������� �����.
    public function htmlDropDown($name, $size, $data, $selectedValue, $valueKey = NULL, $textKey = NULL, $otherTags = '') {        $HTML = '';

        foreach ($data as $key => $val) {            $value = '';
            $text = '';

            if (is_array($val))
            {
                $value = $val[$valueKey];
                $text = $val[$textKey];
            }
            else
            {                $value = $key;
                $text = $val;
            }

            if(strcasecmp($value, $selectedValue) == 0)
                $HTML .= '<option value="'.$value.'" selected="selected">'.$text.'</option>';
            else
                $HTML .= '<option value="'.$value.'">'.$text.'</option>';
        }

        if (strlen($HTML) != 0)
            $HTML = '<select name="'.$name.'" size="'.$size.'" '.$otherTags.'>'.$HTML.'</select>';

        return $HTML;
    }

    public function htmlDropDownStyled ($title, $id = '', $cssClass, $data, $onClick, $textKey = 'text', $linkKey = 'link', $otherTags = '') {        $_launcher = $this->htmlLink ($this->htmlJavaScriptSingle($onClick), $title, false, false);        $HTML = $this->htmlRawStyled ($this->htmlRawSpanStyled($_launcher, $cssClass, $id), $cssClass.'Container', $id.'ContainerID');
        $HTML = '<ul';
        if (isset($id))
            $HTML .= ' id="'.$id.'ListContainerID"';
        $HTML .= 'class="'.$cssClass.'ListContainer"';
        foreach ($data as $key => $val) {            $HTML .= '<li>';
            if (is_array($val))
                $HTML .= $this->htmlLink ($val[$linkKey], $val[$textKey]);
            else
                $HTML .= $val;

            $HTML .= '</li>';
        }
        $HTML .= '</ul>';
    }

    // ����� ������� �������� �� ������� � �������.
    // $totalElements - �������� ������� ������ � �������.
    // $tableSize - ������� ������  �� ���� ������� �������.
    // $currPage - ����� ������� �������.
    // $getKey - ����, �� ������ �������� ������� � ��������� � ����������.
    // $navSize - ������� ������ � �������� ������� ��� �����������.
    // $retType - ��� ���������� (true - �����, flase - ������).
    // $pageSeparator - ���������
    // ������� ����� ������� �������, ��� flase ���� ���� �������.
    public function htmlPager($totalElements, $tableSize, $currPage, $getKey, $navSize, $retType, $linkSuffix=false, $pageSeparator = '<small>�</small>')
    {        $pagesBlocks = array();

        if ($totalElements === 0)
            return $pagesBlocks;

        if (strlen($pageSeparator) != 0)
            $pageSeparator = ' '.$pageSeparator. ' ';

        // ���� ��� ����������� ��������� ������ �����, �� �������� � �-���
        if ($tableSize >= $totalElements)
            return $pagesBlocks;

        $pages = intval($totalElements / $tableSize);

        if (($pages * $tableSize) < $totalElements)
            $pages++;

        if ($currPage > 1)
            $pagesBlocks[] = '<a href="'.$this->_setGET($getKey, 0).$linkSuffix.'" class="pagerLink pagerLinkFirst">&laquo;</a>';

        if (($currPage - 1) > 0)
            $pagesBlocks[] = '<a href="'.$this->_setGET($getKey, ($currPage - 1)).$linkSuffix.'" class="pagerLink pagerLinkPrev">�����</a>';
            
        $plimit = intval($navSize / 2);
        for ($i = $currPage-$plimit; $i <= $currPage + $plimit and $i <= $pages; $i++)
        {            if ($i <= 0)
                continue;
                
            if ($i == $currPage)
                $plink = '<a href="#" class="pagerLink pagerCurrentLink">'.$i.'</a>';
            else
                $plink = '<a href="'.$this->_setGET($getKey, $i).$linkSuffix.'" class="pagerLink">'.$i.'</a>';

            if ($i + 1 <= $pages and $i + 1 <= $currPage + $plimit)
                $plink .= $pageSeparator;

            $pagesBlocks[] = $plink;
        }
        
        if (($currPage) < $pages)
            $pagesBlocks[] = '<a href="'.$this->_setGET($getKey, ($currPage + 1)).$linkSuffix.'" class="pagerLink pagerLinkNext">���</a>';
            
        if($currPage < $pages)
            $pagesBlocks[] = '<a href="'.$this->_setGET($getKey, $pages).$linkSuffix.'" class="pagerLink pagerLinkLast">&raquo;</a>';

        if ($retType)
            return $pagesBlocks;

        if (count($pagesBlocks) > 0)
            return implode('&nbsp;', $pagesBlocks);

        return FALSE;
    }

    /*public function htmlStyleRef ($cssFolderPath, $cssName) {
        return TAB.'<link type="text/css" href="'.$cssFolderPath.DS.$cssName.'" rel="Stylesheet">'.NLN;
    } */
    
    public function htmlStyleRef ($cssName) {
        return TAB.'<link type="text/css" href="'.$cssName.'" rel="Stylesheet" id="'.basename($cssName).'">'.NLN;
    }

    // ����� ���� �������� �����.
    // $cssFolderPath - ����� �����.
    // $cssNames - ����� ����� � �������.
    // ������� HTML-���� �������� �����.
    public function htmlStyleRefs ($cssFolderPath, $cssNames) {        $HTML = '';
        for ($i = 0; $i < count($cssNames); $i++)
            $HTML .= $this->htmlStyleRef ($cssFolderPath.DS.$cssNames[$i]);
        return $HTML;
    }

    public function htmlStyleBlock ($cssText) {
        $HTML = TAB.'<style type="text/css" rel="Stylesheet">'.NLN;
        $HTML = TAB.$cssText.NLN;
        $HTML .= TAB.'</style>'.NLN;
        return $HTML;
    }

    public function htmlJavaScriptRef ($jsPaths) {
        return TAB.'<script type="text/javascript" src="'.$jsPaths.'"></script>'.NLN;
    }

    // ����� ���� �������� java-�������.
    // $jsPaths - ����� ����� � java-���������.
    // ������� HTML-���� �������� �����.
    public function htmlJavaScriptRefs ($jsPaths) {
        $HTML = '';
        for ($i = 0; $i < count($jsPaths); $i++)
            $HTML .= $this->htmlJavaScriptRef($jsPaths[$i]);
        return $HTML;
    }

    public function htmlJavaScriptBlock ($javaScriptText) {        $_jblock = '<script type="text/javascript">';
        $_jblock .= $javaScriptText;
        $_jblock .= '<script>';
        return $_jblock;
    }

    public function htmlJavaScriptSingle ($javaScriptText, $withReturn = TRUE) {
        $HTML = "javascript: ";
        if (!empty($javaScriptText))
            $HTML .= $javaScriptText;
        if ($withReturn)
            $HTML .= "return false;";
        return $HTML;
    }

    public function htmlJavaScriptWithNoBlock ($javaScriptText, $altText) {        $_altJBlock = $this->htmlJavaScriptBlock($javaScriptText);
        $_altJBlock .= '<noscript>';
        $_altJBlock .= $altText;
        $_altJBlock .= '</noscript>';
        return $_altJBlock;
    }

    // ������ ����� ����� ���������� java-������� ��� �������.
    // $jscriptDirectory - ���� �� ��������� ����� java-�������.
    // $plugin - ����� ���� java-������� �������.
    // ������� ����� ������ �� java-�������.
    public function htmlJavaScriptMerged ($jsLibDirectory, $jsAddDirectory, $jsList, $retAsHtml = TRUE) {        //echo printf(HRUNLOG, "Loading javaScripts");
        //echo printf(HRUNLOG, "Initial directory: ".$jsLibDirectory);
        //echo printf(HRUNLOG, "Additional directory: ".$jsAddDirectory[DIR_JSC]);

        $js = false;

        $a = $jsList[LINK_JSC];


        //echo printf(HRUNLOG, "Loading Global JavaScripts");
        if (isset($a[KEY_GLOBAL]))
            foreach ($a[KEY_GLOBAL] as $key => $val)
                $js[] = $jsLibDirectory.DS.$val;
                      // echo "aaaaa";
        //echo printf(HRUNLOG, "Loading Plugin JavaScripts");
        if (isset($a[KEY_PLUGIN])) {
            foreach ($a[KEY_PLUGIN] as $key => $val)
                $js[] = $jsAddDirectory[DIR_JSC].DS.$val;
        }
                     //  echo "bbbb";
       // echo sprintf(HRUNLOG, "Loading Web JavaScripts");
        if (isset($a[KEY_WEB]))
        {
           // echo "ok";
            foreach ($a[KEY_WEB] as $key => $val)
            {
              //  echo $jsAddDirectory.DS.DIR_JSC.DS.$val;
                $js[] = $jsAddDirectory.DS.DIR_JSC.DS.$val;
            }
        }
        if (!$retAsHtml)
            return $js;
        return $this->htmlJavaScriptRefs($js);
    }

    public function GetPluginStyles($plugin) {
        $ppd = $this->$pluginDir.DS.$plugin->config['_NAME'].DS.$plugin->config['_NAME'].'_css';
        if (!file_exists($ppd)) return false;
        $css = Array();
        foreach ($filesArray as $key => $val)
            $css[] = $ppd.DS.$val;
        return $css;
    }

    // ����� ���� ���� ��� ������ javascript-�������.
    // $methodName - ����� ������.
    // $args - ��������� �������.
    // ������� ���� ���� � �������� javascript-�������.
    public function htmlJSMethodCall ($methodName, $args = NULL) {        $HTML = '';

        if (!empty($methodName))
        {            $fargs = '';
            if (isset($args) && count($args) != 0)
            {
                foreach ($args as $value)
                    $fargs .= $value.', ';
                $fargs = substr($fargs, 0, strlen($fargs) - 2);
            }

            $HTML .= $this->htmlExecuteJS($methodName.'('.$fargs.');', TRUE);
        }

        return $HTML;
    }

    // ������ ��� javascript.
    // $jscode - javascript ���.
    // ������� ��������� ��������� javascript-����.
    public function htmlExecuteJS($jscode, $return = FALSE) {        $HTML = '<script type="text/javascript">';
        $HTML .= $jscode;
        $HTML .= '</script>';

        if ($return)
            return $HTML;

        echo $HTML;
    }

    // ����� ��������� �������.
    // $title - ������� ���������.
    // $menuList - ������ ������� ������� �������.
    // $currPlug - ������ ��������.
    // $separator - ��������� ������� ���������.
    // ������� ���������� ������ ��������� ��� �������.
    public function htmlTitle($title, $menuList, $currPlug, $separator)
    {        $HTML = $title;        $sep = ' : ';
        if (isset($separator) and $separator != '')
            $sep = $separator;

        if (isset($menuList[$currPlug]))
            $HTML .=$sep.$menuList[$currPlug];

        return $HTML;
    }

    // ����� ��������� �����.
    // $stringArray - ����� ����� ��� ���������.
    // ������� ������� HTML-�����.
    public function htmlTextLines($stringArray, $prefix = '')
    {        if (isset($prefix) && !empty($prefix))
            foreach ($stringArray as $key => $val)
                 $stringArray[$key] = $prefix.$val;

        return implode('<br />', $stringArray);
    }

    // ����� ������� �� �������� � ����� ���������������� ��������� ���� <code>
    // $pagePath - ���� �� ������� �������.
    // $variables - �������� ���������.
    // ������� ���������� HTML �������.
    public function htmlPage($pagePath, $variables = FALSE)
    {
        $phpStart = 0;
        $phpEnd = 0;
        $phpStrTag = FALSE;
        $phpArrTag = Array();
        $phpTagKey = FALSE;
        $phpTagValue = FALSE;
        $phpRezult = 'hello';

        $data = FALSE;

        if (file_exists($pagePath))
            $data = join('',file($pagePath));

        while (TRUE)
        {
            $phpStart = strpos($data, '<php:output');
            if ($phpStart === FALSE)
                BREAK;
            $phpEnd = strpos($data, '>', $phpStart);
            $phpStart += 11;
            $phpEnd -= $phpStart;

            $phpStrTag = substr($data, $phpStart, $phpEnd);

            if (!empty($phpStrTag))
            {                $_tag = str_replace('"', '', $phpStrTag);                $_tag = trim($_tag);
                $_tag = strtolower($_tag);
                $_tag = explode(' ', $_tag);

                $phpArrTag = Array();
                foreach ($_tag as $param)
                    $phpArrTag = array_merge($phpArrTag, explode('=', $param));

                $phpTagKey = $phpArrTag[0];
                $phpTagValue = $phpArrTag[1];

                switch ($phpTagKey)
                {                    case 'function':
                        if (isset($phpArrTag[2]) && strcasecmp($phpArrTag[2], 'object') == 0 && isset($phpArrTag[3]) && strlen($phpArrTag[3]) != 0)
                            $phpRezult = htmlcode($phpTagValue, $phpArrTag[3]);
                        else
                            $phpRezult = htmlcode($phpTagValue);
                        break;
                    case 'variable':
                        if (isset($variables[$phpTagValue]))
                        {                            if (is_array($variables[$phpTagValue]))
                            {                                $phpRezult = $variables[$phpTagValue];
                                if (isset($phpArrTag[2]) && strcasecmp($phpArrTag[2], 'index') == 0 && isset($phpArrTag[3]) && strlen($phpArrTag[3]) != 0)
                                {                                    $indexes = explode('.', $phpArrTag[3]);
                                    foreach ($indexes as $idx)                                        if (isset($phpRezult[$idx]))
                                            $phpRezult = $phpRezult[$idx];
                                        else
                                            break;
                                }

                                if (is_array($phpRezult))
                                    $phpRezult = join('', $phpRezult);
                            }
                            else
                                $phpRezult = $variables[$phpTagValue];
                        }
                        else
                            $phpRezult = '<b>myPhpWebSite. ERROR 2.</b> Variable not exists ('.$phpTagValue.'). Please check your syntax.';
                        break;
                    default:
                        $phpRezult = '<b>myPhpWebSite. ERROR 1.</b> Parameter not exists ('.$phpTagValue.'). Please check your syntax.';
                        break;
                }
                $data = str_replace('<php:output'.$phpStrTag.'>', $phpRezult, $data);
            }
        }

        // ³��������� ������� �� ��������
        //include_once($cfg_path['WEB'].'/'.'web.'.$pageName.'.tpl');
        echo $data;
    }

    // ������� ���������� ������ � DIV ���������
    // $value - ����� ������
    // $className - ���� ����� ������
    // $otherTags - �������� ����
    // ������� HTML-���� ������.
    public function htmlStyledButton ($value, $className, $otherTags = '')
    {
        if (strlen($otherTags) != 0)
            $otherTags = ' '.$otherTags;

        $HTML = '<div class="'.$className.'">';
        $HTML .= '<input type="button" value="'.$value.'" class="'.$className.'"'.$otherTags.'>';
        $HTML .= '</div>';

        return $HTML;
    }

    public function htmlButton ($value, $cssClass, $id = '', $onClick = '', $other = '')
    {        $HTML = '<input type="button"';
        if (!empty($id))
            $HTML .= ' id='.DQUO.$id.DQUO;
        $HTML .= ' value='.DQUO.$value.DQUO;
        $HTML .= ' onClick='.DQUO.$onClick.DQUO;
        if (!empty($other))
            $HTML .= ' '.$other;
        $HTML .= '/>';
        return $HTML;
    }

    public function htmlRaw ($value) {        print($value);
    }

    public function htmlRawStyled ($value, $cssClass, $id = '') {        $HTML = '<div';
        if (isset($id))
            $HTML .= ' id="'.$id.'"';
        $HTML .= 'class="'.$cssClass.'"';
        $HTML .= '>'.$value.'</div>';
        return $HTML;
    }

    public function htmlRawSpanStyled ($value, $cssClass, $id = '') {
        $HTML = '<span';
        if (isset($id))
            $HTML .= ' id="'.$id.'"';
        $HTML .= 'class="'.$cssClass.'"';
        $HTML .= '>'.$value.'</span>';
        return $_block;
    }

    // ������ PHP ���.
    // $code - ���.
    // $useCheck - �������� ��������� �� ���������.
    // $data - ����� ���.
    // ������� ��������� ��������� ����.
    public function _evaluateValue($exeCode, $useCheck = FALSE, $data = FALSE, $allow = TRUE)
    {        $_isEval = false;
        $out = false;

        if (!empty($data) && is_array($data))
        {            extract($data);
            //echo '<br>extracted '.$a.' elements<br>';
        }

        //echo 'defValue = '.$_defOut.'; value = '.$value.'; code = '.$exeCode.'<br>';
        // �������� ������� ���� �� ��������
        if ($useCheck)
        {
            $_p = strpos($exeCode, ':');
            $_type = substr($exeCode, 0, $_p);
            $_isEval = ($_p >= 0 && strtolower($_type) == 'eval');
            if ($_isEval)
                $exeCode = substr($exeCode, $_p + 1);
        }

        // �������� ��� �� ��� ��� ��������� �� �� ��������
        if ($allow && $_isEval)
            eval($exeCode);

        if (!$_isEval)
            $out = $exeCode;
        else
            if (!$allow && isset($defValue))
                $out = $defValue;

        return $out;
    }

    private function _setGET($key, $value, $request = '')
    {
        // ����� ������ GET � ������� ���������� ?...&$key=$value&
        global $_GET;

        if (empty($request))
            $request = $_GET;

        $get = '?';
        $b = FALSE;

        foreach ($request as $id => $val)
        {
            if ($id == $key)
            {
                $v = urlencode($value);
                $b = TRUE;
            }
            else
                $v = $val;

            if ($id === "nocache")
                continue;

            $get .= $id.'='.urlencode($v).'&';
        }
        if ($b === false)
            $get .= $key.'='.urlencode($value).'&';

        return $get;
    }

    public function _createAction($actionMask, $row, $value, $useEval = FALSE)
    {        if (!isset($actionMask) || strlen($actionMask) == 0)
            return '';

        $data = Array('value' => $value);

        $link = $this->_evaluateValue($actionMask, true, $data, $useEval);

        //if ($link === $actionMask)
        //{
            if (!empty($row))
                foreach ($row as $key => $val)
                    $link = str_replace($key, $val, $link);
        //}

        if (!empty($link) && substr($link, 0, 1) == '>')
        {
            $link = substr($link, 1);

            $_rs = explode('&', $link);
            $rs = false;

            foreach ($_rs as $key => $val)
            {
                $_item = explode('=', $val);
                $rs[$_item[0]] = $_item[1];
            }

            $outputQuery = false;

            foreach ($_GET as $key => $val)
            {
                if (isset($rs[$key]))
                {
                    $outputQuery[] = $key.'='.$rs[$key];
                    unset($rs[$key]);
                }
                else
                    $outputQuery[] = $key.'='.$val;
            }

            foreach ($rs as $key => $val)
                $outputQuery[] = $key.'='.$val;

            $link = '?'.implode('&', $outputQuery);
        }

        return $link;
    }

    public function getTemplateResult ($model, $templateFile) {
        ob_start();
        include $templateFile;
        return ob_get_clean();
    }


    public function renderTemplate($model) {
        $_tplName = $model['PAGE']['template'];
        $_custName = $model['SITE']['customer'];

        // customer template
        $customerTplFile = SITEPATH . '//web//customers//'.$_custName.'//templates//' . $_tplName . '.html';
        // default template
        $templateFile = SITEPATH . '//web//default//templates//' . $_tplName . '.html';
        if (file_exists($customerTplFile))
            $templateFile = $customerTplFile;

        //echo 'rendering ' . $templateFile;
        
        ob_start();
        include $templateFile;
        return ob_get_clean();
        
    }



        public function getTemplate($name, $data, $displaytype = false, $extract = true){
            if (!empty($data) && is_array($data) && $extract)
                extract($this->getSingleKeyData($data));
            //var_dump(getSingleKeyData($data));
            //$pi = pathinfo(__FILE__);
            //$root = dirname($pi['dirname']);
            
            $config = $this->assertConfig();
            $page = $this->page;

            ob_start();
            include SITEPATH . '//web//templates//' . $name . '.html';
            return ob_get_clean();
            /*
            $tpl = file_get_contents('web\\templates\\' . $name . '.html');
            //$htpl = htmlentities($tpl);
            $htpl = $tpl;
            $hrd = str_replace($this->getKeys($data), $this->getValues($data), $htpl);
            eval("\$aaaxxddd = \"$hrd\";");
            //echo $aaaxxddd;
            //return html_entity_decode($htd);
            return html_entity_decode($aaaxxddd);
            */
        }

        public function getTemplateRaw($name){
            return file_get_contents(SITEPATH . '//web//templates//raw//' . $name . '.html');
        }

        public function assertConfig() {
            return $this->config;
        }

        public function getValues($d, &$out = array()){
            foreach($d as $k => $v){
                if (is_array($v)){
                    $this->getValues($v, $out);
                }
                else
                    $out[] = $v;
            }
            return $out;
        }

        public function getKeys($d, &$out = array(), $kr = false){
            foreach($d as $k => $v){
                if (is_array($v)){
                    if ($kr === false)
                        $this->getKeys($v, $out, $k);
                    else
                        $this->getKeys($v, $out, $kr.'_'.$k);
                }
                else{
                    if ($kr === false)
                        $out[] = ''.$k.'';
                    else
                        $out[] = ''.$kr.'_'.$k.'';
                }
            }
            return $out;
        }

        public function getSingleKeyData($data){
            $keys = $this->getKeys($data);
            $vals = $this->getValues($data);
            $sgc = array();
            $tot = count($keys);
            for ($i = 0; $i < $tot; $i++)
                $src[$keys[$i]] = $vals[$i];
            return $src;
        }

}

?>
