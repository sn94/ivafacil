<?= $this->extend("admin/layout/index") ?>
<?= $this->section("titulo") ?>
Bienvenido
<?= $this->endSection() ?>



<?= $this->section("estilos") ?>


<style>
    #right-panel {
        background-image: url(<?= base_url('assets/img/papers.jpg') ?>) !important;
        background-repeat: no-repeat;
        background-size: cover;
    }

    html {
        line-height: unset !important;
        height: 100% !important;
        background-image: url(<?= base_url('assets/img/papers.jpg') ?>) !important;
        background-repeat: no-repeat;
        background-size: cover;
    }

</style>

<?= $this->endSection() ?>

<?= $this->section("contenido") ?>
<h4 class="text-center mt-5">Bienvenido</h4>
<?= $this->endSection() ?>