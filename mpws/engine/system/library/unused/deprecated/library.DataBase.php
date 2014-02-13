<?php
# OldName: Database.php
# Name: class.dbmysql.php
# File Description: MySQL Class to allow easy and clean access to common mysql commands
# Author: ricocheting
# Web: http://www.ricocheting.com/
# Update: 2009-12-17
# Version: 2.2.2
# Copyright 2003 ricocheting.com


/*
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/



//require("config.inc.php");
//$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);


###################################################################################################
###################################################################################################
###################################################################################################
class libraryDataBase {
//class DBMySql {

/*
    var $server   = ""; //database server
    var $user     = ""; //database login name
    var $pass     = ""; //database login password
    var $database = ""; //database name
    var $pre      = ""; //table prefix
*/

    //internal info
    var $error = "";
    var $errno = 0;
    var $charset = "";
    var $affected_rows = 0;
    var $link_id = 0;
    var $query_id = 0;
    var $selectCount = 0;

    function __construct(/*$cfg = Array()*/)
    {
        //
        //global $cfg_db;

        /*if (!empty($cfg_db))
            $c = $cfg_db;
        else
            $c = $cfg;
        //
        $this->server = $c['DB_HOST'];
        $this->user = $c['DB_USERNAME'];
        $this->pass = $c['DB_PASSWORD'];
        $this->database = $c['DB_NAME'];
        $this->charset = $c['DB_CHARSET'];*/
        //var_dump($c);
    }

    #-#############################################
    # desc: connect and select database using vars above
    # Param: $new_link can force connect() to open a new link, even if mysql_connect() was called before with the same parameters
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

    }#-#connect()


    #-#############################################
    # desc: close the connection
    function _close() {
        //if ($this->link_id)
        //    mysql_close($this->link_id);
        /*
        if(!mysql_close($this->link_id)){
            $this->oops("Connection close failed.");
        }*/
    }#-#close()


    #-#############################################
    # Desc: escapes characters to be mysql ready
    # Param: string
    # returns: string
    function escape($string) {
        //if(get_magic_quotes_runtime()) $string = stripslashes($string);
        //return @mysql_real_escape_string($string,$this->link_id);
        return $string;
    }#-#escape()


    #-#############################################
    # Desc: executes SQL query to an open connection
    # Param: (MySQL query) to execute
    # returns: (query_id) for fetching results etc
    function query($sql) {
        // do query
        $this->query_id = @mysql_query($sql, $this->link_id);
        //echo "<div class=\"sqlcommanddebug\">COMMAND : <br>" . $sql."<BR><br></div>";
        if (!$this->query_id) {
            $this->oops("<b>MySQL Query fail:</b> $sql");
            return 0;
        }

        $this->affected_rows = @mysql_affected_rows($this->link_id);
        //echo "rows: ".$this->affected_rows."<BR>";

        $GLOBALS['sqlc']++;

        // $this->selectCount++;

        $GLOBALS['sqlall'][] = $sql;

        return $this->query_id;
    }#-#query()


    #-#############################################
    # desc: fetches and returns results one line at a time
    # param: query_id for mysql run. if none specified, last used
    # return: (array) fetched record(s)
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
//echo "<br>";
//print_r($record);
//echo "<br>";
        return $record;
    }#-#fetch_array()


    #-#############################################
    # desc: returns all the results (not one row)
    # param: (MySQL query) the query to run on server
    # returns: assoc array of ALL fetched results
    function fetch_all_array($sql) {
        $query_id = $this->query($sql);
        $out = array();

        while ($row = $this->fetch_array($query_id, $sql)){
            $out[] = $row;
        }

        $this->free_result($query_id);//var_dump($out);
        return $out;
    }#-#fetch_all_array()


    #-#############################################
    # desc: frees the resultset
    # param: query_id for mysql run. if none specified, last used
    function free_result($query_id=-1) {
        if ($query_id!=-1) {
            $this->query_id=$query_id;
        }
        if($this->query_id!=0 && !@mysql_free_result($this->query_id)) {
            $this->oops("Result ID: <b>$this->query_id</b> could not be freed.");
        }
    }#-#free_result()


    #-#############################################
    # desc: does a query, fetches the first row only, frees resultset
    # param: (MySQL query) the query to run on server
    # returns: array of fetched results
    function query_first($query_string) {
        $query_id = $this->query($query_string);
        $out = $this->fetch_array($query_id);
        $this->free_result($query_id);
        return $out;
    }#-#query_first()


    #-#############################################
    # desc: does an update query with an array
    # param: table (no prefix), assoc array with data (doesn't need escaped), where condition
    # returns: (query_id) for fetching results etc
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


    #-#############################################
    # desc: does an insert query with an array
    # param: table (no prefix), assoc array with data
    # returns: id of inserted record, false if error
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


    #-#############################################
    # desc: throw an error message
    # param: [optional] any custom error to display
    function oops($msg='') {
    
        echo $msg;
        echo mysql_error($this->link_id);
        echo mysql_errno($this->link_id);
    }#-#oops()


    #################################################
    ######## CUSTOM METHODS

    public function PerformCommand($sql) {
        return $this->fetch_all_array($sql);
    }

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
    
    public function GetTable($cmd, $tableName = FALSE, $join = FALSE, $condition = 1, $order = FALSE, $sort = FALSE, $startRecord = false, $limit = false) {
        $sqlCMD = '';

        if (!empty($cmd))
                $sqlCMD = $cmd;
        else
        {
                if (empty($tableName))
                        return FALSE;

                $sqlCMD = 'SELECT * FROM '.$tableName;

                if (!empty($join))
                        $sqlCMD .= ' '.$join.' ';

                if (!empty($condition))
                        $sqlCMD .= ' WHERE '.$condition;

                if (!empty($order))
                {
                        $sqlCMD .= ' ORDER BY '.$order;
                        if (!empty($sort))
                                $sqlCMD .= ' '.$sort;
                }

                if (isset($startRecord) & is_numeric($startRecord))
                {
                        $sqlCMD .= ' LIMIT '.$startRecord;
                        if (!empty($limit))
                                $sqlCMD .= ', '.$limit;
                } else
        ;//$sqlCMD .= ' LIMIT 0, 5';

    }
        
        //echo '<p>'.$sqlCMD.'</p>';

            return $this->fetch_all_array($sqlCMD);
        }

        public function ClearTable($tableName) {
                if (isset($tableName) && strlen($tableName) != 0)
                        return $this->Query('DELETE * FROM'.$tableName);

                return false;
        }

        public function InsertRow($tableName, $rowData, $quote = false) {
            return $this->query_insert($tableName, $rowData, $quote);
        }

        public function GetRow($cmd, $tableName = FALSE, $join = FALSE, $condition = FALSE, $order = FALSE, $sort = FALSE) {
                $sqlCMD = '';

                if (!empty($cmd))
                        $sqlCMD = $cmd;
                else
                {
                        if (empty($tableName))
                                return FALSE;

                        $sqlCMD = 'SELECT * FROM '.$tableName;

                        if (!empty($join))
                                $sqlCMD .= ' '.$join.' ';

                        if (!empty($condition))
                                $sqlCMD .= ' WHERE '.$condition;

                        if (!empty($order))
                        {
                                $sqlCMD .= ' ORDER BY '.$order;
                                if (!empty($sort))
                                        $sqlCMD .= ' '.$sort;
                        }

                    $sqlCMD .= ' LIMIT 0, 1';
                }

                $this->Query($sqlCMD);
                return $this->fetch_array();
        }

        public function UpdateRow($tableName, $filter, $newData) {
                $sqlCMD = 'UPDATE '.$tableName.' SET ';

                foreach ($newData as $key => $val)
                        $sqlCMD .= $key.'='.str_replace(',',' ', $val).', ';

                $sqlCMD = substr($sqlCMD, 0, strlen($sqlCMD) - 2);
                $sqlCMD .= ' WHERE '.$filter;

                return $this->Query($sqlCMD);
        }

        public function DeleteRow($tableName, $filter = '')
        {
                $sqlCMD = 'DELETE FROM '.$tableName;

                if (strlen($filter) !== 0)
                        $sqlCMD .= ' WHERE '.$filter;

                return $this->Query($sqlCMD);
        }

        public function Close() {
                return $this->_close();
        }

        public function GetCount($tableName, $condition = FALSE){

           if ($condition)
               $condition = ' WHERE ' . $condition;

           $rc = $this->fetch_array($this->query("SELECT count(*) FROM " . $tableName . $condition));
           return $rc['count(*)'];
        }


}//CLASS Database

class DB_RECTYPE
{
    const ALL = 'all';
    const SINGLE = 'single';
    // etc.
}

###################################################################################################

?>
