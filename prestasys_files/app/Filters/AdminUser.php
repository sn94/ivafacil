<?php namespace App\Filters;

use App\Models\Admin_model;
use App\Models\Usuario_model;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminUser implements FilterInterface
{

 
 
    private function session_id_valido(){
        $usu= new  Admin_model();
        $request = \Config\Services::request();
        $IVASESSION= is_null($request->getHeader("Ivasession")) ? "" :  $request->getHeader("Ivasession")->getValue();
        $res= $usu->where( "session_id", $IVASESSION )->first();
     
        
		if( is_null( $res) ) return false;
		else{ 
			// return true;
				//recuperar sesion si es valida
				$hoy= strtotime(  date(  "Y-m-d H:i:s")  );
                $expir=  strtotime(  $res->session_expire);
               
				return (  $hoy <=  $expir ) ;
			 
		}
    }



    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here
        $session = \Config\Services::session();
        $request = \Config\Services::request();

        $uri = $request->uri; 
        $principal= sizeof($uri->getSegments()) ==0  ; 
        if (!$session->has('nick')  &&   ! $session->has('ruc')  ){
            if(  $principal )
            return redirect()->to(base_url("welcome/publico"));
            else
            {
                if( !$this->session_id_valido())
                return redirect()->to(base_url("admin/sign-in"));
            }
          
        }   
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}