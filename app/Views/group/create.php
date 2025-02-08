<?=$this->extend('layouts/main')?>

<?=$this->section('content')?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <?php if (session()->getFlashdata('error')) { ?>

            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <?php }?>

            <div class="card">
                <div class="card-header">
                    <h5>Add Group                        
                        <a href="<?= base_url('lending/group/'); ?>" class="btn btn-danger btn-sm float-end">BACK</a>
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('lending/group/add/') ?>" method="POST">
                        <div class="form-group mb-2">
                            <label> Name <span style="color:red">*</span></label>
                            <input type="text" name="name" class="form-control decimal" placeholder="Enter Group Name" required/>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary mt-2">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

</script>    

<?=$this->endSection()?>