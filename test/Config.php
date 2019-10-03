<?php
namespace Ph\Test;

use Ph\DB as DB;

class Config{
        
    public function __construct(){
        $_SERVER['config']['db'] = [
			'host' => '127.0.0.1',
			'login' => 'reptily',
			'pwd' => '',
			'db' => 'test'
		];
        
        $DB = new DB;
        if (!$DB->table){
            $table = [
                "id" =>[
                    "type"=>"int",
                    "count"=>11,
                    "isNull"=>false,
                    "autoIncrement"=>true
                ],
                "name"=>[]
            ];
            $DB->Create("table",$table);
        }
    }
}