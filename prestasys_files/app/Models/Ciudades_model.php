<?php

namespace App\Models;

use CodeIgniter\Model;

class Ciudades_model extends Model {



   protected $table = 'ciudades';

   protected $primaryKey = 'regnro';

   protected $returnType     = 'object';
   /** */

   protected $useTimestamps = true;
   protected $allowedFields =
   [
      'departa', 'ciudad'
   ];
    
  
  
  public function __construct(){
      
      parent::__construct();
      $this->db= \Config\Database::connect();
      $this->request = \Config\Services::request();
	 }


 
}


?>