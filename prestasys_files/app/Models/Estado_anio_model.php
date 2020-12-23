<?php

namespace App\Models;

use CodeIgniter\Model;

class Estado_anio_model extends Model {



   protected $table      = 'estado_anio';
   protected $returnType= "object";
   protected $primaryKey = 'regnro';
   protected $useTimestamps = true;
   protected $allowedFields = [
      'codcliente','anio','estado',  't_i_compras',  't_i_ventas', 't_retencion', 'saldo', 'saldo_inicial', 'ruc', 'dv'
   ];

    
    

     
 

   

  

}


?>