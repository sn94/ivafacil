<?= $this->extend("layouts/index_cliente") ?>
<?= $this->section("titulo") ?>
Bienvenido
<?= $this->endSection() ?>

<?= $this->section("contenido") ?>

<style>
    
h4.text-center {
  /* color: #272727; */
  color: #646464;
  font-weight: 600;
}



.offset-md-3 > div:nth-child(1) > div:nth-child(1) {
  background-color: #d1d1d1;
}

 
.p-0 {
  border: 1px solid #c8c0c0;
  border-radius: 15px 15px 0px 0px;
}

</style>




<div class="row mt-3">


    <div class="col-12" id="loaderplace">
    </div>
    <div class="col-12">
        <?= view("plantillas/message") ?>

    </div>

    <div class="col-12  offset-md-3 col-md-6 ">
        <div class="container p-0">
            <div class="container" style="border-radius: 15px 15px 0px 0px; background-color: #e4e4e4; border-bottom: 1px solid #cecece;border-right: 1px solid #cecece; border-left: 1px solid #cecece;">
                <h4  class="text-center">Retención</h4>
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




    async function obtener_cambio(ev) {

        let id = ev.target.value;

        let req = await fetch("<?= base_url("monedas/show") ?>/" + id);
        let json_r = await req.json();
        if ("data" in json_r) {
            let cambio = json_r.data.tcambio;
            try {
                cambio = parseInt(cambio);
            } catch (err) {
                cambio = 0;
            }
            $("input[name=tcambio]").val(dar_formato_millares(cambio));
        }
    }

    function cargar_cambio(ev) {
        if (parseInt(ev.target.value) != 1)
            $("#cambio1,#cambio2").removeClass("d-none");
        else $("#cambio1,#cambio2").addClass("d-none");
        obtener_cambio(ev);
    }




    //Fuente de datos


    async function get_monedas() {

        let req = await fetch("<?= base_url("auxiliar/monedas") ?>");
        let json_r = await req.json();
        json_r.forEach(function(obj) {
            $("select[name=moneda]").append("<option value='" + obj.regnro + "'>" + obj.moneda + " (" + obj.prefijo + ")" + "</option>");
        });
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





    function show_loader() {
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#loaderplace").html(loader);
    }

    function hide_loader() {
        $("#loaderplace").html("");
    }



    //procesar form

   async  function guardar(ev) {
        ev.preventDefault();

        //limpiar numericos
        $("input[name=importe]").val($("input[name=importe]").val().replaceAll(new RegExp(/\.+/g), ""));
        show_loader();
        let req = await fetch(ev.target.action, {
            "method": "POST",
            headers: {
                // "Content-Type": "application/json"
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: $(ev.target).serialize()
        });
        let resp = await req.json();
        hide_loader();
        if ("data" in resp) alert(resp.data);
        else alert(procesar_errores(resp.msj));

        window.location.reload();
    }




    //init
    window.onload = function() {
        get_monedas();

    };
</script>

<?= $this->endSection() ?>