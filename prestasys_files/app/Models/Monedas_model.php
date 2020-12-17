<?php

namespace App\Models;

use CodeIgniter\Model;

class Monedas_model extends Model {



   protected $table = 'monedas';

   protected $primaryKey = 'regnro';

   protected $returnType     = 'object';/** */

   protected $useTimestamps = true;
   protected $allowedFields = 
  [ 
   'moneda','prefijo','nombre','tcambio','fechacambio'
  ];
    
  
  
  public function __construct(){
      
      parent::__construct();
      $this->db= \Config\Database::connect();
      $this->request = \Config\Services::request();
	 }
 
}


?>