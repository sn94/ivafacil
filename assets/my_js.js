
var listaDeVendedores= [];

/**
 * Ayuda a ejecutar peticiones rapidamente, y obtener la respuesta asincronamente para mostrar dentro de un
 * contenedor html como div
 * @param {} ev 
 * @param {*} div  El nombre del elemento contenedor, dentro del cual se mostraran los resultados
 */
function peticion(ev, div){
    ev.preventDefault();

    if( confirm("Continuar?")){
        let ruta=  ev.target.action;
        let metodo=  ev.target.method; 
        $.ajax( {
            url: ruta ,
             method: metodo, 
            data: $(ev.target).serialize(),
            success: function (res){
                $(  div ).html( res );
                ev.target.reset();
            } 
        });
    }

}





function get_pay_load(   context){
    let formulario= $( context); 
   let t_enc= formulario.attr("enctype");
   let data= null;

   if(   formulario.attr("method")  == "post"){

    if( t_enc == "multipart/form-data"){
        // Create an FormData object 
        data = new FormData(   context ); 
    }else{  data=   formulario.serialize();  }
   } 
    return data;
}

function peticionWithMsg(ev, div){
    ev.preventDefault(); 
    
    if( confirm("Continuar?")){
        let ruta=  ev.target.action;
        let metodo=  ev.target.method; 
        let enctype= ( ev.target.enctype == "multipart/form-data")?  'multipart/form-data':'application/x-www-form-urlencoded';
        let config_= {
            url: ruta ,
            method: metodo, 
            enctype: enctype,
            data: get_pay_load( ev.target ) ,
            success: function (res){
                $("#mensajes").html("");
                $(  div ).html( res );
                ev.target.reset();
            },
            beforeSend: function(){
                $("#mensajes").html("Espere un momento...");
            }
        };
        if(  ev.target.enctype == "multipart/form-data")
        {config_.processData= false; config_.contentType= false;}
        $.ajax( config_);
    }

}


//solo en multipart/form-data
// processData: false,
//contentType: false,



/**
 * 
 * validaciones
 */

function numericInput(evt){
    let ar= evt.target.value;
    if( !(ar.charCodeAt( ar.length-1) >= 48  &&   ar.charCodeAt(ar.length-1) <= 57)) 
    evt.target.value= ar.substr( 0, ar.length-1);
  
}
 

  

/**
 * fuentes de datos
 */


 function autoCompleteCiudades( input ){ 
     $.get("/crediweb/assets/ciudades.json", function( res){

        let res2= res.map( (ar)=>{  return {value: ar.id_ciu, label: ar.nombre}  });
       
              // Show label and insert label into the input:
                new Awesomplete(input, {
                    list:  res2,
                    minChars: 1,
                    // insert label instead of value into the input.
                    replace: function(suggestion) {
                        this.input.value = suggestion.label;
                    }
                });
     });
 }


 function autoCompleteVendedores( input ){ 
    $.get("/crediweb/usuario/list_vendedores", function( res){
        //parsear 
        let list_vend= []; 
        try{
            list_vend= JSON.parse( res );
            let lista_= list_vend.map( (ar)=>{  return {value: ar.cedula, label: ar.cedula+"-"+ar.nombres}  });
            listaDeVendedores=  lista_;
            // Show label and insert label into the input:
            new Awesomplete(input, {
                       list:  lista_,
                       minChars: 1,
                       // insert label instead of value into the input.
                       replace: function(suggestion) {
                           this.input.value = suggestion.value;//nombres
                           //inputForId.text( "Vendedor CI: "+ suggestion.value);//cedula
                       }
                   });
        }catch( err){

        }
       
    });
}


 
 function autoCompleteClientes( searchterm, input , replaceFunc){ 
     let nom= searchterm;
     $.ajax({
         url: "/crediweb/cliente/getClientes",
         method: "post",
         data: {"nom": nom },
         success: function( res){
            let resjson= JSON.parse( res);
           
            let res2= resjson.map( (ar)=>{  return {value: ar.cedula, label: ar.nombres}  });
           
                  // Show label and insert label into the input:
                    new Awesomplete(input, {
                        list:  res2,
                        // insert label instead of value into the input.
                        replace: replaceFunc
                    });
         }
     });
}





/***GENERACION DE XLS
 * 
 */
   /****** CREANDO XLS****** */
       
   function jsonToArray( arg){ 
    try{
        let ob=JSON.parse( arg) ;
         let newob= ob.map( ( row)=>  Object.values(row));
         //agregar cabeceras
         let headers= Object.keys(  ob[0] ); 
         newob.unshift( headers);
         return newob;
     }catch( er){
         $("#mensajes").html("");
         return [];
     }
 }
 function createWorkBook( datos , whois="Clientes" ){
    
     $("#mensajes").html("Creando archivo xls/xlsx...");
    
     var wb = XLSX.utils.book_new();//workbook
     let hoy_es= new Date();
     wb.Props = {
             Title: "SheetJS Tutorial",
             Subject: "Test",
             Author: "Anyone",
             CreatedDate: hoy_es
     };
     
     wb.SheetNames.push("Hoja 1");
     var ws_data = jsonToArray( datos) ;
     if( ws_data.length == 0){//LA LISTA DE DATOS ESTA VACIA
         alert("Sin resultados");
         return;
     }
     var ws = XLSX.utils.aoa_to_sheet(ws_data); 
     //var ws = XLSX.utils.table_to_sheet(document.getElementById('tabla'));
     wb.Sheets["Hoja 1"] = ws;
     var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});
     let s2ab= function(s) {
         var buf = new ArrayBuffer(s.length);
         var view = new Uint8Array(buf);
         for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
         return buf;
         
     }
     $("#mensajes").html("");
     let fecha= new Date();
     let fileName= whois+"-"+ ( fecha.getDate()+"-"+(fecha.getMonth()+1)+"-"+fecha.getFullYear())+"-"+ fecha.getMilliseconds()+".xlsx";
     
     saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), fileName);
 }
 


 //mostrar imagen seleccionada
 function show_loaded_image(  target , tagDestination){
    let entrada= target.srcElement;
    let reader = new FileReader();
    reader.onload=    function(e){

        var filePreview = document.createElement('img');
        filePreview.className= "img-responsive";
        filePreview.src = e.target.result;
        filePreview.style.width =  "100%";
        filePreview.style.maxHeight="200px";
        var previewZone = document.querySelector( tagDestination);
        previewZone.innerHTML="";
        previewZone.appendChild(filePreview); 

      };
    reader.readAsDataURL(   entrada.files[0]);

}// show_loaded_image( event, "#idid")