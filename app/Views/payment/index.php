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
                    <h4>Loan Records</h4>
                </div>
                <div class="card-body">
                    <table id="PaymentsPerTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Loan ID</th>
                                <th>Amount</th>
                                <th>Date Paid</th>
                                <th>Scheduled Date</th>
                                <th>Is Paid?</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($payments): ?>
                                <?php foreach($payments as $row) : ?>
                                <tr>
                                    <td><?php echo $row['load_record_row_id']; ?></td>                                    
                                    <td><?php echo $row['amount']; ?></td>                                    
                                    <td><?php echo $row['date_paid']; ?></td>
                                    <td><?php echo $row['scheduled_date']; ?></td>
                                    <td><?= ($row['is_paid'] == 0) ? '<i class="fa-solid fa-square-xmark"></i>': '<i class="fa-solid fa-square-check"></i>'; ?></td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-sm">Payments</a>                                        
                                    </td>
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
        $('#loanTable').DataTable({
            "order": [[0, "desc"]]
        });
    });
</script>
<?=$this->endSection()?>