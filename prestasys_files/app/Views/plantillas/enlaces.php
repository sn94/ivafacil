 


<div class="container col-md-6 col-sm-12 mb-2 p-0">
    <ul class="nav justify-content-end ">
      <li class="nav-item">
        <a class="nav-link active" href="<?=base_url("welcome")?>">
          Inicio
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-light" href="<?=base_url("cliente")?>">Clientes</a>
      </li>

      <?php if( session("tipo") == "S"): ?>
          <li class="nav-item">
            <a class="nav-link  text-light" href="<?=base_url("usuario")?>">Usuarios</a>
          </li>
      <?php  endif; ?>

      <li class="nav-item">

 
            <div class="dropdown dropleft">
            <button class="btn btn-info btn-md dropdown-toggle " type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <?=    session("usuario")?>
            </button>
            <div class="dropdown-menu bg-dark" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item  text-light" href="<?=base_url("usuario/edit".session("id") )?>/1">Mis datos</a>
            <a class="dropdown-item  text-light" href="<?=base_url("usuario/sign_out")?>">Cerrar sesion</a>
              
            </div>
          </div>
      </li>
    </ul>
</div>
