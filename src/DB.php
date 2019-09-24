<?php
namespace Ph;

/**
 * ORM for MySQL
 */

class DB
{
    public $connectId;
    public $tables;
    public $callTable;
    public $limit;
    public $order;
    public $debug=false;
    public $field;
    public $where;
    public $join;
    public $joinTable;
    public $set;
    
    public function __construct(){
        $config=$_SERVER['config']['db'];
        $this->connectId = MYSQLI_CONNECT($config['host'],$config['login'],$config['pwd'],$config['db']) or die ("Server in DOWN or Too many connection DataBase");
        
        $res=$this->Query("SHOW TABLES;");
        $tables=$this->getArray($res);
        foreach ($tables as $table){
            foreach ($table as $table){
            $this->$table=$this;
            }
        }
    }
    
    public function field($field):DB{
        $this->field=$field;
        return $this;
    }
    
    public function where(...$where):DB{
        $or=false;
        foreach ($where as $w){
            if (is_array($w)){
            $key=key($w);
            $val=$w[key($w)];
            $this->where.="`".$this->es($key)."` = '".$this->es($val)."' `~` ";
            } else {
            if (mb_strtolower(trim($w)) == "or"){
                $or=true;
            } else {
                preg_match('/(.*)(>=|==|<=)(.*)/i', $w, $val);
                if (count($val) != 4){
                preg_match('/(.*)(>|<)(.*)/i', $w, $val);
                        }
                
                if (count($val) == 4){
                $val[1]=str_replace(".","`.`",$val[1]);
                $this->where.="`".trim($this->es($val[1]))."` ".$this->es($val[2])." '".trim($this->es($val[3]))."' `~` ";
                }
            }
            }
        }
        $this->where=substr($this->where, 0, -4);
        if ($or){
            $this->where=str_replace("`~`","OR",$this->where);
        } else {
            $this->where=str_replace("`~`","AND",$this->where);
        }
        
        return $this;
    }
        
    public function inner($table):DB{
        $this->joinTable=$table;
        $this->join.=" INNER JOIN `".$this->es($table)."` ";
        return $this;
    }
    
    public function left($table):DB{
        $this->joinTable=$table;
        $this->join.=" LEFT JOIN `".$this->es($table)."` ";
        return $this;
    }
    
    public function on($t1,$t2):DB{
        $this->join.="ON `".$this->callTable."`.`".$this->es($t1)."` = `".$this->joinTable."`.`".$this->es($t2)."` ";
        return $this;
    }
    
    public function set($set):DB{
        $this->set = "SET ";
        $vals = ""; 
        foreach ($set as $key=>$val){
            $vals .= "`".$this->es($key)."` = '".$this->es($val)."',";
        }
        $this->set.=mb_substr($vals, 0, -1);
        return $this;
    }
    
    public function limit(int $l1, int $l2=-1):DB{
        if ($l2 == -1){
          $this->limit=$l1;
        } else {
          $this->limit=$l1.",".$l2;
        }
        return $this;
    }    
    
    public function order($key,$value):DB{
        if ("ASC" == mb_strtoupper($value)){
            $value="ASC";
        } else {
            $value="DESC";
            }
            
        $this->order=['key'=>$this->es($key),'value'=>$value];
        return $this;
    }
    
    public function __get($property):object{
        $this->callTable=$property;
        return $this->tables[$property];
    }
    
    public function __set($property,$value=null){
        $this->tables[$property]=$value;
    }
    
    public function Select():object{
        //Feild
        $fields="* ";
        if (is_array($this->field)){
            $fields="";
            foreach ($this->field as $f=>$field){
            if (is_array($field)){
                foreach ($field as $val){
                $fields.="`".$f."`.`".$val."`,";
                }
            } else {
                $fields.="`".$field."`,";
            }
            }
        }
        $fields=mb_substr($fields, 0, -1);
        
        $sql = "SELECT ".$fields." FROM `".$this->callTable."`";
        
        //JOIN
        if ($this->join != ""){
            $sql .= $this->join;
        }
        
        //WHERE
        if ($this->where != ""){
            $sql .= " WHERE ".$this->where;
        }
        
        //ORDER	
        if (is_array($this->order)){
            $sql .= " ORDER BY `".$this->order['key']."` ".$this->order['value'];
        }
        
        //LIMIT
        if ($this->limit != ""){
            $sql .= " LIMIT ".$this->limit;
        }
        
        $sql.=";";    
        $res=$this->Query($sql);	
            
        return new class($res) extends DB{	    
            public $result;
            
            public function __construct($result){
            $this->result=$result;
            }
            
            public function getArray():array{
            $array = [];
            while($row = $this->result->fetch_array(MYSQLI_ASSOC))
                $array[]=$row;
            if ($this->debug)
                $this->Debug(json_encode($array)."<br>\n");
            return $array;
            }
            
            public function getRow():array{
            return $this->result->fetch_array(MYSQLI_ASSOC);
            }
            
            public function getJson():string{
            return json_encode($this->getArray());
            }
        };
    }
    
    public function Insert($arr):DB{
        $sql = "INSERT INTO `".$this->callTable."`";
        $keys="";
        $vals="";
        $_2d=true;
        foreach ($arr as $key=>$val){
            if (is_array($val)){
            foreach ($val as $keyChild=>$valChild){
                if ($_2d){
                $keys .= "`".$this->es($keyChild)."`,";
                $_2d=false;
                }
                $vals .= "'".$this->es($valChild)."',";
            }
            $vals=mb_substr($vals, 0, -1);
            $vals .= "),(";
            } else {
            $keys .= "`".$this->es($key)."`,";
            $vals .= "'".$this->es($val)."',";
            }
        }
        
        $keys=mb_substr($keys, 0, -1);
        if ($_2d) $vals=mb_substr($vals, 0, -1);
        else $vals=mb_substr($vals, 0, -3);
        $sql = $sql."(".$keys.") VALUES(".$vals.");";
        
        $this->Query($sql);
        
        return $this;
        }
        
        public function Delete():DB{
        $sql = "DELETE FROM `".$this->callTable."`";
        
        //WHERE
        if ($this->where != "")
            $sql .= " WHERE ".$this->where;
        
        $sql.=";";
        $res=$this->Query($sql);
        
        return $this;
    }
    
    public function Update():DB{
        $sql = "UPDATE `".$this->callTable."` ".$this->set;
        
        //WHERE
        if ($this->where != "")
            $sql .= " WHERE ".$this->where;
        
        $sql.=";";    
        $res=$this->Query($sql);
        
        return $this;
    }
    
    private function getArray($result):array{
        $array = [];
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $array[]=$row;
        }
        return $array;
    }

    
    private function Query($q){
        //$this->connectId->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);    
        $result=MYSQLI_QUERY($this->connectId,$q) or die('MySQL Errore: ' . mysqli_error($this->connectId));
        if ($this->debug){
            echo "<br>\nQuery count row:".mysqli_affected_rows($this->connectId)."<br>\n";
            $this->Debug($q);
        }
        //$this->connectId->commit();
        $this->Clear();
        return $result;
    }
    
    private function Clear(){
        $this->limit= "";
        $this->order= "";
        $this->field = null;
        $this->where = "";
        $this->joinTable="";
        $this->join="";
        $this->set="";
    }
    
    public function Debug($sql){
        $sql=str_replace(";",";<br>\n",$sql);
        echo $sql;
    }
    
    public function Create($table, $values){
        $sql = "CREATE TABLE IF NOT EXISTS `".$this->es($table)."` (\n";
        $_autoIncrementKey = null;
        
        foreach ($values as $i=>$val){
            $sql .= "`".$this->es($i)."` ";
            $_type = "text";
            $_count = "";
            $_isNull = " NULL";
            $_default = "";
            $_autoIncrement = "";
            
            foreach ($val as $k=>$v){
                switch($k){
                    case "type":
                        $_type=$values[$i][$k];
                        break;
                    case "count":
                        $_count="(".(int)$values[$i][$k].")";
                        break;
                    case "isNull":
                            if (!$values[$i][$k]){
                                $_isNull = " NOT NULL";
                            }
                            break;
                    case "autoIncrement":
                            $_autoIncrement = " AUTO_INCREMENT";
                            $_autoIncrementKey = $this->es($i);
                            break;
                    case "default":
                            $_default = " DEFAULT '".$this->es($values[$val][$v])."'";
                            break;
                }
            }
            
            $sql .= $_type.$_count.$_isNull.$_default.$_autoIncrement.",\n";
        }
        
        if ($_autoIncrementKey != null){
            $sql .= "PRIMARY KEY (`".$_autoIncrementKey."`)\n";
        } else {
            $sql=mb_substr($sql, 0, count($sql)-2);
        }
        $sql .= ")";
        
        $this->Query($sql);
        $this->$table=$this;
    }
    
    public function getPrimary():array{
        $sql = "SHOW KEYS FROM `".$this->callTable."` WHERE Key_name = 'PRIMARY'";
        $res=$this->Query($sql);
        return $this->getArray($res)[0]['Column_name'] ?? null;
    }
    
    private function es($string):string{
        return mysqli_real_escape_string($this->connectId,$string);
    }
    
}