<?php
namespace Ph\Console\Action;

class Serve{
        
public $help = "for install framework";
        
    public function Handle(){		
		
		if (is_file("index.php")){
			\Ph\Console\Alert::Success("Photom dev server is started! http://127.0.0.1:8000");
			exec("php -S 127.0.0.1:8000 index.php");
		} else {
			\Ph\Console\Alert::Error("not found file index.php");
		}		
    }
    
}