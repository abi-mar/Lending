<?=$this->extend('layouts/main')?>

<?=$this->section('content')?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <h5>Add Payment
                        <?php if(isset($loan_record_row_id)) : ?>
                            <a href="<?= base_url('lending/payment/perLoan/'.$loan_record_row_id); ?>" class="btn btn-danger btn-sm float-end">BACK</a>
                        <?php else : ?>
                            <a href="<?= base_url('lending/payment'); ?>" class="btn btn-danger btn-sm float-end">BACK</a>
                        <?php endif; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('lending/payment/add/') ?>" method="POST" enctype="multipart/form-data">                        
                        <div class="form-group mb-2">
                            <label> Amount </label>
                            <input type="text" name="amount" class="form-control decimal" placeholder="Enter Amount to Pay" required/>
                        </div>
                        <div class="form-group mb-2">
                            <label> Payment Date </label>
                            <input type="text" name="payment_date" class="form-control datepicker" placeholder="Select Payment Date" required/>
                        </div>
                        
                        <?php if(isset($loan_record_row_id)) : ?>
                            <div class="form-group mb-2">
                                <label> Customer </label>
                                <input type="text" class="form-control" value="<?= $record['surname'].', '.$record['firstname'].' '. $record['middlename']; ?>" readonly/>
                                <input type="hidden" name="custno" value="<?= $record['custno'] ?>"/>
                            </div>
                            <div class="form-group mb-2">
                                <label> Loan Record </label>
                                <input type="text" class="form-control" value="<?= 'Loan Date: '.$record['loan_date'].', Loan Amount: '.$record['loan_amount']; ?>" readonly/>
                                <input type="hidden" name="loan_record" value="<?= $loan_record_row_id; ?>"/>
                            </div>
                        <?php else : ?>
                        
                        <div class="form-group mb-2">
                            <label> Customer </label>                            
                            <select class="form-select" name="custno" id="customer" data-placeholder="Select Customer" required>
                                <option value="">---</option>
                                <?php foreach($customers as $customer): ?>
                                    <option value="<?= $customer['custno']; ?>"><?= $customer['surname'].', '.$customer['firstname'].' '.$customer['middlename']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label> Loan Record </label>
                            <select class="form-select" name="loan_record" id="loan_record" required>
                                <option value="">---</option>                                
                            </select>
                        </div>
                        <?php endif; ?>
                        <!--div class="form-group mb-2">
                            <label> Scheduled Payment </label>
                            <input type="text" class="form-control" placeholder="Loan Date" value="" disabled/>
                        </div-->
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
    $(document).ready(function() {
        $('.decimal').on('input', function() {
            this.value = this.value
                .replace(/[^\d.]/g, '')             // numbers and decimals only
                .replace(/(^[\d]{4})[\d]/g, '$1')   // not more than 4 digits at the beginning
                .replace(/(\..*)\./g, '$1')         // decimal can't exist more than once
                .replace(/(\.[\d]{2})./g, '$1');    // not more than 2 digits after decimal
        });

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });

        // initialize chosen selects
        $('#customer').chosen();

        // get loan records based on customer when customer is selected
        $('#customer').on('change', function() {
            var custno = $(this).val();
            var url = '<?= base_url('lending/loan/getLoanRecords/') ?>'+custno;

            $('#loan_record').html('<option value="">---</option>');
            $.get(url, function(data) {
                var loans = data.loans;

                loans.forEach(function(loan) {
                    $('#loan_record').append('<option value="'+loan.row_id+'"> Loan ID: '+loan.row_id+', Loan Date: '+loan.loan_date+', Loan Amount: '+loan.loan_amount+'</option>');
                });
                // $('#loan_record').html(data);
                $('#loan_record').trigger('chosen:updated');
            });
        });
        
    });


</script>    

<?=$this->endSection()?>