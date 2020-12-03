<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RightAccess implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {

        // Do something here
        $session = \Config\Services::session();
        $request = \Config\Services::request();
        $uri = $request->uri;

        if(!$session->has('NICK') )  return;

        $accede_a_inicio=(  $uri->getSegment(1)=="");    
        $lista_segmentos= [];
 
        $segmentos=   "/";
        foreach($uri->getSegments()  as $s  ) { 
          array_push(   $lista_segmentos, $s );
        }
        if( sizeof($lista_segmentos) > 0)
        $segmentos=  join("/",  $lista_segmentos);

       $nivel_usu= session("NIVEL");

       $db = \Config\Database::connect();

       //Es un recurso No controlado?
       $rutasControladas= $db->table("permisos")-> where("RECURSO", $segmentos)->get()->getResultObject();  
        if( sizeof( $rutasControladas) > 0){

                //verificar permisos
                if( $nivel_usu != "U"){//Usuarios predeterminados
                    $permitidos= $db->table("default_permisos")->
                    join("permisos","permisos.IDNRO=default_permisos.IDPERMISO")->
                    select("permisos.RECURSO")->
                    where("OWNER", $nivel_usu)-> 
                    where("RECURSO", $segmentos)
                    ->get()->getResultObject();  
                    if( sizeof($permitidos) == 0)    return redirect()->to(  base_url("home/denegado"));  
                }else{
                    //usuarios custom
                    $permitidos= $db->table("permisos_asigna")->
                    join("permisos","permisos.IDNRO=permisos_asigna.IDPERMISO")->
                    select("permisos.RECURSO")->
                    where("OWNER", $nivel_usu)-> 
                    where("RECURSO", $segmentos)
                    ->get()->getResultObject();  
                    if( sizeof($permitidos) == 0)   return redirect()->to(  base_url("home/denegado"));  
                }
      
        }//Verificacion ruta controlada

    
        
       if( !$session->has('NICK')   )
       return redirect()->to(  base_url("usuario/sign_in"));  
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}