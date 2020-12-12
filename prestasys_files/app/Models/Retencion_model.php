<?php

namespace App\Models;

use CodeIgniter\Model;

class Retencion_model extends Model {



   protected $table = 'retencion';

   protected $primaryKey = 'regnro';

   protected $returnType     = 'object';/** */

   protected $useTimestamps = true;
   protected $allowedFields = 
  [ 
   'ruc','dv','codcliente','fecha','retencion','moneda','tcambio','importe','origen'
  ];
    
  
  
  public function __construct(){
      
      parent::__construct();
      $this->db= \Config\Database::connect();
      $this->request = \Config\Services::request();
	 }

 
}


?>