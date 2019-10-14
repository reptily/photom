<?php
namespace Ph;

class Storage
{
    static private $dir = "storage";
    
    static private function createDir(array $dirs){
        $d=self::$dir."/";
        
        foreach ($dirs as $dir){
            $d.=$dir."/";
            try{
                if (!is_dir($d,)){
                    mkdir($d);
                }
            } catch(\Ph\Exception $e) {
                    \Ph\Exception::show($e);
            }
        }       
    }
    
    static public function get($file){
        try{
            return file_get_contents(self::$dir."/".$file);
        } catch(\Ph\Exception $e) {
            \Ph\Exception::show($e);
            return null;
        }
    }
    
    static public function pull($file,$str){
        if (is_file(self::$dir."/".$file)){
            throw new \Ph\Exception('Файл уже создан');
        }
        
        $dir = explode("/",$file);
        
        if (count($dir) > 1){
            unset($dir[count($dir)-1]);
            self::createDir($dir);
        }
        
        try{
            file_put_contents(self::$dir."/".$file, $str);
        } catch(\Ph\Exception $e) {
            \Ph\Exception::show($e);
        }
    }
    
    static public function copy($file,$newFile){
         $dir = explode("/",$newFile);
        
         if (count($dir) > 1){
             unset($dir[count($dir)-1]);
             self::createDir($dir);
         }
        
         try{
             copy(self::$dir."/".$file,self::$dir."/".$newFile);
         } catch(\Ph\Exception $e) {
            \Ph\Exception::show($e);
         }
    }
    
    static public function move($file,$toFile){
        self::copy($file,$toFile);
        self::delete($file);
    }
    
    static public function download($file,$name = null){
        header('Content-Disposition: attachment; filename="'.$name ?? 'Download'.'"');
        header('Content-Length: ' . filesize(self::$dir."/".$file));
        readfile(self::$dir."/".$file);
    }
    
    static public function delete($file){
        try {
            unlink(self::$dir."/".$file);
        } catch(\Ph\Exception $e) {
            \Ph\Exception::show($e);
        }
    }
}
?>