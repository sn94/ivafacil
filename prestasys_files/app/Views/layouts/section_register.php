<style>
    

/* Elemento | http://localhost/ivafacil/welcome/publico */

div.col-sm-12:nth-child(3) > div:nth-child(1) > a:nth-child(1) {
  background-color: greenyellow;
  font-weight: 600;
  font-size: 20px !important;
  color: #4f4e0f;
  text-transform: uppercase    ;
}

</style>
<section id="register" class="register">
        <div class="container-fullwidth">
            <div class="row text-center">
                <div class="col-sm-6 col-xs-6 no-padding">
                    <div class="single_register single_login">
                        <a href="<?=base_url("usuario/sign-in")?>">ENTRAR</a>
                    </div>
                </div>
                <div class="col-sm-6 col-xs-6 no-padding">
                    <div class="single_register">
                        <a href="<?=base_url("usuario/create")?>">Registrarse</a>
                    </div>
                </div>
                <div class="col-sm-12 col-xs-12 no-padding">
                    <div class="text-center" >
                        <a href="<?=base_url("admin/sign-in")?>">Acceso administrativo</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
