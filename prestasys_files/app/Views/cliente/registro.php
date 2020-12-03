<?= $this->extend("layouts/index") ?>
<?= $this->section("titulo") ?>
Bienvenido
<?= $this->endSection() ?>

<?= $this->section("contenido") ?>



<!-- Menu de Usuario -->
<div class="row">

    <div class="col-12 offset-md-3 col-md-6 ">
        <div class="card">
            <div class="card-header">
                <strong>Registrarse</strong>
            </div>
            <div class="card-body card-block p-0">
                <form action="" method="post" class="container">

                    <div class="row form-group">
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Cédula:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input oninput="formatear(event)" type="text" name="cedula" class=" form-control form-control-label form-control-sm ">
                        </div>

                        <div class="row pl-md-3 pl-0 pr-3">
                            <div class="col-8 ">
                                <div class="row">
                                    <div class="col-3 col-md-3 ">
                                        <label for="nf-email" class=" form-control-label form-control-sm -label">RUC:</label>
                                    </div>
                                    <div class="col-9 col-md-9">
                                        <input type="text" id="nf-email" name="ruc" class="  form-control form-control-label form-control-sm ">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 ml-0">
                                <div class="row">
                                    <div class="col-3 col-md-3 ">
                                        <label for="nf-email" class=" form-control-label form-control-sm -label">DV:</label>
                                    </div>
                                    <div class="col-9 col-md-9">
                                        <input oninput="solo_numero(event)" type="text" id="nf-email" name="dv" class="  form-control form-control-label form-control-sm ">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Nombres:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="text" maxlength="80" name="cliente" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Email:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input maxlength="80" type="text" name="email" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Domicilio:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input maxlength="100" type="text" name="domicilio" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Teléfono:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input oninput="phone_input(event)" maxlength="20" type="text" name="telefono" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Celular:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input oninput="phone_input(event)" maxlength="20" type="text" name="celular" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Ciudad:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <select name="ciudad" class=" form-control form-control-label form-control-sm "></select>
                        </div>
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Rubro:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="text" name="rubro" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Contraseña:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="password" name="pass" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Repetir contraseña:</label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input type="password" class=" form-control form-control-label form-control-sm ">
                        </div>
                        <div class="col-3 col-md-3 pl-md-3 pl-0">
                            <label for="nf-password" class=" form-control-label form-control-sm -label">Saldo anterior: <span>(a favor del contribuyente)</span></label>
                        </div>
                        <div class="col-9 col-md-9">
                            <input oninput="formatear( event)" type="text" name="nf-password" class=" form-control form-control-label form-control-sm ">
                        </div>

                        <div class="col-12">
                            <h6 class="mt-1 text-center" style="color: red; font-weight: 600;">VERIFICAR LOS DATOS REGISTRADOS</h6>
                            <p class="mt-1 text-center" style="color: red; font-weight: 600;"> El primer mes es GRATIS</p>
                            <p style="color: #026804; font-weight: 600;">El costo del servicio es de Gs. 60.000 mensuales, pagos por mes adelantado, se abona en cualquier ventanilla de pagos de servicios </p>
                        </div>

                    </div>




                </form>
            </div>
            <div class="card-footer">
                <a style="font-size: 10px;font-weight: 600;" href="<?= base_url("/") ?>" class="btn btn-success btn-sm">
                    <i class="fa fa-dot-circle-o"></i> ATRÁS
                </a>

                <button style="font-size: 10px;font-weight: 600;" type="submit" class="btn btn-success btn-sm">
                    <i class="fa fa-dot-circle-o"></i> REGISTRAR E IR AL MENÚ
                </button>
            </div>
        </div>
    </div>

</div>


<script>
    function phone_input(ev) {
        if (ev.data == undefined || ev.data == null) return;

        if ((ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57) && ev.data.charCodeAt() != 32) {
            ev.target.value =
                ev.target.value.substr(0, ev.target.selectionStart - 1) + " "
            ev.target.value.substr(ev.target.selectionStart);
        }
    }


    function formatear(ev) {
        if (ev.data == undefined) return;
        if (ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57) {
            ev.target.value =
                ev.target.value.substr(0, ev.target.selectionStart - 1) +
                ev.target.value.substr(ev.target.selectionStart);
        }
        //Formato de millares
        let val_Act = ev.target.value;
        val_Act = val_Act.replaceAll(new RegExp(/[\.]*[,]*/g), "");
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
        let val_Act = ev.target.value;
        val_Act = val_Act.replaceAll(new RegExp(/[\.]*[,]*/), "");
        let enpuntos = new Intl.NumberFormat("de-DE").format(val_Act);
        $(ev.target).val(enpuntos);
    }






    async function get_ciudades() {
        let req = await fetch("<?= base_url("assets/ciudades.json") ?>");
        let json_r = await req.json();


        let departs = json_r.map(
            function(obje) {
                return obje.depart;
            }
        ).filter(function(obj, indice, arr) {

            return arr.indexOf(obj) == indice;
        });
        console.log(departs);

        let ordenado = departs.map(function(key) {
            let cities = json_r.filter(function(obj_ciu) {
                return obj_ciu.depart == key;
            }).map(function(nuevo) {
                return nuevo.nombre;
            });
            return {
                [key]: cities
            };
        });

        ordenado.forEach(function(regi) {

            let depart = Object.keys(regi)[0];
            let ciudades = regi[depart];
            let str_ciudades = ciudades.map(function(citi) {
                return "<option value=''>" + citi + "</option>";
            }).join();

            let optgr = "<optgroup label='" + depart + "'>" + str_ciudades + "</optgroup>";
            //clasificar
            $("select[name=ciudad]").append(optgr);
        });

        /* */
    }

    window.onload = function() {
        get_ciudades();
    }
</script>


<?= $this->endSection() ?>