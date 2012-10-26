<?php

// PHP ��������.
// ��������� FileManager.
// -----------------------
//
// ����������� ��� ������ � �������.
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

class libraryFileManager
{
    // _�����������
    function __construct() { }

    // _����������
    function __destruct() { }

    public static function getAllFilesFromDirectoryAsMap($pathToDirectory, $removeSuffix) {
        $files = self::getAllFilesFromDirectory($pathToDirectory);
        
        
        //var_dump($files);
        $fmap = array();
        foreach ($files as $fpath) {
            $fkey = str_replace(array($pathToDirectory, $removeSuffix, '/'), array('','','.'), $fpath);
            //echo $fkey;
            $fmap[$fkey] = $fpath;
        }
        return $fmap;
    }
    public static function getAllFilesFromDirectory($pathToDirectory) {
        if (empty($pathToDirectory))
            return false;
        
        $fileSPLObjects =  new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($pathToDirectory),
                        RecursiveIteratorIterator::CHILD_FIRST
                    );
        
        $rez = array();
        
        try {
            foreach( $fileSPLObjects as $fullFileName => $fileSPLObject ) {
                //print $fullFileName . " " . $fileSPLObject->getFilename() . "\n";
                if (!$fileSPLObject->isDir())
                    $rez[] = $fullFileName;
            }
        }
        catch (UnexpectedValueException $e) {
            printf("Directory [%s] contained a directory we can not recurse into", $pathToDirectory);
        }
        
        return $rez;
    }
    
    
    public function GetNewFileName($original, $append = '', $prepend = '', $withDate = false, $ret = 'FULL') {
        $p = pathinfo($original);
        $newName = $p['filename'];
        if (!empty($append))
            $newName .= $append;
        if (!empty($prepend))
            $newName = $prepend . $newName;
        if ($withDate) {
            $dd_folder = str_split(substr($p['filename'], 0, 6), 2);
            $dd_folder = implode('-', $dd_folder);
            if (!empty($dd_folder))
                $newName = $dd_folder . DS . $newName;
        }
        switch ($ret) {
            case 'FULL':
                return $p['dirname'] . DS . $newName . DOT . $p['extension'];
            case 'NEW':
                return $newName . DOT . $p['extension'];
            case 'ALL':
                return array(
                            'FULL' => $p['dirname'] . DS . $newName . DOT . $p['extension'],
                            'NEW' => $newName . DOT . $p['extension']);
        }
    }

    // ³������ ���� � ������ ���� ����
    // $tabSize - ����� ������������� �������
    // $filePath - ���� �� ���������� �����
    // ������� ������������ ���� � ���������� �����
    public function GetFileFormattedContent($filePath, $tabSize = 5)
    {
        if (!file_exists($filePath))
            return $res;
        $tabs = str_pad('', $tabSize, '&nbsp;');
        $res = $this->GetFileContent($filePath);
        return str_replace(array("\r\n","\r", "\n", "\t"), array("<br>","<br>","<br>", $tabs), $res);
    }

    // ³������ ���� � ������ ���� ����
    // $filePath - ���� �� ���������� �����
    // ������� ���� � ���������� ����� �� �
    public function GetFileContent($filePath)
    {
        if (!file_exists($filePath))
            return '';
        return file_get_contents($filePath);
    }

    // ������ ��� � ������� �����.
    // $filePath - ���� �� �����.
    // $data - ��� ��� ������.
    // ������� true, ���� ��� ������� ������ false.
    public function PutFileContent($filePath, $data)
    {
        if (empty($filePath))
            return false;
        return (bool)file_put_contents($filePath, $data);
    }

    // ������� ����.
    // $filePath - ���� �� �����.
    // ������� true, ���� ���� �������� ������ false.
    public function DeleteFile($filePath)
    {
        $fn = $this->dir.DS.$filePath;
        $fn = str_replace(DS.DS, DS, $fn);
        return @unlink($fn);
    }

    // ������ ����� � �������� ������� �������.
    // $nameLength - ������� ������.
    // ������� ���������� �����.
    public function CreateTempFileName($nameLength = 15)
    {
        $res = '';
        $image_filename_chars = 'qwertyuiopasdfghjklzxcvbnm1234567890';
        $chars_len = strlen($image_filename_chars) - 1;

        for ($i = 1 ; $i <= $nameLength ; $i++)
              $res .= $image_filename_chars[rand(0, $chars_len)];

        return $res;
    }

    // ������ �������� ��� � ���� � ������ �����.
    // ������� ��������� ����� � ������ dmyHisu.
    public function CreateDatedFileName()
    {
        return date("dmyHisu");
    }

    // �������� ��� �����.
    // $filePath - ���� ��� ��� �����.
    // $extstensions - ����� ���� ��� ��������� ����� � ������� ����, �� ������� ����� ��� ������ ����� ������ ����.
    // ������� true, ���� ���� �������� �� ����������� ����, ������ false.
    public function CheckFileExstension($filePath, $extstensions = false)
    {
        $extArray = false;
        $fext = $this->GetFileExtension($filePath);

        if (is_string($extstensions))
            $extArray = explode(',', $extstensions);
        elseif (is_array($extstensions))
            $extArray = $extstensions;

        return in_array($fext, $extArray);
    }

    // ������ ���������� �����.
    // $filePath - ���� ��� ��� �����.
    // ������� ���������� ����� ��� false.
    public function GetFileExtension($fileName)
    {
        if (empty($fileName))
            return false;
        $p = pathinfo($fileName);
        return strtolower($p['extension']);
    }

    // ³����� ��� ����� ��� ���������� � ���� �����.
    // $filePath - ���� ��� ��� �����.
    // ������� ��� ����� ��� ���������� ��� false.
    public function GetFileNameWithoutExtension($filePath)
    {
        if (empty($filePath))
            return false;
        $filePath = strtolower($filePath);
        $p = pathinfo($filePath);
        return basename($filePath, DOT.$p['extension']);
    }

    // ����� ��� ����� �� ������� ���� ����������.
    // $filePath - ���� ����� ��� ���� ���.
    // $newFileName - ���� ��� ����� ��� ����������.
    // ������� ����� ���� ����� � ����� ������ ��� false.
    public function ChangeFileName($filePath, $newFileName, $changeExt = false)
    {
        if(empty($filePath) || empty($newFileName))
            return false;
        $p = pathinfo($filePath);
        if ($changeExt)
            return $p['dirname'].DS.$newFileName;
        return $p['dirname'].DS.$newFileName.DOT.$p['extension'];
    }

    // ����� ���� �� ��������� ����.
    // $uplFilePath - ���� �� �����.
    // $dataDir - ����� �����������.
    // $name - ��� �����.
    // $useDatedName - ������������ ���������� ����, �� ���� ���.
    // $retMode - ����� ����������:
    //                0 - ������ ���� �� ����� � �����������;
    //                1 - ������ ���� �� ����� ��� ����������;
    //                2 - ��� ����� � �����������;
    //                3 - ��� ����� ��� ����������.
    // ������� ������ ���� ��� ���� ������� �� ������������ �����, ���� ���������
    // ����� ������� ������, ������ ������� �������:
    //                flase - �� ������� ���� ��� ���������;
    //                -1 - �� ������� ����� �����������;
    //                -2 - �� ������� ��� ������ �����.
    //                -3 - ��������� �� ��������.
    
    public function SaveResizedFile($sourcePath, $destPath, $imageThimbnailSizeW = 1024, $imageThimbnailSizeH = 768) {
    
        // image info
        list($width, $height) = getimagesize($sourcePath);
        

        // ratio calc
        $rw = $width / $imageThimbnailSizeW;
        $rh = $height / $imageThimbnailSizeH;
        $r = max($rw, $rh);

        //echo "R === " . $r;
        
        $newWidth = $width / $r;
        $newHeight = $height / $r;
        
        
        $im = @imagecreatefromjpeg($sourcePath);
        $im_final = @imagecreatetruecolor ($newWidth, $newHeight);
        imagecopyresized($im_final, $im, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        $saverez = imagejpeg($im_final, $destPath);
        imagedestroy($im);
        imagedestroy($im_final);
        
        return $saverez;
    }
    
    public function SaveUploadedFile($uplFilePath, $dataDir, $name = '', $useDatedName = false, $retMode = 0, $nw = 1024, $nh = 768)
    {
    
        //var_dump($uplFilePath);
            //echo 0;
        // ���� ���� ������������� ����� �� ��������
        if (!file_exists($uplFilePath))
            return false;
            //echo 1;
        // ���� �� ������� ����� ��� ���������� (������� -1)
        if (!isset($dataDir) || empty($dataDir))
            return -1;
            //echo 2;
        // ���� �� ������� ��'� ����� ��� ���������� (������� -2)
        if ((!isset($name) || empty($name)) && !$useDatedName)
            return -2;
            ///echo 3;
        // �������� ���������� �����
        $ext = $this->GetFileExtension($name);
            //echo 4;
        // ��������, �� ��������, �������� ����� �����
        if ($useDatedName)
            $name = $this->CreateDatedFileName();
        else
            $name = basename($name, '.' . $ext);
            //echo 5;
        //make directory
        if (!file_exists($dataDir)) 
            mkdir($dataDir, 0777, true);
            //echo 6;
        
        /*
        $dd_folder = date('d-m-y');
        if (!file_exists($dataDir.DS.$dd_folder)) {
            echo 'attempt to create folder: ' . $dataDir.DS.$dd_folder;
            mkdir($dataDir.DS.$dd_folder);
        }
        */
        // ���� ����� ���� ��� ���� �� �������� ���� ���
        while (file_exists($dataDir.DS.$name.'.'.$ext))
            $name = $this->CreateDatedFileName();
            
        echo $dataDir.DS.$name.DOT.$ext;
            
        $saverez = $this->SaveResizedFile($uplFilePath, $dataDir.DS.$name.DOT.$ext, $nw, $nh);
        
        //var_dump($uplFilePath);
        //var_dump($im_final);
        //var_dump($dataDir.DS.$name.'.'.$ext);
            
        // ���� �� ��������� ���� (������� -3)
        if (!$saverez)
            return -3;
        // ��������� �������
        $retPath = '';
        // �������� ���������
        switch ($retMode)
        {
            case 1:
                $retPath = $dataDir.DS.$name;
                break;
            case 2:
                $retPath = $name.'.'.$ext;
                break;
            case 3:
                $retPath = $name;
                break;
            case 0:
            default:
                $retPath = $dataDir.DS.$name.'.'.$ext;
                break;
        }

        return $retPath;
    }

    public function GetFilePathByName($fName)
    {
        $dd_folder = str_split(substr($fName, 0, 6), 2);
        $dd_folder = implode('-', $dd_folder);
        return $dd_folder.DS.$fName;
    }

    // ������� �������� ���� �� ������� ����� � ���������� �������.
    // $rootBasedDirPath - ���� �� ����� ������� ���������� ��������.
    // ������� �������� ���� �� �����.
    public function GetRelativePath($rootBasedDirPath)
    {
        $back = US.DS;
        $path = $rootBasedDirPath;
        $depthLimit = 8;

        while (!file_exists($path) && $depthLimit > 0)
        {
            $path = $back.$path;
            $depthLimit--;
        }

        if ($depthLimit == 0 && !file_exists($path))
            return $rootBasedDirPath;

        return $path;
    }

    // ������ ����� ���� ����� � �����.
    // $dirPath - ���� �� ����� � �������.
    // $filter - ������ �����.
    // ������� ����� ���� ����� ��� false.
    public function GetDirectoryFiles($dirPath, $filter = null)
    {
        // ����� �����
        $filesArray = false;

        // ³�������� ����� � ���������� �� ������� ���, �� ��� �
        $dirHandle = opendir($dirPath);

        // ���������� ����� ������� ����� � �����
        while (false != ($filesArray[] = readdir($dirHandle)));

        // ³������� � ������ ������ ����� �������������� ������
        if (isset($filter))
        {
            if (is_string($filter))
                 $filesArray = preg_grep($filter, $filesArray);
            if (is_array($filter))
                foreach ($filter as $regexp)
                     $filesArray = preg_grep($regexp, $filesArray);
        }

        // �������������� �����
        $filesArray = array_values($filesArray);

        // ��������� �����
        closedir($dirHandle);

        return $filesArray;
    }

    // ������� ����� ����� � ����������� � ������ ������.
    // $url - ������ ������.
    // ������� ����� ����� ��� false.
    function GetFileNameFromURL($url)
    {
        if (empty($url))
            return false;

        $pu = parse_url($url);

        if (empty($pu['path']))
            return false;

        $p = pathinfo($pu['path']);

        return $p['basename'];
    }

    public function SaveUploadedImages ($dataPath, $imageKeyScope = '_aupimg', $acceptableExtensions = 'JPG,jpg') {
    

        // ���� ��� ������� ���������
        $img_lib = new Images();
        $aImgString = "";
        $newAImgs = Array();
        $extAImgs = Array();
        $delAImgs = Array();
        $aliAImgs = Array();
        $imgCount = count($_FILES[$imageKeyScope]['name']);

        $returnObj = array(
            'UPLOAD_ERROR' => 0,
            'TYPE_ERROR' => 0,
            'DATA' => ''
        );

        // ERROR KEYS
        // 0 - without errors
        // >0 - upload with errors

        // �������� ��� ��� ������� �� �������� ��������
        foreach ($_POST as $key => $val)
        {
            if (substr($key, 0, 6) == '_aimg_')
            $extAImgs[] = str_replace('_sm', '', $val);
            if (substr($key, 0, 9) == '_delaimg_')
            {
                $delAImgs[] = str_replace('_sm', '', $val);
                $dd = implode('-', str_split(substr($val, 0, 6), 2));
                // ��������� ������� �����������
                if (file_exists($dataPath.DS.$dd.DS.$val))
                    unlink($dataPath.DS.$dd.DS.$val);
                // ��������� �������� ����������
                // small and small30
                if (file_exists($dataPath.DS.$dd.DS.str_replace('_sm', '', $val)))
                    unlink($dataPath.DS.$dd.DS.str_replace('_sm', '', $val));
                if (file_exists($dataPath.DS.$dd.DS.str_replace('_sm30', '', $val)))
                    unlink($dataPath.DS.$dd.DS.str_replace('_sm', '_sm30', $val));
            }
        }

        // �������� � �������� �������� ������
        $aliAImgs = array_diff($extAImgs, $delAImgs);
        $aImgString = join($aliAImgs, ';');
        // ���������� �������� �������������
        //var_dump($_FILES['_aupimg']);
        $uploadedImagesCounterWithError = 0;
        $typeImagesCounterWithError = 0;
        for ($i = 0; $i < $imgCount; $i++ )
        {
            //echo $_FILES['_aupimg']['tmp_name'][$i]."|";
            // ���� ���� �������� �� ����������
            if (empty($_FILES[$imageKeyScope]['tmp_name'][$i]))
                continue;
            //echo $_FILES['_aupimg']['tmp_name'][$i]."|";
            // ���������� ���������� (���) �����
            if (!$this->CheckFileExstension($_FILES[$imageKeyScope]['name'][$i], $acceptableExtensions)) {
                //JPEG, GIF, PNG
                $typeImagesCounterWithError++;
                continue;
            }
            //echo $_FILES['_aupimg']['name'][$i]."|";
            // �������� ���� � �������� ����� � �����������
            $fName = $this->SaveUploadedFile($_FILES[$imageKeyScope]['tmp_name'][$i], $dataPath.DS.date('d-m-y'), $_FILES[$imageKeyScope]['name'][$i], true, 2);
            //echo $fName."|";
            //var_dump('file name is = ' . $fName);
            if (is_int($fName) || strlen($fName) < 10) { //JPEG, GIF, PNG
                $uploadedImagesCounterWithError++;
                continue;
            }

            //var_dump($_FILES['_aupimg']['tmp_name'][$i]);
            //var_dump($img_lib);
            // ������� ��� �����
            $fNewName = $this->GetFileNameWithoutExtension($fName);
            $fNewName_sm = $this->ChangeFileName($fName, $fNewName.'_sm');
            $fNewName_30 = $this->ChangeFileName($fName, $fNewName.'_sm30');
            // �������� �������� ����������
            //echo 'saving sm image';
            $img_lib->ResizeImage($dataPath.DS.date('d-m-y').DS.$fName, $dataPath.DS.date('d-m-y').DS.$fNewName_sm, 160, 120, 0xFFFFFF, 100);
            //echo 'saving sm30 image';
            $img_lib->ResizeImage($dataPath.DS.date('d-m-y').DS.$fName, $dataPath.DS.date('d-m-y').DS.$fNewName_30, 32, 24, 0xFFFFFF, 100);
            // ������ ����������
            $aImgString .= ';'.$fName;

            $returnObj['DATA'] = $aImgString;
            
        }

        $returnObj['TYPE_ERROR'] = $typeImagesCounterWithError;
        $returnObj['UPLOAD_ERROR'] = $uploadedImagesCounterWithError;


        //var_dump($returnObj);
        return $returnObj;

        /*
        $this->messages[] = '������������ ������ (���) ���������� ' . $_FILES[$imageKeyScope]['name'][$i];
        $this->messages[] = '���������� �� ���� ����������� ' . $_FILES[$imageKeyScope]['name'][$i] . ' ('.$fName.')';
        */
    }

    
    
    
    /* FileUploader v2.0 */
    
    public static function FU_StoreTempFiles ($sessionKey) {
        //echo 'FU_StoreTempFiles';
        // get files by upload key
        $key = $_POST['fileUploadKey'];
        if (empty($key))
            return 'Unable to get upload key';
        
        $tempDir = DR . DS . 'data' . DS . 'temp' . DS . $sessionKey;
        
        //make directory
        if (!file_exists($tempDir)) 
            mkdir($tempDir, 0777, true);
        
        //echo 'SAVING TO: ' . $tempDir;

        //var_dump($_FILES);
        
        //$fileInfo = array();
        
        // cancel files
        $filesToCancel = explode(';', $_POST['fileCleanup']);
        foreach ($filesToCancel as $fileToCancel)
            if (!is_dir($tempDir . DS . $fileToCancel) && file_exists($tempDir . DS . $fileToCancel))
                unlink($tempDir . DS . $fileToCancel);
        
        // return all temporary uploaded files
        if (!empty($_FILES))
        foreach ($_FILES[$key]['tmp_name'] as $fileIndex => $fileTmpName) 
            if(move_uploaded_file($fileTmpName, $tempDir . DS . $_FILES[$key]['name'][$fileIndex]));
                /*$fileInfo[$sessionKey][] = array(
                    $_FILES[$key][$fileIndex]['name'],
                    $_FILES[$key][$fileIndex]['size'],
                    $_FILES[$key][$fileIndex]['error'],
                    $_FILES[$key][$fileIndex]['tmp_name']
                );*/

        return self::FU_GetSessionContent($sessionKey);
    }
    
    public static function globToJSON ($globList) {
        $map = array();
        foreach ($globList as $fileName)
            $map[] = basename($fileName);
        return libraryUtils::getJSON($map);
    }
    
    public static function FU_GetSessionContent($sessionKey) {
        //echo 'FU_GetSessionContent';
        $tempDir = DR . DS . 'data' . DS . 'temp' . DS . $sessionKey;
        $rez = array(
            'PATH' => $tempDir,
            'GLOB' => array(),
            'JSON' => '[]'
        );
        if(file_exists($tempDir)) {
            self::putAccessLog($tempDir, true);
            $glob = glob($tempDir . DS . '*');
            $rez['GLOB'] = $glob;
            $rez['JSON'] = self::globToJSON($glob);
        }
        return $rez;
    }
    
    public static function FU_PostFiles ($sessionKey, $owner, $postDate = false) {
        //echo 'FU_PostFiles';
        // return file names
        // use md5 of time() to get unic file name
        if (empty($postDate))
            $postDate = date('Y-m-d');
        else 
            $postDate = date('Y-m-d', strtotime($postDate));
        $uploadDir = DR . DS . 'data' . DS . 'uploads' . DS . $postDate . DS . $owner;
        
        // remove posted files
        $filesToCancel = explode(';', $_POST['fileCleanup']);
        foreach ($filesToCancel as $fileToCancel)
            if (!is_dir($uploadDir . DS . $fileToCancel) && file_exists($uploadDir . DS . $fileToCancel))
                unlink($uploadDir . DS . $fileToCancel);
        self::removeDirIfEmpty($uploadDir);        
        
        //make directory
        if (!file_exists($uploadDir)) 
            mkdir($uploadDir, 0777, true);
        
        $tmpFiles = self::FU_GetSessionContent($sessionKey);
        $fileMap = array();
        foreach ($tmpFiles['GLOB'] as $fileItem) {
            $storedFilePath = $uploadDir . DS . basename($fileItem);
            rename($fileItem, $storedFilePath);
            $fileMap[] = array('OWNER' => $owner, 'FILEPATH' => $storedFilePath);
        }

        // trunkate temporary dir
        self::rrmdir($tmpFiles['PATH']);

        return $fileMap;
    }
    
    public static function removeDirIfEmpty ($dir) {
        //echo 'removeDirIfEmpty';
        $items = glob($dir . DS . '*');
        //var_dump($items);
        //echo 'REMOVING: ' . $dir;
        if (count($items) === 0 && file_exists($dir) && is_dir($dir))
            rmdir($dir);
    }
    
    
    public static function putAccessLog ($fileContainer, $affectDirAccessTimeOnly = false) {
        // source from http://www.php.net/manual/en/function.fputs.php#88916
        // file container where all texts are to be written
        $fileContainer .= DS . date("MjY").'.log';

        // open the said file
        $filePointer = fopen($fileContainer,"w+");

        // text to be written in the file
        $logMsg = "You are located at ".$_SERVER["REMOTE_ADDR"]."\n";

        // below is where the log message has been written to a file.
        fputs($filePointer,$logMsg);

        // close the open said file after writing the text
        fclose($filePointer);
        
        // remove file
        if ($affectDirAccessTimeOnly)
            unlink($fileContainer);
    }
    
    /**
     * Recursively deletes a directory
     *
     * @param  string  path to a directory
     * @return void
     * source: http://davgothic.com/2011/01/recursively-delete-directories-using-php/
     */
    public static function removeDirectiryRecursive($path) {
        // also see previous version at: http://www.php.net/manual/en/function.rmdir.php#98622
	$iterator = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($path),
		RecursiveIteratorIterator::CHILD_FIRST
	);
	foreach ($iterator as $file) {
            if ($file->isDir()) {
                rmdir($file->getPathname());
            }
            elseif ($file->isFile() || $file->isLink()) {
                unlink($file->getPathname());
            }
	}
 
	rmdir($path);
    }

    public static function getUploadLinks ($dbLinks) {
        $map = array();
        $fileKeyPattern = DS.'storage'.DS.'%1$s-%3$s'.DS.'%2$s';
        
        //var_dump($dbLinks);
        
        // '/storage/'.date('Y-m-d').
        foreach ($dbLinks as $uploadedItem)
            $map[sprintf($fileKeyPattern, date('Y-m-d', strtotime($uploadedItem['DateCreated'])), basename($uploadedItem['Path']), $uploadedItem['Owner'])] = basename($uploadedItem['Path']);
        
        //var_dump($map);
        
        return array('MAP' => $map, 'JSON' => libraryUtils::getJSON($map), 'SRC' => $dbLinks);
    }
    
    public static function newDirectory ($path) {
        if (!file_exists($path)) {
            mkdir($path, 0666, true);
            return true;
        }
        return false;
    }
    
    public static function transferDirectoryData ($currentLocation, $newLocation, $isRecursive = true) {
        if ($currentLocation == $newLocation)
            return;
        if (!file_exists($newLocation))
            mkdir($newLocation, 0666, true);
        if ($isRecursive) {
            foreach ($iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($currentLocation, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST) as $item)
            {
                if ($item->isDir()) {
                    mkdir($newLocation . DS . $iterator->getSubPathName());
                } else {
                    copy($item, $newLocation . DS . $iterator->getSubPathName());
                }
            }
        } else
            rename($currentLocation, $newLocation);
        // remove prevoius directory
        self::removeDirectiryRecursive($currentLocation);
    }
        
        
    
}

?>
