<?php
namespace Ph;

class Tools
{
    public static function Css(){      
        if ($_SERVER['config']['cache']){
            echo self::toCache('css');
        } else {
            $echo = "";
            foreach ($_SERVER['config']['css'] as $css){
                $echo .= "<link rel=\"stylesheet\" href=\"".$_SERVER['config']['dir']."assets/css/".$css."\" type=\"text/css\"/>\n";
            }
            
            echo $echo;
        }
    }
    
    public static function Js(){
        if ($_SERVER['config']['cache']){
            echo self::toCache('js');
        } else {
            $echo = "";
            foreach ($_SERVER['config']['js'] as $js){
                $echo .= "<script src=\"".$_SERVER['config']['dir']."assets/js/".$js."\"></script>\n";
            }
            
            echo $echo;
        }
    }
    
    private static function toCache($type){
        $echo = "";
        $files = $_SERVER['config'][$type];
        
        if (isset($_SERVER['config']['module'][$type]))
        {
            $files = array_merge($files,$_SERVER['config']['module'][$type]);
        }
        
        $file = "";
        foreach ($files as $f){
            if (is_file("./assets/".$type."/".$f)){
                $file.=$f.filemtime("./assets/".$type."/".$f);
            } else {
                exit("not found file ./assets/".$type."/".$f);
            }
        }
        
        $file=md5($file).".".$type;
        
        if (!is_file("./cache/".$file)){
            if ($type == "css"){
                self::createFileCss($files,$file);
            }
            if ($type == "js"){
                self::createFileJs($files,$file);
            }
        }
        
        if ($type == "css"){
            $echo .= "<link rel=\"stylesheet\" href=\"".$_SERVER['config']['dir']."cache/".$file."\" type=\"text/css\" />\n";
        }
        
        if ($type == "js"){
            $echo .= "<script src=\"".$_SERVER['config']['dir']."cache/".$file."\"></script>\n";
        }
        
        return $echo;
    }
    
    private static function createFileCss($files,$out){
        $write="";
        foreach ($files as $f){
            $write .= file_get_contents("./assets/css/".$f);
        }
        
        $write = preg_replace('#//.*#','',$write);
        $write = preg_replace('#/\*(?:[^*]*(?:\*(?!/))*)*\*/#','',$write);
        $write = str_replace(["\r","\n","\t","  ", "   "],'',$write);
        
        file_put_contents("./cache/".$out,$write);
    }
    
    private static function createFileJs($files,$out){
        $write="";
        foreach ($files as $f){
            $write .= \Ph\Tools\JSMin::minify(file_get_contents("./assets/js/".$f));
        }
        
        file_put_contents("./cache/".$out,$write);        
    }
}