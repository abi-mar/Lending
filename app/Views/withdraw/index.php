

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
                    <h5>Withdraw</h5>
                </div>
                <div class="card-body">                    
                    <h5>Total Interest: </h5> <?= $interest ?>
                    <h5>Total LRF: </h5>      <?= $LRF ?>
                    <h5>Total Savings: </h5>  <?= $savings ?>
                    <h5>Total Damayan: </h5>  <?= $damayan ?>
                </div>
            </div>

            <div class="card" style="margin-top: 10px">
                <div class="card-body">
                    <form action="<?= base_url('lending/withdraw/doWithdraw') ?>" method="POST">
                        <div class="form-group mb-2">
                            <label> Amount <span style="color:red">*</span></label>
                            <input type="text" name="amount" class="form-control decimal" placeholder="Enter Amount to Withdraw" required/>
                        </div>
                        <div class="form-group mb-2">
                            <label> Where to withdraw from <span style="color:red">*</span></label>
                            
                            <select class="form-select" name="source" id="source" required>
                                <option value="">---</option>                                
                                <option value="interest">Interest</option>
                                <option value="LRF">LRF</option>
                                <option value="savings">Savings</option>
                                <option value="damayan">Damayan</option>
                                
                            </select>                           
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
        $('#source').chosen(); // initialize chosen select
    });
</script>

<?=$this->endSection()?>