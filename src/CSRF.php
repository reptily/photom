<?php
namespace Ph;

class CSRF
{        
    static public function Print(){
        $token = md5(rand(1000000,9999999).microtime().rand(1000000,9999999));
                
        $DB = new \Ph\DB;
        $DB->csrf->insert(['token' => $token, 'time' => time()]);
        
        echo "<meta name=\"csrf\" content=\"".$token."\">\n";
        self::deleteOld();
    }
        
    static private function deleteOld(){
        $DB = new \Ph\DB;
        $DB->csrf->where('time <= '.time())->Delete();
    }
    
    static private function delete(string $token){
        $DB = new \Ph\DB;
        $DB->csrf->where(['tolen' => $token])->Delete();
    }
    
    static public function Check(string $token):boolean{
        $DB = new \Ph\DB;
        $row = $DB->csrf->where(['tolen' => $token])->Select();
        
        if (count($row) > 0)
        {
            return true;
        } else {
            return false;
        }
    }
}