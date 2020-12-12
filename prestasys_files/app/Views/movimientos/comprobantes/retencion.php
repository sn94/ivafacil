<?= $this->extend("layouts/index") ?>
<?= $this->section("titulo") ?>
Bienvenido
<?= $this->endSection() ?>

<?= $this->section("contenido") ?>



<!-- Menu de Usuario -->
<div class="row  mt-2">

    <div class="col-12 offset-md-4 col-md-4 ">
        <div class="card">
            <div class="card-header p-0">
                <h4 class="text-center m-0">Registro de Retención</h4>
            </div>
            <div class="card-body card-block p-0">
                <form action="<?= base_url("retencion/create/N") ?>" method="post" class="container" onsubmit="guardar(event)">


                <input type="hidden" name="ruc" value="<?= session("ruc") ?>">
                <input type="hidden" name="dv" value="<?= session("dv") ?>">
                <input type="hidden" name="codcliente" value="<?= session("id") ?>">
                <input type="hidden" name="origen" value="W">


                    <div class="row form-group">
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-email" class=" form-control-label form-control-sm -label">Fecha:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input value="<?= date("Y-m-d") ?>" type="date" name="fecha" class="  form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">N° de retención:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="text" maxlength="20" name="retencion" class=" form-control form-control-label form-control-sm ">
                        </div>

                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Importe retenido:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input maxlength="15" oninput="formatear_entero( event);" type="text" name="importe" class=" form-control form-control-label form-control-sm ">
                        </div>

                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Moneda:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <select onchange="cargar_cambio( event)" name="moneda" class=" form-control form-control-label form-control-sm "></select>
                        </div>

                        <div id="cambio1" class="col-3 col-md-3  pl-md-3 pl-0 d-none">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Tipo de cambio:</label>
                        </div>
                        <div id="cambio2" class="col-9 col-md-9 d-none">
                            <input value="0" oninput="formatear_entero(event)" type="text" name="tcambio" class=" form-control form-control-label form-control-sm text-right">
                        </div>


                        <div class="col-12">
                            <button style="font-size: 12px;font-weight: 600;width: 100%;" type="submit" class="btn btn-success btn-sm">
                                REGISTRAR
                            </button>
                        </div>
                    </div>




                </form>
            </div>
            <div class="card-footer">
                <a style="font-size: 12px;font-weight: 600;" href="<?= base_url("/") ?>" class="btn btn-secondary btn-sm">
                    <i class="fa fa-dot-circle-o"></i> IR AL MENÚ
                </a>

            </div>
        </div>
    </div>

</div>






<script>
    //Validaciones
    function formatear_entero(ev) {

        //       if (ev.data == undefined) return;
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


    function cargar_cambio(ev) {
        if ( parseInt(  ev.target.value  ) != 1)
            $("#cambio1,#cambio2").removeClass("d-none");
            else  $("#cambio1,#cambio2").addClass("d-none");
    }




    //Fuente de datos


    async function get_monedas() {

        let req = await fetch("<?= base_url("auxiliar/monedas") ?>");
        let json_r = await req.json();

        json_r.forEach(function(obj) {
            $("select[name=moneda]").append("<option value='" + obj.regnro + "'>" + obj.moneda + " (" + obj.prefijo + ")" + "</option>");
        });

    }






    //procesar form

    function guardar(ev) {
        ev.preventDefault();

        //limpiar numericos
        $("input[name=importe]").val(   $("input[name=importe]").val().replaceAll(new RegExp(/\.+/g), "") );
        ev.target.submit();
    }




    //init
    window.onload = function() {
        get_monedas();

    };
</script>

<?= $this->endSection() ?>