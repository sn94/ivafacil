<?php

namespace App\Models;

use CodeIgniter\Model;

class Admin_model extends Model {



   protected $table      = 'admins';
   protected $returnType= "object";
   protected $primaryKey = 'regnro';
   protected $useTimestamps = true;
   protected $allowedFields = [
      'nick', 'pass','estado','email',  'origen', 'session_id', 'session_expire', 'remember'
   ];

    
    

     
 

   

  

}


?>