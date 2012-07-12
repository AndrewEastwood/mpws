<?php

class libraryDataBase {

    //internal info
    var $error = "";
    var $errno = 0;
    var $charset = "";
    var $affected_rows = 0;
    var $link_id = 0;
    var $query_id = 0;
    var $selectCount = 0;

    function __construct() { }

    function Connect($cfg, $new_link=false) {
        $this->server = $cfg['DB_HOST'];
        $this->user = $cfg['DB_USERNAME'];
        $this->pass = $cfg['DB_PASSWORD'];
        $this->database = $cfg['DB_NAME'];
        $this->charset = $cfg['DB_CHARSET'];
        $this->link_id = @mysql_connect($this->server, $this->user, $this->pass, $new_link);
        @mysql_query("set names cp1251", $this->link_id);
        if (!$this->link_id) {//open failed
            $this->oops("Could not connect to server: <b>$this->server</b>.");
            }
        if(!@mysql_select_db($this->database, $this->link_id)) {//no database
            $this->oops("Could not open database: <b>$this->database</b>.");
            }
            
        if (! @mysql_set_charset ( $this->charset, $this->link_id )) { // charset error
            $this->oops ( "Could not set charset: <b>$this->charset</b>." );
        }
        // unset the data so it can't be dumped
        $this->server='';
        $this->user='';
        $this->pass='';
        $this->database='';
    }

    function _close() { }

    function escape($string) {
        return $string;
    }

    function query($sql) {
        $this->query_id = @mysql_query($sql, $this->link_id);
        if (!$this->query_id) {
            $this->oops("<b>MySQL Query fail:</b> $sql");
            return 0;
        }
        $this->affected_rows = @mysql_affected_rows($this->link_id);
        return $this->query_id;
    }

    function fetch_array($query_id=-1) {
        // retrieve row
        if ($query_id!=-1) {
            $this->query_id=$query_id;
        }
        if (isset($this->query_id)) {
            $record = @mysql_fetch_assoc($this->query_id);
        }else{
            $this->oops("Invalid query_id: <b>$this->query_id</b>. Records could not be fetched.");
        }
        return $record;
    }

    function fetch_all_array($sql) {
        $query_id = $this->query($sql);
        $out = array();

        while ($row = $this->fetch_array($query_id, $sql)){
            $out[] = $row;
        }

        $this->free_result($query_id);//var_dump($out);
        return $out;
    }#-#fetch_all_array()

    function free_result($query_id=-1) {
        if ($query_id!=-1) {
            $this->query_id=$query_id;
        }
        if($this->query_id!=0 && !@mysql_free_result($this->query_id)) {
            $this->oops("Result ID: <b>$this->query_id</b> could not be freed.");
        }
    }#-#free_result()

    function query_first($query_string) {
        $query_id = $this->query($query_string);
        $out = $this->fetch_array($query_id);
        $this->free_result($query_id);
        return $out;
    }#-#query_first()

    function query_update($table, $data, $where='1') {
        $q="UPDATE `".$this->pre.$table."` SET ";

        foreach($data as $key=>$val) {
            if(strtolower($val)=='null') $q.= "`$key` = NULL, ";
            elseif(strtolower($val)=='now()') $q.= "`$key` = NOW(), ";
            else $q.= "`$key`='".$this->escape($val)."', ";
        }

        $q = rtrim($q, ', ') . ' WHERE '.$where.';';

        return $this->query($q);
    }#-#query_update()

    function query_insert($table, $data, $quote = false) {
        $q="INSERT INTO `".$this->pre.$table."` ";
        $v=''; $n='';

        foreach($data as $key=>$val) {
            $n.="`$key`, ";
            if(strtolower($val)=='null') $v.="NULL, ";
            elseif(strtolower($val)=='now()') $v.="NOW(), ";
            else {
                if ($quote)
                    $v.= "'" . $this->escape($val) . "', ";
                else
                    $v.= $this->escape($val) . ", ";
            }
        }

        $q .= "(". rtrim($n, ', ') .") VALUES (". rtrim($v, ', ') .");";


        //echo $q;
        if($this->query($q)){
            //$this->free_result();
            return mysql_insert_id($this->link_id);
        }
        else return false;

    }#-#query_insert()

    function oops($msg='') {
    
        echo $msg;
        echo mysql_error($this->link_id);
        echo mysql_errno($this->link_id);
    }#-#oops()

    public function ImportFile($sqlFile) {
        if (!file_exists($sqlFile))
            return false;
        $sql = file_get_contents($sqlFile);
        $sqlLines = explode(';', $sql);
        //echo '<br> executing ' . count($sqlLines) . ' commands';
        foreach ($sqlLines as $sqlLine) {
            $_s = str_replace('\n', ' ', $sqlLine);
            $_s = trim($_s);
            if(!empty($_s)) {
                $this->query($_s);
            }
        }
        return true;
    }

}

?>
