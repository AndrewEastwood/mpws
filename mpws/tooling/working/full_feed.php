<?php

	require_once "../../../../libs/configs/cfg.db.php";
    
	function __autoload($class_name)
	{
$fn = strtolower("D:/hshome/marihooan/pobutteh.com.ua/libs/classes/".$class_name.".class.php");
		require_once $fn;
		
	}
    
    
    $db_lib = new DataBase();
    $db_lib->Connect();

    $lastArtsTable = $db_lib->GetTable("SELECT
        ishop_articles.AID, ishop_articles.APRICE, ishop_articles.ADATECREATE, ishop_articles.ADESC, ishop_articles.APRICE_CORRECT,
        ishop_articles.ANAME, ishop_categories.CATID, ishop_categories.CATNAME,
        ishop_developers.DEVNAME, ishop_developers.DEVID, (ishop_articles.AEXIST1+ishop_articles.AEXIST2+ishop_articles.AEXIST3) as `itemsCount`
    FROM ishop_articles
    LEFT OUTER JOIN ishop_categories ON ishop_articles.CATID = ishop_categories.CATID
    LEFT OUTER JOIN ishop_developers ON ishop_articles.DEVID = ishop_developers.DEVID
    ORDER BY ishop_articles.AID ASC;");
    $currencyRate = $db_lib->GetRow('', ' /* ALL ARTICLES CURRENCY */ ishop_currency', false, "CURRID='1'");
    
    
    $db_lib->Close();
/*
    foreach ($lastArtsTable as $key => $row)
        $HTML .= '<div class="mpwsItem">'.$this->getProductMainImage($row).'<label class="lastArtCategory"><b>'.$row['CATNAME'].'</b></label><label class="lastArtDeveloper">'.$row['DEVNAME'].'</label><a href="shop.php?aid='.$row['AID'].'" class="newArticle"><strong>'.$row['ANAME'].'</strong></a></div>';
/*
while ($line = mysql_fetch_assoc($result))
        {
            $return[] = $line;
        }
                    <docs>http://someurl.com</docs>
                    <managingEditor>you@youremail.com</managingEditor>
                    <webMaster>you@youremail.com</webMaster>
*/
$now = date("D, d M Y H:i:s T");

$output = "<?xml version=\"1.0\"?>
    <rss version=\"2.0\" xmlns:g=\"http://base.google.com/ns/1.0\">
    <channel>
        <title>".strcleaner("Pobutteh Продукти")."</title>
        <link>http://pobutteh.com.ua</link>
        <description>".strcleaner("Товари інтернет-магазину Побуттех")."</description>";

//foreach ($return as $line)

foreach ($lastArtsTable as $key => $line)
{
    $output .= "
        <item>
            <title>".strcleaner($line['DEVNAME'].' '.$line['ANAME'], true)."</title>
            <link>".htmlentities("http://pobutteh.com.ua/shop.php?cat=".$line['DEVID']."&dev=".$line['CATID']."&aid=".$line['AID'])."</link>
            <category>".($line['CATNAME'])."</category>
            <image>
                <url>".htmlentities("http://pobutteh.com.ua/productimage.php?id=".$line['AID'])."</url>
            </image>
            <description><![CDATA[".strcleaner($line['ADESC'])."]]></description>
            <g:image_link>".htmlentities("http://pobutteh.com.ua/productimage.php?id=".$line['AID'])."</g:image_link>
            <g:price>".sprintf("%.0f", $line['APRICE'] * (($line['APRICE_CORRECT']+100)/100)* $currencyRate['CURRATE2'])." UAH</g:price>
            <g:condition>new</g:condition>
            <g:availability>".($line['itemsCount']?"in stock":"out of stock")."</g:availability>
            <g:id>".$line['AID']."</g:id>
            <g:brand>".$line['DEVNAME']."</g:brand>
        </item>";
}

$output .= "</channel></rss>";


function strcleaner($string, $unicode = false, $encode = true){
    // new row
    $out = str_replace('</tr>', '\r\n', $string);
    $out = strip_tags($out);
    $out = preg_replace("/&#?[a-zA-Z0-9]+;/i","", $out);
    if ($unicode)
        $out = preg_replace('/[^(\x20-\x7F)^(\.)] */',' ', $out);
    if ($encode)
        $out = js_urlencode($out);//htmlentities($out, ENT_COMPAT, 'cp1251');
    return $out;
}


function js_urlencode($str)
{
    $str = mb_convert_encoding($str, 'UTF-16', 'UTF-8');
    $out = '';
    for ($i = 0; $i < mb_strlen($str, 'UTF-16'); $i++) 
    {
        $out .= '\\u'.bin2hex(mb_substr($str, $i, 1, 'UTF-16'));
    }
    return $out;
} 

// Сообщяем браузеру что передаем XML
header("Content-type: text/xml; charset=windows-1251");

echo $output;
?>