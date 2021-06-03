<!--{ titulo : comprobantes, opciones: { titulo: "compras", link: "#" } -->

<nav class="navbar navbar-expand-sm navbar-default">

    <div class="navbar-header">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars"></i>
        </button>
        <a class="navbar-brand" href="<?= base_url("welcome/index") ?>"><img src="<?= base_url("assets/img/Logo.png?" . date('is')) ?>" alt="Logo"></a>
        <a class="navbar-brand hidden" href="<?= base_url("welcome/index") ?>"><img src="<?= base_url("assets/img/Logo.png?" . date('is')) ?>" alt="Logo"></a>
    </div>

    <div id="main-menu" class="main-menu collapse navbar-collapse w-100">
        <ul class="nav navbar-nav w-100">

            <?php foreach ($datos as $opcion) : ?>
                <h3 class="menu-title"><?= $opcion['titulo'] ?></h3>
                <?php foreach ($opcion['opciones'] as $item) : ?>
                    <li class="menu-item-has-children dropdown">
                        <a href="<?= $item['link'] ?>" aria-haspopup="true" aria-expanded="false">
                            <i class="<?= $item['icon'] ?>"></i>
                            <?= $item['titulo'] ?> </a>
                    </li>
                <?php endforeach; ?>

            <?php endforeach; ?>

        </ul>
    </div>
</nav>