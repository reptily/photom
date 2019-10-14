<?php
namespace Ph\Console\Action;
use Ph\Console\Tools as Tools;

class Install{
	
	public $help = "for install framework";
        
    public function Handle(){        
		Tools::createDir("App");
		Tools::createDir("App/Controller");
		Tools::createDir("App/Middleware");
		Tools::createDir("App/Model");
		Tools::createDir("App/View");
		Tools::createDir("App/View/Layout");
		Tools::createDir("assets");
		Tools::createDir("cache");
		Tools::createDir("config");
		Tools::createDir("storage");
		chmod("cache",755);
		
		$index = Tools::getTpl("Install");
		Tools::createFile("index.php",$index);
		
		$config = Tools::getTpl("Config");
		Tools::createFile("config/config.php",$config);
		
		$layout = Tools::getTpl("Layout");
		Tools::createFile("App/View/Layout/Main.php",$layout);
		
		$view = Tools::getTpl("ViewHome");
		Tools::createFile("App/View/Home.php",$view);		
		
		$controller = Tools::getTpl("Controller",['Name' =>'Home', 'Tpl' => 'home']);
		Tools::createDir("App/Controller/Home");
		Tools::createFile("App/Controller/Home/index.php",$controller);
		
		\Ph\Console\Alert::Success("Photom he has installed!");
    }
        
}