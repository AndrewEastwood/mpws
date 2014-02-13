<?php

	require_once "../../../libs/configs/cfg.db.php";
    
	function __autoload($class_name)
	{
$fn = strtolower("D:/hshome/marihooan/pobutteh.com.ua/libs/classes/".$class_name.".class.php");
		require_once $fn;
		
	}
    
    
    $db_lib = new DataBase();
    $db_lib->Connect();

    $lastArtsTable = $db_lib->GetTable("SELECT
    ishop_articles.AID, ishop_articles.AIMAGE, ishop_articles.ADATECREATE, ishop_articles.ADESC, ishop_articles.ANAME, ishop_categories.CATID, ishop_categories.CATNAME, ishop_developers.DEVNAME
    FROM ishop_articles
    LEFT OUTER JOIN ishop_categories ON ishop_articles.CATID = ishop_categories.CATID
    LEFT OUTER JOIN ishop_developers ON ishop_articles.DEVID = ishop_developers.DEVID
    ORDER BY ishop_articles.ARATE DESC
    LIMIT 0, 20;");
    
    
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

$output = "<?xml version=\"1.0\" encoding=\"windows-1251\"?>
            <rss version=\"2.0\">
                <channel>
                    <title>Pobutteh Нові надходження</title>
                    <link>http://pobutteh.com.ua/data/ishop/feeds/rss.php</link>
                    <description>Нові надходження товарів інтернет-магазину Побуттех</description>
                    <pubDate>$now</pubDate>
                    <lastBuildDate>$now</lastBuildDate>
                    <docs>http://pobutteh.com.ua/data/ishop/feeds</docs>
                    <generator>mpws 2.0</generator>
                    <copyright>Copyright 2006 pobutteh.com.ua</copyright>
                    <language>ua</language>
            ";
            
//foreach ($return as $line)

    foreach ($lastArtsTable as $key => $line)
{
    $output .= "<item><title><![CDATA[".htmlentities($line['DEVNAME'].' '.$line['ANAME'])."]]></title>
                    <category>".($line['CATNAME'])."</category>
                    <link>".htmlentities("http://pobutteh.com.ua/shop.php?aid=".$line['AID'])."</link>
                    <image><url>".htmlentities("http://pobutteh.com.ua/productimage.php?id=".$line['AID'])."</url></image>
                    
<description><![CDATA[".(strip_tags($line['ADESC']))."]]></description>
                </item>";
}
$output .= "</channel></rss>";

// Сообщяем браузеру что передаем XML
header("Content-type: text/xml; charset=windows-1251");

echo $output;
?>