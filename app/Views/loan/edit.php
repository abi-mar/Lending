<?=$this->extend("layouts/main")?>
  
<?=$this->section("content")?>


<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">

        <div class="card">
                <div class="card-header">
                    <h5>Edit Loan
                        <a href="<?= base_url('lending/loan'); ?>" class="btn btn-danger btn-sm float-end">BACK</a>
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('lending/loan/update/'.$loan['row_id']) ?>" method="POST">                        
                        <input type="hidden" name="custno" value="<?= $loan['custno']?>"/>
                        <div class="form-group mb-2">
                            <label> Loan Amount <span style="color:red">*</span></label>
                            <input type="text" name="loan_amount" class="form-control decimal" placeholder="Enter Amount to Borrow" value="<?= $loan['loan_amount']?>" required/>
                        </div>
                        <div class="form-group mb-2">
                            <label> Loan Date <span style="color:red">*</span></label>
                            <input type="text" name="loan_date" class="form-control datepicker" value="<?= $loan['loan_date']?>" placeholder="Loan Date" required/>
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
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
    });
</script>

<?=$this->endSection()?>