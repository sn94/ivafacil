<?php

namespace App\Http\Controllers;

use App\Bancos;
use App\Demanda;
use App\Demandados;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Mail\AuthAlert;
use App\Mail\CredencialesCliente;
use App\ODemanda;
use App\PagosServicio;
use App\Plan_servicio;
use App\Suscriptores;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;    
use App\Usu_proveedor;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SuscriptoresController extends Controller
{
    

    public function __construct()
    {
        date_default_timezone_set("America/Asuncion");
    }
  


 
 


public function clientes(){
    $ls=    Suscriptores::where("APROBADO", "=", "S")->
    join("planes", "suscriptores.PLAN", "=", "planes.IDNRO")
    ->select( "suscriptores.*", "planes.DESCR as PLAN_")
    ->paginate( 10);
    
    if(  request()->ajax() )
     return view("0provider.suscriptor.grilla", ['clientes'=> $ls ]) ;
    else
    {
        //Obtener numero de solicitantes
        $numSolici=   Suscriptores::where("APROBADO", "N")->count();

        return view('0provider.suscriptor.index' , ["clientes"=>  $ls,  "numero_solicitantes"=>  $numSolici ]);
    }
}



public function solicitantes(){
    $ls=    Suscriptores::where("APROBADO", "N")->paginate(10);
    if(  request()->ajax() )
     return view("0provider.suscriptor.grilla_solicitudes", ['clientes'=> $ls ]) ;
    else
    return view('0provider.suscriptor.solicitudes' , ["clientes"=>  $ls]);
}

 

//Preparacion automatica
 public function nuevo(){
     
    if(  request()->isMethod("POST")){ 

        set_time_limit(0);
        ini_set('memory_limit', '-1');

        DB::beginTransaction();
        try{
         $id_cliente= $this->paso1_suscriptor(  true );
         $this->paso2_crearbd( $id_cliente );
         $this->paso3_creartablas($id_cliente, true);
         $this->paso4_gen_credenciales($id_cliente, true);
         DB::commit();

        }catch( Exception $e){     DB::rollBack();      }

    }else{     return view('0provider.suscriptor.create' );  }
   
 }

 
 public function editar(  $id = 0){
     
    if(  request()->isMethod("POST")){ 

        set_time_limit(0);
        ini_set('memory_limit', '-1');    

        DB::beginTransaction();
        try{
         $cli=  Suscriptores::find(  request()->input("IDNRO")  );
         $cli->fill(  request()->input() );
         $cli->save(); 
         DB::commit();
         return response()->json( ['idnro'=>   $cli->IDNRO] ); 
        }catch( Exception $e){     DB::rollBack();     return response()->json( ['error'=>   $e] );    }
    }else{  
        $cli=  Suscriptores::find(  $id );
        $Plan_servicio= Plan_servicio::get();
        return view('0provider.suscriptor.editar', 
         ['dato'=>  $cli,  'planes'=> $Plan_servicio, 'ocultar_btn_ver_plan'=> true ] );  }
   
 }


 public function paso1_suscriptor(  $interno = false){
    set_time_limit(0);
    ini_set('memory_limit', '-1');

     $DATOS= request()->input();
       //Registrar cliente
       $suscr= new Suscriptores();
       $suscr->fill(   $DATOS);
       if( $suscr->save() )
      { 
          if( $interno) return $suscr->IDNRO;
          else{
            if( request()->ajax())
            return response()->json( ['idnro'=>   $suscr->IDNRO] ); 
            else 
            return view("0provider.suscriptor.registrado");
          }
         
        }
       else
       {
        if( $interno) return "error";
        else{
            if( request()->ajax())
            return response()->json(['error'=>  "Error en el servidor"] );
            else 
            echo "Ha ocurrido un error inesperado en el Servidor. Estamos trabajando en ello. Disculpe las molestias";
        }
      
  }
 }


 public function paso2_crearbd( $clienteId, $interno= false){
    set_time_limit(0);
    ini_set('memory_limit', '-1');
      //crear bd
      $NombreBD=  "cli_". $clienteId;
      $charset__= "utf8mb4";
      $collation__= "utf8mb4_unicode_ci";

      //conexion admin
      $conexion_admin=  DB::connection("God");
    $exitCode=  $conexion_admin->statement("CREATE DATABASE IF NOT EXISTS $NombreBD CHARACTER SET $charset__  COLLATE $collation__");
    
          //mysql:host=localhost;port=3306;dbname=testdb
      if(  $exitCode ) {
          $usu= Suscriptores::find( $clienteId);
          $usu->DSN=  "mysql:host=localhost;port=3306;dbname=$NombreBD";
          $usu->save();
          if( $interno)  return "ok";
          else
          return response()->json( ['ok'=>"Base de datos creada"]);
        }
      else
      {
        if( $interno)  return "error";
        else
          return response()->json(['error'=>  "Error en el servidor"] );
        }
 }


 public function paso3_creartablas(  $clienteId, $interno= false){
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    $NombreBD=  "cli_". $clienteId;
    $comandoR= "mysql -h localhost -u  supervisor -p123 $NombreBD < juridico_v2.sql 2>&1";
    $salida= shell_exec( $comandoR );
    if(  $salida==""   ||  is_null($salida))
    {
        if( $interno) return "ok";
        else
        return response()->json(  ['ok'=>"Tablas creadas"] );
    }
    else
    {
        if( $interno) return "error";
        else
        return response()->json(['error'=>  "Error en el servidor"] );
    }
}



public function  paso4_gen_credenciales( $idcliente,  $interno= false, $email= ""){
    set_time_limit(0);
    ini_set('memory_limit', '-1');

    //get client's Database name
     
    $ClientDB=  "cli_".$idcliente;
    $nickgen=  $idcliente."_usuario";
    //Raw password
    $rawpass=   Helper::generar_password();
    $passgen=   Hash::make(  $rawpass );
    if( $email == ""){
        $DB_ADMIN= DB::connection("principal");
        $obj_suscr= $DB_ADMIN->table("suscriptores")->where("IDNRO", $idcliente)->first();
        $email= $obj_suscr->EMAIL;
    }
    $client_email= $email;
 
    $configDb = array(
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' =>  $ClientDB,
        'username' =>  env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
        'charset' => 'utf8',
        'prefix' => '',
    );
    Config::set('database.connections.mysql', $configDb); 
   $conexionSQL = DB::connection('mysql');
 
   try{
    $conexionSQL->beginTransaction();
   }catch(Exception $ex){
    if( $interno) {    return ['error'=>   $ex]  ; }
    else
    return response()->json(['error'=>  "No se registra la Base de datos cli_$idcliente "] );
   }
    try{
       
           //Crear usuario primero y su passw
            $salida= $conexionSQL->insert('insert into usuarios (nick, pass, tipo, email) values (?, ?, ?, ?)', 
            [ $nickgen, $passgen, 'SA', $client_email]); 
            $conexionSQL->commit();
            //Enviar email
            try{
                Mail::to([ $client_email]) 
                //->queue(   new AuthAlert(  $usr,  ["Suscriptores-agent"=>$SuscriptoresAgent, "ip"=>$Ip] ) );
            ->send(  new CredencialesCliente(  $nickgen, $rawpass  ) );
            }catch( Exception $e){}

            if( $interno) return true;
            else
            return response()->json(  ['ok'=>"Credenciales creadas"] );

    }catch( Exception $ex){
        $conexionSQL->rollBack();
        if( $interno) {    return ['error'=>   $ex]  ; }
        else
        return response()->json(['error'=>  "Error en el servidor "] );
    }

 
}


 
    
    
    


public function actualizar_estado_cliente( $modo,  $idcliente){
   
    if(  $modo != "aprobar")
    {
        $susc= Suscriptores::find( $idcliente );
        $susc->HABILITADO=  $modo== "baja" ?  "No"  : "Si"  ;
        if(  $susc->save()  )
        return response()->json( 
             ['ok'=> ($modo=="aprobar" ) ?"Solicitud aprobada" :  (($modo=="baja")?"Cliente deshabilitado": "Cliente Habilitado" )     ] );
        else
        return response()->json(['error'=>  "Error en el servidor"] );
    }
    else 
   { 
       //Db clientejuridico
       $db_clientes_juridico= DB::connection("principal");
       $susc= $db_clientes_juridico->table("suscriptores")->where("IDNRO", $idcliente)->first();//SUSCRIPTOR
    //   $plan_susc= $db_clientes_juridico->table("planes")->where("IDNRO", $susc->PLAN)->first();//PLAN
        $susc_update= [ 'HABILITADO'=> 'Si', 'APROBADO'=> "S"];
      
        $susc_email=  $susc->EMAIL;

        $db_clientes_juridico->beginTransaction();//Juridico clientes 

        try{
           // $conexTest->select("select curdate()");//verificar si existe base de datos

                $sent_email= $this->paso4_gen_credenciales($idcliente, true, $susc_email); //db clientes
                if( is_bool( $sent_email)  &&  $sent_email) {  

                    $db_clientes_juridico->table("suscriptores")->where("IDNRO", $idcliente)
                    ->update( $susc_update ); 

                    $db_clientes_juridico->commit();
                    return response()->json(   ['ok'=>"Cliente aprobado"]);
                }
                else{ 
                    $db_clientes_juridico->rollBack();
                    return response()->json(   ['error'=> "No se encuentra la base de datos 'cli_$idcliente'" ]);//$sent_email['error']
                }
        
        }catch(Exception $Ex){
            $db_clientes_juridico->rollBack(); 
            return response()->json(   ['error'=>"No se encuentra la base de datos 'cli_$idcliente'"]);
        }
      
    } 

}






public function borrar( $id){
    if(  Suscriptores::find($id)->delete() ) echo json_encode( array('ok'=>  "BORRADO"  ) );
    else json_encode( array( 'error'=> "Hubo un error al guardar uno de los datos") );
 
 }









 
 public function solicitar(){


    //listar planes servicio
    $Plan_servicio= Plan_servicio::get();
     return view("0provider.suscriptor.solicitud", ['planes'=>  $Plan_servicio ]);
 }





 public function pagos( $idcliente){

    $pagos= PagosServicio::where("CLIENTE", $idcliente)
    ->join("suscriptores", "suscriptores.IDNRO",  "pagos.CLIENTE")
    ->join("planes", "planes.IDNRO",  "suscriptores.PLAN")
    ->select( "pagos.*", DB::raw("DATE_ADD( pagos.FECHA,INTERVAL planes.DURACION day) AS VALIDEZ") )
    ->paginate(10);
    $suscr= Suscriptores::find( $idcliente);
    $razon_social=  $suscr->RAZON_SOCIAL;
    if(  request()->ajax())
    return view("0provider.suscriptor.pagos_grilla", [  'pagos'=>  $pagos ]);
    else
    return view("0provider.suscriptor.pagos", [ 'IDCLIENTE'=>$idcliente, 'RAZONSOCIAL'=> $razon_social,  'pagos'=>  $pagos ]);
 

}




 public function pago( $idcliente=""){

    if(  request()->isMethod("POST") ){

        $nuevo_pago= new PagosServicio();
        $nuevo_pago->fill(   request()->input() );
        DB::beginTransaction();
       try{ 

            //incrementar dias
            $susc= Suscriptores::find( request()->input("CLIENTE") );
            //AL DIA?
           $HABIL= $this->sistema_habilitado(   $susc->IDNRO, true) ;
           if( $HABIL =="")
            $susc->HABILITADO= "Si";
            else  $susc->HABILITADO= "No";
            $susc->save();

            $nuevo_pago->save();
            DB::commit();
             return response()->json( ['idnro'=>  $nuevo_pago->IDNRO] ) ;  

            }
        catch( Exception $ex){ 
            DB::rollBack();
            return response()->json( ['error'=>  "No pudo registrarse el pago"] ) ;  }


    }else{
        return view("0provider.suscriptor.pago", [ 'IDCLIENTE'=>$idcliente]);
    }
    
 }




 
public function sistema_habilitado( $systemid , $interno= false){
    $db_admin = DB::connection("principal");

    $dato_cuenta = $db_admin->table("suscriptores")
    ->join("planes","planes.IDNRO", "suscriptores.PLAN")
    ->select("planes.DURACION", 
    DB::raw("if( ABS( datediff( date(suscriptores.created_at), curdate()) )  > planes.DURACION , 'N', 'S') as VALIDO") )
    ->where("suscriptores.IDNRO", $systemid )->first();

    //fecha de creacion
    $Valido=  $dato_cuenta->VALIDO;
    //duracion
    $Plan_duracion=  $dato_cuenta->DURACION; 
    //Fecha pago
    $Fecha_pago= "";

    $HABILITADO= false;
    if(  $Valido == "N"){
        //lEER PAGOS
         //VERIFICAR ULTIMO PAGO
        $estado_pago= $db_admin->table("pagos")
        ->select( "pagos.FECHA AS FECHAPAGO", DB::raw("if( datediff(  DATE_ADD( pagos.FECHA, INTERVAL $Plan_duracion DAY)  , curdate()) > 0, 'S','N') as PERMITIR"))
        ->where("pagos.CLIENTE", $systemid)
        ->orderBy("FECHA", "desc")->first();
        if( ! is_null($estado_pago) ) {
            $Fecha_pago=   $estado_pago->FECHAPAGO;

            if(   $estado_pago->PERMITIR == "S" )  $HABILITADO= true; 
        }

    } else $HABILITADO=  true;

    if(  $interno ){
       if( $HABILITADO) return "";
       else{
           if( $Fecha_pago=="")
           return "El acceso está deshabilitado. Ya venció el período de prueba";
           else
           return "El acceso está deshabilitado. La fecha del último pago fue el $Fecha_pago";
       }
      
    }else{
        if( $HABILITADO ){
            return response()->json( ["ok"=>  "Habilitado. "]);
        }else{
            return response()->json( ["error"=>  "El acceso está deshabilitado. La fecha del último pago fue el $Fecha_pago"]);
        }
    }
   
    //  return response()->json( ["error"=>  "El acceso está deshabilitado. "]);

}
 




}