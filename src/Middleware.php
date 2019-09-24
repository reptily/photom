<?php
namespace Ph;

class Middleware
{
  public function next($val=null){
    return $val;
  }
  
  public function stop(){
    \Ph\Header::send(502);
  }
}