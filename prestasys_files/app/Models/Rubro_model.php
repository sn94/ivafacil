<?php

namespace App\Models;

use CodeIgniter\Model;

class Rubro_model extends Model {



   protected $table = 'rubro';

   protected $primaryKey = 'regnro';

   protected $returnType     = 'object';
   /** */

   protected $useTimestamps = true;
   protected $allowedFields =
   [
      'descr'
   ];
    
  
  
  public function __construct(){
      
      parent::__construct();
      $this->db= \Config\Database::connect();
      $this->request = \Config\Services::request();
	 }


 
}


?>