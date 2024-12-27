<?= $this->include('layouts/inc/header') ?>

<div class="container-fluid">
    <div class="wrapper">
        <?= $this->include('layouts/inc/sidebar') ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>

<?= $this->include('layouts/inc/footer') ?>