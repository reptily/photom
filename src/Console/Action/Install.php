<?php
namespace Ph\Console\Action;

class Install{
        
    public function Handle(){
        
		self::createDir("App");
        self::createDir("App/Controller");
        self::createDir("App/Middleware");
        self::createDir("App/Model");
        self::createDir("App/View");
		self::createDir("App/View/Layout");
        self::createDir("assets");
        self::createDir("cache");
        self::createDir("config");
        chmod("cache",755);
		
		$index = self::getTpl("Install");
		self::createFile("index.php",$index);
		
		$config = self::getTpl("Config");
		self::createFile("config/config.php",$config);
		
		$layout = self::getTpl("Layout");
		self::createFile("App/View/Layout/Main.php",$layout);
		
		$view = self::getTpl("ViewHome");
		self::createFile("App/View/Home.php",$view);
		
		\Ph\Console\Alert::Success("Photom he has installed!");
    }
        
    private function createDir($dir){
        if (!is_dir($dir)){
            mkdir($dir);
        }
	}

	private function createFile($file,$text){
        if (!is_file($file)){
            file_put_contents($file,$text);
        }
	}
	
	private function getTpl($file){
		$file = "tpl/".$file.".tpl";
		if (is_file($file)){
            return file_get_contents($file);
        } else {
			return null;
		}
	}
	
}