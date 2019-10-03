<?php
namespace Ph;

class Table{
    private $DB;
    private $model;
    protected $table;
        
    public function __construct(){
        $this->DB = new \Ph\DB;
        if ($this->table == null){
            $this->table = $this->getNameTable();
        }
    }        
        
    public function find(int $id){
        $key = $this->DB->{$this->table}->getPrimary();
        $this->model = $this->DB->{$this->table}->where([$key=>$id])->Select()->getArray()[0] ?? null;
        
        return new class($this->model,$this->table,$key,$this->DB,$id){
            private $col;
            private $model;
            private $key;
            private $DB;
            private $id;
            
            public function __construct($model,$table,$key,$DB,$id){
                $this->model=$model;
                $this->table=$table;
                $this->key=$key;
                $this->DB=$DB;
                $this->id=$id;
            }
            
            public function __call($name,$arg){
                $a = preg_split("/(?<=\\w)(?=[A-Z])/",$name);
                $method = mb_strtolower($a[1] ?? "Select");
                switch($a[0]){
                    case "get":
                        return $this->model;
                        break;
                    case "set":
                        $this->DB->{$this->table}->set($arg[0])->where([$this->key=>$this->id])->Update();
                        break;
                    default:
                        exit("Can not method");
                }
            }
        };
    }
        
    public function findAll(){
        return $this->DB->{$this->table}->Select()->getArray();
    }
        
    public function add(array $values){
        return $this->DB->{$this->table}->Insert($values)->connectId->insert_id;
    }
        
    public function remove(int $id){
        $key = $this->DB->{$this->table}->getPrimary();
        $this->DB->{$this->table}->where([$key=>$id])->Delete();
    }
    
    public function count(){
        return $this->DB->{$this->table}->Count();
    }
    private function getNameTable(){
        $class = get_class($this);
        $exp = explode("\\",$class);
        return mb_strtolower(str_replace("Controller","",$exp[count($exp)-1]));
    }
}