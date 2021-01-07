<?= $this->extend("admin/layout/index") ?>
<?= $this->section("titulo") ?>
Bienvenido
<?= $this->endSection() ?>

<?= $this->section("contenido") ?>


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

<div class="container p-1 p-md-2 bg-light">



    <div id="message-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content p-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="message-modal-content" class="text-center p-0 m-0" style="font-weight: 600;">
                </div>
            </div>
        </div>
    </div>

    <h3 class="text-center mb-2">MONEDAS</h3>

    <button type="button" class="btn btn-dark btn-sm" onclick="cargar_form()">NUEVO</button>
    <div id="tabla-monedas">
        <?= view("admin/monedas/list") ?>
    </div>


</div>


<script>
    //Actualizar tabla
    async function actualizar_grilla() {
        let loader = "<img style='z-index: 400000;position: absolute;top: 50%;left: 50%;'  src='<?= base_url("assets/img/loader.gif") ?>'   />";
        $("#tabla-monedas").html(loader);
        let form = await fetch("<?= base_url("admin/monedas") ?>", {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        });
        let form_R = await form.text();
        $("#tabla-monedas").html(form_R);
        $("ul.pagination li").addClass("btn btn-dark btn-sm").css("font-weight", "600");
    }




    // Formulario
    async function cargar_form() { //nuevo

        let form = await fetch("<?= base_url("admin/monedas/create") ?>");
        let form_R = await form.text();
        $("#message-modal-content").html(form_R);
        $("#message-modal").modal("show");
    }

    async function cargar_form_edit( ev) {
        ev.preventDefault();
        let form = await fetch( ev.currentTarget);
        let form_R = await form.text();
        $("#message-modal-content").html(form_R);
        $("#message-modal").modal("show");
    }


    async function borrar( ev){
        ev.preventDefault();
        if( !confirm("Borrar?") )  return;
        let form = await fetch( ev.currentTarget);
        let _R = await form.json();
        if(  "data" in  _R ) 
        actualizar_grilla();
        else   alert(  _R.msj);
    }


    window.onload = function() {

        $('#message-modal').on('hidden.bs.modal', function(e) {
            actualizar_grilla();
         
        });
        $("ul.pagination li").addClass("btn btn-dark btn-sm").css("font-weight", "600");

    };
</script>

</div> <!-- .content -->
</div><!-- /#right-panel -->









<?= $this->endSection() ?>