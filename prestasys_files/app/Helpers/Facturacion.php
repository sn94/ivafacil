<?php



namespace App\Helpers;

use App\Controllers\Cierres;

class Facturacion
{


    /**
     * Param: request data
     * */
    public static function  calcular_iva($data)
    {

        $datos =  $data;

        //Valores por defecto
        if (!isset($data['importe1'])  || $data['importe1'] == "") $datos['importe1'] = 0;
        if (!isset($data['importe2']) || $data['importe2'] == "") $datos['importe2'] = 0;
        if (!isset($data['importe3']) || $data['importe3'] == "") $datos['importe3'] = 0;


        //Es IVA incluido?

        $IVA_INCLUIDO =  isset($data['iva_incluido']) ? ($data['iva_incluido'])   :  "S";
        //Si no se proporciona este parametro, por defecto sera iva incluido
        $ES_IVA_INCLUIDO =  $IVA_INCLUIDO == "S";
        /**Por defecto iva incluido  */
        $iva1 =  $datos['importe1'] / 11;
        $iva2 = $datos['importe2'] / 21;
        $iva3 =  0;//Exenta

        if (!$ES_IVA_INCLUIDO) {
            /**NO INCLUIDO */
            $iva1 =  $datos['importe1'] * (10 / 100);
            $iva2 = $datos['importe2'] * (5 / 100);
            $iva3 =  0;
            //Sumar iva 10 a su importe 
            $datos['importe1'] =  $datos['importe1'] + round( $iva1);
            //Sumar iva 5 a su importe
            $datos['importe2'] =  $datos['importe2'] + round( $iva2);
        }

        $datos['iva1'] =  $iva1;
        $datos['iva2'] =  $iva2;
        $datos['iva3'] = $iva3;
        $datos["total"] =  $datos['importe1']  + $datos['importe2']  + $datos['importe3'];
        return $datos;
    }



    /**
     * Param: request data
     * */
    public static function  convertir_a_moneda_nacional($data)
    {
        $cambio = $data['tcambio'];
        if(    $cambio  == ""   ||   $cambio== "0")  $cambio = 1;

        if (array_key_exists("importe",    $data)) {
            //En caso retencion
           
            $im = $data['importe'];
            $datos = $data;
           
            $datos['importe'] =  intval($cambio) * intval($im);

            return $datos;
        }
       
        $im1 = $data['importe1']; //10%
        $im2 = $data['importe2']; //5%
        $im3 = $data['importe3']; //EXE

        $iva1 = $data['iva1']; // 10 %
        $iva2 = $data['iva2']; // 5 %
        $iva3 = $data['iva3']; // exe

        $datos = $data;
        $datos['importe1'] =  intval($cambio) * intval($im1);
        $datos['importe2'] =  intval($cambio) * intval($im2);
        $datos['importe3'] =  intval($cambio) * intval($im3);
        $datos['iva1'] =  intval($cambio) * intval($iva1);
        $datos['iva2'] =  intval($cambio) * intval($iva2);
        $datos['iva3'] =  intval($cambio) * intval($iva3);
        $datos["total"] =  $datos['importe1']  + $datos['importe2']  + $datos['importe3'];
        return $datos;
    }




    /**Validaciones */

 
}
