 <style>
     .card-header {
         background-color: #d1d1d1;
         border: 1px solid beige;
         border-radius: 15px 15px 0px 0px;
     }


     .card {
         border-radius: 15px 15px 0px 0px;
     }


     h4.text-center {
         color: #646464;
         font-weight: bolder;
     }


     .empty-field {
         border: 2px solid #ed2328;
         /*background-color: #ff9595;*/
     }

     .password-ok {
         background-image: url(<?= base_url("assets/img/ok.png") ?>);
         background-repeat: no-repeat;
         background-position: right;
         background-clip: border-box;
         background-size: contain;
     }

     .password-wrong {
         background-image: url(<?= base_url("assets/img/error.png") ?>);
         background-repeat: no-repeat;
         background-position: right;
         background-clip: border-box;
         background-size: contain;
     }
 </style>



 <!-- Right Panel -->

 <div class="container p-0">


     <div class="content mt-3 p-0 m-0">


         <div id="monedas-modal-content" class="alert alert-danger text-center p-0" style="font-weight: 600;">
         </div>


         <div id="loaderplace">
         </div>

         <!-- Menu de Usuario -->
         <div class="row p-0 m-0">
 
             <div class="col-12  p-0 ">

                 <div class="container-fluid m-0 p-0">
                     <?php echo  form_open("admin/calendario",  ['id' => 'calendario-form', 'class' => 'container m-0 p-0', 'onsubmit' => 'registro(event)']); ?>
                     <?= view("admin/calendario/form") ?>
                     </form>
                 </div>


             </div>

         </div>


         <script>
            
             function solo_numero(ev) {

                 if (ev.data == undefined || ev.data == null) return;
                 if (ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57) {
                     ev.target.value =
                         ev.target.value.substr(0, ev.target.selectionStart - 1) +
                         ev.target.value.substr(ev.target.selectionStart);
                 }

             }


             function clean_number(arg) {
                 arg.val(arg.val().replaceAll(/(\.|,)/g, ""));
             }














             //Procesamiento de formulario






             function show_loader() {
                 let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
                 $("#loaderplace").html(loader);
             }

             function hide_loader() {
                 $("#loaderplace").html("");
             }




             function procesar_errores(err) {
                 if (typeof err == "object") {
                     let errs = Object.keys(err);
                     let concat_errs = errs.map(function(it) {
                         return err[it];
                     }).join("<br>");
                     console.log(concat_errs);
                     return concat_errs;
                 }
                 return err;

             }


             async function registro(ev) {


                 ev.preventDefault();

                 let datos = $("#calendario-form").serialize();
                 show_loader();
                 let req = await fetch($("#calendario-form").attr("action"), {
                     method: "POST",
                     headers: {
                         // 'Content-Type': 'application/json',
                         'Content-Type': 'application/x-www-form-urlencoded'
                     },
                     body: datos
                 });
                 let respuesta = await req.json();
                 hide_loader();
                 if (("data" in respuesta) && parseInt(respuesta.code) == 200) {
                  alert(  respuesta.data)
                 } else {
                     alert(respuesta.msj);

                 }
             }
         </script>

     </div> <!-- .content -->
 </div><!-- /#right-panel -->