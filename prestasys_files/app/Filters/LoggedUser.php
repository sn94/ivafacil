<?php namespace App\Filters;

use App\Controllers\Usuario;
use App\Models\Usuario_model;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LoggedUser implements FilterInterface
{



    private function isAPI(){

        $request = \Config\Services::request();
       $uri = $request->uri;
        if (sizeof($uri->getSegments()) > 0 &&  $uri->getSegment(1) == "api") {
            return true;
        } return false;
      /*
        $sesion= $request->getHeader('Ivasession');
        return $sesion != "";*/
    }



    private function session_id_valido(){
        $usu= new Usuario_model();
        $request = \Config\Services::request();
        $IVASESSION= is_null($request->getHeader("Ivasession")) ? "" :  $request->getHeader("Ivasession")->getValue();
        $res= $usu->where( "session_id",  $IVASESSION )->first();
     
		if( is_null( $res) ) return false;
		else{
			 return true;
				//recuperar sesion si es valida
				$hoy= strtotime(  date(  "Y-m-d H:i:s")  );
				$expir=  strtotime(  $res->session_expire);
				return !(  $hoy <  $expir ) ;
			 
		}
    }
    


    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here
        $session = \Config\Services::session();
        $request = \Config\Services::request();

        $uri = $request->uri; 
      
        if (!$this->isAPI()) {
            $accede_a_inicio = ($uri->getSegment(1) == "");
            $no_acceso_a_login =  sizeof($uri->getSegments()) == 2  && $uri->getSegment(1) != "usuario" &&  $uri->getSegment(2) != "sign_in";
            $creacion_usuario=  $no_acceso_a_login =  sizeof($uri->getSegments()) == 2  && $uri->getSegment(1) != "usuario" &&  $uri->getSegment(2) != "create";
            $principal= sizeof($uri->getSegments()) ==0  ;
 
            //&&   $no_acceso_a_login && $auxi  &&  $creacion_usuario
        if (!$session->has('ruc')  ){
            if(  $principal )
            return redirect()->to(base_url("welcome/publico"));
            else
            return redirect()->to(base_url("usuario/sign_in"));
        }
        
          
        }else{
            //Si es usuario de API, verificar la validez de su sesion id
            if( !  $this->session_id_valido()  ){
                $response= \Config\Services::response();
                return $response->setJSON( array("msj"=> "No está autenticado", "code"=> 500 ) );
 
            }

        }

    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}