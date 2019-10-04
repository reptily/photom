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
    
    public function Layout(){
        if (!is_null($args)){
            self::createLayout($args);
        } else {
            \Ph\Console\Alert::Error("not arguments");
        }
    }
    
    public function Model(){
        if (!is_null($args)){
            self::createModel($args);
        } else {
            \Ph\Console\Alert::Error("not arguments");
        }
    }
    
    public function Middleware(){
        if (!is_null($args)){
            self::createMiddleware($args);
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
		Tools::createFile("App/View/".ucfirst($name).".php",$tpl);
    }
    
    static private function createLayout($args){
        $tpl = Tools::getTpl("Layout");
		Tools::createFile("App/View/Layout/".ucfirst($name).".php",$tpl);
    }
    
    static private function createModel($name){
        $tpl = Tools::getTpl("Model",['Name' => ucfirst($name)]);
		Tools::createFile("App/Model/".ucfirst($name).".php",$tpl);
    }
    
    static private function createMiddleware($name){
        $tpl = Tools::getTpl("Middleware",['Name' => ucfirst($name)]);
		Tools::createFile("App/Middleware/".ucfirst($name).".php",$tpl);
    }
    
}