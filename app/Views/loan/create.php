
<?=$this->extend('layouts/main')?>

<?=$this->section('content')?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <h5>Add Loan
                        <a href="<?= base_url('lending/customer'); ?>" class="btn btn-danger btn-sm float-end">BACK</a>
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('lending/loan/add') ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="custno" class="form-control" value="<?php echo $custno; ?>" placeholder="Enter Amount to Borrow" required/>
                        <div class="form-group mb-2">
                            <label> Loan Amount </label>
                            <input type="text" name="loan_amount" class="form-control" placeholder="Enter Amount to Borrow" required/>
                        </div>
                        <div class="form-group mb-2">
                            <label> Loan Date </label>
                            <input type="text" name="firstname" class="form-control" placeholder="Loan Date"/>
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

<?=$this->endSection()?>