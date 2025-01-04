<?=$this->extend('layouts/main')?>
<?=$this->section('content')?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">

        <?php if (session()->getFlashdata('status')) { ?>

            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Message:</strong> <?= session()->getFlashdata('status') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        <?php }?>
            

            <div class="card">
                <div class="card-header">
                    <h4>Payments                        
                        <a href="<?= base_url('lending/payment/make') ?>" class="btn btn-success btn-sm float-end">Make Payment</a>                        
                    </h4>
                </div>
                <div class="card-body">
                    <table id="PaymentsTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Payment ID</th>
                                <th>Amount</th>
                                <th>Payment Date</th>
                                <th>Loan Record ID</th>
                                <th>Customer</th>
                                <!--th>Action</th-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($payments): ?>
                                <?php foreach($payments as $row) : ?>
                                <tr>
                                    <td><?= $row['row_id']; ?></td>
                                    <td><?= $row['amount']; ?></td>
                                    <td><?= $row['payment_date']; ?></td>
                                    <td><a href="<?= base_url('lending/payment/perLoan/'.$row['loan_record_row_id']) ?>"><?= $row['loan_record_row_id']; ?></a></td>
                                    <td><?= $row['surname'].', '.$row['firstname'].' '.$row['middlename']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                                
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <button class="btn btn-primary btn-sm" id="btnLoadPrevious" style="display:none">Load Previous 1000 records</button>
                    <button class="btn btn-primary btn-sm" id="btnLoadNext">Load Next 1000 records</button>
                </div>
            </div>    
        </div>                
    </div>
</div>

<!-- Initialize DataTables -->
<script>    
    var pageCounter = 1;    
    var $overall_count = <?= $total_count; ?>;

    if (pageCounter*1000 >= $overall_count) {
        $('#btnLoadNext').hide();
    }
    
    $('#PaymentsTable').DataTable({
        "order": [[2, "desc"]]
    });

    $('#btnLoadPrevious').click(function() {
        pageCounter--;
        $.ajax({
            url: '<?= base_url('lending/payment/getPaymentsByBatch');?>',
            type: 'GET',
            data: { offset: (pageCounter - 1)*1000},
            success: function(response) {
                $('#PaymentsTable tbody').html(response);
                
                if(pageCounter > 1) {
                    $('#btnLoadPrevious').prop('disabled', false);
                } else {
                    $('#btnLoadPrevious').prop('disabled', true);
                }

                if (pageCounter*1000 >= $overall_count) {
                    $('#btnLoadNext').prop('disabled', true);
                }
            }
        });
    });

    $('#btnLoadNext').click(function() {
        pageCounter++;
        $.ajax({
            url: '<?= base_url('lending/payment/getPaymentsByBatch');?>',
            type: 'GET',
            data: { offset: (pageCounter - 1)*1000},
            success: function(response) {
                $('#PaymentsTable tbody').html(response);

                if(pageCounter > 1) {
                    $('#btnLoadPrevious').prop('disabled', false);
                } else {
                    $('#btnLoadPrevious').prop('disabled', true);
                }
                
                if (pageCounter*1000 >= $overall_count) {
                    $('#btnLoadNext').prop('disabled', true);
                }
            }
        });
    });
    
</script>
<?=$this->endSection()?>