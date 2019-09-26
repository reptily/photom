<?php
namespace Ph\Console;

class Main
{
    public function __construct(){
        if (isset($_SERVER['argv'][1])){
            $this->Controller($_SERVER['argv'][1], $_SERVER['argv'][2] ?? null);
        } else {
            $this->Controller("help");
        }
    }
    
    public function Controller($action, $value=null){
		$command = explode(":",$action);
		$action=$command[0];
		$command=$command[1] ?? null;
		
		$class = "\\Ph\\Console\\Action\\".ucfirst($action);
		
        if (class_exists($class)){
            if (is_null($command)){
				$class::Handle();
			} else {
				if (method_exists($class,ucfirst($command))){
					$class::$command();
				} else {
					\Ph\Console\Alert::Error("Command not found");
				}
			}
        } else {
            \Ph\Console\Alert::Error("Command not found");
        }
    }
}