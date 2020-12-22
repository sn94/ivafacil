 
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

 <div class="container">


     <div class="content mt-3">





         <div id="loaderplace">
         </div>

         <div id="message">

         </div>

         <!-- Menu de Usuario -->
         <div class="row">

             <div class="col-12">
                 <?= view("plantillas/message") ?>

             </div> 
             <div class="col-12  col-md-12 p-0 ">


                 <?php

                    
                    echo  form_open("admin/update",  ['id' => 'user-form', 'class' => 'container', 'onsubmit' => 'registro(event)']); ?>


                 <div class="card">
                     <div class="card-header">
                         <h4 class="text-center">Usuario Administrador</h4>
                     </div>
                     <div class="card-body card-block p-2">
                         <div class="row form-group">

                             <div class="col-12">
                                 <div class="row form-group">
                                     <?= view("admin/admin/form") ?>
                                 </div>
                             </div>
                         </div>
                         <!--end row form  -->


                     </div>
                     <div class="card-footer">

                        <div class="col-12 offset-md-4 col-md-4">
                        <button style="font-size: 12px;font-weight: 600;width: 100%;" type="submit" class="btn btn-success btn-sm">
                             <i class="fa fa-dot-circle-o"></i> ACTUALIZAR
                         </button>
                        </div>
                        

                     </div>

                 </div>
                 </form>


             </div>

         </div>


         <script>
             /***
                Validaciones js

                **/

             function phone_input(ev) {
                 if (ev.data == undefined || ev.data == null) return;

                 if ((ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57) && ev.data.charCodeAt() != 32) {
                     ev.target.value =
                         ev.target.value.substr(0, ev.target.selectionStart - 1) + " " +
                         ev.target.value.substr(ev.target.selectionStart);
                 }
             }


             function formatear(ev) {

                 if (ev.data == null || ev.data == undefined)
                     ev.target.value = ev.target.value.replaceAll(new RegExp(/[.]*[,]*/g), "");


                 if (ev.data != null && (ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57)) {
                     ev.target.value =
                         ev.target.value.substr(0, ev.target.selectionStart - 1) +
                         ev.target.value.substr(ev.target.selectionStart);
                 }
                 //Formato de millares
                 let val_Act = ev.target.value;
                 val_Act = val_Act.replaceAll(new RegExp(/[.]*[,]*/g), "");
                 let enpuntos = new Intl.NumberFormat("de-DE").format(val_Act);
                 $(ev.target).val(enpuntos);
             }

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



             function clave_no_coincide(ev) {
                 let rep = ev.target.value;
                 if (rep == $("input[name=pass]").val()) {
                     $(ev.target).removeClass("empty-field");
                     $(ev.target).removeClass("password-wrong");
                     $(ev.target).addClass("password-ok");
                     $("input[name=pass]").addClass("password-ok");
                 } else {
                     $("input[name=pass]").removeClass("password-ok");
                     $(ev.target).removeClass("password-ok");
                     $(ev.target).addClass("password-wrong");
                 }
             }





             function control_campo_vacio(ev) {
                 if (ev.target.value == "") {
                     $(ev.target).addClass("empty-field");
                     $("#" + ev.target.name).text("Campo obligatorio");

                 } else {
                     $(ev.target).removeClass("empty-field");
                     $("#" + ev.target.name).text("");
                 }
             }










             //Procesamiento de formulario


             function campos_vacios() {
                 if ($("input[name=nick]").val() == "") {
                     $("input[name=nick]").addClass("empty-field");
                     $("#nick").text("Campo obligatorio");
                 }
                 if ($("input[name=email]").val() == "") {
                     $("input[name=email]").addClass("empty-field");
                     $("#email").text("Campo obligatorio");
                 }
                 if ( !($("input[name=pass]").prop("disabled")) &&   $("input[name=pass]").val() == "") {
                     $("input[name=pass]").addClass("empty-field");
                     $("#pass").text("Campo obligatorio");
                 }
                 return ($("input[name=nick]").val() == "") ||
                  ($("input[name=email]").val() == "") ||
                   ( !($("input[name=pass]").prop("disabled")) &&     $("input[name=pass]").val() == "");
             }

             function claves_validas() {
                 if ( !($("input[name=pass]").prop("disabled")) && $("input[name=pass]").val() == "") {
                     alert("Proporcione una contraseña");
                     return false;
                 }
                 
                 
                 return true;
             }


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
                 if (campos_vacios() || !claves_validas()) return;


                 let datos = $("#user-form").serialize();
                 show_loader();
                 let req = await fetch($("#user-form").attr("action"), {
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

                     $("#message").html("REGISTRADO");
                     $("#message-modal").modal("hide");
                 } else {
                     $("#message").html(procesar_errores(respuesta.msj));
                 }
             }
         </script>

     </div> <!-- .content -->
 </div><!-- /#right-panel -->

 