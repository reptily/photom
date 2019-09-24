<?php
namespace Ph;

class Tpl
{
    public $value = [];
    public $array = [];
    private $file;
    
    public function setValue($name,$value){
        $this->value[$name]=$value;
    }
    
    public function getCount($name){
        return count($this->array[$name]);
    }
    
    public function getValue($name){
        if (!isset($this->value[$name])){
            return null;
        }
        return $this->value[$name];
    }
    
    public function setArray($name,$value){
        $this->array[$name]=$value;
    }    
   
    public function getArray($name,$key=0,$value=null){
        if ($value == null && is_int($key)){
            if (isset($this->array[$name])){
                return $this->array[$name];
            } else {
                return null;
            }
        }elseif ($value == null){
            if (isset($this->array[$name][$key])){
                return $this->array[$name][$key];
            }
            else{
                return null;
            }
        } else {
            if (isset($this->array[$name][$key][$value])){
                return $this->array[$name][$key][$value];
            }
            else{
                return null;
            }
        }
    }
    
    public function Read($file,$layout){
        $this->file= ucfirst($file);
        $layout=ucfirst($layout);
        if (!is_file("./App/View/".$this->file.".php")){
            exit("tpl error: ".$this->file);
        }
        global $Tpl;
        $Tpl=$this;
        include "./App/View/Layout/".$layout.".php";
    }
    
    public function Init(){
        global $Tpl;
        include "./App/View/".$this->file.".php";
    }
}