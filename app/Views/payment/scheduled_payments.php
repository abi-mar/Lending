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

            <div class="alert alert-info" role="alert">
                Weekly Amortization: <strong> <?= $loan['weekly_amortization'] ?> </strong> <br>                
                Total For Payment: <?= $loan['amount_topay'] ?> </strong> <br>
                Loan Balance: <?= $loan['balance'] ?>
            </div>
            

            <div class="card">
                <div class="card-header">
                    <h4>Scheduled Payments
                        <a href="<?= base_url('lending/loan/') ?>" class="btn btn-danger btn-sm float-end">BACK</a>
                        <a href="<?= base_url('lending/payment/make/'.$loan['row_id']) ?>" class="btn btn-success btn-sm float-end">Make Payment</a>                        
                    </h4>
                </div>
                <div class="card-body">
                    <table id="PaymentsPerLoanTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Loan ID</th>
                                <th>Amount</th>
                                <th>Date Paid</th>
                                <th>Scheduled Payment</th>
                                <th>Remaining Debt</th>
                                <th>Is Paid?</th>
                                <th>Is Overdue?</th>
                                <!--th>Action</th-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($sPayments):                                 
                                ?>
                                <?php foreach($sPayments as $row) : ?>
                                <tr>
                                    <td><?php echo $row['loan_record_row_id']; ?></td>                                    
                                    <td><?= (isset($row['date_paid'])) ? $row['amount'] : '<span class="badge text-bg-secondary">N/A</span>'; ?></td>
                                    <td><?= (isset($row['date_paid'])) ? $row['date_paid'] : '<span class="badge text-bg-secondary">N/A</span>'; ?></td>
                                    <td><?php echo $row['scheduled_date']; ?></td>
                                    <td><?php echo $row['remaining_debt']; ?></td>
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