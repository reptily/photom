<?php
namespace Ph;
use \Ph\Request as Request;
use \Ph\Route as Route;

class Main
{
    
    static public function Init(){
        require "./config/main.php";
        $_SERVER['config']=$config;
        
        if (Request::get("m") != null){
            Route::redirect(Request::get("m"));
        } else {
            Route::redirect($config['indexModule']);
        }
    }
}