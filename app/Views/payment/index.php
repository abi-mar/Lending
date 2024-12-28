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
                    <h4>Payments per Loan</h4>
                </div>
                <div class="card-body">
                    <table id="PaymentsPerLoanTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Loan ID</th>
                                <th>Amount</th>
                                <th>Date Paid</th>
                                <th>Scheduled Payment</th>
                                <th>Is Paid?</th>
                                <th>Is Overdue?</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($payments): ?>
                                <?php foreach($payments as $row) : ?>
                                <tr>
                                    <td><?php echo $row['load_record_row_id']; ?></td>                                    
                                    <td><?= (isset($row['date_paid'])) ? $row['amount'] : '<span class="badge text-bg-secondary">N/A</span>'; ?></td>
                                    <td><?= (isset($row['date_paid'])) ? $row['date_paid'] : '<span class="badge text-bg-secondary">N/A</span>'; ?></td>
                                    <td><?php echo $row['scheduled_date']; ?></td>
                                    <td><?= ($row['is_paid'] == 1) ? '<span class="badge text-bg-success">Yes</span>': '<span class="badge text-bg-danger">No</span>'; ?></td>
                                    <td>
                                        <?php
                                            $scheduled_date = new DateTime($row['scheduled_date']);
                                            $current_date = new DateTime();
                                            if (isset($row['date_paid'])) {
                                                echo '<span class="badge text-bg-secondary">N/A</span>';
                                            } else {
                                                echo ($scheduled_date < $current_date) ? '<span class="badge text-bg-success">Yes</span>' : '<span class="badge text-bg-danger">No</span>';
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            if (isset($row['date_paid']) || ($scheduled_date > $current_date)) {
                                                echo '<a href="#" class="btn btn-secondary btn-sm">Make Payment</a>';
                                            } else {
                                                echo '<a href="#" class="btn btn-primary btn-sm">Make Payment</a>';
                                            }
                                        ?>                                        
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
        $('#PaymentsPerLoanTable').DataTable();
    });
</script>
<?=$this->endSection()?>