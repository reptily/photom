<?php
namespace Ph;

class Request
{
        static public function get($val){
                if (preg_match("/^[a-zA-Z0-9_.@\-\/]{1,255}$/i",$_GET[$val] ?? null)){               
                        return $_GET[$val] ?? null;
                } else {
                        return null;
                }
        }
        
        static public function post($val){
                return $_POST[$val] ?? null;
        }
}