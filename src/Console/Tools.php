<?php
namespace Ph\Console;

class Tools{
    static public function createDir($dir){
        if (!is_dir($dir)){
            mkdir($dir);
        }
	}

	static public function createFile($file,$text){
        if (!is_file($file)){
            file_put_contents($file,$text);
        }
	}
	
	static public function getTpl($file, $values = []){
		$file = "tpl/".$file.".tpl";
		if (is_file($file)){
			$data = file_get_contents($file);
			foreach ($values as $i=>$value){
				$data = str_replace("%".$i."%",$value,$data);
			}
            return $data;
        } else {
			return null;
		}
	}
}