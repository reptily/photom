<?php
namespace Ph\Console;

class Alert
{
        static public function Error($val){
                echo "\033[1;31m".$val."\033[0m\n";
        }
        
        static public function Success($val){
                echo "\033[1;32m".$val."\033[0m\n";
        }
}