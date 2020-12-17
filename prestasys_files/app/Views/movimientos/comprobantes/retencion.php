<?= $this->extend("layouts/index_cliente") ?>
<?= $this->section("titulo") ?>
Bienvenido
<?= $this->endSection() ?>

<?= $this->section("contenido") ?>






<div class="row mt-3">


<div class="col-12">
    <?= view("plantillas/message") ?>

</div>

    <div class="col-12  offset-md-3 col-md-6 ">
        <div class="container p-0">
            <div class="container" style="border-radius: 15px 15px 0px 0px; background-color: #e4e4e4; border-bottom: 1px solid #cecece;border-right: 1px solid #cecece; border-left: 1px solid #cecece;">
                <h4 style="color: #272727;" class="text-center">Retención</h4>
            </div>

            <form action="<?= base_url("retencion/create") ?>" method="post" class="container" onsubmit="guardar(event)">


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
    </div>
    <!--end second col -->

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
        if (parseInt(ev.target.value) != 1)
            $("#cambio1,#cambio2").removeClass("d-none");
        else $("#cambio1,#cambio2").addClass("d-none");
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
        $("input[name=importe]").val($("input[name=importe]").val().replaceAll(new RegExp(/\.+/g), ""));
        ev.target.submit();
    }




    //init
    window.onload = function() {
        get_monedas();

    };
</script>

<?= $this->endSection() ?>