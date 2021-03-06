<?php

namespace App\Models;

use CodeIgniter\Model;

class Usuario_model extends Model {



   protected $table      = 'usuarios';
   protected $returnType= "object";
   protected $primaryKey = 'regnro';
   protected $useTimestamps = true;
   protected $allowedFields = [
      'ruc', 'dv', 'pass', 'tipo', 'fechainicio', 'demo', 'tipoplan', 'estado', 'email', 'cliente', 'cedula', 'celular', 'telefono',
      'domicilio', 'rubro', 'ciudad', 'pass_anterior', 'origen',
      'session_id', 'session_expire', 'remember', 'remember_pass', 'remember_expire', 'fondo',
      'ultimo_nro', 'saldo_IVA', 'token_recu', 'token_validez',
      'clave_marangatu',  'timbrado'
   ];

    
    

     
 

   

  

}


?>