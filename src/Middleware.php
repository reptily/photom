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
<<<<<<< HEAD
}
=======
}
>>>>>>> f61735f87ec4c3cdd636aafd5b83e7e87c78928d
