<?php


namespace App\Libraries\pdf_gen;



require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
use TCPDF;

//require("tcpdf/tcpdf.php");

class PDF extends TCPDF{
 
    public function __construct( $orientacion="P", $dimensiones=null){
        if( is_null( $dimensiones) )
        parent::__construct( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 
        else parent::__construct( $orientacion, PDF_UNIT, $dimensiones, true, 'UTF-8', false); 
      
    }

    private function setPdfFileName( $no){
        $this->nombrePdf=  $no;
    }
    
    public function prepararPdf( $no, $titulopdf, $subtitulopdf){
        $this->setPdfFileName( $no);
        $this->tituloPdf= $titulopdf;
        $this->subtituloPdf= $subtitulopdf;
        $this->setMetaData();
        $this->setHeaderAndFooter();
        $this->setDefaultFontType();
        $this->setMargins_();
        // set auto page breaks
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        // set image scale factor
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // set some language-dependent strings (optional)
       $this->setLanguagesDepend();
        // ---------------------------------------------------------
        $this->settingFont(); 
    }

    public function generarHtml( $html_custom){ 
         // This method has several options, check the source code documentation for more information.
        $this->AddPage();
        $this->setShadows();
        // Set some content to print
        $html = $html_custom; 
         // Print text using writeHTMLCell()
         $this->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    }

    public function generar(){
        // ---------------------------------------------------------
      // Close and output PDF document
      // This method has several options, check the source code documentation for more information.
      $this->Output(  $this->nombrePdf  , 'D');
      exit();
  }
    public function generar_v2(){
          // ---------------------------------------------------------
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        return $this->Output(  $this->nombrePdf  , 'D');
    }
    /************************************************** */
    public function setMetaData(){
        // set document information
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('Sonia t');
        $this->SetTitle( $this->tituloPdf);
        $this->SetSubject('TCPDF Tutorial');
        $this->SetKeywords('TCPDF, PDF, example, test, guide');
    }

    public function setHeaderAndFooter(){
            // set default header data
            $this->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $this->tituloPdf, $this->subtituloPdf, array(0,64,255), array(0,64,128));
            $this->setFooterData(array(0,64,0), array(0,64,128));

            // set header and footer fonts
            $this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    }

    public function setDefaultFontType(){
        // set default monospaced font
        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    }

    public function setMargins_(){
        // set margins
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);

    }

    public function setLanguagesDepend(){
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $this->setLanguageArray($l);
        }
    }

    public function setShadows(){
         // set text shadow effect
         $this->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

    }

    public function settingFont(){
        // set default font subsetting mode
        $this->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $this->SetFont('dejavusans', '', 14, '', true);
    }

   

  


}



?>