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
                                <th>Date Added</th>
                                <th>Added By</th>
                                <th>Loan Record ID</th>                                
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
                                    <td><?= $row['date_added']; ?></td>
                                    <td><?= $row['added_by']; ?></td>
                                    <td><?= $row['loan_record_row_id']; ?></td>  
                                </tr>
                                <?php endforeach; ?>
                                
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>    
        </div>                
    </div>
</div>

<!-- Initialize DataTables -->
<script>
    $(document).ready(function() {
        $('#PaymentsTable').DataTable();
    });
</script>
<?=$this->endSection()?>