<?php

namespace App\Models;

use CodeIgniter\Model;

class Compras_model extends Model {



   protected $table = 'compras';

   protected $primaryKey = 'regnro';

   protected $returnType     = 'object';/** */
   protected $useTimestamps = true;
   protected $allowedFields = 
  [ 
   'ruc','codcliente','dv','fecha','factura','moneda','tcambio','importe1','importe2','importe3','total','iva1',
   'iva2','iva3' ,  'iva_incluido' ,'origen'
  ];
    
  
  
  public function __construct(){
      
      parent::__construct();
      $this->db= \Config\Database::connect();
      
	 }

 
}


?>