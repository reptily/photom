<?php
namespace Ph;

class Exception extends \Exception
{
    
    public function __construct(string $str=null, int $code = 0, Exception $previous = null){
        echo $str."\n";
        
        parent::__construct($str, $code, $previous);
    }
    
    
    static public function show($e){
        print_r($_SERVER);
        echo $e->getMessage();
    }
    
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}