
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
                    <h5>Add Loan
                        <a href="<?= base_url('lending/loan'); ?>" class="btn btn-danger btn-sm float-end">BACK</a>
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('lending/loan/add') ?>" method="POST" enctype="multipart/form-data">                        
                        <div class="form-group mb-2">
                            <label> Loan Amount <span style="color:red">*</span></label>
                            <input type="text" name="loan_amount" class="form-control decimal" placeholder="Enter Amount to Borrow" required/>
                        </div>
                        <div class="form-group mb-2">
                            <label> Customer <span style="color:red">*</span></label>
                            
                            <select class="form-select" name="custno" id="customer" required>
                                <option value="">---</option>
                                <?php foreach($customers as $customer): ?>
                                    <option value="<?= $customer['custno']; ?>"><?= $customer['surname'].', '.$customer['firstname'].' '.$customer['middlename'] ; ?></option>
                                <?php endforeach; ?>
                            </select>                           
                        </div>
                        <div class="form-group mb-2">
                            <label> Loan Date <span style="color:red">*</span></label>
                            <input type="text" name="loan_date" class="form-control datepicker" placeholder="Loan Date" required/>
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
<script>
    $(document).ready(function() {
        $('#customer').chosen(); // initialize chosen select

        $('.decimal').on('input', function() {
            this.value = this.value
                .replace(/[^\d.]/g, '')             // numbers and decimals only
                //.replace(/(^[\d]{4})[\d]/g, '$1')   // not more than 4 digits at the beginning
                .replace(/(\..*)\./g, '$1')         // decimal can't exist more than once
                .replace(/(\.[\d]{2})./g, '$1');    // not more than 2 digits after decimal
        });

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });
    });
</script>

<?=$this->endSection()?>