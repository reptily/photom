<?php
namespace Ph\Console\Action;
use Ph\Console\Tools as Tools;

class Create{
        
public $help = "";
        
    public function Handle(){
        //print help
    }
    
    public function Page($args){
        if (!is_null($args)){
            self::createController($args);
            self::createView($args);
        } else {
            \Ph\Console\Alert::Error("not arguments");
        }
    }
    
    public function Controller($args){
        if (!is_null($args)){
            self::createController($args);
        } else {
            \Ph\Console\Alert::Error("not arguments");
        }
    }
    
    public function View($args){
        if (!is_null($args)){
            self::createView($args);
        } else {
            \Ph\Console\Alert::Error("not arguments");
        }
    }
    
    static private function createController($name){
        Tools::createDir("App/Controller/".ucfirst($name));
        $tpl = Tools::getTpl("Controller",['Name' => ucfirst($name), 'Tpl' => $name]);
        Tools::createFile("App/Controller/index.php",$tpl);
    }
    
    static private function createView($name){
        $tpl = Tools::getTpl("View");
		Tools::createFile("App/View/".$name.".php",$tpl);
    }
}