<?php

namespace App\Models;

use CodeIgniter\Model;

class Parametros_model extends Model {



   protected $table = 'parametros';

   protected $primaryKey = 'regnro';

   protected $returnType     = 'object';/** */

   protected $useTimestamps = true;
   protected $allowedFields = 
  [ 
   'IVA1','IVA2','IVA3','MORA','REDONDEO','DIASVTO', 'DIASGRATIS', 'FACTURA','RECIBO','FECMIN','FECMAX','EMAIL'
  ];
    
  
  
  public function __construct(){
      
      parent::__construct();
      $this->db= \Config\Database::connect();
      $this->request = \Config\Services::request();
	 }

 
   }

?>