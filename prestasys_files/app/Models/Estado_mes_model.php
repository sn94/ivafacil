<?php

namespace App\Models;

use CodeIgniter\Model;

class Estado_mes_model extends Model {



   protected $table      = 'estado_mes';
   protected $returnType= "object";
   protected $primaryKey = 'regnro';
   protected $useTimestamps = true;
   protected $allowedFields = [
      'codcliente', 'mes','anio','estado', 
      't_impo_compras',  't_impo_ventas', 't_impo_retencion',
       't_i_compras',  't_i_ventas', 't_retencion', 'saldo',
      'saldo_inicial', 'ruc', 'dv',
      'pago'
   ];

    
    

     
 

   

  

}


?>