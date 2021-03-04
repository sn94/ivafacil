<?php

namespace App\Models;

use CodeIgniter\Model;

class Ventas_model extends Model {



   protected $table      = 'ventas';
   protected $primaryKey = 'regnro';
   protected $useTimestamps = true;
   protected $returnType     = 'object';/** */
   protected $allowedFields = [
      'ruc','dv','codcliente','fecha','factura','moneda','tcambio','importe1','importe2','importe3','total',
      'iva1','iva2','iva3',   'iva_incluido' , 'origen', 'estado'
   ];

    public function __construct(){
      parent::__construct();
      $this->db= \Config\Database::connect(); 
	 }
 

 

}


?>