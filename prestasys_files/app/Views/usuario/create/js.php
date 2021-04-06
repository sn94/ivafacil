<script>
    function obtener_dv(ev) {
        if (ev.target.value == "") document.querySelector("input[name=dv]").value = "";


        if (ev.data == undefined || ev.data == null) return;
        solo_numero(ev);
        let cad = calcular_digito_verificador(ev.target.value, 11);
        document.querySelector("input[name=dv]").value = cad;
    }

    function calcular_digito_verificador(tcNumero, tnBaseMax) {
        let lcNumeroAl, i, lcCaracter, k, lnTotal, lnNumeroAux, lnResto, lnDigito;
        lcNumeroAl = ""

        for (let i = 0; i < tcNumero.length; i++) {
            lcCaracter = tcNumero.substr(i, 1).toUpperCase();
            if (lcCaracter.charCodeAt() < 48 || lcCaracter.charCodeAt() > 57)
                lcNumeroAl = lcNumeroAl + String(lcCaracter);
            else
                lcNumeroAl = lcNumeroAl + lcCaracter;
        }
        console.log("lcNumeroAL", lcNumeroAl);

        k = 2;
        lnTotal = 0;
        for (i = lcNumeroAl.length - 1; i >= 0; i--) {
            if (k > tnBaseMax)
                k = 2;

            lnNumeroAux = parseInt(lcNumeroAl.substr(i, 1)); //VAL
            lnTotal = lnTotal + (lnNumeroAux * k);
            k = k + 1
        }
        lnResto = lnTotal % 11;
        if (lnResto > 1)
            lnDigito = 11 - lnResto;
        else
            lnDigito = 0;
        return lnDigito;

    }
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

        try {
            if (parseInt(enpuntos) == 0) $(ev.target).val("");
            else $(ev.target).val(enpuntos);
        } catch (err) {
            $(ev.target).val(enpuntos);
        }
    }


    function solo_numero(ev) {

        if (ev.data == undefined || ev.data == null) return;
        if (ev.data.charCodeAt() < 48 || ev.data.charCodeAt() > 57) {
            ev.target.value =
                ev.target.value.substr(0, ev.target.selectionStart - 1) +
                ev.target.value.substr(ev.target.selectionStart);
        }

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
            if (ev.target.name != "dv")
                $("#" + ev.target.name).text("Campo obligatorio");

        } else {
            $(ev.target).removeClass("empty-field");
            $("#" + ev.target.name).text("");
        }
    }



    /***
    
    **Fuentes de datos
    **/
    async function get_ciudades() {
        let req = await fetch("<?= base_url("auxiliar/ciudades") ?>");
        let json_r = await req.json();


        let departs = json_r.map(
            function(obje) {
                return obje.departa;
            }
        ).filter(function(obj, indice, arr) {

            return arr.indexOf(obj) == indice;
        });


        let ordenado = departs.map(function(key) {
            let cities = json_r.filter(function(obj_ciu) {
                return obj_ciu.departa == key;
            }).map(function(nuevo) {
                return {
                    regnro: nuevo.regnro,
                    ciudad: nuevo.ciudad
                };
            });
            return {
                [key]: cities
            };
        });

        ordenado.forEach(function(regi) {

            let depart = Object.keys(regi)[0];
            let ciudades = regi[depart];
            let str_ciudades = ciudades.map(function(citi) {
                return "<option value='" + citi.regnro + "'>" + citi.ciudad + "</option>";
            }).join();

            let optgr = "<optgroup label='" + depart + "'>" + str_ciudades + "</optgroup>";
            //clasificar
            $("select[name=ciudad]").append(optgr);
        });

        /* */
    }





    async function get_actividades_comer() {

        let req = await fetch("<?= base_url("auxiliar/rubros") ?>");
        let json_r = await req.json();

        json_r.forEach(function(obj) {
            $("select[name=rubro]").append("<option value='" + obj.regnro + "'>" + obj.descr + "</option>");
        });

    }

    async function get_planes() {

        let req = await fetch("<?= base_url("auxiliar/planes") ?>");
        let json_r = await req.json();

        json_r.forEach(function(obj) {
            $("select[name=tipoplan]").append("<option value='" + obj.regnro + "'>" + obj.descr + "</option>");
        });

    }






    //Procesamiento de formulario


    function campos_vacios() {
        if (!$("input[name=aceptar-bases]").prop("checked")) {
            alert("Aceptar primero las bases y condiciones para continuar");
            return true;
        }
        if ($("input[name=email]").val() == "" || $("input[name=ruc]").val() == "" || $("input[name=dv]").val() == "") {
            if ($("input[name=email]").val() == "") {
                $("input[name=email]").addClass("empty-field");

            }
            if ($("input[name=ruc]").val() == "") {
                $("input[name=ruc]").addClass("empty-field");

            }
            if ($("input[name=dv]").val() == "") {
                $("input[name=dv]").addClass("empty-field");

            }
            return true;
        }

        return false;
    }

    function claves_validas() {
        if ($("input[name=pass]").val() == "") {
            alert("Proporcione una contraseña");
            return false;
        }
        if ($("#pass2").val() == "") {
            $("#pass2").addClass("empty-field");
            alert("Por favor repita su contraseña");
            return false;
        }
        if ($("input[name=pass]").val() != $("#pass2").val()) {
            alert("Ambas contraseñas no coinciden");
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

        //limpiar numeros
        clean_number($("input[name=ultimo_nro]"));
        clean_number($("input[name=cedula]"));
        clean_number($("input[name=saldo_IVA]"));

        let datos = $("#user-form").serialize();
        show_loader();
        let req = await fetch($("#user-form").attr("action"), {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: JSON.stringify(datos)
        });
        let respuesta = await req.json();
        hide_loader();
        if (("data" in respuesta) && parseInt(respuesta.code) == 200) {

            $("#message-modal-content").html("REGISTRADO<br> <a href='<?= base_url("usuario/sign-in") ?>'>Iniciar sesión</a>");

            $("#message-modal").modal("show");
        } else {
            $("#message-modal-content").html(procesar_errores(respuesta.msj));
            $("#message-modal").modal("show");
        }
    }








    //init
    window.onload = function() {
        get_planes();
        get_actividades_comer();
        get_ciudades();

    }
</script>