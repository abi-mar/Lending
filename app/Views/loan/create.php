
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
                            <input type="text" name="loan_amount" class="form-control decimal comma_amount" id="loan_amount" placeholder="Enter Amount to Borrow" required/>
                        </div>
                        <div class="form-group mb-2">
                            <label> Customer <span style="color:red">*</span></label>
                            
                            <select class="form-select" name="custno" id="customer">
                                <option value="">---</option>
                                <?php foreach($customers as $customer): ?>
                                    <option value="<?= $customer['custno']; ?>"><?= $customer['surname'].', '.$customer['firstname'].' '.$customer['middlename'] ; ?></option>
                                <?php endforeach; ?>
                            </select>                           
                        </div>
                        <div class="form-group mb-2">
                            <label> Loan Date <span style="color:red">*</span></label>
                            <input type="text" name="loan_date" class="form-control datepicker" placeholder="Date when loan is applied" required/>
                        </div>

                        <div class="form-group mb-2">
                            <label> Date of First Payment <span style="color:red">*</span> 
                                <i class="fa-solid fa-circle-info" 
                                   data-bs-toggle="tooltip" 
                                   data-bs-placement="right" 
                                   title="Select date of when first payment can be collected."></i>
                            </label>
                            <input type="text" name="first_payment" class="form-control datepicker" id="first_payment" placeholder="First Scheduled Payment" required/>  
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
                            
        document.querySelector('form').addEventListener('submit', function(e) {
            var loanAmountInput = document.getElementById('loan_amount');
            if (loanAmountInput) {
                loanAmountInput.value = loanAmountInput.value.replace(/,/g, '');
            }
        });

        this.querySelector('form').addEventListener('submit', function(e) {
            var loanAmount = document.getElementById('loan_amount').value.trim();
            var customer = document.getElementById('customer').value.trim();
            var loanDate = document.querySelector('input[name="loan_date"]').value.trim();
            var firstPayment = document.getElementById('first_payment').value.trim();
            var i = 0;

            var errors = [];


            if (!loanAmount || isNaN(loanAmount.replace(/,/g, '')) || Number(loanAmount.replace(/,/g, '')) < 6000) {
                errors.push(++i + '. Please enter a valid loan amount of at least 6,000.');
            }

            if (!customer) {
                errors.push(++i + '. Please select a customer.');
            }
            if (!loanDate) {
                errors.push(++i + '. Please enter the loan date.');
            }
            if (!firstPayment) {
                errors.push(++i + '. Please enter the date of first payment.');
            }

            if (new Date(firstPayment) <= new Date(loanDate)) {
                errors.push(++i + '. The date of first payment must be after the loan date.');
            }

            if (errors.length > 0) {
                e.preventDefault();
                var errorStr = errors.join('\n');
                console.log(errorStr);
                bootbox.alert({
                    title: 'Error Messages',
                    message: errorStr,
                    className: 'animate__animated animate__bounce'
                });
            }
        }, true);
                            
    });
</script>

<?=$this->endSection()?>